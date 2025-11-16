<?php
    require "assets/php/config.php";
    session_start();

    if(!isset($_SESSION['kosar'])){
        $_SESSION['kosar'] = [];
    }

    $rendezes = "default";

    if (isset($_POST['sorter'])) {
        $rendezes = $_POST['sorter'];
    }

    switch($rendezes){
        case "default":
            $lekerdezes = "SELECT * FROM products";
            break;
        case "name":
            $lekerdezes = "SELECT * FROM products ORDER BY name";
            break;
        case "cheap":
            $lekerdezes = "SELECT * FROM products ORDER BY price";
            break;
        case "exp":
            $lekerdezes = "SELECT * FROM products ORDER BY price DESC";
            break;
        case "ordered":
            $lekerdezes = "SELECT * FROM products ORDER BY sold_quantity DESC";
            break;
        default:
            $lekerdezes = "SELECT * FROM products";
    }

    $talalt = $conn->query($lekerdezes);
    if (isset($_POST['cart-btn'], $_POST['id'])) {
        $id = (int)$_POST['id'];
        $db = 1;

        $_SESSION['kosar'][] = ['id' => $id, 'db' => $db];

        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang='hu'>
<head>
    <title>Főoldal</title>
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
        <div class="products">
            <form method="post">
                <label>Rendezés: </label>
                <select name="sorter" onchange="this.form.submit()">
                    <option value="default" <?php if($rendezes == "default") echo "selected"; ?>>-</option>
                    <option value="name" <?php if($rendezes == "name") echo "selected"; ?>>Név szerint</option>
                    <option value="cheap" <?php if($rendezes == "cheap") echo "selected"; ?>>Olcsók elől</option>
                    <option value="exp" <?php if($rendezes == "exp") echo "selected"; ?>>Drágák elől</option>
                    <option value="ordered" <?php if($rendezes == "ordered") echo "selected"; ?>>Népszerűség szerint</option>
                </select>
            </form>
            <div class="product-list">
                <?php while($termek = $talalt->fetch_assoc()): ?>
                    <article class="card product">
                        <div class="thumb">
                            <img src="<?= $termek['image_path'] ?? 'assets/img/placeholder.png' ?>"
                                 alt="<?= $termek['name'] ?>">
                        </div>
                        <div class="card-body">
                            <div class="title">
                                <a href="product.php?id=<?= $termek['id'] ?>">
                                    <?= $termek['name'] ?>
                                </a>
                            </div>
                            <?php if($termek['sale_price'] == 0): ?>
                                <span class="price"><?= $termek['price'] ?> Ft</span>
                            <?php else: ?>
                                <div class="price-row">
                                    <span class="price old"><?= $termek['price'] ?> Ft</span>
                                    <span class="price"><?= $termek['sale_price'] ?> Ft</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <form method="post">
                                <input type="hidden" name="id" value="<?= (int)$termek['id'] ?>">
                                <button type="submit" name="cart-btn">Kosárba</button>
                            </form>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
        <?php include 'assets/php/footer.php'; ?>
    </main>
</body>
</html>
