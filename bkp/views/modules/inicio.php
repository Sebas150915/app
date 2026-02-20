<?php
//var_dump($_SESSION);
?>
<!doctype html>
<html lang="es">

<head>
  <?php include 'views/templates/head.php' ?>
  <!-- Premium Dashboard Styles -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
      <header class="app-header bg-white border-bottom shadow-sm">
        <?php include 'views/templates/nav.php' ?>
      </header>
      <!--  Header End -->
      <div class="container-fluid py-4">

        <!-- Key Metrics Row -->
        <div class="row g-4 mb-4">
          <!-- Metric 1 -->
          <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="text-muted text-uppercase fw-semibold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Ventas Totales</h6>
                  <div class="icon-shape bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <iconify-icon icon="solar:dollar-minimalistic-linear" class="fs-5"></iconify-icon>
                  </div>
                </div>
                <h3 class="fw-bold text-dark mb-1">S/ 45,231</h3>
                <div class="d-flex align-items-center text-success fs-2 fw-semibold">
                  <iconify-icon icon="solar:round-arrow-right-up-linear" class="me-1"></iconify-icon>
                  <span>+12.5%</span>
                  <span class="text-muted ms-2 fw-medium">vs mes anterior</span>
                </div>
              </div>
            </div>
          </div>
          <!-- Metric 2 -->
          <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="text-muted text-uppercase fw-semibold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Nuevos Clientes</h6>
                  <div class="icon-shape bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <iconify-icon icon="solar:users-group-rounded-linear" class="fs-5"></iconify-icon>
                  </div>
                </div>
                <h3 class="fw-bold text-dark mb-1">1,240</h3>
                <div class="d-flex align-items-center text-success fs-2 fw-semibold">
                  <iconify-icon icon="solar:round-arrow-right-up-linear" class="me-1"></iconify-icon>
                  <span>+8.2%</span>
                  <span class="text-muted ms-2 fw-medium">vs mes anterior</span>
                </div>
              </div>
            </div>
          </div>
          <!-- Metric 3 -->
          <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="text-muted text-uppercase fw-semibold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Productos</h6>
                  <div class="icon-shape bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <iconify-icon icon="solar:box-minimalistic-linear" class="fs-5"></iconify-icon>
                  </div>
                </div>
                <h3 class="fw-bold text-dark mb-1">320</h3>
                <div class="d-flex align-items-center text-danger fs-2 fw-semibold">
                  <iconify-icon icon="solar:round-arrow-right-down-linear" class="me-1"></iconify-icon>
                  <span>-2.1%</span>
                  <span class="text-muted ms-2 fw-medium">vs mes anterior</span>
                </div>
              </div>
            </div>
          </div>
          <!-- Metric 4 -->
          <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="text-muted text-uppercase fw-semibold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Ticket Promedio</h6>
                  <div class="icon-shape bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <iconify-icon icon="solar:bill-list-linear" class="fs-5"></iconify-icon>
                  </div>
                </div>
                <h3 class="fw-bold text-dark mb-1">S/ 42.50</h3>
                <div class="d-flex align-items-center text-success fs-2 fw-semibold">
                  <iconify-icon icon="solar:round-arrow-right-up-linear" class="me-1"></iconify-icon>
                  <span>+4.6%</span>
                  <span class="text-muted ms-2 fw-medium">vs mes anterior</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-4">
          <!-- Traffic Overview (Full Width) -->
          <div class="col-lg-12">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                  <div>
                    <h5 class="card-title fw-bold mb-1">Resumen de Ventas</h5>
                    <p class="text-muted fs-2 mb-0">Comportamiento de ventas en el tiempo</p>
                  </div>
                  <select class="form-select form-select-sm w-auto border-0 bg-light fw-medium">
                    <option value="1">Mes Actual</option>
                    <option value="2">Mes Anterior</option>
                    <option value="3">Últimos 3 meses</option>
                  </select>
                </div>
                <div id="traffic-overview" style="min-height: 350px;"></div>
              </div>
            </div>
          </div>

          <!-- Page Views Table (Modified to Full Width) -->
          <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                  <h5 class="card-title fw-bold mb-0">Rendimiento de Páginas</h5>
                  <button class="btn btn-sm btn-outline-primary border-0 fw-medium">Ver Reporte Completo</button>
                </div>
                <div class="table-responsive">
                  <table class="table text-nowrap align-middle mb-0">
                    <thead class="bg-light">
                      <tr>
                        <th scope="col" class="ps-3 py-3 border-0 rounded-start">Página</th>
                        <th scope="col" class="py-3 border-0">Ruta API</th>
                        <th scope="col" class="text-center py-3 border-0">Visitas</th>
                        <th scope="col" class="text-center py-3 border-0 rounded-end">Ingresos</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="ps-3 border-bottom-0">
                          <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-2" style="width:40px; height:40px;">
                              <iconify-icon icon="solar:home-smile-angle-linear" class="fs-5"></iconify-icon>
                            </div>
                            <div>
                              <h6 class="fw-semibold text-dark mb-0">Bienvenida</h6>
                              <span class="fs-2 text-muted">Landing Page</span>
                            </div>
                          </div>
                        </td>
                        <td class="border-bottom-0">
                          <span class="badge bg-light text-dark fw-medium border">/index.html</span>
                        </td>
                        <td class="text-center fw-bold text-dark border-bottom-0">18,456</td>
                        <td class="text-center fw-bold text-success border-bottom-0">$2.40</td>
                      </tr>
                      <tr>
                        <td class="ps-3 border-bottom-0">
                          <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center bg-info-subtle text-info rounded-2" style="width:40px; height:40px;">
                              <iconify-icon icon="solar:chart-square-linear" class="fs-5"></iconify-icon>
                            </div>
                            <div>
                              <h6 class="fw-semibold text-dark mb-0">Dashboard</h6>
                              <span class="fs-2 text-muted">Analytics</span>
                            </div>
                          </div>
                        </td>
                        <td class="border-bottom-0">
                          <span class="badge bg-light text-dark fw-medium border">/dashboard</span>
                        </td>
                        <td class="text-center fw-bold text-dark border-bottom-0">17,452</td>
                        <td class="text-center fw-bold text-success border-bottom-0">$0.97</td>
                      </tr>
                      <tr>
                        <td class="ps-3 border-bottom-0">
                          <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-2" style="width:40px; height:40px;">
                              <iconify-icon icon="solar:shop-linear" class="fs-5"></iconify-icon>
                            </div>
                            <div>
                              <h6 class="fw-semibold text-dark mb-0">Catálogo</h6>
                              <span class="fs-2 text-muted">E-commerce</span>
                            </div>
                          </div>
                        </td>
                        <td class="border-bottom-0">
                          <span class="badge bg-light text-dark fw-medium border">/checkout</span>
                        </td>
                        <td class="text-center fw-bold text-dark border-bottom-0">12,180</td>
                        <td class="text-center fw-bold text-success border-bottom-0">$7.50</td>
                      </tr>
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
  <script src="<?= base_url() ?>/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="<?= base_url() ?>/assets/js/dashboard.js"></script>
</body>

</html>