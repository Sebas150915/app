<!doctype html>
<html lang="es">

<head>
  <?php include 'views/templates/head.php'; ?>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical">
    <?php include 'views/templates/aside.php'; ?>
    <div class="body-wrapper" style="margin-left: 270px;">
      <header class="app-header bg-dark">
        <?php include 'views/templates/nav.php'; ?>
      </header>
      <div class="container-fluid">
        <div class="card border border-dark">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
              <h5 class="mb-0">Trabajadores</h5>
              <div class="col-md-6">
                <select class="form-control" id="clientes">
                  <option value="">--SELECCIONE CLIENTE--</option>
                </select>
              </div>
            </div>
            <button class="btn btn-primary" id="botonCrear" data-bs-toggle="modal" data-bs-target="#modalTrabajador">
              <i class="bi bi-plus-circle"></i> Nuevo Trabajador
            </button>
          </div>
          <div class="card-body">
            <table id="tblTrabajadores" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>DNI</th>
                  <th>Apellidos y Nombres</th>
                  <th>Cargo</th>
                  <th>Banco</th>
                  <th>AFP</th>
                  <th>Centro</th>
                  <th>Contrato</th>
                  <th>Estado</th>
                  <th>Editar</th>
                  <th>Dar de Baja</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

        <?php include 'views/templates/pie.php'; ?>
      </div>
    </div>
  </div>

  <?php include 'views/templates/footer.php'; ?>
  <?php include 'views/modules/modals/trabajador.php'; ?>

  <script src="<?= BASE_URL ?>assets/js/trabajadores.js?<?= time() ?>"></script>
</body>

</html>
