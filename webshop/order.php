<?php
    require "assets/php/config.php";
    require "assets/php/functions.php";

    session_start();

    $vegosszeg  = 0;
    $orderRows  = [];
    $orderCode  = "";
    $errorMsg   = "";

    if (isset($_POST['code-btn']) && isset($_POST['code']) && $_POST['code'] !== "") {
        $orderCode = $_POST['code'];
        header("Location: order.php?ordercode=" . $orderCode);
        exit;
    }

    if (isset($_GET['ordercode'])) {
        $orderCode = $_GET['ordercode'];

        $sql = "SELECT * FROM orders WHERE code = '$orderCode'";
        $talalt = $conn->query($sql);

        if ($talalt && $talalt->num_rows > 0) {
            $rendeles = $talalt->fetch_assoc();
            $productsString = $rendeles['products'];

            $termekek = explode(";", $productsString);

            foreach ($termekek as $t) {
                if ($t === "") {
                    continue;
                }

                $parts = explode("-", $t);
                if (count($parts) != 2) {
                    continue;
                }

                $id = (int)$parts[0];
                $db = (int)$parts[1];

                if ($id <= 0 || $db <= 0) {
                    continue;
                }

                $termek_lekerdezes = "SELECT * FROM products WHERE id = $id";
                $termek_talalt = $conn->query($termek_lekerdezes);

                if ($termek_talalt && $termek_talalt->num_rows > 0) {
                    $termek = $termek_talalt->fetch_assoc();

                    if ($termek['sale_price'] == 0) {
                        $ar = $termek['price'];
                    } else {
                        $ar = $termek['sale_price'];
                    }

                    $osszeg = $ar * $db;
                    $vegosszeg += $osszeg;

                    $orderRows[] = [
                            'name'   => $termek['name'],
                            'db'     => $db,
                            'ar'     => $ar,
                            'osszeg' => $osszeg
                    ];
                }
            }
        } else {
            $errorMsg = "Nincs rendelés ilyen azonosítóval!";
        }
    }
?>
<!DOCTYPE html>
<html lang='hu'>
<head>
    <title>Rendelés nyomonkövetése</title>
    <meta charset='UTF-8'>
    <meta name='description' content='Rövid leírás az oldal tartalmáról'>
    <meta name='keywords' content='rendelés, követés, webshop'>
    <meta name='author' content='Csontos Kincső'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='assets/css/styles.css'>
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <script src='assets/js/script.js'></script>
</head>
<body>
<main>
    <?php include 'assets/php/navbar.php'; ?>
    <div class="order-container">
        <form method="post">
            <input
                    type="text"
                    name="code"
                    placeholder="Rendelésed kódja"
                    value="<?php echo $orderCode; ?>"
            >
            <br><br>
            <input type="submit" name="code-btn" value="Keresés">
        </form>
        <?php if ($errorMsg !== ""): ?>
            <p class="error mt-4"><?php echo $errorMsg; ?></p>
        <?php endif; ?>
        <?php if ($orderCode !== "" && $errorMsg === "" && count($orderRows) > 0): ?>
            <h2 class="mt-4">
                Rendelés kódja:
                <span style="color: var(--brand);">
                    <?php echo $orderCode; ?>
                </span>
            </h2>
            <table class="table mt-3">
                <thead>
                <tr>
                    <th>Termék neve</th>
                    <th>Mennyiség</th>
                    <th>Egységár</th>
                    <th>Összesen</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orderRows as $row): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['db']; ?> db</td>
                        <td><?php echo $row['ar']; ?> Ft</td>
                        <td><?php echo $row['osszeg']; ?> Ft</td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" style="text-align:right;"><b>Végösszeg:</b></td>
                    <td><b><?php echo $vegosszeg; ?> Ft</b></td>
                </tr>
                </tbody>
            </table>
        <?php elseif ($orderCode !== "" && $errorMsg === "" && count($orderRows) === 0): ?>
            <p class="no-data mt-4">A rendeléshez nem tartoznak termékek.</p>
        <?php endif; ?>
    </div>
    <?php include 'assets/php/footer.php'; ?>
</main>
</body>
</html>
