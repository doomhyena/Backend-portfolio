<?php
    require "assets/php/config.php";
    session_start();

    if (!isset($_SESSION['kosar'])) {
        $_SESSION['kosar'] = [];
    }

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        die("Érvénytelen termék.");
    }

    $sql = "SELECT * FROM products WHERE id = $id";
    $talalt = $conn->query($sql);
    $termek = $talalt ? $talalt->fetch_assoc() : null;

    if (!$termek) {
        http_response_code(404);
        die("A keresett termék nem található.");
    }

    if (isset($_POST['cart-btn'])) {
        $db = isset($_POST['db']) ? (int)$_POST['db'] : 1;
        if ($db < 1) $db = 1;

        if (isset($termek['quantity'])) {
            $max = (int)$termek['quantity'];
            if ($db > $max) $db = $max;
        }

        $found = false;
        foreach ($_SESSION['kosar'] as &$item) {
            if ($item['id'] === $id) {
                $item['db'] += $db;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['kosar'][] = ['id' => $id, 'db' => $db];
        }

        header("Location: cart.php");
    }

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title><?= $termek['name'] ?> - Webshop</title>
    <meta name='description' content='Rövid leírás az oldal tartalmáról'>
    <meta name='keywords' content='Keresést, Segítő, Szavak, Vesszővel, Elválasztva'>
    <meta name="author" content="Csontos Kincső">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <script src="assets/js/script.js"></script>
</head>
<body>
<main>
    <?php include 'assets/php/navbar.php'; ?>
    <section class="container" style="padding: 40px 0;">
        <div class="grid">
            <div class="col-6">
                <article class="card product">
                    <div class="thumb">
                        <img src="<?= $termek['image_path'] ?? 'assets/img/placeholder.png' ?>"
                             alt="<?= $termek['name'] ?>">
                    </div>
                </article>
            </div>
            <div class="col-6">
                <article class="card" style="background: var(--panel);">
                    <div class="card-body">
                        <h1 class="product-title" style="margin-top: 0; margin-bottom: 10px;">
                            <?= $termek['name'] ?>
                        </h1>
                        <?php if (!empty($termek['description'])): ?>
                            <p style="color: var(--muted);">
                                <?= $termek['description'] ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($termek['sale_price'] > 0): ?>
                            <div class="price-row mt-3">
                                <span class="price old">
                                    <?= $termek['price'] ?> Ft
                                </span>
                                <span class="price sale">
                                    <?= $termek['sale_price'] ?> Ft
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="mt-3">
                                <span class="price">
                                    <?= $termek['price'] ?> Ft
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($termek['quantity'])): ?>
                            <p class="mt-2" style="color: var(--muted); font-size: 14px;">
                                Készlet: <?= (int)$termek['quantity'] ?> db
                            </p>
                        <?php endif; ?>
                        <form method="post" class="mt-4" style="display: grid; gap: 10px; max-width: 260px;">
                            <label for="db">Mennyiség</label>
                            <input id="db" type="number" name="db" value="1" min="1"
                                <?php if (isset($termek['quantity'])): ?>
                                    max="<?= (int)$termek['quantity'] ?>"
                                <?php endif; ?>
                            >
                            <button type="submit" name="cart-btn" class="btn">
                                Kosárba
                            </button>
                        </form>
                    </div>
                </article>
            </div>
        </div>
    </section>
    <?php include 'assets/php/footer.php'; ?>
</main>
</body>
</html>
