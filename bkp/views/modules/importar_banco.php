<!doctype html>
<html lang="en">

<head>
    <?php include 'views/templates/head.php' ?>
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

                        <!-- UPLOAD CARD -->
                        <div class="card border border-primary shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0 text-white">Importar PDF a Banco</h5>
                            </div>
                            <div class="card-body">
                                <form id="formImport" enctype="multipart/form-data">
                                    <div class="row align-items-end">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-bold">Empresa (Cliente)</label>
                                            <select class="form-select" id="selectCliente" required>
                                                <option value="">-- Seleccione Empresa --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-bold">Banco</label>
                                            <select class="form-select" id="selectBanco" name="id_banco" required disabled>
                                                <option value="">-- Seleccione Cliente Primero --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-bold">Archivo PDF</label>
                                            <input type="file" class="form-control" name="pdf_file" accept=".pdf" required>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label fw-bold">¿Tiene Clave?</label>
                                            <select class="form-select" id="tieneClave" name="tiene_clave">
                                                <option value="NO">No</option>
                                                <option value="SI">Sí</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 mb-3 d-flex align-items-end">
                                            <button type="submit" class="btn btn-success w-100" id="btnSubir">
                                                <i class="ti ti-upload"></i> Subir
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row" id="divClavePdf" style="display: none;">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-danger">
                                                <i class="ti ti-lock"></i> Contraseña del PDF
                                            </label>
                                            <input type="password" class="form-control" id="clavePdf" name="clave_pdf" placeholder="Ingrese la contraseña del PDF">
                                            <small class="text-muted">Cada PDF puede tener una contraseña diferente</small>
                                        </div>
                                    </div>
                                </form>
                                <div id="alertContainer" class="mt-3"></div>
                            </div>
                        </div>

                        <!-- TABLE CARD -->
                        <div class="card border border-dark">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Movimientos Importados</h5>
                            </div>
                            <div class="card-body">
                                <table id="tblMovimientos" class="table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Banco</th>
                                            <th>Fecha</th>
                                            <th>Glosa</th>
                                            <th>Tipo</th>
                                            <th>Importe</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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
        $(document).ready(function() {
            const baseAjax = "assets/ajax/importar_banco_ajax.php";
            let dataTable;

            // 1. Load Clients
            // We can reuse the logic from bancos.php or call our new endpoint if we implemented op=clientes there?
            // Let's use the one in bancos.php?op=clientes which is generic for session.
            // Wait, AJAX endpoint I wrote doesn't have op=clientes. I should add it or use existing one.
            // I'll reuse 'assets/ajax/bancos.php?op=clientes' as seen in the reference code for consistency.
            $.ajax({
                url: "assets/ajax/bancos.php?op=clientes",
                type: "POST", // Bancos.php uses POST? Reference says POST but code had switch variable.
                // Actually code has switch($variable) where variable = $_GET['op'].
                // But inside case 'clientes', it uses $idempresa from session.
                // So GET/POST doesn't matter much for op, but let's stick to what works.
                dataType: "json",
                success: function(data) {
                    let opts = '<option value="">-- Seleccione Empresa --</option>';
                    data.forEach(item => {
                        opts += `<option value="${item.id}">${item.ruc} - ${item.razon}</option>`;
                    });
                    $("#selectCliente").html(opts);
                }
            });

            // Toggle password field visibility
            $("#tieneClave").on('change', function() {
                if ($(this).val() === 'SI') {
                    $("#divClavePdf").slideDown();
                    $("#clavePdf").prop('required', true);
                } else {
                    $("#divClavePdf").slideUp();
                    $("#clavePdf").prop('required', false).val('');
                }
            });

            // 2. Client Change -> Load Banks
            $("#selectCliente").on('change', function() {
                let clientId = $(this).val();
                $("#selectBanco").html('<option value="">Cargando...</option>').prop('disabled', true);

                if (clientId) {
                    $.ajax({
                        url: baseAjax + "?op=get_bancos&id_cliente=" + clientId,
                        type: "GET",
                        dataType: "json",
                        success: function(banks) {
                            let opts = '<option value="">-- Seleccione Banco --</option>';
                            if (banks.length > 0) {
                                banks.forEach(b => {
                                    // Using ID as value, but maybe user wants Name if PDF parser uses names?
                                    // Code uses ID for tbl_movimientos_banco.
                                    opts += `<option value="${b.id}">${b.nombre} (${b.moneda})</option>`;
                                });
                            } else {
                                opts = '<option value="">No hay bancos registrados</option>';
                            }
                            $("#selectBanco").html(opts).prop('disabled', false);
                            reloadTable(clientId); // Reload table for this client
                        }
                    });
                } else {
                    $("#selectBanco").html('<option value="">-- Seleccione Cliente Primero --</option>');
                    reloadTable(0);
                }
            });

            // 2.1 Bank Change -> Reload Table
            $("#selectBanco").on('change', function() {
                let clientId = $("#selectCliente").val();
                reloadTable(clientId);
            });

            // 3. Handle Upload
            $("#formImport").on('submit', function(e) {
                e.preventDefault();
                let clientId = $("#selectCliente").val();
                if (!clientId) {
                    alert("Seleccione un cliente");
                    return;
                }

                let formData = new FormData(this);
                formData.append('id_cliente', clientId);
                // id_banco is in the form already

                $("#btnSubir").prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i> Procesando...');

                $.ajax({
                    url: baseAjax + "?op=upload",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(resp) {
                        $("#btnSubir").prop('disabled', false).html('<i class="ti ti-upload"></i> Subir');
                        if (resp.success) {
                            $("#alertContainer").html(`<div class="alert alert-success alert-dismissible fade show">
                        <strong>¡Éxito!</strong> ${resp.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`);
                            reloadTable(clientId);
                            // Reset file input
                            $("input[name='pdf_file']").val('');
                        } else {
                            $("#alertContainer").html(`<div class="alert alert-danger alert-dismissible fade show">
                        <strong>Error:</strong> ${resp.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`);
                        }
                    },
                    error: function(err) {
                        $("#btnSubir").prop('disabled', false).html('<i class="ti ti-upload"></i> Subir');
                        alert("Error de conexión");
                        console.error(err);
                    }
                });
            });

            // 4. Datatable
            function reloadTable(clientId) {
                let bankId = $("#selectBanco").val() || '';

                if ($.fn.DataTable.isDataTable('#tblMovimientos')) {
                    dataTable.destroy();
                }

                dataTable = $('#tblMovimientos').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: baseAjax + "?op=list&id_cliente=" + clientId + "&id_banco=" + bankId,
                        type: "POST"
                    },
                    "language": {
                        "decimal": "",
                        "emptyTable": "No hay datos disponibles en la tabla",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                        "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                        "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ entradas",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar:",
                        "zeroRecords": "No se encontraron registros coincidentes",
                        "paginate": {
                            "first": "Primero",
                            "last": "Último",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                    "order": [
                        [2, "desc"]
                    ]
                });
            }

            // --- LINKING LOGIC ---
            let currentMovId = 0;
            let currentMovAmount = 0;

            // Open Modal
            $(document).on('click', '.btn-vincular', function() {
                let tr = $(this).closest('tr');
                let row = dataTable.row(tr).data();
                // row indexes: 0=id, 1=banco, 2=fecha, 3=glosa, 4=tipo, 5=importe, 6=estado
                currentMovId = row[0];
                // Importe comes as string "S/ 1,200.00", need to parse
                let amtStr = row[5].replace('S/ ', '').replace(/,/g, '');
                currentMovAmount = parseFloat(amtStr);

                $("#linkModalLabel").text("Vincular Movimiento: " + row[3] + " (" + row[5] + ")");
                $("#mdlAmount").text(row[5]);
                $("#linkResultTable tbody").empty();
                $("#inpLinkSearch").val('');

                // Load default tab (Gastos)
                loadLinkOptions('GASTO');

                var myModal = new bootstrap.Modal(document.getElementById('linkModal'));
                myModal.show();
            });

            // Tabs
            $('.nav-tabs button').on('click', function(e) {
                let type = $(this).data('type');
                loadLinkOptions(type);
            });

            // Search
            $("#inpLinkSearch").on('keyup', function() {
                let type = $('.nav-tabs .active').data('type');
                loadLinkOptions(type, $(this).val());
            });

            // Toggle Selection
            $(document).on('click', '.btn-select-link', function() {
                $(this).toggleClass('active-link btn-outline-primary btn-success');
                if ($(this).hasClass('active-link')) {
                    $(this).text('Seleccionado');
                    $(this).closest('tr').addClass('table-primary');
                } else {
                    $(this).text('Seleccionar');
                    $(this).closest('tr').removeClass('table-primary');
                }

                // Update Amount Remaining? (Optional enhancement)
            });

            function loadLinkOptions(type, search = '') {
                let clientId = $("#selectCliente").val();
                $("#linkResultTable tbody").html('<tr><td colspan="4" class="text-center">Cargando...</td></tr>');

                $.ajax({
                    url: baseAjax + "?op=get_link_options&id_cliente=" + clientId + "&type=" + type + "&search=" + search,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        let html = '';
                        if (data.length === 0) {
                            html = '<tr><td colspan="4" class="text-center">No se encontraron resultados</td></tr>';
                        } else {
                            data.forEach(item => {
                                // Gasto: id, nombre, codigo
                                // Compra/Venta: id, descripcion, saldo, total...
                                let desc = item.descripcion || (item.codigo + ' - ' + item.nombre);
                                let saldo = item.saldo ? parseFloat(item.saldo) : 0;
                                let saldoStr = item.saldo ? 'S/ ' + saldo.toFixed(2) : '-';

                                html += `
                                    <tr>
                                        <td>${desc}</td>
                                        <td>${saldoStr}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm inp-monto-link" 
                                                data-id="${item.id}" data-max="${saldo}" value="${Math.min(saldo || currentMovAmount, currentMovAmount)}" step="0.01">
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary btn-select-link">Seleccionar</button>
                                        </td>
                                    </tr>
                                `;
                            });
                        }
                        $("#linkResultTable tbody").html(html);
                    }
                });
            }

            // Save Link
            $("#btnSaveLink").on('click', function() {
                // Determine selected items
                // Simplified: User picks ONE item to link against for now? 
                // Or checkboxes? 
                // Let's implement: User types amount in the row they want to link and clicks "Seleccionar"?
                // Actually the cleanest UX for now: 
                // Just take the INPUT values that are > 0 ??
                // Let's iterate inputs

                let links = [];
                let type = $('.nav-tabs .active').data('type');
                let totalLinked = 0;

                $(".inp-monto-link").each(function() {
                    let val = parseFloat($(this).val());
                    if (val > 0 && $(this).closest('tr').find('.btn-select-link').hasClass('active-link')) { // Only if selected
                        links.push({
                            type: type,
                            id: $(this).data('id'),
                            monto: val
                        });
                        totalLinked += val;
                    }
                });

                if (links.length === 0) {
                    // Fallback logic if my UX above is confusing:
                    // Maybe just checking which inputs have value > 0 is enough?
                    // Let's rely on a "Check" box?
                    // Let's just assume the user enters amount in the row they want.
                    $(".inp-monto-link").each(function() {
                        let val = parseFloat($(this).val());
                        if (val > 0) {
                            links.push({
                                type: type,
                                id: $(this).data('id'),
                                monto: val
                            });
                        }
                    });
                }

                if (links.length === 0) {
                    alert("Ingrese un monto a vincular");
                    return;
                }

                $.ajax({
                    url: baseAjax + "?op=vincular",
                    type: "POST",
                    data: JSON.stringify({
                        id_movimiento: currentMovId,
                        items: links
                    }),
                    contentType: "application/json",
                    dataType: "json",
                    success: function(resp) {
                        if (resp.success) {
                            alert("Vinculación exitosa");
                            $('#linkModal').modal('hide');
                            let clientId = $("#selectCliente").val();
                            reloadTable(clientId);
                        } else {
                            alert("Error: " + resp.error);
                        }
                    }
                });
            });

        });
    </script>

    <!-- LINK MODAL -->
    <div class="modal fade" id="linkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="linkModalLabel">Vincular Movimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Importe Movimiento: <strong id="mdlAmount"></strong>
                    </div>

                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-type="GASTO">Gastos</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-type="COMPRA">Cuentas por Pagar</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-type="VENTA">Cuentas por Cobrar</button>
                        </li>
                    </ul>

                    <div class="mb-3">
                        <input type="text" class="form-control" id="inpLinkSearch" placeholder="Buscar por nombre, documento...">
                    </div>

                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm table-hover" id="linkResultTable">
                            <thead>
                                <tr>
                                    <th>Descripción/Documento</th>
                                    <th>Saldo (Doc)</th>
                                    <th style="width: 150px;">Monto a Usar</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnSaveLink">Guardar Vinculación</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>