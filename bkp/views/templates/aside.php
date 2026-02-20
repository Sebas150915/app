<aside class="left-sidebar vh-100 position-fixed shadow-sm" id="sidebar" style="z-index: 1200;">
  <!-- Logo y control de menú -->
  <div class="brand-logo d-flex align-items-center justify-content-between p-4 border-bottom">
    <a href="<?= base_url() ?>/inicio" class="text-nowrap logo-img d-flex align-items-center text-decoration-none">
      <img src="<?= base_url() ?>/assets/images/logos/icono.png" alt="SmartBase - Inicio" class="me-3" style="height: 36px;">
      <span class="fw-bold fs-6 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.5px;"><?= nombre() ?></span>
    </a>
    <button class="close-btn d-xl-none d-block border-0 bg-transparent" id="sidebarCollapse" aria-label="Alternar menú">
      <i class="ti ti-x fs-6 text-dark"></i>
    </button>
  </div>

  <!-- Menú de navegación -->
  <nav class="sidebar-nav scroll-sidebar" data-simplebar>
    <ul class="list-unstyled mb-0 px-3" id="sidebarnav">

      <!-- INICIO -->
      <li class="nav-section text-uppercase small fw-bold text-muted mt-3 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Navegación</li>
      <li class="nav-item mb-1">
        <a href="<?= base_url() ?>/inicio" class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none sidebar-link">
          <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">Dashboard</span>
        </a>
      </li>

      <!-- MAESTROS -->
      <li class="nav-section text-uppercase small fw-bold text-muted mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Administración</li>
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none sidebar-link has-arrow"
          data-bs-toggle="collapse"
          href="#submenu-maestros"
          role="button"
          aria-expanded="false"
          aria-controls="submenu-maestros">
          <iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">Maestros</span>
          <i class="ti ti-chevron-down ms-auto small transition dropdown-arrow" aria-hidden="true"></i>
        </a>

        <ul class="collapse list-unstyled ms-3 mt-1 ps-2 border-start" id="submenu-maestros" data-bs-parent="#sidebarnav">
          <li class="nav-item"><a href="<?= base_url() ?>/centro_costos" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Centro de Costos</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/presupuestos" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Presupuestos</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/concepto_gasto" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Concepto de Gasto</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/bancos" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Bancos</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/locales" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Locales</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/clientes" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Empresas</a></li>
          <?php if($_SESSION['id_empresa']==1 || $_SESSION['id_empresa']==3){ ?>
          <li class="nav-item"><a href="<?= base_url() ?>/clientes_empresas" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Clientes</a></li>
          <?php } ?>
        </ul>
      </li>

      <!-- SIRE -->
      <li class="nav-section text-uppercase small fw-bold text-muted mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">SUNAT</li>
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none sidebar-link has-arrow"
          data-bs-toggle="collapse"
          href="#submenu-sire"
          role="button"
          aria-expanded="false"
          aria-controls="submenu-sire">
          <iconify-icon icon="solar:document-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">SUNAT</span>
          <i class="ti ti-chevron-down ms-auto small transition dropdown-arrow" aria-hidden="true"></i>
        </a>

        <ul class="collapse list-unstyled ms-3 mt-1 ps-2 border-start" id="submenu-sire" data-bs-parent="#sidebarnav">
          <li class="nav-item"><a href="<?= base_url() ?>/sire_compras" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">SIRE Compras</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/sire_ventas" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">SIRE Ventas</a></li>
          <div class="my-1 border-top opacity-50"></div>
          <li class="nav-item"><a href="<?= base_url() ?>/honorarios" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">HONORARIOS</a></li>
        </ul>
      </li>


      <!-- PLANILLAS -->
      <li class="nav-section text-uppercase small fw-bold text-muted mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">PLANILLAS</li>
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none sidebar-link has-arrow"
          data-bs-toggle="collapse"
          href="#submenu-planilla"
          role="button"
          aria-expanded="false"
          aria-controls="submenu-planilla">
          <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">PLANILLAS</span>
          <i class="ti ti-chevron-down ms-auto small transition dropdown-arrow" aria-hidden="true"></i>
        </a>

        <ul class="collapse list-unstyled ms-3 mt-1 ps-2 border-start" id="submenu-planilla" data-bs-parent="#sidebarnav">
          <li class="nav-item"><a href="<?= base_url() ?>/trabajadores" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">TRABAJADOR</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/afp" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">AFP</a></li>
          <div class="my-1 border-top opacity-50"></div>
          <li class="nav-item"><a href="<?= base_url() ?>/configuracion" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">CONFIGURACION</a></li>
           <div class="my-1 border-top opacity-50"></div>
          <li class="nav-item"><a href="<?= base_url() ?>/planillas" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">PLANILLAS</a></li>
        </ul>
      </li>
      <!-- EXTRA -->
      <li class="nav-section text-uppercase small fw-bold text-muted mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Operaciones</li>
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none sidebar-link has-arrow"
          data-bs-toggle="collapse"
          href="#submenu-extra"
          role="button"
          aria-expanded="false"
          aria-controls="submenu-extra">
          <iconify-icon icon="solar:widget-2-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">Extras</span>
          <i class="ti ti-chevron-down ms-auto small transition dropdown-arrow" aria-hidden="true"></i>
        </a>

        <ul class="collapse list-unstyled ms-3 mt-1 ps-2 border-start" id="submenu-extra" data-bs-parent="#sidebarnav">
          <li class="nav-item"><a href="<?= base_url() ?>/detracciones" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Detracciones Ventas</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/rendiciones" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Rendiciones</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/bancos_procesos" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Bancos</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/importar_banco" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Importar PDF</a></li>
        </ul>
      </li>


      <li class="nav-section text-uppercase small fw-bold text-muted mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Reportes</li>
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none sidebar-link has-arrow"
          data-bs-toggle="collapse"
          href="#submenu-reporte"
          role="button"
          aria-expanded="false"
          aria-controls="submenu-reporte">
          <iconify-icon icon="solar:chart-square-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">Reportes</span>
          <i class="ti ti-chevron-down ms-auto small transition dropdown-arrow" aria-hidden="true"></i>
        </a>

        <ul class="collapse list-unstyled ms-3 mt-1 ps-2 border-start" id="submenu-reporte" data-bs-parent="#sidebarnav">
          <li class="nav-item"><a href="<?= base_url() ?>/rpt_rendicion" class="d-block py-2 px-3 rounded-2 text-decoration-none sub-link">Rendiciones</a></li>

        </ul>
      </li>

    </ul>

    <!-- Estado del sistema -->
    <div class="system-status mt-5 mx-3 mb-4 p-3 bg-light rounded-4 border">
      <div class="d-flex align-items-center">
        <span class="position-relative d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm me-2" style="width: 24px; height: 24px;">
          <span class="status-indicator bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
        </span>
        <small class="text-dark fw-semibold">Sistema Online</small>
      </div>
      <small class="text-muted d-block mt-2" style="font-size: 0.7rem;">Última actualización: Hoy</small>
    </div>
  </nav>
</aside>

<!-- Script para funcionalidades mejoradas -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');

    // Control de colapso del menú
    if (sidebarCollapse) {
      sidebarCollapse.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        document.body.classList.toggle('sidebar-collapsed');
      });
    }

    // Mejorar accesibilidad del menú desplegable
    const dropdownToggles = document.querySelectorAll('[data-bs-toggle="collapse"]');
    dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', function() {
        // Toggle arrow rotation
        const icon = this.querySelector('.dropdown-arrow');
        if (icon) {
          // Reset others
          document.querySelectorAll('.dropdown-arrow').forEach(i => {
            if (i !== icon && !i.closest('.show')) i.classList.remove('rotate-180');
          });
          icon.classList.toggle('rotate-180');
        }
      });
    });

    // Resaltar elemento activo
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('#sidebarnav a[href]');

    navLinks.forEach(link => {
      // Comparación simple (se puede mejorar si usas rutas absolutas/relativas complejas)
      if (link.getAttribute('href') === currentPath || (currentPath === '/' && link.getAttribute('href').includes('inicio'))) {
        link.classList.add('active');
        // link.classList.remove('text-white-50'); // Removed legacy class
        // link.classList.add('text-white'); // Removed legacy class

        // Si es un sub-link
        if (link.classList.contains('sub-link')) {
          link.classList.add('fw-semibold');
        }

        // Expandir menú padre si está en un submenú
        const parentCollapse = link.closest('.collapse');
        if (parentCollapse) {
          const toggle = document.querySelector(`[href="#${parentCollapse.id}"]`);
          if (toggle) {
            toggle.setAttribute('aria-expanded', 'true');
            toggle.classList.add('active-parent');
            // toggle.classList.add('text-white'); // Removed legacy class
            const icon = toggle.querySelector('.dropdown-arrow');
            if (icon) icon.classList.add('rotate-180');
          }
          parentCollapse.classList.add('show');
        }
      }
    });
  });
</script>
```
