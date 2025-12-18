<aside class="left-sidebar bg-dark text-white vh-100 position-fixed shadow-lg" id="sidebar" style="z-index: 1200;">
  <!-- Logo y control de menú -->
  <div class="brand-logo d-flex align-items-center justify-content-between p-4 border-bottom border-secondary">
    <a href="<?= base_url() ?>/inicio" class="text-nowrap logo-img d-flex align-items-center text-decoration-none">
      <img src="<?= base_url() ?>/assets/images/logos/icono.png" alt="SmartBase - Inicio" class="me-3" style="height: 36px;">
      <span class="fw-bold fs-6 text-white" style="font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.5px;"><?= nombre() ?></span>
    </a>
    <button class="close-btn d-xl-none d-block border-0 bg-transparent" id="sidebarCollapse" aria-label="Alternar menú">
      <i class="ti ti-x fs-6 text-white"></i>
    </button>
  </div>

  <!-- Menú de navegación -->
  <nav class="sidebar-nav scroll-sidebar" data-simplebar>
    <ul class="list-unstyled mb-0 px-3" id="sidebarnav">

      <!-- INICIO -->
      <li class="nav-section text-uppercase small fw-bold text-white-50 mt-3 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Navegación</li>
      <li class="nav-item mb-1">
        <a href="<?= base_url() ?>/inicio" class="nav-link d-flex align-items-center px-3 py-2_5 rounded-3 text-decoration-none sidebar-link text-white-50">
          <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">Dashboard</span>
        </a>
      </li>

      <!-- MAESTROS -->
      <li class="nav-section text-uppercase small fw-bold text-white-50 mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Administración</li>
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center px-3 py-2_5 rounded-3 text-decoration-none sidebar-link has-arrow text-white-50"
          data-bs-toggle="collapse"
          href="#submenu-maestros"
          role="button"
          aria-expanded="false"
          aria-controls="submenu-maestros">
          <iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">Maestros</span>
          <i class="ti ti-chevron-down ms-auto small transition dropdown-arrow" aria-hidden="true"></i>
        </a>

        <ul class="collapse list-unstyled ms-3 mt-1 ps-2 border-start border-secondary" id="submenu-maestros" data-bs-parent="#sidebarnav">
          <li class="nav-item"><a href="<?= base_url() ?>/centro_costos" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">Centro de Costos</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/presupuestos" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">Presupuestos</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/concepto_gasto" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">Concepto de Gasto</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/bancos" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">Bancos</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/locales" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">Locales</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/clientes" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">Empresas</a></li>
        </ul>
      </li>

      <!-- SIRE -->
      <li class="nav-section text-uppercase small fw-bold text-white-50 mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">SUNAT</li>
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center px-3 py-2_5 rounded-3 text-decoration-none sidebar-link has-arrow text-white-50"
          data-bs-toggle="collapse"
          href="#submenu-sire"
          role="button"
          aria-expanded="false"
          aria-controls="submenu-sire">
          <iconify-icon icon="solar:document-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">SUNAT</span>
          <i class="ti ti-chevron-down ms-auto small transition dropdown-arrow" aria-hidden="true"></i>
        </a>

        <ul class="collapse list-unstyled ms-3 mt-1 ps-2 border-start border-secondary" id="submenu-sire" data-bs-parent="#sidebarnav">
          <li class="nav-item"><a href="<?= base_url() ?>/sire_compras" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">SIRE Compras</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/sire_ventas" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">SIRE Ventas</a></li>
          <div class="my-1 border-top border-secondary opacity-50"></div>
          <li class="nav-item"><a href="<?= base_url() ?>/honorarios" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">HONORARIOS</a></li>
        </ul>
      </li>


      <!-- PLANILLAS -->
      <li class="nav-section text-uppercase small fw-bold text-white-50 mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">PLANILLAS</li>
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center px-3 py-2_5 rounded-3 text-decoration-none sidebar-link has-arrow text-white-50"
          data-bs-toggle="collapse"
          href="#submenu-planilla"
          role="button"
          aria-expanded="false"
          aria-controls="submenu-planilla">
          <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">PLANILLAS</span>
          <i class="ti ti-chevron-down ms-auto small transition dropdown-arrow" aria-hidden="true"></i>
        </a>

        <ul class="collapse list-unstyled ms-3 mt-1 ps-2 border-start border-secondary" id="submenu-planilla" data-bs-parent="#sidebarnav">
          <li class="nav-item"><a href="<?= base_url() ?>/trabajadores" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">TRABAJADOR</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/afp" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">AFP</a></li>
          <div class="my-1 border-top border-secondary opacity-50"></div>
          <li class="nav-item"><a href="<?= base_url() ?>/configuracion" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">CONFIGURACION</a></li>
        </ul>
      </li>
      <!-- EXTRA -->
      <li class="nav-section text-uppercase small fw-bold text-white-50 mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Operaciones</li>
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center px-3 py-2_5 rounded-3 text-decoration-none sidebar-link has-arrow text-white-50"
          data-bs-toggle="collapse"
          href="#submenu-extra"
          role="button"
          aria-expanded="false"
          aria-controls="submenu-extra">
          <iconify-icon icon="solar:widget-2-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">Extras</span>
          <i class="ti ti-chevron-down ms-auto small transition dropdown-arrow" aria-hidden="true"></i>
        </a>

        <ul class="collapse list-unstyled ms-3 mt-1 ps-2 border-start border-secondary" id="submenu-extra" data-bs-parent="#sidebarnav">
          <li class="nav-item"><a href="<?= base_url() ?>/detracciones" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">Detracciones Ventas</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/rendiciones" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">Rendiciones</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/bancos_procesos" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">Bancos</a></li>
          <li class="nav-item"><a href="<?= base_url() ?>/importar_banco" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">Importar PDF</a></li>
        </ul>
      </li>


      <li class="nav-section text-uppercase small fw-bold text-white-50 mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Reportes</li>
      <li class="nav-item mb-1">
        <a class="nav-link d-flex align-items-center px-3 py-2_5 rounded-3 text-decoration-none sidebar-link has-arrow text-white-50"
          data-bs-toggle="collapse"
          href="#submenu-reporte"
          role="button"
          aria-expanded="false"
          aria-controls="submenu-reporte">
          <iconify-icon icon="solar:chart-square-bold-duotone" class="fs-5 me-3 nav-icon"></iconify-icon>
          <span class="fw-medium">Reportes</span>
          <i class="ti ti-chevron-down ms-auto small transition dropdown-arrow" aria-hidden="true"></i>
        </a>

        <ul class="collapse list-unstyled ms-3 mt-1 ps-2 border-start border-secondary" id="submenu-reporte" data-bs-parent="#sidebarnav">
          <li class="nav-item"><a href="<?= base_url() ?>/rpt_rendicion" class="d-block py-2 px-3 rounded-2 text-decoration-none text-white-50 sub-link">Rendiciones</a></li>

        </ul>
      </li>

    </ul>

    <!-- Estado del sistema -->
    <div class="system-status mt-5 mx-3 mb-4 p-3 bg-darker rounded-4 border border-secondary">
      <div class="d-flex align-items-center">
        <span class="position-relative d-flex align-items-center justify-content-center bg-white/10 rounded-circle shadow-sm me-2" style="width: 24px; height: 24px;">
          <span class="status-indicator bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
        </span>
        <small class="text-white fw-semibold">Sistema Online</small>
      </div>
      <small class="text-white-50 d-block mt-2" style="font-size: 0.7rem;">Última actualización: Hoy</small>
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
        link.classList.remove('text-white-50');
        link.classList.add('text-white');

        // Si es un sub-link
        if (link.classList.contains('sub-link')) {
          link.classList.add('fw-semibold', 'text-white');
          // link.style.background = "rgba(255,255,255,0.1)"; // Optional: highlight sublink bg
        }

        // Expandir menú padre si está en un submenú
        const parentCollapse = link.closest('.collapse');
        if (parentCollapse) {
          const toggle = document.querySelector(`[href="#${parentCollapse.id}"]`);
          if (toggle) {
            toggle.setAttribute('aria-expanded', 'true');
            toggle.classList.add('active-parent');
            toggle.classList.add('text-white'); // Color parent text
            const icon = toggle.querySelector('.dropdown-arrow');
            if (icon) icon.classList.add('rotate-180');
          }
          parentCollapse.classList.add('show');
        }
      }
    });
  });
</script>

<style>
  /* Variables locales para sidebar */
  :root {
    --sidebar-width: 270px;
    --sidebar-collapsed-width: 80px;
    --primary: #4f46e5;
    --primary-light: #eef2ff;
    --primary-dark: #4338ca;
  }

  /* Layout Principal */
  .left-sidebar {
    width: var(--sidebar-width);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    top: 0;
    left: 0;
    background-color: #0d1117 !important;
    /* GitHub Dark Dimmed style for better contrast */
    box-shadow: 4px 0 24px rgba(0, 0, 0, 0.4);
  }

  .left-sidebar.collapsed {
    transform: translateX(-100%);
  }

  /* Scrollbar Custom */
  .scroll-sidebar {
    height: calc(100vh - 80px);
    overflow-y: auto;
  }

  .scroll-sidebar::-webkit-scrollbar {
    width: 5px;
  }

  .scroll-sidebar::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 4px;
  }

  .scroll-sidebar::-webkit-scrollbar-track {
    background: transparent;
  }

  /* Links Generales */
  .sidebar-link {
    color: #94a3b8 !important;
    /* Slate 400 */
    transition: all 0.2s ease-in-out;
  }

  .sidebar-link:hover {
    color: #fff !important;
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(4px);
    color: #fff !important;
  }

  .sidebar-link .nav-icon {
    transition: color 0.2s;
  }

  .sidebar-link:hover .nav-icon {
    color: #fff;
  }

  /* Estado Activo (Principal) */
  .sidebar-link.active {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
  }

  .sidebar-link.active .nav-icon {
    color: white !important;
  }

  .sidebar-link.active .dropdown-arrow {
    color: white !important;
  }

  /* Submenus */
  .sub-link {
    transition: all 0.2s;
    color: #94a3b8 !important;
  }

  .sub-link:hover {
    color: #fff !important;
    background-color: rgba(255, 255, 255, 0.05);
    padding-left: 1.5rem !important;
  }

  /* Utilidades */
  .py-2_5 {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
  }

  .rotate-180 {
    transform: rotate(180deg);
  }

  .bg-darker {
    background-color: rgba(0, 0, 0, 0.2);
  }

  /* Responsive Mobile */
  @media (max-width: 1199.98px) {
    .left-sidebar {
      transform: translateX(-105%);
      /* Oculto por defecto en móvil */
    }

    .left-sidebar.collapsed {
      transform: translateX(0);
      /* Mostrar al activar */
    }
  }

  .active-parent {
    color: #fff !important;
    background-color: rgba(255, 255, 255, 0.05);
  }

  /* Main Content Adjustment */
  .body-wrapper {
    margin-left: var(--sidebar-width);
    transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  @media (max-width: 1199.98px) {
    .body-wrapper {
      margin-left: 0;
    }
  }

  /* Handle Collapsed State on Desktop if needed */
  body.sidebar-collapsed .body-wrapper {
    margin-left: 0;
  }
</style>
```