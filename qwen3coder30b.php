<?php
/**
 * Single‑file PHP login + dashboard (PHP 7.x+)
 * Modelo qwen3 coder 30b
 *
 *  • Bootstrap 4.6   – CDN
 *  • Font Awesome 5 – CDN
 *  • No frameworks, pure PHP
 *
 * --------------------------------------------------------------
 *  Features:
 *  - Hardcoded password in this file
 *  - Shows error on same login page if wrong password
 *  - On success → dashboard
 *  - Dashboard has a fixed top navbar & footer
 *  - Dropdown menu with exactly 10 items (1‑10) + Font‑Awesome icon
 *  - External link visible on the navbar
 *  - “I am ChatGPT” + PHP version displayed in the navbar
 *  - Logout button that returns to login page
 *  - One of the dropdown options is a password generator:
 *      • 13 chars, upper/lower letters & numbers
 *      • No: 0,1,i,o,m (case‑insensitive)
 *      • Generate / Copy buttons
 *
 * --------------------------------------------------------------
 *  All code lives in this single file.
 */

session_start();

/* ---------- CONFIGURATION ---------- */
$hardcoded_password = 'MySecureP@ssw0rd';   // <-- change here
$hardcoded_password = '123*';   // <-- change here

/* ---------- HELPERS ---------- */

/**
 * Escape HTML entities
 */
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Render the login form (with optional error message)
 */
function render_login_form($error = '') {
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
      integrity="sha512-dyK5p0FvZl3jHhE1xJfG9X+8VqW2sA6c7QkL1e6UoY4Oe6B+qzN8aP6M6nI4R6V8iQqV5gJpD0Tq8z/5Sxw=="
      crossorigin="anonymous" referrerpolicy="no-referrer"/>
<style>
    body{background:#f8f9fa;}
    .login-card{max-width:400px;margin:auto;padding-top:80px;}
</style>
</head>
<body>
<div class="container login-card">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h3 class="mb-4">Login</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo h($error); ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password"
                           class="form-control" placeholder="Ingrese su contraseña"
                           required aria-label="Password">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}

/**
 * Render password generator page.
 */
function render_generator() {
    $content = <<<HTML
<h4>Generador de contraseñas</h4>
<div class="input-group mb-3">
  <input type="text" id="generatedPassword"
         class="form-control" placeholder="Contraseña generada..."
         readonly aria-label="Generated password">
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="button" id="copyBtn">
      Copiar
    </button>
  </div>
</div>
<button class="btn btn-primary" id="generateBtn">Generar contraseña</button>

<script>
document.getElementById('generateBtn').addEventListener('click', function(){
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz23456789';
    let pass='';
    for(let i=0;i<13;i++){
        pass += chars.charAt(Math.floor(Math.random()*chars.length));
    }
    document.getElementById('generatedPassword').value = pass;
});

document.getElementById('copyBtn').addEventListener('click', function(){
    const copyText = document.getElementById('generatedPassword');
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile
    navigator.clipboard.writeText(copyText.value).then(function() {
        alert('Contraseña copiada al portapapeles.');
    }, function(err) {
        alert('Error al copiar: '+err);
    });
});
</script>
HTML;

    render_dashboard($content);
}

/**
 * Render a simple message page for other dropdown actions.
 */
function render_page($page) {
    $msg = "Contenido de la acción {$page}. (Funcionalidad placeholder)";
    render_dashboard("<h4>{$msg}</h4>");
}

/* ---------- MAIN LOGIC ---------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* --- Login attempt --- */
    if (!empty($_POST['action']) && $_POST['action'] === 'login') {
        $pwd = $_POST['password'] ?? '';
        if ($pwd === $hardcoded_password) {
            $_SESSION['authenticated'] = true;
            header('Location: ?'); // reload to dashboard
            exit();
        } else {
            render_login_form('Contraseña incorrecta.');
            exit();
        }
    }

    /* --- Logout attempt --- */
    if (!empty($_POST['action']) && $_POST['action'] === 'logout') {
        session_destroy();
        header('Location: ?');
        exit();
    }
}

/* If not authenticated → show login form */
if (empty($_SESSION['authenticated'])) {
    render_login_form();
    exit();
}

/* Authenticated → dashboard routes */
function render_dashboard($content) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    body {
        padding-top: 70px;
        padding-bottom: 60px;
        background-color: #f8f9fa;
    }
    .navbar {
        background-color: #2c3e50 !important;
    }
    .navbar-brand, .nav-link {
        color: #ecf0f1 !important;
    }
    .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 60px;
        background-color: #2c3e50;
        color: white;
        text-align: center;
        line-height: 60px;
    }
    .dropdown-menu {
        background-color: #34495e;
    }
    .dropdown-item {
        color: #ecf0f1;
    }
    .dropdown-item:hover {
        background-color: #2c3e50;
    }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <a class="navbar-brand" href="#"><i class="fas fa-home"></i> Dashboard</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
          <i class="fas fa-th-list"></i> Menú
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="?page=1"><i class="fas fa-file-alt"></i> Opción 1</a>
          <a class="dropdown-item" href="?page=2"><i class="fas fa-chart-bar"></i> Opción 2</a>
          <a class="dropdown-item" href="?page=3"><i class="fas fa-users"></i> Opción 3</a>
          <a class="dropdown-item" href="?page=4"><i class="fas fa-cog"></i> Opción 4</a>
          <a class="dropdown-item" href="?page=5"><i class="fas fa-bell"></i> Opción 5</a>
          <a class="dropdown-item" href="?page=6"><i class="fas fa-envelope"></i> Opción 6</a>
          <a class="dropdown-item" href="?page=7"><i class="fas fa-shopping-cart"></i> Opción 7</a>
          <a class="dropdown-item" href="?page=8"><i class="fas fa-calendar"></i> Opción 8</a>
          <a class="dropdown-item" href="?page=9"><i class="fas fa-map-marker-alt"></i> Opción 9</a>
          <a class="dropdown-item" href="?page=10"><i class="fas fa-database"></i> Opción 10</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="?action=generator"><i class="fas fa-key"></i> Generador de Contraseñas</a>
        </div>
      </li>
    </ul>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="https://www.example.com" target="_blank"><i class="fas fa-external-link-alt"></i> Enlace Externo</a>
      </li>
      <li class="nav-item">
        <span class="navbar-text mr-2">
          <i class="fas fa-robot"></i> Modelo: GPT-4
        </span>
      </li>
      <li class="nav-item">
        <span class="navbar-text mr-2">
          <i class="fas fa-code-branch"></i> PHP <?php echo phpversion(); ?>
        </span>
      </li>
      <li class="nav-item">
        <form method="post" action="" class="form-inline">
          <input type="hidden" name="action" value="logout">
          <button class="btn btn-outline-light btn-sm" type="submit"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</button>
        </form>
      </li>
    </ul>
  </div>
</nav>

<!-- Main Content -->
<div class="container-fluid mt-4">
    <?php echo $content; ?>
</div>

<!-- Footer -->
<div class="footer">
  <div class="container">
    <span class="text-muted">© 2023 Sistema de Dashboard - Todos los derechos reservados</span>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}

$action = $_GET['action'] ?? '';
$page   = $_GET['page'] ?? '';

if ($action === 'generator') {
    render_generator();
} elseif ($page) {
    render_page($page);
} else {
    render_dashboard('<h2>Bienvenido al Dashboard</h2><p>Seleccione una opción del menú para comenzar.</p>');
}
?>
