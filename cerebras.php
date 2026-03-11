<?php
/* --------------------------------------------------------------
 *  SISTEMA DE LOGIN + DASHBOARD EN UN SOLO ARCHIVO
 *  Requisitos:
 *   - PHP 7.x+ (sin frameworks)
 *   - Bootstrap 4.6 (CDN)
 *   - Font Awesome 5 (CDN)
 *   cerebras.ai https://chat.cerebras.ai
 * -------------------------------------------------------------- */
session_start();

/* ---------- CONFIGURACIÓN ---------- */
//define('HARDCODED_PASSWORD', 'MiClaveSegura2024');   // ← Cambia la contraseña aquí
define('HARDCODED_PASSWORD', '123*');   // ← Cambia la contraseña aquí
define('AI_MODEL', 'ChatGPT (GPT‑4)');               // Modelo de IA a mostrar
/* ----------------------------------- */

/* ---------- ACCIONES ---------- */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if ($action === 'logout') {
    session_destroy();
    header('Location: '.$_SERVER['PHP_SELF']);
    exit;
}

/* ---------- LÓGICA DE LOGIN ---------- */
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pwd'])) {
    $pwd = trim($_POST['pwd']);
    if ($pwd === HARDCODED_PASSWORD) {
        $_SESSION['logged'] = true;
        header('Location: '.$_SERVER['PHP_SELF'].'?page=dashboard');
        exit;
    } else {
        $login_error = 'Contraseña incorrecta. Inténtalo de nuevo.';
    }
}

/* ---------- CONTROL DE ACCESO ---------- */
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
if (!isset($_SESSION['logged']) && $page !== null && $page !== 'login') {
    // Si no está logueado, fuerza al login
    header('Location: '.$_SERVER['PHP_SELF']);
    exit;
}

/* ---------- FUNCIONES DE AYUDA ---------- */
function active_page($p) {
    global $page;
    return ($page === $p) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login / Dashboard</title>
    <!-- Bootstrap 4.6 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"  />
    <style>
        body { padding-top: 70px; padding-bottom: 70px; background:#f5f5f5; }
        .navbar-dark { background-color:#2c3e50; }
        .footer { background:#2c3e50; color:#fff; }
        .fixed-footer { position:fixed; bottom:0; left:0; right:0; height:60px; line-height:60px; }
        .card { border:none; }
        .dropdown-item i { width:20px; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['logged'])): /* ---------- VISTA DE LOGIN ---------- */ ?>
    <div class="container d-flex align-items-center justify-content-center" style="min-height:80vh;">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-center mb-4"><i class="fas fa-lock"></i> Iniciar sesión</h4>
                    <?php if ($login_error): ?>
                        <div class="alert alert-danger"><?php echo $login_error; ?></div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="pwd">Contraseña</label>
                            <input type="password" class="form-control" id="pwd" name="pwd" required autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php else: /* ---------- VISTA DE DASHBOARD ---------- */ ?>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#"><i class="fas fa-tools"></i> OficinaPro</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navContent"
                aria-controls="navContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav mr-auto">
                <!-- Menú dropdown con 10 opciones -->
                <li class="nav-item dropdown <?php echo active_page('dashboard'); ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="menu10" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-th-list"></i> Opciones
                    </a>
                    <div class="dropdown-menu" aria-labelledby="menu10">
                        <?php
                        $icons = ['fa-home','fa-user','fa-cog','fa-chart-bar','fa-envelope',
                                  'fa-calendar','fa-file','fa-bell','fa-lock','fa-key'];
                        for ($i=1;$i<=10;$i++) {
                            $active = ($i===10) ? 'active' : '';
                            $label = ($i===10) ? 'Generador de contraseñas' : "Opción $i";
                            $icon  = $icons[$i-1];
                            $href  = ($i===10) ? '#password-generator' : '#';
                            echo "<a class=\"dropdown-item $active\" href=\"$href\"><i class=\"fas $icon\"></i> $label</a>";
                        }
                        ?>
                    </div>
                </li>
                <!-- Enlace externo visible en la barra -->
                <li class="nav-item">
                    <a class="nav-link" href="https://www.example.com" target="_blank"><i class="fas fa-external-link-alt"></i> Sitio externo</a>
                </li>
            </ul>
            <span class="navbar-text mr-3">
                <i class="fas fa-info-circle"></i> Modelo IA: <?php echo AI_MODEL; ?>
            </span>
            <span class="navbar-text mr-3">
                <i class="fas fa-code"></i> PHP <?php echo PHP_VERSION; ?>
            </span>
            <a href="?action=logout" class="btn btn-outline-light"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container mt-4">
        <h2 class="mb-4"><i class="fas fa-tachometer-alt"></i> Dashboard</h2>

        <!-- Sección del generador de contraseñas -->
        <section id="password-generator" class="card shadow-sm mb-5">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-key"></i> Generador de contraseñas
            </div>
            <div class="card-body">
                <p>Genera una contraseña segura de 13 caracteres con mayúsculas, minúsculas y números,
                    excluyendo los caracteres: <strong>0, 1, i, o, m</strong> (tanto mayúsculas como minúsculas).</p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="generatedPwd" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-success" id="btnCopy"><i class="fas fa-copy"></i> Copiar</button>
                    </div>
                </div>
                <button class="btn btn-primary" id="btnGenerate"><i class="fas fa-sync-alt"></i> Generar</button>
            </div>
        </section>

        <!-- Otras 9 opciones (placeholder) -->
        <div class="row">
            <?php for ($i=1;$i<=9;$i++): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><i class="fas <?php echo $icons[$i-1]; ?>"></i> Opción <?php echo $i; ?></h5>
                            <p class="card-text">Contenido de ejemplo para la opción <?php echo $i; ?>.</p>
                            <a href="#" class="mt-auto btn btn-outline-primary btn-sm"><i class="fas fa-arrow-right"></i> Ir</a>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer fixed-footer text-center">
        <div class="container">
            <small>&copy; <?php echo date('Y'); ?> OficinaPro – Todos los derechos reservados.</small>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ---------- GENERADOR DE CONTRASEÑAS ----------
        const charset = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklnpqrstuvwxyz23456789";
        // (Se han quitado 0,1,i,o,m y sus mayúsculas)

        function generatePassword(length = 13) {
            let pwd = "";
            const n = charset.length;
            for (let i = 0; i < length; i++) {
                pwd += charset.charAt(Math.floor(Math.random() * n));
            }
            return pwd;
        }

        document.getElementById('btnGenerate').addEventListener('click', () => {
            const pwd = generatePassword();
            document.getElementById('generatedPwd').value = pwd;
        });

        document.getElementById('btnCopy').addEventListener('click', () => {
            const pwdField = document.getElementById('generatedPwd');
            pwdField.select();
            pwdField.setSelectionRange(0, 99999); // para móviles
            document.execCommand('copy');
            // feedback visual
            const btn = document.getElementById('btnCopy');
            btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
            }, 1500);
        });
    </script>
<?php endif; ?>
</body>
</html>
