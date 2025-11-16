<?php
    require "../assets/php/config.php";
    session_start();

    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit;
    }

    if (!isset($_GET['id'])) {
        header("Location: adminpanel.php");
    }
    $id = $_GET['id'];

    $talalt = $conn->query("SELECT * FROM products WHERE id = " . $id);
    if (!$talalt || $talalt->num_rows === 0) {
        header("Location: adminpanel.php");
    }
    $termek = $talalt->fetch_assoc();
    $success = "";

    if (isset($_POST['save-btn'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $qty = $_POST['qty'];
        $sale_price = $_POST['sale_price'];

        if (!isset($_POST['is_sale'])) {
            $sale_price = 0;
        }

        $sql = "UPDATE products SET name = '$name', price = $price, sale_price = $sale_price, quantity = $qty WHERE id = " . $id;
        $conn->query($sql);

        $success = "A termék módosításai mentésre kerültek.";

        $talalt = $conn->query("SELECT * FROM products WHERE id = " . $id);
        if ($talalt && $talalt->num_rows > 0) {
            $termek = $talalt->fetch_assoc();
        }
    }
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Termék szerkesztése</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon">
    <script src="../assets/js/script.js"></script>
</head>
<body>
<main>
    <nav>
        <div class="container navbar">
            <a class="brand" href="../index.php">
                <img src="../assets/img/logo.svg" alt="Logo">
                <span>Webshop – Admin</span>
            </a>
            <button class="menu-toggle" aria-label="Menü">☰</button>
            <div class="nav-links">
                <a href="adminpanel.php">Admin panel</a>
                <a href="logout.php">Kijelentkezés</a>
            </div>
        </div>
    </nav>

    <div class="container admin-page">
        <div class="admin-card">
            <h2 class="admin-title">Termék szerkesztése (ID: <?= $termek['id'] ?>)</h2>

            <?php if ($success !== ""): ?>
                <p class="form-success"><?= $success ?></p>
            <?php endif; ?>

            <form method="post" class="admin-form">
                <div class="form-row">
                    <div class="field">
                        <label>Név</label>
                        <input type="text" name="name" value="<?= $termek['name'] ?>" required>
                    </div>
                    <div class="field">
                        <label>Ár (Ft)</label>
                        <input type="number" name="price" min="1" value="<?= $termek['price'] ?>" required>
                    </div>
                    <div class="field">
                        <label>Akciós ár (Ft)</label>
                        <input type="number" name="sale_price" min="0" value="<?= $termek['sale_price'] ?>">
                    </div>
                    <div class="field">
                        <label>Darabszám</label>
                        <input type="number" name="qty" min="0" value="<?= $termek['quantity'] ?>" required>
                    </div>
                    <div class="field switch-field">
                        <label>Akciós</label>
                        <label class="switch">
                            <input type="checkbox" name="is_sale" <?= ($termek['sale_price'] > 0 ? "checked" : "") ?>>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                <div class="actions-row">
                    <button class="admin-submit" type="submit" name="save-btn">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</main>
</body>
</html>
