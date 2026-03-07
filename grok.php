<?php
session_start();

// Hardcoded password
$hardcoded_password = "securepassword123"; // Change this to your desired password
$hardcoded_password = "123*"; // Change this to your desired password

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_password = $_POST['password'];
    if ($entered_password === $hardcoded_password) {
        $_SESSION['logged_in'] = true;
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=dashboard");
        exit;
    } else {
        $error = "Incorrect password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Login System</title>
    <!-- Bootstrap 4.6 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome 5 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Elegant modern colors for office environment */
        body {
            background-color: #f4f7fa; /* Light gray-blue */
            color: #333;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background-color: #2c3e50; /* Dark blue-gray */
        }
        .navbar-brand, .nav-link {
            color: #ecf0f1 !important; /* Light gray */
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #bdc3c7 !important; /* Slightly darker light gray */
        }
        .dropdown-menu {
            background-color: #34495e; /* Darker blue-gray */
        }
        .dropdown-item {
            color: #ecf0f1;
        }
        .dropdown-item:hover {
            background-color: #2c3e50;
            color: #fff;
        }
        .footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            text-align: center;
            padding: 10px;
            margin-top: auto;
        }
        .content {
            padding: 20px;
            flex: 1;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #3498db; /* Blue */
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .alert-danger {
            margin-bottom: 20px;
        }
        #generatedPassword {
            font-family: monospace;
            font-size: 18px;
        }
    </style>
</head>
<body>

<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>

    <!-- Fixed Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="<?php echo $_SERVER['PHP_SELF']; ?>?page=dashboard">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Menu
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="?page=1"><i class="fas fa-home mr-2"></i>Option 1</a>
                        <a class="dropdown-item" href="?page=2"><i class="fas fa-user mr-2"></i>Option 2</a>
                        <a class="dropdown-item" href="?page=3"><i class="fas fa-cog mr-2"></i>Option 3</a>
                        <a class="dropdown-item" href="?page=4"><i class="fas fa-chart-bar mr-2"></i>Option 4</a>
                        <a class="dropdown-item" href="?page=generator"><i class="fas fa-key mr-2"></i>Password Generator (Option 5)</a>
                        <a class="dropdown-item" href="?page=6"><i class="fas fa-envelope mr-2"></i>Option 6</a>
                        <a class="dropdown-item" href="?page=7"><i class="fas fa-file-alt mr-2"></i>Option 7</a>
                        <a class="dropdown-item" href="?page=8"><i class="fas fa-bell mr-2"></i>Option 8</a>
                        <a class="dropdown-item" href="?page=9"><i class="fas fa-lock mr-2"></i>Option 9</a>
                        <a class="dropdown-item" href="?page=10"><i class="fas fa-sign-out-alt mr-2"></i>Option 10</a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="https://example.com" target="_blank">External Link <i class="fas fa-external-link-alt"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?logout=true">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content mt-5 pt-3">
        <div class="container">
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
            if ($page === 'dashboard' || preg_match('/^\d+$/', $page)) {
                echo "<h2>Welcome to the Dashboard</h2>";
                echo "<p>AI Model: Grok 4 built by xAI</p>";
                echo "<p>Current PHP Version: " . phpversion() . "</p>";
                if (preg_match('/^\d+$/', $page)) {
                    echo "<p>You selected Option $page.</p>";
                }
            } elseif ($page === 'generator') {
                ?>
                <h2>Password Generator</h2>
                <p>Generate a 13-character password with uppercase, lowercase letters, and numbers. Excludes: 0,1,i,I,o,O,m,M</p>
                <button class="btn btn-primary" onclick="generatePassword()">Generate</button>
                <button class="btn btn-secondary" onclick="copyPassword()" id="copyBtn" disabled>Copy to Clipboard</button>
                <div class="mt-3">
                    <strong>Generated Password:</strong>
                    <span id="generatedPassword"></span>
                </div>
                <script>
                    function generatePassword() {
                        const uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // Excludes I, O, M
                        const lowercase = 'abcdefghjklnpqrstuvwxyz'; // Excludes i, o, m
                        const numbers = '23456789'; // Excludes 0,1
                        const allChars = uppercase + lowercase + numbers;

                        let password = '';
                        // Ensure at least one of each
                        password += uppercase[Math.floor(Math.random() * uppercase.length)];
                        password += lowercase[Math.floor(Math.random() * lowercase.length)];
                        password += numbers[Math.floor(Math.random() * numbers.length)];

                        // Fill the rest
                        for (let i = 3; i < 13; i++) {
                            password += allChars[Math.floor(Math.random() * allChars.length)];
                        }

                        // Shuffle to randomize positions
                        password = password.split('').sort(() => 0.5 - Math.random()).join('');

                        document.getElementById('generatedPassword').textContent = password;
                        document.getElementById('copyBtn').disabled = false;
                    }

                    function copyPassword() {
                        const password = document.getElementById('generatedPassword').textContent;
                        if (password) {
                            navigator.clipboard.writeText(password).then(() => {
                                alert('Password copied to clipboard!');
                            }).catch(err => {
                                console.error('Failed to copy: ', err);
                            });
                        }
                    }
                </script>
                <?php
            } else {
                echo "<h2>Page not found</h2>";
            }
            ?>
        </div>
    </div>

    <!-- Fixed Footer -->
    <footer class="footer fixed-bottom">
        <p>&copy; 2026 PHP Login System</p>
    </footer>

<?php else: ?>

    <!-- Login Form -->
    <div class="login-container">
        <h2 class="text-center mb-4">Login</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>

<?php endif; ?>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
