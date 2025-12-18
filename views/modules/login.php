<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inicio de Sesión</title>
  <link rel="shortcut icon" type="image/png" href="<?= media() ?>/images/logos/icono.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <style>
    :root {
      --primary: #4f46e5;
      --primary-dark: #4338ca;
      --secondary: #0ea5e9;
      --surface: rgba(255, 255, 255, 0.75);
      --glass-border: rgba(255, 255, 255, 0.5);
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #f8fafc;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      overflow: hidden;
      position: relative;
    }

    /* Dynamic Background Blobs */
    .blob {
      position: absolute;
      filter: blur(50px);
      z-index: -1;
      opacity: 0.6;
      animation: float 20s infinite alternate;
    }

    .blob-1 {
      top: -10%;
      left: -10%;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, #c084fc 0%, rgba(192, 132, 252, 0) 70%);
      animation-delay: 0s;
    }

    .blob-2 {
      bottom: -15%;
      right: -10%;
      width: 700px;
      height: 700px;
      background: radial-gradient(circle, #60a5fa 0%, rgba(96, 165, 250, 0) 70%);
      animation-delay: -5s;
    }

    .blob-3 {
      top: 40%;
      left: 40%;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, #f472b6 0%, rgba(244, 114, 182, 0) 70%);
      transform: translate(-50%, -50%);
      animation-delay: -10s;
    }

    @keyframes float {
      0% {
        transform: translate(0, 0) scale(1);
      }

      100% {
        transform: translate(30px, 50px) scale(1.1);
      }
    }

    /* Glass Card */
    .login-container {
      perspective: 1000px;
      width: 100%;
      max-width: 440px;
      padding: 20px;
    }

    .login-card {
      background: var(--surface);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-radius: 24px;
      border: 1px solid var(--glass-border);
      box-shadow:
        0 20px 50px -12px rgba(0, 0, 0, 0.1),
        0 10px 20px -5px rgba(0, 0, 0, 0.04);
      padding: 48px 40px;
      transition: transform 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-5px);
    }

    /* Typography & Elements */
    .brand-logo img {
      height: 48px;
      margin-bottom: 32px;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }

    h3 {
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 0.5rem;
      letter-spacing: -0.5px;
    }

    p.subtitle {
      color: #64748b;
      font-size: 0.95rem;
      margin-bottom: 2.5rem;
    }

    /* Input Groups */
    .input-group-custom {
      position: relative;
      margin-bottom: 1.25rem;
    }

    .input-group-custom i {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 1.25rem;
      transition: color 0.3s ease;
      z-index: 10;
    }

    .form-control-custom {
      width: 100%;
      padding: 16px 20px 16px 55px;
      border: 2px solid transparent;
      background: rgba(255, 255, 255, 0.6);
      border-radius: 16px;
      font-size: 0.95rem;
      color: #334155;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .form-control-custom:focus {
      background: #fff;
      border-color: var(--primary);
      outline: none;
      box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .form-control-custom:focus+i {
      color: var(--primary);
    }

    .form-control-custom::placeholder {
      color: #94a3b8;
    }

    /* Button */
    .btn-login {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      width: 100%;
      padding: 16px;
      border-radius: 16px;
      border: none;
      font-weight: 600;
      font-size: 1rem;
      margin-top: 1rem;
      cursor: pointer;
      box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.4);
    }

    .btn-login:active {
      transform: scale(0.98);
    }

    .btn-login::after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: 0.5s;
    }

    .btn-login:hover::after {
      left: 100%;
    }

    .footer {
      margin-top: 2rem;
      text-align: center;
      color: #94a3b8;
      font-size: 0.85rem;
    }
  </style>
</head>

<body>

  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>
  <div class="blob blob-3"></div>

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
          <input type="text" class="form-control-custom" id="ruc" placeholder="RUC de la empresa" maxlength="11" required>
          <i class='bx bxs-business'></i>
          <div class="invalid-feedback d-block invalid-feedback-custom" style="display:none !important">RUC inválido</div>
        </div>

        <div class="input-group-custom">
          <input type="text" class="form-control-custom" id="usuario" placeholder="Nombre de usuario" required>
          <i class='bx bxs-user'></i>
        </div>

        <div class="input-group-custom">
          <input type="password" class="form-control-custom" id="clave" placeholder="Contraseña de acceso" required>
          <i class='bx bxs-lock-alt'></i>
        </div>

        <button type="button" onclick="iniciarsesion()" class="btn-login">
          Iniciar Sesión
        </button>

        <div class="footer">
          &copy; <?= date('Y') ?> Gestia ERP. <br>Todos los derechos reservados.
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
    function iniciarsesion() {
      const ruc = $('#ruc').val().trim();
      const usuario = $('#usuario').val().trim();
      const clave = $('#clave').val().trim();
      const action = 'valida_login';
      const $btn = $('.btn-login');
      let valid = true;

      // Visual Reset
      $('.form-control-custom').css('border-color', 'transparent');

      if (ruc.length !== 11 || !/^\d{11}$/.test(ruc)) {
        $('#ruc').css('border-color', '#ef4444').focus(); // Red border
        blinkInput('#ruc');
        valid = false;
      } else if (usuario === "") {
        $('#usuario').css('border-color', '#ef4444').focus();
        blinkInput('#usuario');
        valid = false;
      } else if (clave === "") {
        $('#clave').css('border-color', '#ef4444').focus();
        blinkInput('#clave');
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
            $btn.html('<i class="bx bx-check"></i> ¡Éxito!');
            setTimeout(() => {
              window.location.href = base_url + '/inicio';
            }, 500);
          } else {
            alert(response.message || 'Credenciales incorrectas.');
            $btn.prop('disabled', false).html(originalText);
            $('#clave').val('').focus();
          }
        },
        error: function() {
          alert('Error de conexión.');
          $btn.prop('disabled', false).html(originalText);
        }
      });
    }

    function blinkInput(selector) {
      $(selector).animate({
        opacity: 0.5
      }, 100).animate({
        opacity: 1
      }, 100);
    }

    // Enter key support
    $(document).on('keypress', function(e) {
      if (e.which == 13) iniciarsesion();
    });
  </script>
</body>

</html>