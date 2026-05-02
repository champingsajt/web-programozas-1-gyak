<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($password)) {
        $error = "Minden mezőt ki kell tölteni.";
    } elseif ($password !== $confirm_password) {
        $error = "A jelszavak nem egyeznek.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "A felhasználónév már foglalt.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashed_password])) {
                $success = "Sikeres regisztráció! Most már bejelentkezhetsz.";
            } else {
                $error = "Hiba történt a regisztráció során.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Regisztráció - Procrastinator Exciter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card p-4">
                    <h2 class="text-center mb-4">Regisztráció</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Felhasználónév</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jelszó</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jelszó újra</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Regisztráció</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="login.php">Már van fiókod? Jelentkezz be!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
