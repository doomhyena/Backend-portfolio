<?php
    require "../assets/php/config.php";
    session_start();

    if (isset($_SESSION['admin_id'])) {
        header("Location: adminpanel.php");
        exit;
    }

    $error = "";

    if (isset($_POST['login-btn'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM admin WHERE username = '$username'";
        $talalt = $conn->query($sql);

        if ($talalt && $talalt->num_rows > 0) {
            $admin = $talalt->fetch_assoc();

            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                header("Location: adminpanel.php");
                exit;
            } else {
                $error = "Hibás jelszó!";
            }
        } else {
            $error = "Nincs ilyen admin felhasználó!";
        }
    }
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin bejelentkezés</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/js/script.js"></script>
</head>
<body>
<main class="auth">
    <section class="auth-card">
        <header class="auth-head">
            <h1>Admin bejelentkezés</h1>
        </header>
        <div class="auth-body">
            <?php if ($error !== ""): ?>
                <p class="form-error"><?= $error ?></p>
            <?php endif; ?>
            <form method="post">
                <div class="field">
                    <label for="username">Felhasználónév</label>
                    <div class="input">
                        <input id="username" name="username" type="text" required>
                    </div>
                </div>
                <div class="field">
                    <label for="password">Jelszó</label>
                    <div class="input">
                        <input id="password" name="password" type="password" required>
                    </div>
                </div>
                <button class="auth-submit mt-3" type="submit" name="login-btn">Belépés</button>
            </form>
        </div>
    </section>
</main>
</body>
</html>
