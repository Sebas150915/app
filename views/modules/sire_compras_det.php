<?php
$movkey = $_GET['id'] ?? '';
$idcliente = $_GET['idcliente'] ?? '';
?>
<!doctype html>
<html lang="en">

<head>
    <?php include 'views/templates/head.php' ?>
    <style>
        .body-wrapper>.container-fluid {
            max-width: 95%;
        }
    </style>
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <?php include 'views/templates/aside.php' ?>
        <div class="body-wrapper">
            <header class="app-header bg-dark">
                <?php include 'views/templates/nav.php' ?>
            </header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card border border-dark">
                            <div class="card-header bg-warning">
                                <h5 class="card-title text-white mb-0">Detalle de Compra: <?= $movkey ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <button class="btn btn-primary" id="btnExtraer" onclick="extraerDetalle('<?= $movkey ?>', '<?= $idcliente ?>')">
                                        <i class="bx bx-download"></i> Extraer Detalle de SUNAT
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table id="tablaDetalles" class="table table-striped table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Item</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Cantidad</th>
                                                <th>Unidad</th>
                                                <th>P. Unitario</th>
                                                <th>IGV</th>
                                                <th>Total</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyDetalles">
                                            <!-- Data cargada por AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include 'views/templates/pie.php' ?>
            </div>
        </div>
    </div>
    <?php include 'views/templates/footer.php' ?>

    <script>
        function cargarDetalles(movkey) {
            $.ajax({
                url: 'assets/ajax/extraer_detalle.php',
                type: 'POST',
                data: {
                    accion: 'listar',
                    movkey: movkey

                },
                success: function(response) {
                    try {
                        const res = typeof response === 'string' ? JSON.parse(response) : response;
                        if (res.data) {
                            let html = '';
                            res.data.forEach(item => {
                                html += `<tr>
                                    <td>${item.item}</td>
                                    <td>${item.codigo}</td>
                                    <td>${item.descripcion}</td>
                                    <td>${item.cantidad}</td>
                                    <td>${item.unidad_medida}</td>
                                    <td>${item.precio_unitario}</td>
                                    <td>${item.igv}</td>
                                    <td>${item.precio_total}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="editarCodigo(${item.id}, '${item.codigo}')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>`;
                            });
                            $('#tbodyDetalles').html(html);
                        }
                    } catch (e) {
                        console.error("Error parsing response", e);
                    }
                }
            });
        }

        function editarCodigo(id, currentCode) {
            Swal.fire({
                title: 'Editar Código',
                input: 'text',
                inputValue: currentCode,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                showLoaderOnConfirm: true,
                preConfirm: (newCode) => {
                    return $.ajax({
                        url: 'assets/ajax/extraer_detalle.php',
                        type: 'POST',
                        data: {
                            accion: 'actualizar_codigo',
                            id: id,
                            codigo: newCode
                        }
                    }).then(response => {
                        try {
                            const res = typeof response === 'string' ? JSON.parse(response) : response;
                            if (!res.success) {
                                throw new Error(res.message || 'Error al actualizar');
                            }
                            return res;
                        } catch (e) {
                            throw new Error('Error de respuesta del servidor');
                        }
                    }).catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Actualizado!',
                        text: 'El código ha sido actualizado properly.',
                        icon: 'success'
                    });
                    cargarDetalles('<?= $movkey ?>');
                }
            })
        }

        function extraerDetalle(movkey, idcliente) {
            Swal.fire({
                title: 'Extrayendo...',
                text: 'Conectando con SUNAT...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: 'assets/ajax/extraer_detalle.php',
                type: 'POST',
                data: {
                    accion: 'extraer',
                    movkey: movkey,
                    idcliente: idcliente
                },
                success: function(response) {
                    Swal.close();
                    try {
                        const res = typeof response === 'string' ? JSON.parse(response) : response;
                        if (res.success) {
                            Swal.fire('Éxito', 'Detalle extraído correctamente', 'success');
                            cargarDetalles(movkey);
                        } else {
                            Swal.fire('Error', res.message || 'No se pudo extraer el detalle', 'error');
                        }
                    } catch (e) {
                        console.error(e);
                        Swal.fire('Error', 'Error de respuesta del servidor', 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Fallo en la comunicación', 'error');
                }
            });
        }

        $(document).ready(function() {
            cargarDetalles('<?= $movkey ?>');
        });
    </script>
</body>

</html>