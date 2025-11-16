<?php
    require "../assets/php/config.php";
    session_start();

    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
    }

    if (isset($_POST['action']) && $_POST['action'] === "add") {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $qty = $_POST['qty'];
        $sale_price = $_POST['sale_price'];
        $description = $_POST['description'];

        if (!isset($_POST['is_sale'])) {
            $sale_price = 0;
        }

        if ($name !== "" && $price !== "" && $qty !== "") {
            $sql = "INSERT INTO products (name, price, sale_price, quantity, sold_quantity, description, image_path) VALUES ( '$name', $price, $sale_price, $qty, 0, '$description', '')";
            $conn->query($sql);
        }
    }
    $products = [];
    $sql = $conn->query("SELECT * FROM products ORDER BY id DESC");
    if ($sql && $sql->num_rows > 0) {
        while ($row = $sql->fetch_assoc()) {
            $products[] = $row;
        }
    }
?>
<!DOCTYPE html>
<html lang='hu'>
<head>
    <title>Admin Panel</title>
    <meta charset='UTF-8'>
    <meta name='description' content='Admin felület'>
    <meta name='keywords' content='admin, webshop'>
    <meta name='author' content='Csontos Kincső'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='../assets/css/styles.css'>
    <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon">
    <script src='../assets/js/script.js'></script>
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
                <a href="../index.php">Főoldal</a>
                <a href="../order.php">Rendelés követése</a>
                <a href="../cart.php">Kosár
                    <span class="badge"><?= (isset($_SESSION["kosar"]) ? count($_SESSION["kosar"]) : 0) ?></span>
                </a>
                <a href="logout.php">Kijelentkezés</a>
            </div>
        </div>
    </nav>
    <div class="container admin-page">
        <div class="admin-card">
            <h2 class="admin-title">Új termék</h2>
            <form method="post" class="admin-form">
                <input type="hidden" name="action" value="add">
                <div class="form-row">
                    <div class="field">
                        <label>Név</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="field">
                        <label>Ár (Ft)</label>
                        <input type="number" name="price" min="1" required>
                    </div>
                    <div class="field">
                        <label>Akciós ár (Ft)</label>
                        <input type="number" name="sale_price" min="0" value="0">
                    </div>
                    <div class="field">
                        <label>Darabszám</label>
                        <input type="number" name="qty" min="1" required>
                    </div>
                    <div class="field">
                        <label>Leírás</label>
                        <input type="text" name="description">
                    </div>
                    <div class="field switch-field">
                        <label>Akciós</label>
                        <label class="switch">
                            <input type="checkbox" name="is_sale">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                <div class="actions-row">
                    <button class="admin-submit" type="submit">Felvétel</button>
                </div>
            </form>
        </div>
        <div class="admin-card">
            <div class="row spread">
                <h3 class="admin-title">Termékek</h3>
            </div>
            <?php if ($products): ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Név</th>
                        <th>Ár</th>
                        <th>Darabszám</th>
                        <th>Akciós</th>
                        <th>Műveletek</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= $p['name'] ?></td>
                            <td><?= $p['price'] ?> Ft</td>
                            <td><?= $p['quantity'] ?></td>
                            <td><?= ($p['sale_price'] > 0 ? 'Igen' : 'Nem') ?></td>
                            <td class="actions">
                                <a href="edit.php?id=<?= $p['id'] ?>">Szerkesztés</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Nincs feltöltve termék az adatbázisba.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include '../assets/php/footer.php'; ?>
</main>
</body>
</html>
