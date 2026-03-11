<?php
// https://chat.together.ai/ minimax
// articulo en https://vibecodingmexico.com/ejercicio-con-once-ias-desconocidas/
?>
<?php
session_start();

// Configuración
$hardcodedPassword = "Admin123";
$hardcodedPassword = "123*";
$aiModel = "MiniMax-M2.5";
$phpVersion = PHP_VERSION;

// Rutas CDN
$bootstrapCDN = "https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css";
$fontAwesomeCDN = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css";

// Procesar logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Procesar login
$loginError = "";
if (isset($_POST['login'])) {
    $password = $_POST['password'] ?? "";
    if ($password === $hardcodedPassword) {
        $_SESSION['logged_in'] = true;
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=dashboard");
        exit;
    } else {
        $loginError = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> Contraseña incorrecta
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>';
    }
}

// Generador de contraseñas
$generatedPassword = "";
if (isset($_POST['generate_password'])) {
    $uppercase = 'ABCDEFGHJJKLNPQRSTUVWXYZ'; // Sin I, O, M
    $lowercase = 'abcdefghjklpqrstuvwxyz';  // Sin i, o, m
    $numbers = '23456789';                   // Sin 0, 1
    
    $allChars = $uppercase . $lowercase . $numbers;
    $password = "";
    
    // Asegurar al menos un carácter de cada tipo
    $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
    $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
    $password .= $numbers[random_int(0, strlen($numbers) - 1)];
    
    // Llenar hasta 13 caracteres
    for ($i = 3; $i < 13; $i++) {
        $password .= $allChars[random_int(0, strlen($allChars) - 1)];
    }
    
    // Mezclar caracteres
    $password = str_shuffle($password);
    $generatedPassword = $password;
}

$currentPage = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Login - <?= $currentPage === 'dashboard' ? 'Dashboard' : 'Login' ?></title>
    <link rel="stylesheet" href="<?= $bootstrapCDN ?>">
    <link rel="stylesheet" href="<?= $fontAwesomeCDN ?>">
    <style>
        :root {
            --primary-dark: #1a1f2e;
            --secondary-dark: #252b3d;
            --accent-blue: #4a90d9;
            --accent-cyan: #17a2b8;
            --text-light: #e8e8e8;
            --text-muted: #a0a0a0;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }
        
        body {
            background: linear-gradient(135deg, #1a1f2e 0%, #2c3e50 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-custom {
            background: linear-gradient(90deg, #0d1117 0%, #161b22 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .navbar-dark .navbar-nav .nav-link {
            color: var(--text-light);
            transition: color 0.3s ease;
        }
        
        .navbar-dark .navbar-nav .nav-link:hover {
            color: var(--accent-blue);
        }
        
        .dropdown-menu-custom {
            background: var(--secondary-dark);
            border: 1px solid #3a3f52;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        }
        
        .dropdown-item-custom {
            color: var(--text-light);
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        
        .dropdown-item-custom:hover {
            background: var(--accent-blue);
            color: white;
        }
        
        .card-custom {
            background: var(--secondary-dark);
            border: 1px solid #3a3f52;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        .footer-custom {
            background: linear-gradient(90deg, #0d1117 0%, #161b22 100%);
            color: var(--text-muted);
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 15px 0;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: var(--secondary-dark);
            border: 1px solid #3a3f52;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
        }
        
        .form-control-custom {
            background: var(--primary-dark);
            border: 1px solid #3a3f52;
            color: var(--text-light);
            border-radius: 8px;
            padding: 12px 15px;
        }
        
        .form-control-custom:focus {
            background: var(--primary-dark);
            border-color: var(--accent-blue);
            color: var(--text-light);
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 217, 0.25);
        }
        
        .btn-primary-custom {
            background: linear-gradient(90deg, var(--accent-blue) 0%, #357abd 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(74, 144, 217, 0.4);
        }
        
        .password-display {
            background: var(--primary-dark);
            border: 2px solid var(--accent-blue);
            border-radius: 8px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 1.3em;
            color: var(--accent-cyan);
            text-align: center;
            letter-spacing: 3px;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .main-content {
            padding-bottom: 80px;
            padding-top: 80px;
        }
        
        .welcome-text {
            color: var(--text-light);
        }
        
        .info-badge {
            background: var(--accent-blue);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            margin-left: 10px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
    <!-- VISTA DE LOGIN -->
    <div class="login-container">
        <div class="login-card animate-fade">
            <div class="text-center mb-4">
                <i class="fas fa-lock fa-3x" style="color: var(--accent-blue);"></i>
                <h2 class="mt-3 welcome-text">Sistema Secure</h2>
                <p class="text-muted">Ingrese sus credenciales</p>
            </div>
            
            <?= $loginError ?>
            
            <form method="POST">
                <div class="form-group">
                    <label class="text-muted">Contraseña</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="background: var(--primary-dark); border: 1px solid #3a3f52; color: var(--text-muted);">
                                <i class="fas fa-key"></i>
                            </span>
                        </div>
                        <input type="password" name="password" class="form-control form-control-custom" placeholder="Ingrese la contraseña" required autofocus>
                    </div>
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-block btn-primary-custom mt-4">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
            </form>
            
            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> Entorno de oficina seguro
                </small>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- DASHBOARD CON NAVBAR FIJA -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-custom">
        <a class="navbar-brand" href="#">
            <i class="fas fa-shield-alt" style="color: var(--accent-blue);"></i>
            <span class="ml-2">Sistema Secure</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-bars"></i> Menú
                    </a>
                    <div class="dropdown-menu dropdown-menu-custom" aria-labelledby="menuDropdown">
                        <a class="dropdown-item dropdown-item-custom" href="#"><i class="fas fa-home w-3"></i> Opción 1</a>
                        <a class="dropdown-item dropdown-item-custom" href="#"><i class="fas fa-user w-3"></i> Opción 2</a>
                        <a class="dropdown-item dropdown-item-custom" href="#"><i class="fas fa-cog w-3"></i> Opción 3</a>
                        <a class="dropdown-item dropdown-item-custom" href="#"><i class="fas fa-chart-bar w-3"></i> Opción 4</a>
                        <a class="dropdown-item dropdown-item-custom" href="#"><i class="fas fa-envelope w-3"></i> Opción 5</a>
                        <a class="dropdown-item dropdown-item-custom" href="#"><i class="fas fa-calendar w-3"></i> Opción 6</a>
                        <a class="dropdown-item dropdown-item-custom" href="#"><i class="fas fa-file w-3"></i> Opción 7</a>
                        <a class="dropdown-item dropdown-item-custom" href="#"><i class="fas fa-image w-3"></i> Opción 8</a>
                        <a class="dropdown-item dropdown-item-custom" href="#"><i class="fas fa-shopping-cart w-3"></i> Opción 9</a>
                        <a class="dropdown-item dropdown-item-custom" href="#" data-toggle="modal" data-target="#passwordGeneratorModal">
                            <i class="fas fa-key w-3"></i> <strong>Opción 10 - Generador Password</strong>
                        </a>
                    </div>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="https://www.google.com" target="_blank" title="Ir a Google">
                        <i class="fas fa-external-link-alt"></i> Google
                    </a>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        <i class="fas fa-robot"></i> IA: <?= $aiModel ?>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        <i class="fab fa-php"></i> PHP <?= explode('.', $phpVersion)[0] . '.' . explode('.', $phpVersion)[1] ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?logout=1" title="Cerrar Sesión">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container main-content">
        <div class="row">
            <div class="col-12">
                <div class="card card-custom animate-fade">
                    <div class="card-header" style="background: var(--primary-dark); border-bottom: 2px solid var(--accent-blue);">
                        <h4 class="mb-0 welcome-text">
                            <i class="fas fa-tachometer-alt" style="color: var(--accent-blue);"></i>
                            Dashboard Principal
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="card" style="background: var(--primary-dark); border: 1px solid #3a3f52;">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-check fa-2x mb-3" style="color: var(--success-color);"></i>
                                        <h5 class="text-muted">Usuario</h5>
                                        <p class="welcome-text">Administrador</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card" style="background: var(--primary-dark); border: 1px solid #3a3f52;">
                                    <div class="card-body text-center">
                                        <i class="fas fa-server fa-2x mb-3" style="color: var(--accent-blue);"></i>
                                        <h5 class="text-muted">Servidor</h5>
                                        <p class="welcome-text">PHP <?= $phpVersion ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card" style="background: var(--primary-dark); border: 1px solid #3a3f52;">
                                    <div class="card-body text-center">
                                        <i class="fas fa-robot fa-2x mb-3" style="color: var(--accent-cyan);"></i>
                                        <h5 class="text-muted">Modelo IA</h5>
                                        <p class="welcome-text"><?= $aiModel ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info" style="background: var(--primary-dark); border: 1px solid var(--accent-blue); color: var(--text-light);">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Bienvenido al sistema.</strong> Utilice el menú desplegable para acceder a las 10 opciones disponibles. 
                            La opción 10 contiene el generador de contraseñas seguras.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL GENERADOR DE CONTRASEÑAS -->
    <div class="modal fade" id="passwordGeneratorModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background: var(--secondary-dark); border: 1px solid #3a3f52;">
                <div class="modal-header" style="background: var(--primary-dark); border-bottom: 2px solid var(--accent-blue);">
                    <h5 class="modal-title welcome-text">
                        <i class="fas fa-key" style="color: var(--accent-blue);"></i>
                        Generador de Contraseñas
                    </h5>
                    <button type="button" class="close text-light" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-muted">Contraseña Generada (13 caracteres)</label>
                        <div class="password-display" id="passwordDisplay">
                            <?= $generatedPassword ?: 'Presione Generar' ?>
                        </div>
                    </div>
                    
                    <div class="alert alert-secondary" style="background: var(--primary-dark); border: 1px solid #3a3f52; color: var(--text-muted);">
                        <i class="fas fa-info-circle"></i> 
                        <small>Reglas: 13 caracteres, mayúsculas, minúsculas, números. Sin: 0, 1, i, I, o, O, m, M</small>
                    </div>
                    
                    <form method="POST">
                        <div class="d-flex gap-2">
                            <button type="submit" name="generate_password" class="btn btn-primary-custom flex-grow-1">
                                <i class="fas fa-random"></i> Generar
                            </button>
                            <button type="button" class="btn btn-success" id="copyBtn" style="border-radius: 8px;">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER FIJO -->
    <footer class="footer-custom text-center">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="mb-0">
                        <i class="fas fa-copyright"></i> <?= date('Y') ?> Sistema Secure - 
                        <span class="info-badge"><i class="fas fa-robot"></i> <?= $aiModel ?></span> - 
                        <span class="info-badge"><i class="fab fa-php"></i> PHP <?= explode('.', $phpVersion)[0] . '.' . explode('.', $phpVersion)[1] ?></span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Script para copiar al portapapeles
        document.getElementById('copyBtn').addEventListener('click', function() {
            var passwordText = document.getElementById('passwordDisplay').innerText.trim();
            if (passwordText && passwordText !== 'Presione Generar') {
                navigator.clipboard.writeText(passwordText).then(function() {
                    alert('¡Contraseña copiada al portapapeles!');
                });
            } else {
                alert('Por favor, genere una contraseña primero.');
            }
        });
    </script>

<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
