<?php
// Archivo principal del proyecto, hermes en modo local
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Incluir archivos de CDN -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
<?php if ($_POST['password'] == 'hardcoded'): ?>
    <!-- Mostrar error de contraseña -->
    <div class="alert alert-danger">Contraseña incorrecta</div>
<?php else: ?>
    <!-- Redirigir al dashboard -->
    header('Location: dashboard.php');
    exit;
<?php endif; ?>
<main role="main" class="container mt-5">
    <h1 class="text-primary text-center">Login</h1>
    <!-- Formulario de login -->
    <form method="post">
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label">Username:</label>
            <div class="col-sm-10">
                <input type="text" id="username" name="username" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Password:</label>
            <div class="col-sm-10">
                <input type="password" id="password" name="password" required>
            </div>
        </div>
        <!-- Botón de login -->
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</main>
<!-- Barra de navegación fija superior -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <!-- Logo -->
    <a class="navbar-brand" href="#">Logo</a>
    <!-- Toggler/collapse button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Option <?= $i ?></a>
                    <div class="dropdown-menu">
                        <!-- Menú con iconos de font awesome -->
                        <a href="#" class="dropdown-item"><i class="fas fa-cog"></i> Option content</a>
                        <a href="#" class="dropdown-item"><i class="fas fa-user-tie"></i> Option content</a>
                        <a href="#" class="dropdown-item"><i class="fas fa-money-bill-alt"></i> Option content</a>
                    </div>
                </li>
            <?php endfor; ?>
        </ul>
        <!-- Enlace externo -->
        <a href="#" class="nav-link">Link 1</a>
    </div>
</nav>
<!-- Generador de contraseñas -->
<h2 class="text-primary text-center mt-5">Generar contraseña</h2>
<p>Escribe una contraseña segura:</p>
<form method="post">
    <div class="form-group row">
        <label for="password" class="col-sm-2 col-form-label">Password:</label>
        <div class="col-sm-10">
            <input type="password" id="password" name="password" required>
        </div>
    </div>
    <!-- Botones de generar y copiar contraseña -->
    <button type="submit" class="btn btn-primary mt-3">Generate</button>
    <button onclick="copyToClipboard()" class="btn btn-secondary mt-3">Copy to clipboard</button>
</form>
<!-- Footer fijo en la parte inferior -->
<footer class="bg-dark fixed-bottom text-center">
    <!-- Versión de PHP actual -->
    <p>&copy; <?= date('Y') ?> <?php echo 'PHP ' . phpversion() ?></p>
</footer>
<!-- Scripts para el formulario y el generador de contraseñas -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/+Ocq7j4nQx8aMiX/9poTgPmF1DdV/ZmZlZcAwkRtjz4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<!-- Copiar contraseña al portapapeles -->
<script>
function copyToClipboard() {
    var password = document.getElementById("password");
    password.select();
    document.execCommand("copy");
}
</script>
</body>
</html>
