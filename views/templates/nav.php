<nav class="navbar navbar-expand-lg navbar-light fixed-top px-0 py-3 glass-header">
  <div class="container-fluid px-4">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler nav-icon-hover p-0 me-3" id="headerCollapse" href="javascript:void(0)">
          <iconify-icon icon="solar:hamburger-menu-bold-duotone" class="fs-6"></iconify-icon>
        </a>
      </li>
    </ul>
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

        <li class="nav-item me-3">
          <a class="nav-link nav-icon-hover p-0" href="javascript:void(0)">
            <div class="position-relative d-inline-flex align-items-center justify-content-center">
              <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-6 text-dark"></iconify-icon>
              <div class="notification bg-primary rounded-circle position-absolute top-0 end-0 translate-middle p-1 border border-white">
                <span class="visually-hidden">New alerts</span>
              </div>
            </div>
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link nav-icon-hover d-flex align-items-center gap-2" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
            aria-expanded="false">
            <div class="d-none d-md-block text-end lh-sm">
              <span class="d-block fw-bold text-dark fs-2 text-truncate" style="max-width: 150px;">Administrador</span>
              <small class="d-block text-muted fs-1">Gestia ERP</small>
            </div>
            <img src="<?= base_url() ?>/assets/images/profile/user-1.jpg" alt="" width="40" height="40" class="rounded-circle border border-2 border-white shadow-sm transition-transform">
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up border-0 shadow-lg rounded-4 p-0 overflow-hidden" aria-labelledby="drop2" style="min-width: 200px;">
            <div class="p-4 bg-primary text-white text-center">
              <div class="d-inline-block position-relative mb-2">
                <img src="<?= base_url() ?>/assets/images/profile/user-1.jpg" alt="" width="56" height="56" class="rounded-circle border border-2 border-white">
              </div>
              <h6 class="mb-0 text-white">Administrador</h6>
              <small class="text-white-50">admin@gestia.com</small>
            </div>
            <div class="message-body p-2">
              <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item px-3 py-2 rounded-2">
                <iconify-icon icon="solar:user-circle-bold-duotone" class="fs-5 text-muted"></iconify-icon>
                <span class="fs-3">Mi Perfil</span>
              </a>
              <a href="<?= base_url() ?>/cerrar" class="btn btn-outline-danger w-100 mt-2 d-flex align-items-center justify-content-center gap-2">
                <iconify-icon icon="solar:logout-2-bold-duotone" class="fs-4"></iconify-icon>
                Cerrar Sesión
              </a>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<style>
  /* Glass Header Style */
  .glass-header {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    transition: all 0.3s ease;
  }

  .nav-icon-hover {
    transition: all 0.2s;
  }

  .nav-icon-hover:hover iconify-icon {
    color: var(--primary);
    transform: scale(1.1);
  }

  .notification {
    width: 10px;
    height: 10px;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7);
    }

    70% {
      box-shadow: 0 0 0 6px rgba(79, 70, 229, 0);
    }

    100% {
      box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
    }
  }

  .dropdown-menu-animate-up {
    animation: fadeInUp 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .transition-transform {
    transition: transform 0.2s;
  }

  .nav-link:hover .transition-transform {
    transform: scale(1.05);
  }
</style>