<?php
session_start();

/**
 * CONFIGURACIÓN Y LÓGICA
 */
$password_hardcoded = "oficina2026"; // Contraseña para el acceso
$password_hardcoded = "123*"; // Contraseña para el acceso
$error = "";

// Lógica de Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Lógica de Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    if ($_POST['password'] === $password_hardcoded) {
        $_SESSION['autenticado'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = "Contraseña incorrecta. Intente de nuevo.";
    }
}

// Generador de Contraseñas (Lógica para la opción 7)
function generarPasswordSegura($largo = 13) {
    // Excluimos: 0, 1, i, o, m (mayúsculas y minúsculas)
    $caracteres = '23456789abcdefghjklpqrstuvwxyzABCDEFGHJKLPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $largo; $i++) {
        $password .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $password;
}

$pass_generada = isset($_POST['generate_pass']) ? generarPasswordSegura() : "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión | Oficina</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; height: 100vh; display: flex; flex-direction: column; }
        .navbar { background-color: #1a2a3a !important; }
        .footer { background-color: #1a2a3a; color: #fff; padding: 10px 0; position: fixed; bottom: 0; width: 100%; }
        .login-container { max-width: 400px; margin-top: 10%; }
        .btn-office { background-color: #2c3e50; color: white; border-radius: 4px; }
        .btn-office:hover { background-color: #34495e; color: white; }
        .card { border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        main { flex: 1; padding-top: 80px; padding-bottom: 60px; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['autenticado'])): ?>
    <div class="container login-container">
        <div class="card p-4">
            <div class="text-center mb-4">
                <i class="fas fa-user-shield fa-3x text-secondary"></i>
                <h4 class="mt-2">Acceso Corporativo</h4>
            </div>
            <form method="POST">
                <div class="form-group">
                    <label>Contraseña del Sistema</label>
                    <input type="password" name="password" class="form-control" placeholder="Ingrese clave" required>
                </div>
                <?php if ($error): ?>
                    <div class="alert alert-danger py-2"><?php echo $error; ?></div>
                <?php endif; ?>
                <button type="submit" name="login" class="btn btn-office btn-block">Entrar</button>
            </form>
        </div>
    </div>

<?php else: ?>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-building mr-2"></i>Oficina Virtual</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropMenu" data-toggle="dropdown">
                            <i class="fas fa-list-ul mr-1"></i> Servicios
                        </a>
                        <div class="dropdown-menu shadow">
                            <a class="dropdown-item" href="#"><i class="fas fa-chart-line mr-2"></i> 1. Reportes</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-users mr-2"></i> 2. RRHH</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-file-invoice-dollar mr-2"></i> 3. Facturación</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-calendar-alt mr-2"></i> 4. Agenda</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-envelope mr-2"></i> 5. Mensajes</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-project-diagram mr-2"></i> 6. Proyectos</a>
                            <a class="dropdown-item bg-light font-weight-bold" href="?page=generator">
                                <i class="fas fa-key mr-2 text-primary"></i> 7. Generador Seguros
                            </a>
                            <a class="dropdown-item" href="#"><i class="fas fa-box mr-2"></i> 8. Inventario</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-headset mr-2"></i> 9. Soporte</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i> 10. Ajustes</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.google.com" target="_blank">
                            <i class="fas fa-external-link-alt mr-1"></i> Google
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-danger btn-sm" href="?logout=1">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h3>Bienvenido al Panel de Control</h3>
                    <hr>
                    <p><strong>Modelo de IA:</strong> Soy Gemini 3 Flash.</p>
                    <p><strong>Versión de PHP:</strong> <?php echo phpversion(); ?></p>
                </div>
            </div>

            <?php if (isset($_GET['page']) && $_GET['page'] == 'generator' || isset($_POST['generate_pass'])): ?>
            <div class="col-md-6 mx-auto">
                <div class="card p-4 shadow-sm border-primary">
                    <h5 class="text-primary"><i class="fas fa-shield-alt mr-2"></i>Generador de Contraseñas (Reglas Oficina)</h5>
                    <p class="small text-muted">13 caracteres, sin 0, 1, i, o, m.</p>
                    
                    <form method="POST">
                        <div class="input-group mb-3">
                            <input type="text" id="passBox" class="form-control font-weight-bold text-center" 
                                   value="<?php echo $pass_generada; ?>" readonly placeholder="Clic en generar">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">
                                    <i class="fas fa-copy"></i> Copiar
                                </button>
                            </div>
                        </div>
                        <button type="submit" name="generate_pass" class="btn btn-primary btn-block">Generar Nueva</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer text-center">
        <div class="container">
            <small>&copy; 2026 Entorno de Oficina Profesional - Sistema de Gestión Interna</small>
        </div>
    </footer>

    <script>
    function copyToClipboard() {
        var copyText = document.getElementById("passBox");
        if(copyText.value === "") return;
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("¡Copiado al portapapeles!: " + copyText.value);
    }
    </script>

<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
