<!doctype html>
<html lang="es">
<head>
  <?php include 'views/templates/head.php' ?>
</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <?php include 'views/templates/aside.php' ?>
    <div class="body-wrapper">
      <header class="app-header">
        <?php include 'views/templates/nav.php' ?>
      </header>
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">CLIENTES</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalClientesEmpresas" id="ce_btnCrear"><i class="bi bi-plus-circle-fill"></i> Crear</button>
              </div>
              <div class="card-body">
                <table id="tblClientesEmpresas" class="table table-bordered table-striped w-100">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>RUC</th>
                      <th>Razón Social</th>
                      <th>Dirección</th>
                      <th>Fecha Inicio</th>
                      <th>Fecha Fin</th>
                      <th>Paquetes</th>
                      <th>Estado</th>
                      <th>Editar</th>
                      <th>Borrar</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
          <?php include 'views/templates/pie.php' ?>
        </div>
      </div>
    </div>
    <?php include 'views/templates/footer.php' ?>
    <?php include 'views/modules/modals/clientes_empresas.php' ?>
    <script>
      $(function () {
        const $form = $('#formClientesEmpresas');
        const $modal = $('#modalClientesEmpresas');
        const table = $('#tblClientesEmpresas').DataTable({
          processing: true,
          serverSide: true,
          order: [],
          ajax: {
            url: base_url + "/assets/ajax/clientes_empresas.php?op=listar",
            type: "POST"
          },
          language: {
            emptyTable: "No hay registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            infoEmpty: "Mostrando 0 a 0 de 0 Entradas",
            lengthMenu: "Mostrar _MENU_ Entradas",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
            zeroRecords: "Sin resultados",
            paginate: { first: "Primero", last: "Último", next: "Siguiente", previous: "Anterior" }
          }
        });

        $('#ce_btnCrear').on('click', function () {
          $form[0].reset();
          $('#ce_id').val('');
          $('#ce_operacion').val('Crear');
          $('#ce_action').text('Guardar');
        });

        $form.on('submit', function (e) {
          e.preventDefault();
          $.ajax({
            url: base_url + "/assets/ajax/clientes_empresas.php?op=guardar",
            method: "POST",
            data: $form.serialize(),
            dataType: "json",
            success: function (resp) {
              $modal.modal('hide');
              table.ajax.reload();
            },
            error: function () { alert("Error al guardar"); }
          });
        });

        $(document).on('click', '.editar', function () {
          const id = $(this).attr('id');
          $.ajax({
            url: base_url + "/assets/ajax/clientes_empresas.php?op=buscar",
            method: "POST",
            data: { id },
            dataType: "json",
            success: function (d) {
              $('#ce_id').val(d.id);
              $('#ce_ruc').val(d.ruc);
              $('#ce_razon').val(d.razon);
              $('#ce_direccion').val(d.direccion);
              $('#ce_paquetes').val(d.paquetes);
              $('#ce_inicio').val(d.fecha_inicio);
              $('#ce_fin').val(d.fecha_fin);
              $('#ce_estado').val(d.estado);
              $('#ce_operacion').val('Editar');
              $('#ce_action').text('Actualizar');
              $modal.modal('show');
            }
          });
        });

        $(document).on('click', '.borrar', function () {
          const id = $(this).attr('id');
          if (!confirm("¿Cambiar estado del registro " + id + "?")) return;
          $.ajax({
            url: base_url + "/assets/ajax/clientes_empresas.php?op=eliminar",
            method: "POST",
            data: { id },
            dataType: "json",
            success: function () { table.ajax.reload(); }
          });
        });
      });
    </script>
  </div>
</body>
</html>
