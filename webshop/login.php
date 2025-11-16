<?php
    session_start();
    require "assets/php/config.php";

    if (isset($_POST['login-btn'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username='$username'";
        $found_user = $conn->query($sql);

        if ($found_user && mysqli_num_rows($found_user) > 0) {
            $user = $found_user->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                setcookie("id", $user['id'], time() + 3600, "/");
                header("Location: index.php");
            } else {
                echo "<script>alert('Hibás jelszó!')</script>";
            }
        } else {
            echo "<script>alert('Nincs ilyen felhasználó!')</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang='hu'>
       <title>Bejelentkezés</title>
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
                <h1>Bejelentkezés</h1>
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
                        <label for="password">Jelszó</label>
                        <div class="input">
                            <input id="password" name="password" type="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    <br>
                    <button class="auth-submit" type="submit" name="login-btn">Bejelentkezem</button>
                    <div class="auth-actions">
                        <div class="links">Még nincs fiókod? <a href="reg.php">Regisztrálj</a>.</div>
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