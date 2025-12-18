<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header('Content-Type: application/json');

require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");
require_once("../plugins/vendor/autoload.php"); // Autoload for PDF Parser

use Smalot\PdfParser\Parser;

session_start();

// ========== PARSER FUNCTIONS ==========

function parseBCP($text)
{
    $lines = explode("\n", $text);
    $movimientos = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        // BCP Format: DD-MM Description Amount-
        if (preg_match('/^(\d{2}-\d{2})\s+(.+?)\s+([\d,]*\.?\d{1,2}-?)$/', $line, $matches)) {
            $dateStr = $matches[1];
            $rawDesc = $matches[2];
            $amountStr = $matches[3];

            // Date Processing
            $currentYear = date('Y');
            list($day, $month) = explode('-', $dateStr);
            $fecha = "$currentYear-$month-$day";

            // Amount Processing
            $isNegative = (substr($amountStr, -1) === '-');
            $cleanAmount = str_replace([',', '-'], '', $amountStr);
            if (strpos($cleanAmount, '.') === 0) {
                $cleanAmount = "0" . $cleanAmount;
            }
            $amountVal = floatval($cleanAmount);

            // I/S Logic
            $i_s = $isNegative ? 'S' : 'I';

            // Description Cleaning
            $separators = [' TLC', ' BPI', ' INT', ' VEN', ' ¥'];
            $cleanGlosa = $rawDesc;
            foreach ($separators as $sep) {
                $pos = strpos($cleanGlosa, $sep);
                if ($pos !== false) {
                    $cleanGlosa = substr($cleanGlosa, 0, $pos);
                }
            }
            $cleanGlosa = trim($cleanGlosa);

            $movimientos[] = [
                'fecha' => $fecha,
                'glosa' => $cleanGlosa,
                'i_s' => $i_s,
                'importe' => $amountVal
            ];
        }
    }

    return $movimientos;
}

function parseBBVA($text)
{
    $lines = explode("\n", $text);
    $movimientos = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        // BBVA Format (estimated): DD/MM/YYYY Description Cargo/Abono Amount
        // Example: 15/12/2025 TRANSFERENCIA ABONO 1,500.00
        if (preg_match('/(\d{2}\/\d{2}\/\d{4})\s+(.+?)\s+(CARGO|ABONO)\s+([\d,]+\.?\d{0,2})/', $line, $matches)) {
            $dateStr = $matches[1];
            $description = trim($matches[2]);
            $tipo = $matches[3];
            $amountStr = $matches[4];

            // Date Processing
            list($day, $month, $year) = explode('/', $dateStr);
            $fecha = "$year-$month-$day";

            // Amount Processing
            $cleanAmount = str_replace(',', '', $amountStr);
            $amountVal = floatval($cleanAmount);

            // I/S Logic
            $i_s = ($tipo === 'ABONO') ? 'I' : 'S';

            $movimientos[] = [
                'fecha' => $fecha,
                'glosa' => $description,
                'i_s' => $i_s,
                'importe' => $amountVal
            ];
        }
    }

    return $movimientos;
}

function parseInterbank($text)
{
    // Placeholder for Interbank parser
    return parseGenerico($text);
}

function parseGenerico($text)
{
    // Generic parser - tries common patterns
    $lines = explode("\n", $text);
    $movimientos = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        // Try pattern 1: DD-MM Description Amount
        if (preg_match('/(\d{2}-\d{2})\s+(.+?)\s+([\d,]+\.?\d{0,2})/', $line, $matches)) {
            $dateStr = $matches[1];
            $description = trim($matches[2]);
            $amountStr = $matches[3];

            $currentYear = date('Y');
            list($day, $month) = explode('-', $dateStr);
            $fecha = "$currentYear-$month-$day";

            $cleanAmount = str_replace(',', '', $amountStr);
            $amountVal = floatval($cleanAmount);

            // Assume negative if ends with -
            $i_s = (substr($amountStr, -1) === '-') ? 'S' : 'I';

            $movimientos[] = [
                'fecha' => $fecha,
                'glosa' => $description,
                'i_s' => $i_s,
                'importe' => $amountVal
            ];
        }
    }

    return $movimientos;
}

// ========== END PARSER FUNCTIONS ==========

$op = $_GET['op'] ?? '';

switch ($op) {
    case 'get_bancos':
        $id_cliente = $_GET['id_cliente'] ?? 0;
        if (!$id_cliente) {
            echo json_encode([]);
            exit;
        }

        // Fetch banks associated with this client
        $stmt = $connect->prepare("SELECT id, nombre, codigo, moneda FROM tbl_bancos WHERE cliente = :cliente AND estado = 'ACTIVO'");
        $stmt->execute([':cliente' => $id_cliente]);
        $bancos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($bancos);
        break;

    case 'upload':
        if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Error al subir el archivo.']);
            exit;
        }

        $id_cliente = $_POST['id_cliente'] ?? 0;
        $id_banco = $_POST['id_banco'] ?? '';
        $tiene_clave = $_POST['tiene_clave'] ?? 'NO';
        $clave_pdf = $_POST['clave_pdf'] ?? '';

        if (!$id_cliente || !$id_banco) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos (Cliente o Banco).']);
            exit;
        }

        // Validate password requirement
        if ($tiene_clave === 'SI' && empty($clave_pdf)) {
            echo json_encode(['success' => false, 'message' => 'Debe ingresar la contraseña del PDF.']);
            exit;
        }

        try {
            // Get bank name to determine parser
            $stmtBanco = $connect->prepare("SELECT nombre FROM tbl_bancos WHERE id = :id LIMIT 1");
            $stmtBanco->execute([':id' => $id_banco]);
            $banco = $stmtBanco->fetch(PDO::FETCH_ASSOC);
            $banco_nombre = strtoupper($banco['nombre'] ?? 'GENERICO');

            // Original PDF file
            $pdfFile = $_FILES['pdf_file']['tmp_name'];
            $pdfToProcess = $pdfFile;
            $tempDecryptedFile = null;

            // STEP 1: Handle password-protected PDFs
            if ($tiene_clave === 'SI' && !empty($clave_pdf)) {
                $tempDecryptedFile = sys_get_temp_dir() . '/decrypted_' . uniqid() . '.pdf';

                // Determine binary name based on OS
                $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
                $qpdfBinary = $isWindows ? 'qpdf.exe' : 'qpdf';
                $gsBinary = $isWindows ? 'gswin64c.exe' : 'gs';

                // CRITICAL CHECK: Verify if exec is enabled
                if (!function_exists('exec')) {
                    throw new Exception("La función 'exec' de PHP está deshabilitada en este servidor. No es posible ejecutar herramientas externas (QPDF/Ghostscript) para desencriptar. Por favor contacte a su proveedor de Hosting para habilitarla.");
                }

                // 1. Try Configured Path
                $toolPath = defined('PDF_TOOL_PATH') && !empty(PDF_TOOL_PATH) ? PDF_TOOL_PATH : null;
                $toolType = 'unknown';

                if ($toolPath && file_exists($toolPath)) {
                    // Detect tool type by name
                    if (stripos($toolPath, 'qpdf') !== false) {
                        $toolType = 'qpdf';
                    } elseif (stripos($toolPath, 'gs') !== false || stripos($toolPath, 'gswin') !== false) {
                        $toolType = 'gs';
                    }
                } else {
                    // 2. Auto-detect in PATH (Standard for Hosting/Prod)
                    // Check for QPDF
                    $output = [];
                    $returnVar = 0;
                    $checkCmd = $isWindows ? "where $qpdfBinary" : "which $qpdfBinary";
                    exec("$checkCmd 2>&1", $output, $returnVar);

                    if ($returnVar === 0 && !empty($output)) {
                        $toolPath = trim($output[0]); // It's in PATH
                        $toolType = 'qpdf';
                    } else {
                        // Check for Ghostscript
                        $checkCmd = $isWindows ? "where $gsBinary" : "which $gsBinary";
                        exec("$checkCmd 2>&1", $output, $returnVar);
                        if ($returnVar === 0 && !empty($output)) {
                            $toolPath = trim($output[0]);
                            $toolType = 'gs';
                        }
                    }
                }

                // 3. Check Project Local Binaries (Portable)
                if (!$toolPath) {
                    // Define binary name based on OS
                    $binName = $isWindows ? 'qpdf.exe' : 'qpdf';

                    // Check multiple possible locations in plugins
                    $possiblePaths = [
                        __DIR__ . '/../plugins/qpdf/bin/' . $binName,
                        __DIR__ . '/../plugins/qpdf/' . $binName,
                        __DIR__ . '/../plugins/bin/' . $binName,
                        // Custom Linux Binary Directory
                        __DIR__ . '/../plugins/qpdflinux/bin/qpdf',
                        __DIR__ . '/../plugins/qpdflinux/qpdf'
                    ];

                    foreach ($possiblePaths as $localBin) {
                        if (file_exists($localBin)) {
                            // On Linux, try to ensure it is executable
                            if (!$isWindows) {
                                @chmod($localBin, 0755);
                            }

                            $toolPath = $localBin;
                            $toolType = 'qpdf';
                            break;
                        }
                    }
                }

                // 4. Fallback: Check Common Windows Paths (if not found yet and we are in Windows)
                if (!$toolPath && $isWindows) {
                    $commonPaths = [
                        // QPDF
                        'C:\\Program Files\\qpdf\\bin\\qpdf.exe',
                        'C:\\Program Files (x86)\\qpdf\\bin\\qpdf.exe',
                        'C:\\ProgramData\\chocolatey\\bin\\qpdf.exe',
                        // Ghostscript
                        'C:\\Program Files\\gs\\gs10.04.0\\bin\\gswin64c.exe',
                        'C:\\Program Files\\gs\\gs10.03.1\\bin\\gswin64c.exe',
                        'C:\\Program Files (x86)\\gs\\gs10.04.0\\bin\\gswin32c.exe',
                    ];

                    // Allow wildcards/glob for Ghostscript versions
                    foreach (glob('C:\Program Files\gs\gs*\bin\gswin64c.exe') as $gsFound) {
                        $commonPaths[] = $gsFound;
                    }

                    foreach ($commonPaths as $path) {
                        if (file_exists($path)) {
                            $toolPath = $path;
                            $toolType = (stripos($path, 'qpdf') !== false) ? 'qpdf' : 'gs';
                            break;
                        }
                    }
                }

                // 5. Fallback: Check Common Linux/Unix Paths (Explicit check)
                if (!$toolPath && !$isWindows) {
                    $linuxPaths = [
                        '/usr/bin/qpdf',
                        '/usr/local/bin/qpdf',
                        '/bin/qpdf',
                        '/opt/bin/qpdf',
                        '/usr/bin/gs',
                        '/usr/local/bin/gs',
                        '/bin/gs',
                        '/usr/bin/ghostscript'
                    ];

                    foreach ($linuxPaths as $path) {
                        if (file_exists($path)) {
                            $toolPath = $path;
                            $toolType = (stripos($path, 'qpdf') !== false) ? 'qpdf' : 'gs';
                            break;
                        }
                    }
                }

                if (!$toolPath) {
                    throw new Exception("No se encontró herramienta de desencriptación (QPDF o Ghostscript). Contacte al administrador para instalarla en el servidor.");
                }

                // Execute Decryption
                $command = "";
                if ($toolType === 'qpdf') {
                    // QPDF Syntax
                    // qpdf --password=your-password --decrypt input.pdf output.pdf
                    $command = "\"$toolPath\" --password=" . escapeshellarg($clave_pdf) . " --decrypt " . escapeshellarg($pdfFile) . " " . escapeshellarg($tempDecryptedFile) . " 2>&1";
                } elseif ($toolType === 'gs') {
                    // Ghostscript Syntax
                    $command = "\"$toolPath\" -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sPDFPassword=" . escapeshellarg($clave_pdf) . " -sOutputFile=" . escapeshellarg($tempDecryptedFile) . " " . escapeshellarg($pdfFile) . " 2>&1";
                } else {
                    throw new Exception("Herramienta de PDF desconocida configurada.");
                }

                $output = [];
                $returnVar = 0;
                exec($command, $output, $returnVar);

                // Validation
                if ($returnVar === 0 && file_exists($tempDecryptedFile) && filesize($tempDecryptedFile) > 0) {
                    $pdfToProcess = $tempDecryptedFile;
                } else {
                    $outStr = implode(" ", $output);
                    if (stripos($outStr, 'invalid password') !== false || stripos($outStr, 'password incorrect') !== false || stripos($outStr, 'Password') !== false) {
                        throw new Exception("Contraseña incorrecta.");
                    } else {
                        throw new Exception("Error al desencriptar: " . $outStr);
                    }
                }
            }

            // STEP 2: Parse PDF content
            $parser = new Parser();

            try {
                $pdf = $parser->parseFile($pdfToProcess);
                $text = $pdf->getText();
            } catch (Exception $parseException) {
                throw new Exception("Error al leer el PDF: " . $parseException->getMessage());
            } finally {
                // Clean up temporary file if it exists
                if ($tempDecryptedFile && file_exists($tempDecryptedFile)) {
                    @unlink($tempDecryptedFile);
                }
            }

            // Normalize newlines
            $text = str_replace(["\r\n", "\r"], "\n", $text);

            // STEP 3: Parse based on bank format
            $movimientos = [];
            switch ($banco_nombre) {
                case 'BCP':
                case 'BANCO DE CREDITO':
                case 'BANCO DE CREDITO DEL PERU':
                    $movimientos = parseBCP($text);
                    break;
                case 'BBVA':
                case 'BBVA CONTINENTAL':
                    $movimientos = parseBBVA($text);
                    break;
                case 'INTERBANK':
                    $movimientos = parseInterbank($text);
                    break;
                default:
                    $movimientos = parseGenerico($text);
                    break;
            }

            if (empty($movimientos)) {
                throw new Exception("No se encontraron movimientos en el PDF. Verifique el formato del archivo.");
            }

            // STEP 4: Insert movements into database
            $count = 0;
            $stmt = $connect->prepare("INSERT INTO tbl_movimientos_banco (id_cliente, id_banco, fecha, glosa, i_s, importe) VALUES (?, ?, ?, ?, ?, ?)");

            foreach ($movimientos as $mov) {
                $stmt->execute([$id_cliente, $id_banco, $mov['fecha'], $mov['glosa'], $mov['i_s'], $mov['importe']]);
                $count++;
            }

            echo json_encode([
                'success' => true,
                'count' => $count,
                'banco' => $banco_nombre,
                'message' => "Se importaron $count movimientos de $banco_nombre correctamente."
            ]);
        } catch (Exception $e) {
            // Clean up temporary file on error
            if (isset($tempDecryptedFile) && file_exists($tempDecryptedFile)) {
                @unlink($tempDecryptedFile);
            }

            echo json_encode(['success' => false, 'message' => 'Error al procesar PDF: ' . $e->getMessage()]);
        }
        break;

    case 'get_link_options':
        // Fetch pending items for linking
        // type: GASTO, COMPRA, VENTA
        $type = $_GET['type'] ?? '';
        $id_cliente = $_GET['id_cliente'] ?? 0;
        $search = $_GET['search'] ?? '';

        if ($type === 'GASTO') {
            // tbl_concepto_gasto
            $sql = "SELECT id, nombre, codigo FROM tbl_concepto_gasto WHERE cliente = :cliente AND estado = 'ACTIVO'";

            if ($search) $sql .= " AND (nombre LIKE :s OR codigo LIKE :s)";
            $stmt = $connect->prepare($sql);
            $stmt->bindValue(':cliente', $id_cliente);
            if ($search) $stmt->bindValue(':s', "%$search%");
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } elseif ($type === 'COMPRA') {
            // mov_compras (Payables)
            $sql = "SELECT movkey as id, rucemisor, razonemisor, seriedoc, numdoc, fechadocsire, moneda, totaldocsire, saldo 
                    FROM mov_compras 
                    WHERE idcliente = :cliente AND saldo <> 0";

            if ($search) $sql .= " AND (rucemisor LIKE :s OR razonemisor LIKE :s OR numdoc LIKE :s)";
            $sql .= " ORDER BY fechadocsire DESC LIMIT 50";

            $stmt = $connect->prepare($sql);
            $stmt->bindValue(':cliente', $id_cliente);
            if ($search) $stmt->bindValue(':s', "%$search%");
            $stmt->execute();
            $bs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($bs as &$b) {
                $b['descripcion'] = $b['razonemisor'] . " (" . $b['seriedoc'] . "-" . $b['numdoc'] . ")";
            }
            echo json_encode($bs);
        } elseif ($type === 'VENTA') {
            // mov_venta (Receivables)
            $sql = "SELECT movkey as id, numdoc, movfec, valtot, saldo, movdoc 
                    FROM mov_venta 
                    WHERE empid = :cliente AND saldo <> 0";

            if ($search) $sql .= " AND (numdoc LIKE :s)";
            $sql .= " ORDER BY movfec DESC LIMIT 50";

            $stmt = $connect->prepare($sql);
            $stmt->bindValue(':cliente', $id_cliente);
            if ($search) $stmt->bindValue(':s', "%$search%");
            $stmt->execute();
            $vs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($vs as &$v) {
                $v['descripcion'] = "Doc: " . $v['numdoc'] . " (" . $v['movdoc'] . ")";
            }
            echo json_encode($vs);
        } else {
            echo json_encode([]);
        }
        break;

    case 'vincular':
        $data = json_decode(file_get_contents('php://input'), true);

        $id_movimiento = $data['id_movimiento'] ?? 0;
        $items = $data['items'] ?? [];
        // Items: [{ type: 'GASTO', id: '..', monto: 100 }, ...]

        if (!$id_movimiento || empty($items)) {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            exit;
        }

        try {
            $connect->beginTransaction();

            foreach ($items as $item) {
                $tipo = $item['type']; // GASTO, COMPRA, VENTA
                $id_destino = $item['id'];
                $monto = floatval($item['monto']);

                // 1. Insert Enlace
                $stmt = $connect->prepare("INSERT INTO tbl_movimientos_enlace (id_movimiento, tipo_destino, id_destino, monto) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id_movimiento, $tipo, $id_destino, $monto]);

                // 2. Update Balances
                if ($tipo === 'COMPRA') {
                    // Update mov_compras
                    $stmtUpd = $connect->prepare("UPDATE mov_compras SET importe_pago = importe_pago + ?, saldo = saldo - ? WHERE movkey = ?");
                    $stmtUpd->execute([$monto, $monto, $id_destino]);
                } elseif ($tipo === 'VENTA') {
                    // Update mov_venta
                    $stmtUpd = $connect->prepare("UPDATE mov_venta SET importe_cobro = importe_cobro + ?, saldo = saldo - ? WHERE movkey = ?");
                    $stmtUpd->execute([$monto, $monto, $id_destino]);
                }
            }

            $connect->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $connect->rollBack();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'list':
        // Datatable list logic
        $id_cliente = $_GET['id_cliente'] ?? 0;
        $id_banco = $_GET['id_banco'] ?? '';

        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 10;

        // Base Query
        $sql = "SELECT b.*, 
                (SELECT IFNULL(SUM(monto),0) FROM tbl_movimientos_enlace WHERE id_movimiento = b.id) as monto_vinculado
                FROM tbl_movimientos_banco b 
                WHERE b.id_cliente = :cliente";
        $params = [':cliente' => $id_cliente];

        if ($id_banco) {
            $sql .= " AND b.id_banco = :banco";
            $params[':banco'] = $id_banco;
        }

        $sql .= " ORDER BY b.fecha DESC, b.id DESC LIMIT $start, $length";

        $stmt = $connect->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Total Count
        $sqlCount = "SELECT COUNT(*) FROM tbl_movimientos_banco WHERE id_cliente = :cliente";
        if ($id_banco) $sqlCount .= " AND id_banco = :banco";
        $stmtCount = $connect->prepare($sqlCount);
        $stmtCount->execute($params);
        $total = $stmtCount->fetchColumn();

        // Format for Datatable
        $formattedData = [];
        foreach ($data as $row) {
            $badgeType = ($row['i_s'] == 'I') ? '<span class="badge bg-success">INGRESO</span>' : '<span class="badge bg-danger">SALIDA</span>';

            // Status Logic
            $importe = floatval($row['importe']);
            $vinculado = floatval($row['monto_vinculado']);
            $pendiente = $importe - $vinculado;

            if ($pendiente <= 0.01) {
                $estadoBadge = '<span class="badge bg-success">Completo</span>';
                $btnClass = 'btn-secondary disabled';
                $btnText = 'Completado';
            } elseif ($vinculado > 0.01) {
                $estadoBadge = '<span class="badge bg-warning text-dark">Parcial</span>';
                $btnClass = 'btn-primary btn-vincular';
                $btnText = 'Vincular';
            } else {
                $estadoBadge = '<span class="badge bg-secondary">Pendiente</span>';
                $btnClass = 'btn-primary btn-vincular';
                $btnText = 'Vincular';
            }

            $formattedData[] = [
                $row['id'],
                $row['id_banco'],
                date('d/m/Y', strtotime($row['fecha'])),
                htmlspecialchars($row['glosa']),
                $badgeType,
                'S/ ' . number_format($importe, 2),
                $estadoBadge,
                '<button class="btn btn-sm ' . $btnClass . '">' . $btnText . '</button>'
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => intval($total),
            "recordsFiltered" => intval($total),
            "data" => $formattedData
        ]);
        break;

    default:
        echo json_encode(['error' => 'Invalid operation']);
        break;
}
