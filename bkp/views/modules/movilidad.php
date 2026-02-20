<!doctype html>
<html lang="es">

<head>
  <?php include 'views/templates/head.php' ?>
</head>

<body>
<div class="page-wrapper" id="main-wrapper" 
     data-layout="vertical" data-navbarbg="skin6" 
     data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

  <?php include 'views/templates/aside.php' ?>

  <div class="body-wrapper">
    <header class="app-header bg-dark">
      <?php include 'views/templates/nav.php' ?>
    </header>

    <div class="container-fluid">

      <div class="row">
        <div class="col-lg-12">

          <div class="card border border-dark">
            <div class="card-header">
              <div class="row">
                <div class="col-8">
                  <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                    PLANILLAS DE MOVILIDAD
                  </h5>
                </div>

                <div class="col-4">
                  <button type="button" class="btn btn-primary w-100" 
                          id="btnNueva" data-bs-toggle="modal" data-bs-target="#modalMovilidad">
                    <i class="bi bi-plus-circle-fill"></i> Nueva Planilla
                  </button>
                </div>
              </div>
            </div>

            <div class="card-body">

              <table id="tablaMovilidad" class="table table-bordered table-striped">
                <thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Empleado</th>
                    <th>Periodo</th>
                    <th>Fecha Emisión</th>
                    <th>Total</th>
                    <th>Editar</th>
                    <th>PDF</th>
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

<?php include 'views/modules/modals/movilidad.php' ?>
<?php include 'views/templates/footer.php' ?>

<script>
$(document).ready(function() {

  // ============================
  // CREAR – LIMPIAR FORM
  // ============================
  $("#btnNueva").click(function () {
      $("#formMovilidad")[0].reset();
      $("#tablaMov tbody").empty();
      $("#totalMostrar").text("0.00");
      $(".modal-title").text("Registrar Planilla de Movilidad");
      $("#operacion").val("Crear");
      $("#action").val("Crear");
  });

  // ============================
  // DATATABLE PRINCIPAL
  // ============================
  var dataTable = $('#tablaMovilidad').DataTable({
      processing: true,
      serverSide: true,
      order: [],
      ajax: {
        url: base_url + "/assets/ajax/movilidad.php?op=listar",
        type: "POST"
      }
  });

  // ============================
  // GUARDAR FORM
  // ============================
  $(document).on("submit", "#formMovilidad", function(e){
      e.preventDefault();

      $.ajax({
          url: base_url + "/assets/ajax/movilidad.php?op=guardar",
          method: "POST",
          data: new FormData(this),
          contentType: false,
          processData: false,
          dataType: "json",
          success: function(data){
              Swal.fire("Correcto", data.respuesta, "success");
              $("#modalMovilidad").modal("hide");
              dataTable.ajax.reload();
          }
      });
  });

});
</script>

</body>
</html>
