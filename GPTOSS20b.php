<?php
/**
 * Single‑file PHP login + dashboard (PHP 7.x+)
 * GPT OSS 20b LM studio
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
                           class="form-control" required autofocus>
                </div>
                <button class="btn btn-primary btn-block" type="submit">Entrar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<?php
}

/**
 * Render the dashboard page.
 */
function render_dashboard($content = '') {
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
      integrity="sha512-dyK5p0FvZl3jHhE1xJfG9X+8VqW2sA6c7QkL1e6UoY4Oe6B+qzN8aP6M6nI4R6V8iQqV5gJpD0Tq8z/5Sxw=="
      crossorigin="anonymous" referrerpolicy="no-referrer"/>
<style>
    body{margin-top:140px;margin-bottom:60px;}
    footer{background:#343a40;color:white;position:fixed;bottom:0;width:100%;height:50px;
           display:flex;align-items:center;justify-content:center;font-size:.9rem;}
</style>
</head>
<body>

<!-- Fixed navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="?">Mi App</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Dropdown menu with 10 items -->
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav mr-auto">

            <?php for ($i = 1; $i <= 10; $i++): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       href="#"
                       id="dropdownMenuLink<?php echo $i; ?>"
                       role="button" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog"></i> Opción <?php echo $i; ?>
                    </a>
                    <div class="dropdown-menu"
                         aria-labelledby="dropdownMenuLink<?php echo $i; ?>">
                        <?php
                        if ($i == 10) {
                            // Password generator page
                            ?>
                            <a class="dropdown-item" href="?action=generator">
                                Generador de contraseñas
                            </a>
                            <?php
                        } else {
                            ?>
                            <a class="dropdown-item"
                               href="?page=<?php echo $i; ?>">
                                Acción <?php echo $i; ?>
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                </li>
            <?php endfor; ?>

        </ul>

        <!-- External link -->
        <a class="nav-link" href="https://www.example.com"
           target="_blank">Sitio externo</a>

        <!-- Model and PHP version -->
        <span class="navbar-text mr-3">
            Soy ChatGPT • PHP <?php echo phpversion(); ?>
        </span>

        <!-- Logout button -->
        <form method="post" action="" class="form-inline my-2 my-lg-0">
            <input type="hidden" name="action" value="logout">
            <button class="btn btn-outline-light my-2 my-sm-0"
                    type="submit">Logout</button>
        </form>
    </div>
</nav>

<!-- Main content -->
<div class="container">
    <?php echo $content; ?>
</div>

<footer>
    © 2026 Mi App • Todos los derechos reservados
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqk8bN3c9i2L7pWvHqP5QZfKJ+X1tA6lCwM0jYpB8zJdT2D2eG7Y"
        crossorigin="anonymous"></script>
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
$action = $_GET['action'] ?? '';
$page   = $_GET['page'] ?? '';

switch ($action) {
    case 'generator':
        render_generator();
        break;
    default:
        if (!empty($page)) {
            render_page($page);
        } else {
            // Default dashboard content
            $defaultContent = <<<HTML
<h4>Bienvenido al Dashboard</h4>
<p>Seleccione una opción del menú desplegable.</p>
HTML;
            render_dashboard($defaultContent);
        }
}
?>
