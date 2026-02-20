<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inicio de Sesión - Gestia ERP</title>
  <link rel="shortcut icon" type="image/png" href="<?= media() ?>/images/logos/icono.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --primary: #667eea;
      --primary-dark: #5568d3;
      --accent: #764ba2;
      --surface: rgba(255, 255, 255, 0.85);
      --glass-border: rgba(255, 255, 255, 0.6);
      --shadow-color: rgba(102, 126, 234, 0.15);
      --error: #ef4444;
      --success: #10b981;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      overflow: hidden;
      position: relative;
    }

    /* Enhanced Animated Background */
    .bg-animation {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
      overflow: hidden;
    }

    .blob {
      position: absolute;
      filter: blur(80px);
      opacity: 0.5;
      animation: float 25s infinite ease-in-out;
    }

    .blob-1 {
      top: -15%;
      left: -10%;
      width: 700px;
      height: 700px;
      background: radial-gradient(circle, rgba(147, 51, 234, 0.4) 0%, transparent 70%);
      animation-delay: 0s;
    }

    .blob-2 {
      bottom: -20%;
      right: -15%;
      width: 800px;
      height: 800px;
      background: radial-gradient(circle, rgba(59, 130, 246, 0.4) 0%, transparent 70%);
      animation-delay: -8s;
    }

    .blob-3 {
      top: 50%;
      left: 50%;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(236, 72, 153, 0.3) 0%, transparent 70%);
      transform: translate(-50%, -50%);
      animation-delay: -15s;
    }

    @keyframes float {
      0%, 100% {
        transform: translate(0, 0) scale(1) rotate(0deg);
      }
      33% {
        transform: translate(50px, -50px) scale(1.1) rotate(120deg);
      }
      66% {
        transform: translate(-30px, 40px) scale(0.95) rotate(240deg);
      }
    }

    /* Premium Glass Card */
    .login-container {
      perspective: 1500px;
      width: 100%;
      max-width: 460px;
      padding: 24px;
      position: relative;
      z-index: 10;
    }

    .login-card {
      background: var(--surface);
      backdrop-filter: blur(30px) saturate(180%);
      -webkit-backdrop-filter: blur(30px) saturate(180%);
      border-radius: 32px;
      border: 1.5px solid var(--glass-border);
      box-shadow:
        0 30px 60px -15px rgba(0, 0, 0, 0.15),
        0 15px 30px -10px rgba(0, 0, 0, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
      padding: 56px 48px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--primary-gradient);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-8px);
      box-shadow:
        0 40px 80px -20px rgba(0, 0, 0, 0.2),
        0 20px 40px -15px rgba(102, 126, 234, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
    }

    .login-card:hover::before {
      opacity: 1;
    }

    /* Logo & Branding */
    .brand-logo {
      text-align: center;
      margin-bottom: 40px;
      animation: fadeInDown 0.6s ease-out;
    }

    .brand-logo img {
      height: 56px;
      filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.1));
      transition: transform 0.3s ease;
    }

    .brand-logo img:hover {
      transform: scale(1.05);
    }

    /* Typography */
    .text-center h3 {
      font-weight: 800;
      font-size: 1.75rem;
      background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 0.5rem;
      letter-spacing: -0.8px;
      animation: fadeInUp 0.6s ease-out 0.1s both;
    }

    p.subtitle {
      color: #64748b;
      font-size: 0.95rem;
      font-weight: 500;
      margin-bottom: 2.5rem;
      animation: fadeInUp 0.6s ease-out 0.2s both;
    }

    /* Enhanced Input Groups */
    .input-group-custom {
      position: relative;
      margin-bottom: 1.5rem;
      animation: fadeInUp 0.6s ease-out calc(0.3s + var(--delay)) both;
    }

    .input-group-custom:nth-child(1) { --delay: 0s; }
    .input-group-custom:nth-child(2) { --delay: 0.1s; }
    .input-group-custom:nth-child(3) { --delay: 0.2s; }

    .input-group-custom i {
      position: absolute;
      left: 22px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 1.3rem;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 10;
      pointer-events: none;
    }

    .toggle-password {
      position: absolute;
      right: 22px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 1.25rem;
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 10;
      padding: 4px;
    }

    .toggle-password:hover {
      color: var(--primary);
      transform: translateY(-50%) scale(1.1);
    }

    .form-control-custom {
      width: 100%;
      padding: 18px 24px 18px 60px;
      border: 2px solid rgba(226, 232, 240, 0.8);
      background: rgba(255, 255, 255, 0.7);
      border-radius: 16px;
      font-size: 0.95rem;
      font-weight: 500;
      color: #1e293b;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .form-control-custom.has-toggle {
      padding-right: 55px;
    }

    .form-control-custom:hover {
      border-color: rgba(148, 163, 184, 0.4);
      background: rgba(255, 255, 255, 0.85);
    }

    .form-control-custom:focus {
      background: #ffffff;
      border-color: var(--primary);
      outline: none;
      box-shadow: 
        0 0 0 4px rgba(102, 126, 234, 0.12),
        0 4px 12px rgba(102, 126, 234, 0.15);
      transform: translateY(-1px);
    }

    .form-control-custom:focus ~ i {
      color: var(--primary);
      transform: translateY(-50%) scale(1.1);
    }

    .form-control-custom::placeholder {
      color: #94a3b8;
      font-weight: 400;
    }

    /* Premium Button */
    .btn-login {
      background: var(--primary-gradient);
      color: white;
      width: 100%;
      padding: 18px;
      border-radius: 16px;
      border: none;
      font-weight: 700;
      font-size: 1rem;
      letter-spacing: 0.3px;
      margin-top: 1.5rem;
      cursor: pointer;
      box-shadow: 
        0 12px 24px -8px rgba(102, 126, 234, 0.4),
        0 4px 12px rgba(102, 126, 234, 0.2);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      animation: fadeInUp 0.6s ease-out 0.5s both;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: 0.6s;
    }

    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: 
        0 20px 35px -10px rgba(102, 126, 234, 0.5),
        0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-login:hover::before {
      left: 100%;
    }

    .btn-login:active {
      transform: translateY(-1px) scale(0.98);
    }

    .btn-login:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
    }

    /* Footer */
    .footer {
      margin-top: 2.5rem;
      text-align: center;
      color: #94a3b8;
      font-size: 0.85rem;
      font-weight: 500;
      animation: fadeInUp 0.6s ease-out 0.6s both;
      line-height: 1.6;
    }

    /* Animations */
    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-8px); }
      75% { transform: translateX(8px); }
    }

    .shake {
      animation: shake 0.4s ease-in-out;
    }

    /* Error State */
    .form-control-custom.error {
      border-color: var(--error) !important;
      background: rgba(254, 242, 242, 0.8);
      animation: shake 0.4s ease-in-out;
    }

    .form-control-custom.error ~ i {
      color: var(--error);
    }

    /* Loading Spinner */
    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .bx-spin {
      animation: spin 1s linear infinite;
    }

    /* Responsive */
    @media (max-width: 480px) {
      .login-card {
        padding: 40px 32px;
        border-radius: 24px;
      }

      .text-center h3 {
        font-size: 1.5rem;
      }

      .form-control-custom {
        padding: 16px 20px 16px 55px;
      }

      .btn-login {
        padding: 16px;
      }
    }
  </style>
</head>

<body>

  <div class="bg-animation">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
  </div>

  <div class="login-container">
    <div class="login-card">
      <div class="brand-logo">
        <img src="<?= media() ?>/images/logos/logoGestia.jpg" alt="Gestia Logo">
      </div>
      <div class="text-center">
        <h3>Bienvenido de nuevo</h3>
        <p class="subtitle">Ingresa tus credenciales para continuar</p>
      </div>

      <form id="loginForm" novalidate onsubmit="return false;">

        <div class="input-group-custom">
          <input type="text" class="form-control-custom" id="ruc" placeholder="RUC de la empresa" maxlength="11" required autocomplete="off">
          <i class='bx bxs-business'></i>
        </div>

        <div class="input-group-custom">
          <input type="text" class="form-control-custom" id="usuario" placeholder="Nombre de usuario" required autocomplete="username">
          <i class='bx bxs-user'></i>
        </div>

        <div class="input-group-custom">
          <input type="password" class="form-control-custom has-toggle" id="clave" placeholder="Contraseña de acceso" required autocomplete="current-password">
          <i class='bx bxs-lock-alt'></i>
          <i class='bx bx-hide toggle-password' id="togglePassword"></i>
        </div>

        <button type="button" onclick="iniciarsesion()" class="btn-login">
          Iniciar Sesión
        </button>

        <div class="footer">
          &copy; <?= date('Y') ?> Gestia ERP<br>
          Todos los derechos reservados
        </div>
      </form>
    </div>
  </div>

  <!-- Dependencies -->
  <script src="<?= media() ?>/libs/jquery/dist/jquery.min.js"></script>
  <script src="<?= media() ?>/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const base_url = "<?= base_url(); ?>";
  </script>

  <script>
    // Password Toggle
    $('#togglePassword').on('click', function() {
      const passwordInput = $('#clave');
      const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
      passwordInput.attr('type', type);
      $(this).toggleClass('bx-hide bx-show');
    });

    // Input animations on focus
    $('.form-control-custom').on('focus', function() {
      $(this).parent().addClass('focused');
    }).on('blur', function() {
      $(this).parent().removeClass('focused');
    });

    function iniciarsesion() {
      const ruc = $('#ruc').val().trim();
      const usuario = $('#usuario').val().trim();
      const clave = $('#clave').val().trim();
      const action = 'valida_login';
      const $btn = $('.btn-login');
      let valid = true;

      // Reset error states
      $('.form-control-custom').removeClass('error').css('border-color', '');

      // Validation
      if (ruc.length !== 11 || !/^\d{11}$/.test(ruc)) {
        $('#ruc').addClass('error').focus();
        valid = false;
      } else if (usuario === "") {
        $('#usuario').addClass('error').focus();
        valid = false;
      } else if (clave === "") {
        $('#clave').addClass('error').focus();
        valid = false;
      }

      if (!valid) return;

      // Loading State
      const originalText = $btn.html();
      $btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Verificando...');

      $.ajax({
        url: base_url + '/assets/ajax/login.php',
        type: 'POST',
        dataType: 'json',
        data: {
          ruc,
          usuario,
          clave,
          action
        },
        success: function(response) {
          if (response.status === 'ok') {
            $btn.html('<i class="bx bx-check-circle"></i> ¡Éxito!').css('background', 'linear-gradient(135deg, #10b981 0%, #059669 100%)');
            setTimeout(() => {
              window.location.href = base_url + '/inicio';
            }, 600);
          } else {
            showError(response.message || 'Credenciales incorrectas');
            $btn.prop('disabled', false).html(originalText);
            $('#clave').val('').focus();
          }
        },
        error: function() {
          showError('Error de conexión. Por favor, intenta nuevamente.');
          $btn.prop('disabled', false).html(originalText);
        }
      });
    }

    function showError(message) {
      // Create elegant error notification
      const errorDiv = $('<div>')
        .css({
          position: 'fixed',
          top: '20px',
          right: '20px',
          background: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
          color: 'white',
          padding: '16px 24px',
          borderRadius: '12px',
          boxShadow: '0 10px 25px rgba(239, 68, 68, 0.3)',
          zIndex: 9999,
          fontWeight: '600',
          fontSize: '0.9rem',
          maxWidth: '350px',
          animation: 'slideInRight 0.3s ease-out'
        })
        .html('<i class="bx bx-error-circle" style="margin-right: 8px; font-size: 1.2rem; vertical-align: middle;"></i>' + message);

      $('body').append(errorDiv);

      setTimeout(() => {
        errorDiv.fadeOut(300, function() {
          $(this).remove();
        });
      }, 3500);
    }

    // Enter key support
    $(document).on('keypress', function(e) {
      if (e.which == 13 && !$('.btn-login').prop('disabled')) {
        iniciarsesion();
      }
    });

    // Add slide in animation
    const style = document.createElement('style');
    style.textContent = `
      @keyframes slideInRight {
        from {
          transform: translateX(400px);
          opacity: 0;
        }
        to {
          transform: translateX(0);
          opacity: 1;
        }
      }
    `;
    document.head.appendChild(style);
  </script>
</body>

</html>