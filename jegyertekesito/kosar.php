<?php
    require "assets/php/config.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
    $cart_items = [];
    $total = 0;

    if (!empty($cart)) {
        $ids = implode(',', array_map('intval', array_keys($cart)));
        $sql = "SELECT jegyek.*, rendezvenyek.nev AS rendezveny_nev FROM jegyek JOIN rendezvenyek ON jegyek.rendezveny_id = rendezvenyek.id WHERE jegyek.id IN ($ids)";
        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['kosar_darabszam'] = $cart[$row['id']];
                $cart_items[] = $row;
            }
        }
    }

    $vasarlas_siker = false;
    if (isset($_POST['checkout']) && !empty($cart_items)) {
        $email = $_POST['email'];
        foreach ($cart_items as $item) {

            $ticket_id = (int)$item['id'];
            $mennyiseg = (int)$item['kosar_darabszam'];
            $conn->query("UPDATE jegyek SET darabszam = GREATEST(darabszam - $mennyiseg, 0) WHERE id = $ticket_id");
        }
        setcookie('cart', '', time() - 3600, "/"); 
        $vasarlas_siker = true;
        $cart_items = [];
    }
?>
<!DOCTYPE html>
<html lang='hu'>
   <head>
       <title>Kosár</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Koncertjegy értékesítő oldal'>
       <meta name='keywords' content='Koncert, Jegy, Értékesítés, Rendezvény, Zene'>
       <meta name='author' content='Csontos Kincső'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='assets/css/styles.css?v=<?=time()?>'>
   </head>
   <body>
    <?php include 'assets/php/navbar.php'; ?>
        <h1>Kosár</h1>
        <?php if ($vasarlas_siker): ?>
            <div class="success">Sikeres vásárlás! Köszönjük a rendelést.</div>
        <?php endif; ?>
        <?php if (!empty($cart_items)): ?>
        <form method="POST">
            <label for="email">Email cím:</label>
            <input type="email" name="email" required>
            <h2>Rendelésed:</h2>
            <ul>
                <?php foreach ($cart_items as $item): ?>
                    <li>
                        <?php echo htmlspecialchars($item['nev']); ?> (<?php echo htmlspecialchars($item['rendezveny_nev']); ?>) -
                        <?php echo $item['kosar_darabszam']; ?> db
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="submit" name="checkout">Rendelés véglegesítése</button>
        </form>
        <?php else: ?>
            <div class="ures-kosar">A kosarad üres.</div>
        <?php endif; ?>
    <?php include 'assets/php/footer.php'; ?>
    </body>
</html>