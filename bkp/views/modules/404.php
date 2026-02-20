<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página No Encontrada | 404 Error</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a6cf7;
            --secondary-color: #6b7dff;
            --dark-color: #1d2144;
            --light-color: #f5f8ff;
            --text-color: #6b7280;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .error-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .error-content {
            text-align: center;
            max-width: 650px;
            padding: 2rem;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 3px 3px 0 rgba(74, 108, 247, 0.1);
        }
        
        .error-title {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--dark-color);
        }
        
        .error-description {
            font-size: 1.125rem;
            color: var(--text-color);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }
        
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 2rem;
            font-weight: 500;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(74, 108, 247, 0.3);
        }
        
        .btn-primary-custom:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 108, 247, 0.4);
        }
        
        .error-illustration {
            max-width: 300px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }
        
        .search-box {
            max-width: 400px;
            margin: 0 auto 2rem;
        }
        
        .footer {
            background-color: white;
            padding: 1.5rem 0;
            border-top: 1px solid rgba(0,0,0,0.05);
            text-align: center;
            color: var(--text-color);
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }
            
            .error-title {
                font-size: 2rem;
            }
            
            .error-description {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .error-code {
                font-size: 4rem;
            }
            
            .error-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-content">
                        <!-- Ilustración SVG -->
                        <div class="error-illustration">
                            <svg viewBox="0 0 500 300" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#4a6cf7" d="M158.6,121.3c0,0-22.1-18.2-35.4-37.5c-13.3-19.3-18.6-39.6-18.6-39.6s-4.1,23.5,4.5,45.7
                                    c8.6,22.2,26.4,42.2,26.4,42.2L158.6,121.3z" opacity="0.1"/>
                                <path fill="#4a6cf7" d="M308.9,121.3c0,0,22.1-18.2,35.4-37.5c13.3-19.3,18.6-39.6,18.6-39.6s4.1,23.5-4.5,45.7
                                    c-8.6,22.2-26.4,42.2-26.4,42.2L308.9,121.3z" opacity="0.1"/>
                                <circle fill="#4a6cf7" cx="250" cy="150" r="130" opacity="0.1"/>
                                <path fill="#4a6cf7" d="M250,50c55.2,0,100,44.8,100,100s-44.8,100-100,100s-100-44.8-100-100S194.8,50,250,50 M250,20
                                    C126.9,20,20,126.9,20,250s106.9,230,230,230s230-106.9,230-230S373.1,20,250,20L250,20z"/>
                                <g>
                                    <path fill="#4a6cf7" d="M250,170c-11,0-20-9-20-20V80c0-11,9-20,20-20s20,9,20,20v70C270,161,261,170,250,170z"/>
                                    <circle fill="#4a6cf7" cx="250" cy="220" r="20"/>
                                </g>
                            </svg>
                        </div>
                        
                        <h1 class="error-code">404</h1>
                        <h2 class="error-title">Página No Encontrada</h2>
                        <p class="error-description">
                            Lo sentimos, la página que estás buscando no existe o ha sido movida. 
                            Por favor, verifica la URL o navega a través de nuestro sitio web.
                        </p>
                        
                       
                        <!-- Botones de acción -->
                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                            <a href="<?=base_url()?>/inicio" class="btn btn-primary-custom" style="color:white">
                                <i class="fas fa-home me-2" ></i>Volver al Inicio
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="mb-0">&copy; 2018 - <?= date('Y') ?>. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>