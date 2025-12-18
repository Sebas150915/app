<?php
//var_dump($_SESSION);


/*
array(7) { ["iniciarSesion"]=> string(6) "cinema" 
           ["id"]=> int(1) 
           ["id_empresa"]=> int(1) 
           ["empresa"]=> string(4) "JMVC" 
           ["ruc"]=> string(11) "10441689166" 
           ["fecha_vencimiento"]=> string(10) "2026-10-06" 
           ["local"]=> int(2) }
*/
?>


<!doctype html>
<html lang="es">

<head>
  <?php include 'views/templates/head.php' ?>

  <!-- Premium Dashboard Styles -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    /* Styling Override */
    :root {
      --primary: #4f46e5;
      --primary-light: #eef2ff;
      --secondary: #0ea5e9;
      --success: #10b981;
      --warning: #f59e0b;
      --dark: #1e293b;
      --light-gray: #f8fafc;
      --border-color: #e2e8f0;
      --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
      --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif !important;
      background-color: var(--light-gray);
      color: var(--dark);
    }

    /* Premium Card */
    .card {
      border: 1px solid rgba(255, 255, 255, 0.6);
      border-radius: 20px;
      box-shadow: var(--card-shadow);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      margin-bottom: 24px;
      overflow: hidden;
    }

    .card:hover {
      box-shadow: var(--hover-shadow);
      transform: translateY(-4px);
    }

    .card-body {
      padding: 1.75rem;
    }

    .card-title {
      font-weight: 700;
      color: #0f172a;
      letter-spacing: -0.025em;
      margin-bottom: 1.5rem;
      font-size: 1.1rem;
    }

    /* Traffic Card Special */
    .bg-gradient-primary {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    }

    /* Table Styling */
    .table thead th {
      background-color: transparent !important;
      color: #64748b;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.05em;
      border-bottom: 2px solid var(--border-color);
      padding-bottom: 1rem;
    }

    .table td,
    .table th {
      padding: 1.25rem 1rem;
      vertical-align: middle;
      border-color: var(--border-color);
    }

    .table-link1 {
      font-weight: 600;
      color: var(--dark);
    }

    /* Badges & Text */
    .badge {
      font-weight: 600;
      padding: 0.5em 0.8em;
      border-radius: 8px;
    }

    .text-muted {
      color: #94a3b8 !important;
    }

    /* Productivity Card */
    .productivity-card {
      background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(79, 70, 229, 0.03) 100%);
    }

    .btn-primary {
      background: var(--primary);
      border: none;
      box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
      border-radius: 12px;
      padding: 10px 20px;
      font-weight: 600;
    }

    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-1px);
    }

    /* Sessions Icons */
    .icon-box {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 10px;
    }

    .icon-box.primary {
      background-color: var(--primary-light);
      color: var(--primary);
    }

    .icon-box.secondary {
      background-color: #e0f2fe;
      color: var(--secondary);
    }

    .icon-box.success {
      background-color: #dcfce7;
      color: var(--success);
    }

    /* Progress bars */
    .progress {
      height: 8px;
      border-radius: 4px;
      background-color: #f1f5f9;
    }

    .progress-bar {
      border-radius: 4px;
    }

    /* Blog Cards */
    .blog-img {
      height: 200px;
      object-fit: cover;
      width: 100%;
      border-radius: 20px 20px 0 0;
    }

    .hover-img {
      border-radius: 24px;
    }

    .hover-img .card-body {
      position: relative;
      background: white;
      z-index: 2;
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
      <header class="app-header bg-white border-bottom shadow-sm">
        <?php include 'views/templates/nav.php' ?>
      </header>
      <!--  Header End -->
      <div class="container-fluid py-4">
        <div class="row g-4">

          <!-- Traffic Overview -->
          <div class="col-lg-8">
            <div class="card h-100">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-4">
                  <h5 class="card-title mb-0">Resumen de Tráfico</h5>
                  <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-pill px-3" type="button">Mes Actual</button>
                  </div>
                </div>
                <div id="traffic-overview"></div>
              </div>
            </div>
          </div>

          <!-- Productivity Tips -->
          <div class="col-lg-4">
            <div class="card h-100 productivity-card border-0">
              <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                <div class="mb-4 position-relative">
                  <div style="width:120px; height:120px; background:var(--primary-light); border-radius:50%; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); z-index:0;"></div>
                  <img src="<?= base_url() ?>/assets/images/backgrounds/product-tip.png" alt="image" class="img-fluid position-relative z-1" width="180">
                </div>
                <h4 class="fw-bold mb-2">¡Tips de Productividad!</h4>
                <p class="text-muted mb-4 px-3">Optimiza tu flujo de trabajo con nuestros nuevos atajos de teclado.</p>
                <button class="btn btn-primary w-100 mt-auto">Ver Tips</button>
              </div>
            </div>
          </div>

          <!-- Page Views Table -->
          <div class="col-lg-8">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Vistas por Página</h5>
                <div class="table-responsive">
                  <table class="table text-nowrap align-middle mb-0">
                    <thead>
                      <tr>
                        <th scope="col" class="ps-0">Nombre de Página</th>
                        <th scope="col">Ruta</th>
                        <th scope="col" class="text-center">Vistas</th>
                        <th scope="col" class="text-center">Valor</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="ps-0 border-0">
                          <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-light text-primary rounded-circle" style="width:35px; height:35px;"><i class="bi bi-window"></i></div>
                            <div>
                              <h6 class="fw-semibold mb-0">Bienvenida</h6>
                              <span class="fs-2 text-muted">Landing Page</span>
                            </div>
                          </div>
                        </td>
                        <td class="border-0">
                          <span class="badge bg-light text-dark fw-medium">/index.html</span>
                        </td>
                        <td class="text-center fw-bold text-dark border-0">18,456</td>
                        <td class="text-center fw-bold text-success border-0">$2.40</td>
                      </tr>
                      <tr>
                        <td class="ps-0 border-0">
                          <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-light text-info rounded-circle" style="width:35px; height:35px;"><i class="bi bi-pie-chart"></i></div>
                            <div>
                              <h6 class="fw-semibold mb-0">Dashboard</h6>
                              <span class="fs-2 text-muted">Analytics</span>
                            </div>
                          </div>
                        </td>
                        <td class="border-0">
                          <span class="badge bg-light text-dark fw-medium">/dashboard</span>
                        </td>
                        <td class="text-center fw-bold text-dark border-0">17,452</td>
                        <td class="text-center fw-bold text-success border-0">$0.97</td>
                      </tr>
                      <tr>
                        <td class="ps-0 border-0">
                          <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-light text-warning rounded-circle" style="width:35px; height:35px;"><i class="bi bi-cart"></i></div>
                            <div>
                              <h6 class="fw-semibold mb-0">Catálogo</h6>
                              <span class="fs-2 text-muted">E-commerce</span>
                            </div>
                          </div>
                        </td>
                        <td class="border-0">
                          <span class="badge bg-light text-dark fw-medium">/checkout</span>
                        </td>
                        <td class="text-center fw-bold text-dark border-0">12,180</td>
                        <td class="text-center fw-bold text-success border-0">$7.50</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Sessions By Device -->
          <div class="col-lg-4">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title d-flex align-items-center justify-content-between mb-5">
                  Sesiones por Disp.
                  <span class="text-muted fs-6"><i class="bi bi-three-dots"></i></span>
                </h5>

                <div class="row text-center mb-4">
                  <div class="col-4">
                    <div class="icon-box primary mx-auto"><iconify-icon icon="solar:laptop-minimalistic-line-duotone" class="fs-5"></iconify-icon></div>
                    <span class="fs-2 text-muted d-block mt-2">PC</span>
                    <h6 class="fw-bold mb-0">87%</h6>
                  </div>
                  <div class="col-4">
                    <div class="icon-box secondary mx-auto"><iconify-icon icon="solar:smartphone-line-duotone" class="fs-5"></iconify-icon></div>
                    <span class="fs-2 text-muted d-block mt-2">Móvil</span>
                    <h6 class="fw-bold mb-0">9.2%</h6>
                  </div>
                  <div class="col-4">
                    <div class="icon-box success mx-auto"><iconify-icon icon="solar:tablet-line-duotone" class="fs-5"></iconify-icon></div>
                    <span class="fs-2 text-muted d-block mt-2">Tablet</span>
                    <h6 class="fw-bold mb-0">3.1%</h6>
                  </div>
                </div>

                <div class="vstack gap-4">
                  <div>
                    <div class="d-flex justify-content-between align-items-end mb-1">
                      <span class="fs-2 fw-semibold">Computadoras</span>
                      <span class="fs-2 fw-bold text-primary">87%</span>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-primary shadow-sm" style="width: 87%"></div>
                    </div>
                  </div>
                  <div>
                    <div class="d-flex justify-content-between align-items-end mb-1">
                      <span class="fs-2 fw-semibold">Smartphones</span>
                      <span class="fs-2 fw-bold text-info">9.2%</span>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-info shadow-sm" style="width: 9.2%"></div>
                    </div>
                  </div>
                  <div>
                    <div class="d-flex justify-content-between align-items-end mb-1">
                      <span class="fs-2 fw-semibold">Tabletas</span>
                      <span class="fs-2 fw-bold text-success">3.1%</span>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-success shadow-sm" style="width: 3.1%"></div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- Blog Section -->
          <div class="col-lg-4">
            <div class="card hover-img h-100 p-0 border-0 shadow-none">
              <div class="position-relative">
                <a href="javascript:void(0)" class="d-block overflow-hidden rounded-4">
                  <img src="<?= base_url() ?>/assets/images/blog/blog-img1.jpg" class="card-img-top blog-img transition-transform" alt="blog">
                </a>
                <span class="badge bg-white text-dark fs-2 fw-bold position-absolute bottom-0 end-0 m-3 py-2 px-3 shadow-sm rounded-pill">2 min</span>
              </div>
              <div class="card-body px-0 pt-4">
                <span class="badge bg-primary-subtle text-primary fs-2 fw-bold px-3 py-2 rounded-pill mb-3">Social</span>
                <a class="d-block fs-5 text-dark fw-bold mb-3 text-decoration-none lh-sm" href="">Tendencias tecnológicas en Japón para 2025</a>
                <div class="d-flex align-items-center gap-3 text-muted fs-2 fw-medium">
                  <div class="d-flex align-items-center gap-1"><i class="bi bi-eye-fill"></i> 9k</div>
                  <div class="d-flex align-items-center gap-1"><i class="bi bi-chat-dots-fill"></i> 3</div>
                  <div class="ms-auto"><i class="bi bi-calendar-event"></i> Dic 19</div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card hover-img h-100 p-0 border-0 shadow-none">
              <div class="position-relative">
                <a href="javascript:void(0)" class="d-block overflow-hidden rounded-4">
                  <img src="<?= base_url() ?>/assets/images/blog/blog-img2.jpg" class="card-img-top blog-img transition-transform" alt="blog">
                </a>
                <span class="badge bg-white text-dark fs-2 fw-bold position-absolute bottom-0 end-0 m-3 py-2 px-3 shadow-sm rounded-pill">2 min</span>
              </div>
              <div class="card-body px-0 pt-4">
                <span class="badge bg-warning-subtle text-warning fs-2 fw-bold px-3 py-2 rounded-pill mb-3">Gadgets</span>
                <a class="d-block fs-5 text-dark fw-bold mb-3 text-decoration-none lh-sm" href="">Intel y la batalla por la innovación en chips</a>
                <div class="d-flex align-items-center gap-3 text-muted fs-2 fw-medium">
                  <div class="d-flex align-items-center gap-1"><i class="bi bi-eye-fill"></i> 4k</div>
                  <div class="d-flex align-items-center gap-1"><i class="bi bi-chat-dots-fill"></i> 38</div>
                  <div class="ms-auto"><i class="bi bi-calendar-event"></i> Dic 18</div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card hover-img h-100 p-0 border-0 shadow-none">
              <div class="position-relative">
                <a href="javascript:void(0)" class="d-block overflow-hidden rounded-4">
                  <img src="<?= base_url() ?>/assets/images/blog/blog-img3.jpg" class="card-img-top blog-img transition-transform" alt="blog">
                </a>
                <span class="badge bg-white text-dark fs-2 fw-bold position-absolute bottom-0 end-0 m-3 py-2 px-3 shadow-sm rounded-pill">2 min</span>
              </div>
              <div class="card-body px-0 pt-4">
                <span class="badge bg-success-subtle text-success fs-2 fw-bold px-3 py-2 rounded-pill mb-3">Salud</span>
                <a class="d-block fs-5 text-dark fw-bold mb-3 text-decoration-none lh-sm" href="">Impacto del teletrabajo en la salud mental</a>
                <div class="d-flex align-items-center gap-3 text-muted fs-2 fw-medium">
                  <div class="d-flex align-items-center gap-1"><i class="bi bi-eye-fill"></i> 9.4k</div>
                  <div class="d-flex align-items-center gap-1"><i class="bi bi-chat-dots-fill"></i> 12</div>
                  <div class="ms-auto"><i class="bi bi-calendar-event"></i> Dic 17</div>
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