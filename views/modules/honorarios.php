a<!doctype html>
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
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <?php include 'views/templates/aside.php' ?>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header bg-dark">
                <?php include 'views/templates/nav.php' ?>
            </header>
            <!--  Header End -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card border border-dark">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-8">
                                        <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                                            RECIBO POR HONORARIOS</h5>
                                    </div>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <select class="form-control" id="clientes">
                                            <option value="">--SELECCIONE CLIENTE--</option>

                                        </select>

                                    </div>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <form id="formUpload" enctype="multipart/form-data" class="row g-2 align-items-center">



                                            <div class="col-md-2">
                                                <select name="anio" id="anio" class="form-control">
                                                    <?php for ($y = 2023; $y <= 2032; $y++): ?>
                                                        <option value="<?= $y ?>"><?= $y ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <select name="mes" id="mes" class="form-control">
                                                    <option value="01">Enero</option>
                                                    <option value="02">Febrero</option>
                                                    <option value="03">Marzo</option>
                                                    <option value="04">Abril</option>
                                                    <option value="05">Mayo</option>
                                                    <option value="06">Junio</option>
                                                    <option value="07">Julio</option>
                                                    <option value="08">Agosto</option>
                                                    <option value="09">Setiembre</option>
                                                    <option value="10">Octubre</option>
                                                    <option value="11">Noviembre</option>
                                                    <option value="12">Diciembre</option>
                                                </select>
                                            </div>

                                            <div class="col-md-auto">
                                                <button type="button" id="btnListar" class="btn btn-success">
                                                    <i class="bx bx-list-ul"></i> Traer Honorarios
                                                </button>
                                            </div>


                                        </form>
                                    </div>
                                </div>
                                <hr>

                                <!-- Botones de acción masiva -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <!-- Checkbox principal -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                                <label class="form-check-label" for="selectAll">
                                                    Seleccionar todos
                                                </label>
                                            </div>

                                            <!-- Botones de acción -->
                                            <button type="button" id="btnSiscontSi" class="btn btn-success btn-sm" disabled>
                                                <i class="bx bx-check"></i> Marcar SISCONT SI
                                            </button>

                                            <button type="button" id="btnSiscontNo" class="btn btn-danger btn-sm" disabled>
                                                <i class="bx bx-x"></i> Marcar SISCONT NO
                                            </button>

                                            <button type="button" id="btnEditarMasivo" class="btn btn-primary btn-sm" disabled>
                                                <i class="bx bx-edit"></i> Editar Seleccionados
                                            </button>

                                            <span class="text-muted small" id="contadorSeleccion">
                                                0 seleccionados
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div id="tablaDatos"></div>
                            </div>
                        </div>
                    </div>
                </div>



                <?php include 'views/templates/pie.php' ?>
            </div>
        </div>
    </div>
    <?php include 'views/templates/footer.php' ?>

    <!-- Modal para edición masiva -->
    <div class="modal fade" id="modalEdicionMasiva" tabindex="-1" aria-labelledby="modalEdicionMasivaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEdicionMasivaLabel">
                        <i class="bx bx-edit"></i> Editar Registros Seleccionados
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEdicionMasiva">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Centro de Costos</label>
                                <select class="form-control" id="editCentroCostos" name="centro_costos">
                                    <option value="">-- SELECCIONE --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Presupuesto</label>
                                <select class="form-control" id="editPresupuesto" name="presupuesto">
                                    <option value="">-- SELECCIONE --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Cambio</label>
                                <input type="text" class="form-control" id="editTipoCambio" name="tipo_cambio" placeholder="Ingrese tipo de cambio">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Glosa SIRE</label>
                                <input type="text" class="form-control" id="editGlosaSire" name="glosasire" placeholder="Ingrese glosa SIRE">
                            </div>
                        </div>
                        <input type="hidden" id="editIds" name="ids">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarCambios">
                        <i class="bx bx-save"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function cargarTabla() {
            var anio = $("#anio").val();
            var mes = $("#mes").val();
            let cliente_id = $('#clientes').val();


            $.ajax({
                url: "assets/ajax/tabla_honorarios.php",
                type: "POST",
                data: {
                    anio: anio,
                    mes: mes,
                    cliente_id: cliente_id
                },
                success: function(data) {
                    $("#tablaDatos").html(data);

                    // Inicializar DataTable SOLO si existe la tabla
                    if ($("#tablacompras").length) {
                        $('#tablacompras').DataTable({
                            destroy: true,
                            scrollX: true, // 👈 scroll horizontal
                            lengthChange: false,
                            buttons: ['copy', 'excel', 'pdf', 'print'],
                            columnDefs: [{
                                targets: "_all",
                                className: "dt-nowrap"
                            }]
                        }).buttons().container().appendTo('#tablacompras_wrapper .col-md-6:eq(0)');
                    }
                }
            });
        }



        $(document).ready(function() {
            cargarTabla();

            $("#anio, #mes").on("change", function() {
                cargarTabla();
            });



            $("#btnListar").on("click", function() {
                var anio = $("#anio").val();
                var mes = $("#mes").val();
                let cliente_id = $('#clientes').val();
                $.ajax({
                    url: "assets/ajax/honorarios.php",
                    type: "POST",
                    data: {
                        anio: anio,
                        mes: mes,
                        cliente_id: cliente_id
                    },
                    success: function(data) {
                        cargarTabla();
                    }
                });






            });
        });


        // ======== CARGAR CLIENTES ===========
        $.ajax({
            url: base_url + "/assets/ajax/bancos.php?op=clientes",
            type: "POST",
            dataType: "json",
            success: function(data) {
                let opciones = '<option value="">--SELECCIONE CLIENTE--</option>';
                $.each(data, function(i, item) {
                    // Asegúrate de usar el nombre real de tus columnas
                    opciones += `<option value="${item.id}">${item.ruc} - ${item.razon}</option>`;
                });
                $("#clientes").html(opciones);
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar clientes:", error);
            }
        });
        // ==========================================
        // LOGICA DE ACCIONES MASIVAS (Clonado de Compras)
        // ==========================================

        function cargarComboboxPorCliente() {
            const cliente_id = $('#clientes').val();

            if (!cliente_id) {
                // Limpiar combobox si no hay cliente seleccionado
                $('#editCentroCostos').html('<option value="">-- SELECCIONE --</option>');
                $('#editPresupuesto').html('<option value="">-- SELECCIONE --</option>');
                return;
            }

            // Cargar centros de costos
            $.ajax({
                url: base_url + "/assets/ajax/centro_costos.php",
                type: "POST",
                data: {
                    op: 'listar_cc',
                    cliente: cliente_id
                },
                dataType: "json",
                success: function(data) {
                    let opciones = '<option value="">-- SELECCIONE --</option>';
                    if (Array.isArray(data)) {
                        $.each(data, function(i, item) {
                            opciones += `<option value="${item.id}">${item.nombre}</option>`;
                        });
                    }
                    $('#editCentroCostos').html(opciones);
                },
                error: function() {
                    $('#editCentroCostos').html('<option value="">Error al cargar centros de costos</option>');
                }
            });

            // Cargar presupuestos
            $.ajax({
                url: base_url + "/assets/ajax/centro_costos.php",
                type: "POST",
                data: {
                    op: 'listar_pre',
                    cliente: cliente_id
                },
                dataType: "json",
                success: function(data) {
                    let opciones = '<option value="">-- SELECCIONE --</option>';
                    if (Array.isArray(data)) {
                        $.each(data, function(i, item) {
                            opciones += `<option value="${item.id}">${item.nombre}</option>`;
                        });
                    }
                    $('#editPresupuesto').html(opciones);
                },
                error: function() {
                    $('#editPresupuesto').html('<option value="">Error al cargar presupuestos</option>');
                }
            });
        }

        // Agregar evento change al combobox de clientes para cargar datos del modal
        $("#clientes").on("change", function() {
            cargarComboboxPorCliente();
        });


        // Evento para seleccionar todos (principal)
        $(document).on('change', '#selectAll', function() {
            const isChecked = $(this).prop('checked');
            $('.row-checkbox, #selectAllTable').prop('checked', isChecked);
            actualizarBotonesAccion();
        });

        // Evento para seleccionar todos (en la tabla)
        $(document).on('change', '#selectAllTable', function() {
            const isChecked = $(this).prop('checked');
            $('.row-checkbox, #selectAll').prop('checked', isChecked);
            actualizarBotonesAccion();
        });

        // Evento para checkbox individuales
        $(document).on('change', '.row-checkbox', function() {
            actualizarBotonesAccion();
            actualizarSelectAll();
        });

        // Botón para marcar SISCONT = SI
        $('#btnSiscontSi').on('click', function() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                Swal.fire('Advertencia', 'Debe seleccionar al menos un registro', 'warning');
                return;
            }

            Swal.fire({
                title: 'Confirmar Cambio',
                text: `¿Desea cambiar SISCONT a "SI" para ${selectedIds.length} registro(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    actualizarSiscont(selectedIds, 'SI');
                }
            });
        });

        // Botón para marcar SISCONT = NO
        $('#btnSiscontNo').on('click', function() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                Swal.fire('Advertencia', 'Debe seleccionar al menos un registro', 'warning');
                return;
            }

            Swal.fire({
                title: 'Confirmar Cambio',
                text: `¿Desea cambiar SISCONT a "NO" para ${selectedIds.length} registro(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    actualizarSiscont(selectedIds, 'NO');
                }
            });
        });

        // Botón para edición masiva
        $('#btnEditarMasivo').on('click', function() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                Swal.fire('Advertencia', 'Debe seleccionar al menos un registro', 'warning');
                return;
            }

            // Abrir modal
            $('#modalEdicionMasiva').modal('show');
            // Asegurarse de que los combos estén cargados (deberían estarlo al seleccionar cliente)
            if ($('#editCentroCostos option').length <= 1) {
                cargarComboboxPorCliente();
            }
        });

        // Botón para guardar cambios masivos
        $('#btnGuardarCambios').on('click', function() {
            const selectedIds = getSelectedIds();
            const formData = {
                ids: selectedIds,
                centro_costos: $('#editCentroCostos').val(),
                presupuesto: $('#editPresupuesto').val(),
                tipo_cambio: $('#editTipoCambio').val(),
                glosasire: $('#editGlosaSire').val()
            };

            if (!formData.centro_costos && !formData.presupuesto && !formData.tipo_cambio && !formData.glosasire) {
                Swal.fire('Advertencia', 'Debe completar al menos un campo para actualizar', 'warning');
                return;
            }

            Swal.fire({
                title: 'Guardando Cambios',
                text: 'Actualizando registros...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // REUTILIZAMOS EL SCRIPT DE COMPRAS PORQUE LA TABLA ES LA MISMA (mov_compras)
            $.ajax({
                url: base_url + "/assets/ajax/actualizar_masivo_compras.php",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire('Éxito', response.message || 'Registros actualizados correctamente', 'success');
                        $('#modalEdicionMasiva').modal('hide');
                        cargarTabla();
                        limpiarSeleccion();
                    } else {
                        Swal.fire('Error', response.message || 'Error al actualizar registros', 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Error de conexión con el servidor', 'error');
                }
            });
        });

        // Funciones auxiliares
        function getSelectedIds() {
            const ids = [];
            $('.row-checkbox:checked').each(function() {
                ids.push($(this).data('id'));
            });
            return ids;
        }

        function actualizarBotonesAccion() {
            const selectedCount = $('.row-checkbox:checked').length;
            $('#contadorSeleccion').text(`${selectedCount} seleccionados`);

            const hasSelection = selectedCount > 0;
            $('#btnSiscontSi, #btnSiscontNo, #btnEditarMasivo').prop('disabled', !hasSelection);
        }

        function actualizarSelectAll() {
            const totalCheckboxes = $('.row-checkbox').length;
            const checkedCheckboxes = $('.row-checkbox:checked').length;
            const isAllSelected = totalCheckboxes === checkedCheckboxes && checkedCheckboxes > 0;
            $('#selectAll, #selectAllTable').prop('checked', isAllSelected);
        }

        function limpiarSeleccion() {
            $('.row-checkbox, #selectAll, #selectAllTable').prop('checked', false);
            actualizarBotonesAccion();
        }

        function actualizarSiscont(ids, valor) {
            Swal.fire({
                title: 'Actualizando estado SISCONT...',
                text: 'Actualizando estado SISCONT...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // REUTILIZAMOS EL SCRIPT DE COMPRAS PORQUE LA TABLA ES LA MISMA (mov_compras)
            $.ajax({
                url: base_url + "/assets/ajax/actualizar_siscont_compras.php",
                type: "POST",
                data: {
                    ids: ids,
                    siscont: valor
                },
                dataType: "json",
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire('Éxito', response.message || `SISCONT actualizado a "${valor}" correctamente`, 'success');
                        cargarTabla();
                        limpiarSeleccion();
                    } else {
                        Swal.fire('Error', response.message || 'Error al actualizar SISCONT', 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Error de conexión con el servidor', 'error');
                }
            });
        }
    </script>
</body>

</html>