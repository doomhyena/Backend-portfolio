<?php
    require __DIR__ . "/../../common/config.php";
    require __DIR__ . "/../../common/functions.php"; 

    $allowed_statuses = [
        'lefoglalva' => 'Lefoglalva',
        'elfogadva' => 'Foglalás elfogadva',
        'megerkezett' => 'Megérkezett',
        'fizetett' => 'Fizetett'
    ];

    if (isset($_POST['update-status-btn'])) {
        do {
            $resId = isset($_POST['res_id']) ? (int)$_POST['res_id'] : 0;
            $newStatus = $_POST['new_status'] ?? '';

            if ($resId <= 0) {
                Message("Érvénytelen foglalás azonosító.");
                break;
            }

            if (!array_key_exists($newStatus, $allowed_statuses)) {
                Message("Érvénytelen státusz érték.");
                break;
            }

            $sqlOld = "SELECT r.*, rm.room_number, rm.price_per_night, rt.name AS room_type FROM reservations r INNER JOIN rooms rm ON rm.id = r.room_id INNER JOIN room_types rt ON rt.id = rm.room_type_id WHERE r.id = $resId LIMIT 1";
            $resOld = $conn->query($sqlOld);

            if (!$resOld || $resOld->num_rows === 0) {
                Message("Nem található ilyen foglalás.");
                break;
            }

            $old = $resOld->fetch_assoc();
            $oldStatus = $old['status'];

            $newStatusEsc = $conn->real_escape_string($newStatus);
            $sqlUpdate = "UPDATE reservations SET status = '$newStatusEsc' WHERE id = $resId";
            if (!$conn->query($sqlUpdate)) {
                Message("Hiba történt a státusz frissítésekor: " . $conn->error);
                break;
            }

            Message("Státusz sikeresen frissítve.");

            if ($newStatus === 'elfogadva' && $oldStatus !== 'elfogadva') {

                $hotel_name = "Stellar Hotel";
                $hotel_email = "info@stellarhotel.hu";
                $hotel_address = "1234 Budapest, Valami utca 1.";
                $hotel_phone = "+36 30 123 4567";
                $booking_link = "foglalas.php?id=" . $resId;

                $checkinDate  = $old['checkin_date'];
                $checkoutDate = $old['checkout_date'];

                $d1 = new DateTime($checkinDate);
                $d2 = new DateTime($checkoutDate);
                $diff = $d1->diff($d2);
                $nights = $diff->days;

                $pricePerNight = (int)$old['price_per_night'];
                $totalPrice    = $nights * $pricePerNight;

                $subject = "Foglalás elfogadva - " . $hotel_name;

                $uzenet = "
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <title>Foglalás elfogadva</title>
                </head>
                <body style='font-family: Arial, sans-serif; background-color:#f5f5f5; margin:0; padding:20px;'>
                    <div style='max-width:600px; margin:0 auto; background:#ffffff; border:1px solid #dddddd; border-radius:4px; padding:20px;'>
                        <h1 style='font-size:22px; margin-top:0;'>Kedves ".htmlspecialchars($old['guest_name'],ENT_QUOTES,'UTF-8')."!</h1>
                        <p>Örömmel értesítünk, hogy a(z) <strong>{$hotel_name}</strong> elfogadta a foglalásodat.</p>
                        <p>Foglalás részletei:</p>
                        <table style='width:100%; border-collapse:collapse; margin:15px 0;' role='presentation'>
                            <tr><th align='left'>Foglalási azonosító</th><td>#{$resId}</td></tr>
                            <tr><th align='left'>Szobatípus</th><td>". $old['room_type'] ."</td></tr>
                            <tr><th align='left'>Szobaszám</th><td>". $old['room_number'] ."</td></tr>
                            <tr><th align='left'>Érkezés dátuma</th><td>{$checkinDate}</td></tr>
                            <tr><th align='left'>Távozás dátuma</th><td>{$checkoutDate}</td></tr>
                            <tr><th align='left'>Éjszakák száma</th><td>{$nights}</td></tr>
                            <tr><th align='left'>Ár / éjszaka</th><td>". $pricePerNight ." Ft</td></tr>
                            <tr><th align='left'>Végösszeg</th><td><strong>". $totalPrice ." Ft</strong></td></tr>
                        </table>
                        <p>Amennyiben módosítani vagy lemondani szeretnéd a foglalást, kérjük, jelezd elérhetőségeinken.</p>
                        <p>
                            Üdvözlettel,<br>
                            <strong>{$hotel_name}</strong><br>
                            {$hotel_address}<br>
                            Telefon: {$hotel_phone}<br>
                            E-mail: {$hotel_email}
                        </p>
                    </div>
                </body>
                </html>
                ";

                $headers  = "From: {$hotel_name} <{$hotel_email}>\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8\r\n";

                mail($old['guest_email'], $subject, $uzenet, $headers);
            }

        } while (false);
    }

    $sqlRevenue = "SELECT SUM(DATEDIFF(r.checkout_date, r.checkin_date) * rm.price_per_night) AS total_revenue FROM reservations r INNER JOIN rooms rm ON rm.id = r.room_id WHERE r.status = 'fizetett' AND YEAR(r.checkin_date) = YEAR(CURDATE()) AND MONTH(r.checkin_date) = MONTH(CURDATE())";
    $revResult = $conn->query($sqlRevenue);
    $monthlyRevenue = 0;

    if ($revResult) {
        $revRow = $revResult->fetch_assoc();
        if ($revRow && $revRow['total_revenue'] !== null) {
            $monthlyRevenue = $revRow['total_revenue'];
        }
    } else {
        Message("Hiba történt a havi bevétel lekérdezésekor: " . $conn->error);
    }

    $sqlList = "SELECT  r.*, rm.room_number, rm.price_per_night, rt.name AS room_type, DATEDIFF(r.checkout_date, r.checkin_date) AS nights, (DATEDIFF(r.checkout_date, r.checkin_date) * rm.price_per_night) AS total_price FROM reservations r INNER JOIN rooms rm ON rm.id = r.room_id INNER JOIN room_types rt ON rt.id = rm.room_type_id ORDER BY r.created_at DESC";
    $reservations = $conn->query($sqlList);

?>
<!DOCTYPE html>
<html lang='hu'>
   <head>
       <title>Admin - Foglalások</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Adminisztrációs felület főoldala'>
       <meta name='keywords' content='Kezelő, Adminisztráció, Szobák, Foglalások'>
       <meta name='author' content='Csontos Kincső Anastázia'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       <link rel="stylesheet" href="../../common/css/styles.css">
       <script src="https://code.jquery.com/jquery-latest.js"></script>
       <script src="/Hotel/common/js/script.js"></script>
   </head>
   <body>
   <div class="page">
       <div class="wrapper">
           <nav class="nav">
               <a href="index.php">Vissza a főoldalra</a>
           </nav>
            <h1>Foglalások</h1>
            <p style="font-weight:bold; font-size: 18px; margin-bottom: 20px;">
                Havi bevétel: <?= $monthlyRevenue ?> Ft
            </p>

            <?php if ($reservations && $reservations->num_rows > 0): ?>
                <table border="1" cellpadding="6">
                    <tr>
                        <th>ID</th>
                        <th>Vendég neve</th>
                        <th>E-mail</th>
                        <th>Szobaszám</th>
                        <th>Szobatípus</th>
                        <th>Érkezés</th>
                        <th>Távozás</th>
                        <th>Éjszakák</th>
                        <th>Ár / éj</th>
                        <th>Összesen</th>
                        <th>Státusz</th>
                        <th>Művelet</th>
                    </tr>
                    <?php while ($row = $reservations->fetch_assoc()): ?>
                        <?php
                            $nights = $row['nights'];
                            $pricePerNight = $row['price_per_night'];
                            $total = $row['total_price'] ? $row['total_price'] : $nights * $pricePerNight;
                            $currentStatus  = $row['status'];
                        ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['guest_name'] ?></td>
                            <td><?= $row['guest_email'] ?></td>
                            <td><?= $row['room_number'] ?></td>
                            <td><?= $row['room_type'] ?></td>
                            <td><?= $row['checkin_date'] ?></td>
                            <td><?= $row['checkout_date'] ?></td>
                            <td><?= $nights ?></td>
                            <td><?= $pricePerNight ?> Ft</td>
                            <td><?= $total ?> Ft</td>
                            <td>
                                <form method="POST" style="margin:0; display:flex; gap:4px; align-items:center;">
                                    <input type="hidden" name="res_id" value="<?= (int)$row['id'] ?>">
                                    <select name="new_status">
                                        <?php foreach ($allowed_statuses as $value => $label): ?>
                                            <option value="<?= $value ?>" <?= $value === $currentStatus ? 'selected' : '' ?>>
                                                <?= $label ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                            </td>
                            <td>
                                    <input type="submit" name="update-status-btn" value="Mentés">
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Jelenleg nincs egyetlen foglalás sem.</p>
            <?php endif; ?>
       </div>
   </div>
   <?php include __DIR__ . "/../../common/templates/footer.php"; ?>
   </body>
</html>
