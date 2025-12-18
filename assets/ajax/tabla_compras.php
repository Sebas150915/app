<?php
require_once("../../config/config.php");
require_once("../../helpers/helpers.php");
require_once("../../libraries/conexion.php");

$anio = $_POST['anio'] ?? date('Y');
$mes  = $_POST['mes']  ?? date('m');
$id_cliente = $_POST['cliente_id'] ?? 0;
$periodo = $anio.$mes;

$stmt = $connect->prepare("SELECT * FROM mov_compras WHERE periodouso = ? and idcliente =? and tipdoc NOT IN ('02', '00') ORDER BY movkey DESC");
$stmt->execute([$periodo,$id_cliente]);

$colorconta = '#119906';
$colorsire  = '#EB9115';
$colortexto = '#fff';
?>

<?php if($stmt->rowCount() > 0): ?>
<table id="tablacompras" class="table table-striped table-bordered" style="width:100%;">
    <thead class="table-dark dark">
        <tr class="table-dark dark">
            <th class="table-dark dark">movkey</th>
            <th class="table-dark dark">periodo</th>
            <th class="table-dark dark">RUC Proveedor</th>
            <th class="table-dark dark">Razon Proveedor</th>
            <th class="table-dark dark">tipo</th>
            <th class="table-dark dark">serie</th>
            <th class="table-dark dark">número</th>
            <th class="table-dark dark">fecha</th>
            <th class="table-dark dark">Descripcionn</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorsire?> !important;">B.I. Conta</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorsire?> !important;">I.G.V. Conta</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorsire?> !important;">oth Conta</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorsire?> !important;">Total Conta</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorconta?> !important;">B.I. SIRE</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorconta?> !important;">I.G.V. SIRE</th>
              <th style="color: <?=$colortexto?>;background-color: <?=$colorconta?> !important;">Adq. no Gravada</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorconta?> !important;">oth SIRE</th>
            <th style="color: <?=$colortexto?>;background-color: <?=$colorconta?> !important;">total SIRE</th>
            <th class="table-dark dark">Dif. B.I.</th>
            <th class="table-dark dark">Dif. I.G.V.</th>
            <th class="table-dark dark">Adq. no Gravada</th>
            <th class="table-dark dark">Dif. oth</th>
            <th class="table-dark dark">Dif. Totales</th>
            <th class="table-dark dark">Moneda</th>
            <th class="table-dark dark">Tip. Cambio</th>
            <th class="table-dark dark">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
          $moneda = $row['moneda'];
          $simbolo ='$';
          if($moneda=='PEN')
          {
              $simbolo ='S/';
          }
        ?>
            <tr>
                <td><?= htmlspecialchars($row['movkey']) ?></td>
                <td><?= htmlspecialchars($row['periodouso']) ?></td>
                <td><?= htmlspecialchars($row['rucemisor']) ?></td>
                <td><?= htmlspecialchars($row['razonemisor']) ?></td>
                <td><?= htmlspecialchars($row['tipdoc']) ?></td>
                <td><?= htmlspecialchars($row['seriedoc']) ?></td>
                <td><?= htmlspecialchars($row['numdoc']) ?></td>
                <td><?= $row['fechadocsire'] ?></td>
                <th>Descripcionn</th>

                <td align="right"><?= formatMoney($row['basedocple'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['igvdocple'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['othdocple'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['totaldocple'],$simbolo) ?></td>

                <td align="right"><?= formatMoney($row['basedocsire'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['igvdocsire'],$simbolo) ?></td>
                 <td align="right"><?= formatMoney($row['nogravado'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['othdocsire'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['totaldocsire'],$simbolo) ?></td>

                <td align="right"><?= formatMoney($row['basedocple']-$row['basedocsire'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['igvdocple']-$row['igvdocsire'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['nogravado']-$row['nogravado'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['othdocsire']-$row['othdocsire'],$simbolo) ?></td>
                <td align="right"><?= formatMoney($row['totaldocsire']-$row['totaldocsire'],$simbolo) ?></td>
                <td><?= htmlspecialchars($row['moneda']) ?></td>
                <td><?= htmlspecialchars($row['tcambiosire']) ?></td>
                <td>
    <div class="btn-group" role="group">
    <button onclick="descargar('02', '<?= $row['rucemisor'] ?>', '<?= $row['tipdoc'] ?>', '<?= $row['seriedoc'] ?>', '<?= $row['numdoc'] ?>')" 
            class="btn btn-sm btn-primary">XML</button>

    <button onclick="descargar('03', '<?= $row['rucemisor'] ?>', '<?= $row['tipdoc'] ?>', '<?= $row['seriedoc'] ?>', '<?= $row['numdoc'] ?>')"
            class="btn btn-sm btn-success">CDR</button>

    <button onclick="descargar('01', '<?= $row['rucemisor'] ?>', '<?= $row['tipdoc'] ?>', '<?= $row['seriedoc'] ?>', '<?= $row['numdoc'] ?>')"
            class="btn btn-sm btn-danger">PDF</button>
</div>
</td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <div class="alert alert-warning">No hay registros para el periodo <?= $anio ?>-<?= $mes ?>.</div>
<?php endif; ?>
