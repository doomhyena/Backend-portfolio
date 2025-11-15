<?php
    require "assets/php/config.php";

    $rendezveny_id = $_GET['id'];

    $error = '';
    if (isset($_POST['add_to_cart_id'])) {
        $ticket_id = $_POST['add_to_cart_id'];
        $quantity = $_POST['quantity'] ?? 1;
        $ticket_res = $conn->query("SELECT darabszam FROM jegyek WHERE id = $ticket_id");
        $ticket_row = $ticket_res ? $ticket_res->fetch_assoc() : null;
        $available = $ticket_row ? (int)$ticket_row['darabszam'] : 0;
        if ($available <= 0) {
            $error = 'Ez a jegy elfogyott!';
        } elseif ($quantity > $available) {
            $error = 'Nem vásárolhatsz több jegyet, mint amennyi elérhető!';
        } else {
            $cart = isset($_COOKIE['cart']) ? $_COOKIE['cart'] : [];
            if (!isset($cart[$ticket_id])) {
                $cart[$ticket_id] = $quantity;
            } else {
                $cart[$ticket_id] += $quantity;
                if ($cart[$ticket_id] > $available) {
                    $cart[$ticket_id] = $available;
                }
            }
            setcookie('cart', json_encode($cart), time() + 3600, "/");
            header("Location: rendezveny.php?id=$rendezveny_id");
        }
    }

    $sql = "SELECT * FROM rendezvenyek WHERE id = $rendezveny_id";
    $result = $conn->query($sql);
    if (!$result || $result->num_rows === 0) {
        die("A rendezvény nem található.");
    }
    $rendezveny = $result->fetch_assoc();

    $sql_jegyek = "SELECT * FROM jegyek WHERE rendezveny_id = $rendezveny_id";
    $result_jegyek = $conn->query($sql_jegyek);
    $jegyek = [];
    if ($result_jegyek && $result_jegyek->num_rows > 0) {
        while ($row = $result_jegyek->fetch_assoc()) {
            $jegyek[] = $row;
        }
    }
?>
<!DOCTYPE html>
<html lang='hu'>
   <head>
       <title><?php echo htmlspecialchars($rendezveny['nev']); ?></title>
       <meta charset='UTF-8'>
       <meta name='description' content='Koncertjegy értékesítő oldal'>
       <meta name='keywords' content='Koncert, Jegy, Értékesítés, Rendezvény, Zene'>
       <meta name='author' content='Csontos Kincső'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='assets/css/styles.css?v=<?=time()?>'>
   </head>
   <body>
    <?php include 'assets/php/navbar.php'; ?>
    <div class="rendezveny-oldal">
        <h1><?php echo htmlspecialchars($rendezveny['nev']); ?></h1>
        <?php
            $kep = !empty($rendezveny['kep']) && file_exists('uploads/' . $rendezveny['kep'])
                ? 'uploads/' . htmlspecialchars($rendezveny['kep'])
                : 'assets/img/no-image.jpg';
        ?>
        <img src="<?= $kep ?>" alt="<?php echo htmlspecialchars($rendezveny['nev']); ?>">
        <p><b>Dátum:</b> <?php echo htmlspecialchars($rendezveny['datum']); ?></p>
        <p><b>Leírás:</b> <?php echo htmlspecialchars($rendezveny['leiras']); ?></p>
        <h2>Jegyek</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (count($jegyek) > 0): ?>
            <div class="jegyek-lista">
                <?php foreach ($jegyek as $jegy): ?>
                    <div class="jegy">
                        <h3><?php echo htmlspecialchars($jegy['nev']); ?></h3>
                        <?php if ((int)$jegy['darabszam'] > 0): ?>
                            <p>Elérhető: <?php echo (int)$jegy['darabszam']; ?> db</p>
                            <form method="post" style="display:inline-flex; gap:8px; align-items:center; justify-content:center;">
                                <input type="hidden" name="add_to_cart_id" value="<?php echo $jegy['id']; ?>">
                                <input type="number" name="quantity" min="1" max="<?php echo (int)$jegy['darabszam']; ?>" value="1" style="width:60px; padding:6px; border-radius:5px; border:1px solid #ccc;">
                                <button class="kosarba-btn" type="submit">Kosárba</button>
                            </form>
                        <?php else: ?>
                            <p class="elfogyott">Elfogytak a jegyek!</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="ures">Nincs elérhető jegy ehhez a rendezvényhez.</div>
        <?php endif; ?>
    </div>
    <?php include 'assets/php/footer.php'; ?>
   </body>
</html>