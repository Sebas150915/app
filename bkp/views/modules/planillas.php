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
        <div class="row">
          <div class="col-lg-12">
            <div class="card border border-dark">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Planilla Mensual</h5>
                <button id="btnCalcularPlanilla" class="btn btn-primary">
                  <i class="bi bi-calculator-fill"></i> Calcular Planilla
                </button>
              </div>
              <div class="card-body">
                <table id="tblPlanilla" class="table table-bordered table-striped"></table>
              </div>
            </div>
          </div>
        </div>

        <?php include 'views/templates/pie.php'; ?>
      </div>
    </div>
  </div>

  <?php include 'views/templates/footer.php'; ?>
  <script src="<?php echo base_url(); ?>/assets/js/planilla.js?v=0"></script>
</body>
</html>
