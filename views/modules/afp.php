<!doctype html>
<html lang="es">
<head>
  <?php include 'views/templates/head.php'; ?>
</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical">
    <?php include 'views/templates/aside.php'; ?>
    <div class="body-wrapper">
      <header class="app-header bg-dark">
        <?php include 'views/templates/nav.php'; ?>
      </header>
      <div class="container-fluid">
        <div class="card border border-dark">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5>AFP</h5>
            <button class="btn btn-primary" id="botonCrear" data-bs-toggle="modal" data-bs-target="#modalAFP">
              <i class="bi bi-plus-circle"></i> Nuevo AFP
            </button>
          </div>
          <div class="card-body">
            <table id="tblAFP" class="table table-bordered table-striped">
              <thead>
                <tr><th>ID</th><th>Nombre</th><th>Descripcion</th><th>Estado</th><th>Editar</th><th>Borrar</th></tr>
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
  <?php include 'views/modules/modals/afp_modal.php'; ?>
  <script src="<?= BASE_URL ?>assets/js/afp.js"></script>
</body>
</html>
