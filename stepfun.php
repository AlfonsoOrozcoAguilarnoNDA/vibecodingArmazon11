<?php
// https://stepfun.ai
session_start();

// ============ CONFIGURACIÓN ============
//define('CONTRASENA_CORRECTA', 'AdminPass123!'); // Cambia esta contraseña
define('CONTRASENA_CORRECTA', '123*'); // Cambia esta contraseña
$ia_modelo = 'Step 3.5 Flash';
$php_version = phpversion();

// ============ LÓGICA DE LOGIN ============
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $contrasena = $_POST['contrasena'] ?? '';
    if ($contrasena === CONTRASENA_CORRECTA) {
        $_SESSION['logueado'] = true;
        header('Location: ?dashboard=1');
        exit;
    } else {
        $error = 'Contraseña incorrecta';
    }
}

// ============ LÓGICA DE DASHBOARD ============
$seccion = $_GET['seccion'] ?? 1;
$logout = $_GET['logout'] ?? 0;

if ($logout) {
    session_destroy();
    header('Location: ?');
    exit;
}

// ============ GENERADOR DE CONTRASEÑAS ============
function generar_contrasena() {
    $caracteres = '';
    // Mayúsculas sin O, I, M
    for ($i = 65; $i <= 90; $i++) {
        $c = chr($i);
        if (!in_array($c, ['O', 'I', 'M'])) $caracteres .= $c;
    }
    // Minúsculas sin o, i, m
    for ($i = 97; $i <= 122; $i++) {
        $c = chr($i);
        if (!in_array($c, ['o', 'i', 'm'])) $caracteres .= $c;
    }
    // Números sin 0,1
    for ($i = 2; $i <= 9; $i++) $caracteres .= $i;
    
    $longitud = 13;
    $contrasena = '';
    $max = strlen($caracteres) - 1;
    for ($i = 0; $i < $longitud; $i++) {
        $contrasena .= $caracteres[random_int(0, $max)];
    }
    return $contrasena;
}

if (isset($_POST['generar'])) {
    $nueva_contrasena = generar_contrasena();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Login</title>
    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --light-color: #ecf0f1;
            --dark-color: #1a252f;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 70px;
            padding-bottom: 70px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .footer {
            background: var(--dark-color);
            color: #bdc3c7;
            padding: 1.5rem 0;
            margin-top: auto;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .dropdown-item:hover {
            background-color: var(--accent-color);
            color: white;
        }
        .password-box {
            background-color: #f1f8ff;
            border-radius: 8px;
            padding: 15px;
            border: 2px dashed var(--accent-color);
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            letter-spacing: 2px;
            word-break: break-all;
        }
        .info-badge {
            background-color: var(--secondary-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <?php if (!isset($_SESSION['logueado']) || !$_SESSION['logueado']): ?>
        <!-- ============ VISTA DE LOGIN ============ -->
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white text-center py-3">
                            <h4><i class="fas fa-lock mr-2"></i>Acceso Seguro</h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Contraseña</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" name="contrasena" class="form-control" required autofocus>
                                    </div>
                                </div>
                                <button type="submit" name="login" class="btn btn-primary btn-block">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                                </button>
                            </form>
                        </div>
                        <div class="card-footer text-center text-muted">
                            <small>Sistema Interno - Acceso Restringido</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- ============ VISTA DE DASHBOARD ============ -->
        <!-- Navbar fija superior -->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-cube mr-2"></i>Panel Admin
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav mr-auto">
                        <!-- Menú dropdown con 10 opciones -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-toggle="dropdown">
                                <i class="fas fa-bars mr-1"></i> Menú
                            </a>
                            <div class="dropdown-menu">
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <a class="dropdown-item" href="?dashboard=1&seccion=<?= $i ?>">
                                        <i class="fas fa-<?= 
                                            $i == 1 ? 'home' : 
                                            ($i == 10 ? 'key' : 'cube') 
                                        ?> mr-2"></i>
                                        Opción <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        </li>
                    </ul>
                    <!-- Información IA y PHP -->
                    <div class="navbar-text mr-3">
                        <span class="info-badge">
                            <i class="fas fa-robot mr-1"></i><?= htmlspecialchars($ia_modelo) ?>
                        </span>
                    </div>
                    <div class="navbar-text mr-3">
                        <span class="info-badge">
                            <i class="fab fa-php mr-1"></i>PHP <?= $php_version ?>
                        </span>
                    </div>
                    <!-- Enlace externo -->
                    <a href="https://github.com" target="_blank" class="btn btn-outline-light btn-sm mr-2">
                        <i class="fab fa-github mr-1"></i>GitHub
                    </a>
                    <!-- Logout -->
                    <a href="?dashboard=1&logout=1" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sign-out-alt mr-1"></i>Salir
                    </a>
                </div>
            </div>
        </nav>

        <!-- Contenido principal -->
        <div class="container mt-5 pt-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-<?= 
                                    $seccion == 10 ? 'key' : 
                                    'cube' 
                                ?> mr-2 text-primary"></i>
                                <?= $seccion == 10 ? 'Generador de Contraseñas' : "Sección $seccion" ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($seccion == 10): ?>
                                <!-- Generador de contraseñas -->
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="text-center mb-4">
                                            <h6>Contraseña segura (13 caracteres)</h6>
                                            <small class="text-muted">Mayúsculas, minúsculas y números (sin 0,1,i,o,m)</small>
                                        </div>
                                        
                                        <?php if (isset($nueva_contrasena)): ?>
                                            <div class="password-box mb-3" id="passwordDisplay">
                                                <?= htmlspecialchars($nueva_contrasena) ?>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <form method="POST" class="d-inline">
                                                    <button type="submit" name="generar" class="btn btn-primary">
                                                        <i class="fas fa-sync-alt mr-2"></i>Generar Nueva
                                                    </button>
                                                </form>
                                                <button onclick="copiarPortapapeles()" class="btn btn-success">
                                                    <i class="far fa-copy mr-2"></i>Copiar
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <form method="POST">
                                                <button type="submit" name="generar" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-magic mr-2"></i>Generar Contraseña
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Contenido placeholder para secciones 1-9 -->
                                <div class="text-center py-5">
                                    <i class="fas fa-<?= 
                                        $seccion == 1 ? 'home' : 
                                        ($seccion == 2 ? 'chart-bar' : 
                                        ($seccion == 3 ? 'users' : 
                                        ($seccion == 4 ? 'cog' : 
                                        ($seccion == 5 ? 'envelope' : 
                                        ($seccion == 6 ? 'calendar' : 
                                        ($seccion == 7 ? 'file-alt' : 
                                        ($seccion == 8 ? 'image' : 'bell'))))))) 
                                    ?> fa-4x text-muted mb-4"></i>
                                    <h4>Sección <?= $seccion ?></h4>
                                    <p class="text-muted">Contenido de ejemplo para la opción <?= $seccion ?> del menú</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Footer fijo inferior -->
    <footer class="footer fixed-bottom">
        <div class="container text-center">
            <span class="text-muted">
                &copy; <?= date('Y') ?> Sistema de Login | Desarrollado con 
                <i class="fas fa-heart text-danger"></i> y PHP <?= $php_version ?>
            </span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <script>
    function copiarPortapapeles() {
        const texto = document.getElementById('passwordDisplay').innerText;
        navigator.clipboard.writeText(texto).then(() => {
            alert('¡Contraseña copiada al portapapeles!');
        }).catch(err => {
            console.error('Error al copiar: ', err);
            alert('Error al copiar. Por favor, copia manualmente.');
        });
    }
    </script>
</body>
</html>
