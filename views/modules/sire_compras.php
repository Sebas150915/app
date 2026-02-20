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
                                            SIRE COMPRAS</h5>
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
                                            <div class="col-md-4">
                                                <input type="file" name="archivo" accept=".txt" required class="form-control">
                                            </div>
                                            <div class="col-md-auto">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bx bx-upload"></i> Subir y Guardar
                                                </button>
                                            </div>

                                            <div class="col-md-2">
                                                <select name="anio" id="anio" class="form-control">
                                                    <?php for ($y = 2024; $y <= 2032; $y++): ?>
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
                                                    <i class="bx bx-list-ul"></i> Traer Sire
                                                </button>
                                            </div>

                                            <div class="col-md-auto">
                                                <button type="button" id="btnGenerarTxt" class="btn btn-info" onclick="generarTxtComprimido()">
                                                    <i class="bx bx-download"></i> Generar TXT
                                                </button>
                                            </div>

                                            <div class="col-md-auto">
                                                <button type="button" id="btnDescargaMasiva" class="btn btn-warning">
                                                    <i class="bx bx-cloud-download"></i> Descarga Masiva
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

    <?php

    include 'views/modules/modals/bancos.php';

    ?>

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








    <!-- Script para generar TXT comprimido -->
    <script src="assets/js/generar_txt.js"></script>

    <script>
        function cargarTabla() {
            var anio = $("#anio").val();
            var mes = $("#mes").val();
            let cliente_id = $('#clientes').val();

            $.ajax({
                url: "assets/ajax/tabla_compras.php",
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
                        new DataTable('#tablacompras', {
                            scrollX: true,
                            dom: 'Bfrtip',
                            layout: {
                                topStart: 'buttons'
                            },
                            buttons: [{
                                extend: 'excelHtml5',
                                text: 'Descargar Excel',
                                className: 'btn btn-sm success'
                            }],
                            columnDefs: [{
                                targets: "_all",
                                className: "dt-nowrap"
                            }]
                        });
                    }
                }
            });
        }

        $("#formUpload").on("submit", function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: base_url + "/assets/ajax/upload_compras.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status === "ok") {
                        cargarTabla();
                        alert(res.mensaje + " - Insertados: " + res.si_insertados + " | Duplicados: " + res.no_insertados);
                    } else {
                        alert("Error al procesar archivo");
                    }
                }
            });
        });

        function cargarComboboxPorCliente() {
            const cliente_id = $('#clientes').val();

            if (!cliente_id) {
                // Limpiar combobox si no hay cliente seleccionado
                $('#editCentroCostos').html('<option value="">-- SELECCIONE --</option>');
                $('#editPresupuesto').html('<option value="">-- SELECCIONE --</option>');
                return;
            }

            // Cargar centros de costos desde tbl_centro_costo usando listar_cc
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

            // Cargar presupuestos desde tbl_presupuestos usando listar_pre
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

        $(document).ready(function() {
            cargarTabla();

            $("#anio, #mes").on("change", function() {
                cargarTabla();
            });

            // Agregar evento change al combobox de clientes
            $("#clientes").on("change", function() {
                cargarTabla();
                cargarComboboxPorCliente();
            });

            // ======== FUNCIONALIDADES DE SELECCIÓN MÚLTIPLE ========

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

                // Abrir modal y cargar datos
                $('#modalEdicionMasiva').modal('show');
                cargarDatosParaEdicion();
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

            function cargarDatosParaEdicion() {
                const cliente_id = $('#clientes').val();

                if (!cliente_id) {
                    Swal.fire('Advertencia', 'Debe seleccionar un cliente primero', 'warning');
                    $('#modalEdicionMasiva').modal('hide');
                    return;
                }

                // Cargar centros de costos desde tbl_centro_costo usando listar_cc
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

                // Cargar presupuestos desde tbl_presupuestos usando listar_pre
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

                // No cargar tipos de cambio ya que ahora es un input de texto
            }

            $("#btnListar").on("click", function() {
                var anio = $("#anio").val();
                var mes = $("#mes").val();
                let cliente_id = $('#clientes').val();
                $.ajax({
                    url: "assets/ajax/compras.php",
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

            $("#btnDescargaMasiva").on("click", function() {
                var anio = $("#anio").val();
                var mes = $("#mes").val();
                let cliente_id = $('#clientes').val();

                if (!cliente_id) {
                    Swal.fire("Error", "Seleccione un cliente", "error");
                    return;
                }

                iniciarDescargaMasiva(anio, mes, cliente_id);
            });
        });

        function iniciarDescargaMasiva(anio, mes, cliente_id) {
            Swal.fire({
                title: 'Descargando Archivos...',
                html: 'Procesando descargas...<br><b>Por favor no cierre esta ventana</b>',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            procesarLote(anio, mes, cliente_id);
        }

        function procesarLote(anio, mes, cliente_id) {
            $.ajax({
                url: base_url + "/assets/ajax/descarga_masiva.php",
                type: "POST",
                data: {
                    anio: anio,
                    mes: mes,
                    cliente_id: cliente_id
                },
                dataType: "json",
                success: function(res) {
                    if (res.status === 'ongoing') {
                        // Update status message
                        Swal.update({
                            text: `Descargando... Procesados: ${res.processed} | Descargados: ${res.downloaded} | Errores: ${res.errors}`
                        });

                        // Continue to next batch
                        procesarLote(anio, mes, cliente_id);
                    } else if (res.status === 'finished') {
                        Swal.fire("Completado", "La descarga masiva ha finalizado.", "success");
                        cargarTabla();
                    } else {
                        Swal.fire("Error", res.message || "Ocurrió un error desconocido", "error");
                    }
                },
                error: function(err) {
                    console.error(err);
                    Swal.fire("Error", "Error de conexión con el servidor", "error");
                }
            });
        }




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

                // Agregar evento change después de cargar los clientes
                $("#clientes").on("change", function() {
                    cargarTabla();
                    cargarComboboxPorCliente();
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar clientes:", error);
            }
        });





        function descargar(tipo, ruc, tipodoc, seriedoc, numerodoc) {

            let cliente_id = $('#clientes').val();
            const data = {
                tipo,
                ruc,
                tipodoc,
                seriedoc,
                numerodoc,
                cliente_id
            };

            Swal.fire({
                title: 'Procesando...',
                text: 'Obteniendo archivo del servidor...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(base_url + '/assets/ajax/proxy_descarga.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(response => {
                    Swal.close();

                    if (response.error) {
                        throw new Error(response.error);
                    }

                    if (response.archivo_b64 && response.nombre_archivo) {
                        // Decodificar Base64
                        const byteCharacters = atob(response.archivo_b64);
                        const byteNumbers = new Array(byteCharacters.length);
                        for (let i = 0; i < byteCharacters.length; i++) {
                            byteNumbers[i] = byteCharacters.charCodeAt(i);
                        }
                        const byteArray = new Uint8Array(byteNumbers);
                        const blob = new Blob([byteArray], {
                            type: "application/zip"
                        });

                        // Descargar
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = response.nombre_archivo;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                    } else {
                        throw new Error("Respuesta inválida del servidor");
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire("Error", err.message, "error");
                });
        }
    </script>
</body>

</html>