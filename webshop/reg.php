<?php
    require "assets/php/config.php";

    if (isset($_POST['reg-btn'])) {
        $username = $_POST['username'] ?? '';
        $email    = $_POST['email'] ?? '';
        $pass1    = $_POST['pass1'] ?? '';
        $pass2    = $_POST['pass2'] ?? '';

        if (!$username || !$email || !$pass1 || !$pass2) {
            echo "<script>alert('Hiányzó adatok!');</script>";
        } elseif ($pass1 !== $pass2) {
            echo "<script>alert('A két jelszó nem egyezik!');</script>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Érvénytelen email cím!');</script>";
        } else {
            $sql = "SELECT * FROM users WHERE username='$username'";
            $talalt_felhasznalo = $conn->query($sql);

            if ($talalt_felhasznalo && mysqli_num_rows($talalt_felhasznalo) > 0) {
                echo "<script>alert('Már létezik ilyen felhasználónév!');</script>";
            } else {
                $sql = "SELECT * FROM users WHERE email='$email'";
                $talalt_email = $conn->query($sql);

                if ($talalt_email && mysqli_num_rows($talalt_email) > 0) {
                    echo "<script>alert('Már létezik ilyen email cím!');</script>";
                } else {
                    $titkositott_jelszo = password_hash($pass1, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$titkositott_jelszo')";
                    $conn->query($sql);

                    echo "<script>alert('Sikeres regisztráció!'); window.location.href='login.php';</script>";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang='hu'>
       <title>Regisztráció</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Rövid leírás az oldal tartalmáról'>
       <meta name='keywords' content='Keresést, Segítő, Szavak, Vesszővel, Elválasztva'>
       <meta name='author' content='Csontos Kincső'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       <link rel='stylesheet' href='assets/css/styles.css'>
       <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
       <script src='assets/js/script.js'></script>
   </head>
   <body>
    <?php
        include 'assets/php/navbar.php';
    ?>
    <main class="auth">
        <section class="auth-card">
            <header class="auth-head">
                <h1>Regisztráció</h1>
            </header>
            <div class="auth-body">
                <form method="post" novalidate>
                    <div class="field">
                        <label for="username">Felhasználónév</label>
                        <div class="input">
                            <input id="username" name="username" type="text" placeholder="pl. doomhyena" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label for="email">E-mail</label>
                        <div class="input">
                            <input id="email" name="email" type="email" placeholder="te@pelda.hu" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="field">
                            <label for="pass1">Jelszó</label>
                            <div class="input">
                                <input id="pass1" name="pass1" type="password" placeholder="••••••••" required>
                            </div>
                        </div>
                        <div class="field">
                            <label for="pass2">Jelszó mégegyszer</label>
                            <div class="input">
                                <input id="pass2" name="pass2" type="password" placeholder="••••••••" required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <button class="auth-submit" type="submit" name="reg-btn" value="1">Regisztrálok</button>
                    <br>
                    <div class="auth-actions">
                        <div class="links">Van már fiókod? <a href="login.php">Jelentkezz be</a>.</div>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php
        include 'assets/php/footer.php';
    ?>
   </body>
</html>