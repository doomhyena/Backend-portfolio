<?php
require "assets/php/config.php";
require "assets/php/functions.php";
session_start();

// user id cookie-ból (ha nincs id cookie, marad 0)
$user_id = 0;
if (isset($_COOKIE['id'])) {
    $user_id = $_COOKIE['id'];
}

// kosár session inicializálás
if (!isset($_SESSION['kosar'])) {
    $_SESSION['kosar'] = array();
}

$successCode = "";

// KOSÁR TÖRLÉSE
if (isset($_POST['del-btn'])) {
    $_SESSION['kosar'] = array();
    if ($user_id) {
        $conn->query("DELETE FROM cart WHERE user_id = " . $user_id);
    }
}

// MEGRENDELÉS
if (isset($_POST['order-btn']) && $_SESSION['kosar']) {
    $kosar = $_SESSION['kosar'];

    // products string felépítése kézzel, implode nélkül
    $rendeles = "";
    $first = 1;
    foreach ($kosar as $item) {
        if ($first === 0) {
            $rendeles = $rendeles . ";";
        }
        $rendeles = $rendeles . $item['id'] . "-" . $item['db'];
        $first = 0;
    }

    $code = codeGenerator(); // ez saját függvényed, maradhat

    $conn->query(
            "INSERT INTO orders (products, code, user_id) VALUES ('" .
            $rendeles . "', '" . $code . "', " . $user_id . ")"
    );

    $_SESSION['kosar'] = array();
    if ($user_id) {
        $conn->query("DELETE FROM cart WHERE user_id = " . $user_id);
    }

    $successCode = $code;
}

// Ha be van jelentkezve és üres a session kosár, töltsük be DB-ből
if ($user_id && !$_SESSION['kosar']) {
    $result = $conn->query("SELECT * FROM cart WHERE user_id = " . $user_id);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $uj = array();
            $uj['id'] = $row['product_id'];
            $uj['db'] = $row['quantity'];
            $_SESSION['kosar'][] = $uj;
        }
    }
}

// Session kosár visszamentése DB-be (szinkron)
if ($user_id) {
    $conn->query("DELETE FROM cart WHERE user_id = " . $user_id);
    foreach ($_SESSION['kosar'] as $t) {
        $pid = $t['id'];
        $db  = $t['db'];
        $conn->query(
                "INSERT INTO cart (user_id, product_id, quantity) VALUES (" .
                $user_id . ", " . $pid . ", " . $db . ")"
        );
    }
}

// Kosár tételek + végösszeg kiszámítása
$items = array();
$vegosszeg = 0;
$hasItems = 0;

foreach ($_SESSION['kosar'] as $t) {
    $id = $t['id'];
    $db = $t['db'];

    $lekerdezes = "SELECT * FROM products WHERE id = " . $id;
    $talalt = $conn->query($lekerdezes);

    if ($talalt && $talalt->num_rows > 0) {
        $termek = $talalt->fetch_assoc();
        if ($termek['sale_price'] == 0) {
            $ar = $termek['price'];
        } else {
            $ar = $termek['sale_price'];
        }

        $osszeg = $ar * $db;
        $vegosszeg = $vegosszeg + $osszeg;

        $sor = array();
        $sor['name'] = $termek['name'];
        $sor['db'] = $db;
        $sor['ar'] = $ar;
        $sor['osszeg'] = $osszeg;

        $items[] = $sor;
        $hasItems = 1;
    }
}
?>
<!DOCTYPE html>
<html lang='hu'>
<head>
    <title>Kosár</title>
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
<main>
    <?php include 'assets/php/navbar.php'; ?>

    <div class="container" style="padding: 30px 0;">

        <?php if ($successCode !== ""): ?>
            <p class="form-success mt-3">
                A rendelésed azonosítója:
                <b><?= $successCode ?></b>
            </p>
        <?php endif; ?>

        <?php if ($hasItems === 0): ?>
            <p class="no-data">A kosár üres.</p>
        <?php else: ?>
            <table class="table mt-4">
                <thead>
                <tr>
                    <th>Termék neve</th>
                    <th>Mennyiség</th>
                    <th>Egységár</th>
                    <th>Összesen</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['db'] ?> db</td>
                        <td><?= $item['ar'] ?> Ft</td>
                        <td><?= $item['osszeg'] ?> Ft</td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" style="text-align:right;"><b>Végösszeg:</b></td>
                    <td><b><?= $vegosszeg ?> Ft</b></td>
                </tr>
                </tbody>
            </table>

            <form method="post" class="mt-4">
                <input class="mt-3" type="submit" name="order-btn" value="Megrendelés">
                <input class="mt-2 btn danger" type="submit" name="del-btn" value="Kosár törlése">
            </form>
        <?php endif; ?>
    </div>

    <?php include 'assets/php/footer.php'; ?>
</main>
</body>
</html>
