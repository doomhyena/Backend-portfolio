<?php
    $isLoggedIn = false;
    $user = null;
    $notify_number = 0;


    if (!empty($_COOKIE['id'])) {
        $userid = $_COOKIE['id'];

        $sql = "SELECT * FROM users WHERE id = $userid";
        $found_user = $conn->query($sql);

        if ($found_user && $found_user->num_rows > 0) {
            $user = $found_user->fetch_assoc();
            $isLoggedIn = true;

            $sql = "SELECT * FROM notifys WHERE toid = $userid AND readed = 0";
            $founded_notify = $conn->query($sql);
            $notify_number = $founded_notify ? $founded_notify->num_rows : 0;
            $currentUserId = ($user['id'] ?? 0);
        } else {
            $isLoggedIn = false;
            setcookie("id", "", time() - 3600, "/");
        }
    }
?>

<nav>
    <div class="container navbar">
        <a class="brand" href="index.php">
            <img src="assets/img/logo.svg" alt="Logo">
            <span>Webshop</span>
        </a>
        <button class="menu-toggle" aria-label="Menü">☰</button>
        <div class="nav-links">
            <a href="index.php">Főoldal</a>
            <a href="order.php">Rendelés követése</a>
            <a href="cart.php">
                Kosár
                <span class="badge">
                    <?= isset($_SESSION["kosar"]) ? count($_SESSION["kosar"]) : 0 ?>
                </span>
            </a>
            <?php if ($isLoggedIn): ?>
                <span>Helló, <?= $user['username'] ?></span>
                <a href="assets/php/logout.php">Kijelentkezés</a>
            <?php else: ?>
                <a href="login.php">Bejelentkezés</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
