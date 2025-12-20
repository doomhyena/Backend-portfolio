<?php
    require __DIR__ . "/../../common/config.php";
    require __DIR__ . "/../../common/functions.php"; 
    
    $typeId = isset($_GET['typeid']) ? (int)$_GET['typeid'] : 0;

    if ($typeId <= 0) {
        Message("Nem megfelelő szobatípus azonosító.");
    }

    if (isset($_POST['book-room-btn'])) {

        do {
            if ($typeId <= 0) {
                Message("Nem megfelelő szobatípus azonosító.");
                break;
            }

            $roomId = $_POST['room_id'];
            $guestName = $_POST['guest_name'];
            $guestEmail = $_POST['guest_email'];
            $checkinDate = $_POST['checkin_date'];
            $checkoutDate = $_POST['checkout_date'];

            if ($roomId <= 0) {
                Message("Válassz szobát a foglaláshoz.");
                break;
            }

            if ($guestName === '') {
                Message("A név megadása kötelező.");
                break;
            }

            if ($guestEmail === '' || strpos($guestEmail, '@') === false) {
                Message("Adj meg egy érvényes email címet.");
                break;
            }

            if ($checkinDate === '' || $checkoutDate === '') {
                Message("Add meg az érkezés és távozás dátumát.");
                break;
            } elseif ($checkinDate >= $checkoutDate) {
                Message("A távozás dátumának az érkezés után kell lennie.");
                break;
            }

            $sqlCheck = "SELECT id FROM reservations WHERE room_id = $roomId AND NOT (checkout_date <= '$checkinDate' OR checkin_date >= '$checkoutDate')LIMIT 1";
            $resCheck = $conn->query($sqlCheck);

            if ($resCheck && $resCheck->num_rows > 0) {
                Message("Erre az időszakra már van foglalás erre a szobára, kérjük válassz másik dátumot.");
            }

            $sqlInsert = "INSERT INTO reservations (room_id, guest_name, guest_email, checkin_date, checkout_date, created_at) VALUES ($roomId, '$guestName', '$guestEmail', '$checkinDate', '$checkoutDate', NOW())";

            if ($conn->query($sqlInsert)) {
            $reservationId = $conn->insert_id;

            $sql = "SELECT r.room_number, rt.name AS room_type, r.price_per_night FROM rooms r INNER JOIN room_types rt ON rt.id = r.room_type_id WHERE r.id = $roomId";
            $res = $conn->query($sql);
            $room = $res->fetch_assoc();

            $hotel_name = "Stellar Hotel";
            $hotel_email = "info@stellarhotel.hu";
            $hotel_address = "1234 Budapest, Valami utca 1.";
            $hotel_phone = "+36 30 123 4567";
            $booking_link = "foglalas.php?id=" . $reservationId;

            $checkin  = new DateTime($checkinDate);
            $checkout = new DateTime($checkoutDate);
            $nights_count = $checkout->days;

            $price_per_night = (int)$room['price_per_night'];
            $total_price = $nights_count * $price_per_night;

            $subject = "Foglalás visszaigazolása - " . $hotel_name;
            $uzenet = "
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>Foglalás visszaigazolása</title>
            </head>
            <body style='font-family: Arial, sans-serif; background-color:#f5f5f5; margin:0; padding:20px;'>
                <div style='max-width:600px; margin:0 auto; background:#ffffff; border:1px solid #dddddd; border-radius:4px; padding:20px;'>
                    <h1 style='font-size:22px; margin-top:0;'>Kedves ". $guestName ."!</h1>
                    <p>Köszönjük, hogy a(z) <strong>{$hotel_name}</strong> szállodát választottad. Az alábbiakban összefoglaltuk a foglalásod adatait.</p>
                    <table style='width:100%; border-collapse:collapse; margin:15px 0;' role='presentation'>
                        <tr><th align='left'>Foglalási azonosító</th><td>#{$reservationId}</td></tr>
                        <tr><th align='left'>Szobatípus</th><td>". $room['room_type'] ."</td></tr>
                        <tr><th align='left'>Szobaszám</th><td>". $room['room_number'] ."</td></tr>
                        <tr><th align='left'>Érkezés dátuma</th><td>{$checkinDate}</td></tr>
                        <tr><th align='left'>Távozás dátuma</th><td>{$checkoutDate}</td></tr>
                        <tr><th align='left'>Éjszakák száma</th><td>{$nights_count}</td></tr>
                        <tr><th align='left'>Ár / éjszaka</th><td>". $price_per_night ." Ft</td></tr>
                        <tr><th align='left'>Végösszeg</th><td><strong>". $total_price  ." Ft</strong></td></tr>
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

            mail($guestEmail, $subject, $uzenet, $headers);
            header("Location: booking_success.php");
        }

            $reservationId = $conn->insert_id;

            $sql = "SELECT r.room_number, rt.name AS room_type, r.price_per_night FROM rooms r INNER JOIN room_types rt ON rt.id = r.room_type_id WHERE r.id = $roomId";

            $res = $conn->query($sql);
            $room = $res->fetch_assoc();

            $hotel_name    = "Stellar Hotel";
            $hotel_email   = "info@stellarhotel.hu";
            $hotel_address = "1234 Budapest, Valami utca 1.";
            $hotel_phone   = "+36 30 123 4567";
            $cancel_deadline_days = 3;
            $booking_link  = "foglalas.php?id=" . $reservationId;

            $checkin  = new DateTime($checkinDate);
            $checkout = new DateTime($checkoutDate);
            $nights_count = $checkout->days; 

            $price_per_night = $room['price_per_night'];
            $total_price = $nights_count * $price_per_night;

            $subject = "Foglalás visszaigazolása " . $hotel_name;

            $uzenet = '
            <html><head>
                <meta charset="UTF-8">
                <title>Foglalás visszaigazolása</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        margin: 0;
                        padding: 0;
                    }
                    .wrapper {
                        width: 100%;
                        padding: 20px 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        border-radius: 4px;
                        border: 1px solid #dddddd;
                        padding: 20px;
                    }
                    h1 {
                        font-size: 22px;
                        margin-top: 0;
                        color: #333333;
                    }
                    p {
                        font-size: 14px;
                        line-height: 1.6;
                        color: #555555;
                    }
                    .details-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 15px 0;
                    }
                    .details-table th,
                    .details-table td {
                        text-align: left;
                        padding: 8px;
                        font-size: 14px;
                    }
                    .details-table th {
                        background-color: #f0f0f0;
                    }
                    .details-table tr:nth-child(even) td {
                        background-color: #fafafa;
                    }
                    .footer {
                        font-size: 12px;
                        color: #999999;
                        margin-top: 20px;
                        text-align: center;
                    }
                    .btn {
                        display: inline-block;
                        margin-top: 15px;
                        padding: 10px 18px;
                        background-color: #007bff;
                        color: #ffffff !important;
                        text-decoration: none;
                        border-radius: 4px;
                        font-size: 14px;
                    }
                </style>
            </head><body>
            <div class="wrapper">
                <div class="container">
                    <h1>Kedves ' . $guestName . '!</h1>
                    <p>Köszönjük, hogy szállodánkat választottad. Az alábbiakban összefoglaltuk a foglalásod adatait.</p>
                    <table class="details-table" role="presentation">
                        <tr><th>Foglalási azonosító</th><td>' . $reservationId . '</td></tr>
                        <tr><th>Szobatípus</th><td>' . $room["room_type"] . '</td></tr>
                        <tr><th>Szobaszám</th><td>' . $room["room_number"] . '</td></tr>
                        <tr><th>Érkezés dátuma</th><td>' . $checkinDate . '</td></tr>
                        <tr><th>Távozás dátuma</th><td>' . $checkoutDate . '</td></tr>
                        <tr><th>Éjszakák száma</th><td>' . $nights_count . '</td></tr>
                        <tr><th>Ár / éjszaka</th><td>' . $price_per_night . ' Ft</td></tr>
                        <tr><th>Végösszeg</th><td><strong>' . $total_price . ' Ft</strong></td></tr>
                    </table>
                    <p>Szeretettel várunk:<br><strong>' . $hotel_name . '</strong><br>' . $hotel_address . '<br>Telefon: ' . $hotel_phone . '<br>E-mail: ' . $hotel_email . '</p>
                    <p style="text-align:center;"><a href="' . $booking_link . '" class="btn">Foglalás megtekintése</a></p>
                </div>
            </div>
            </body></html>';

            $headers  = "From: " . $hotel_name . " <" . $hotel_email . ">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";

            mail($guestEmail, $subject, $uzenet, $headers);
            header("Location: booking_success.php");
        } while (false);
    }

    $typeData = null;
    $rooms = [];

    if ($typeId > 0) {
        $sqlType    = "SELECT id, name, capacity, description FROM room_types WHERE id = $typeId";
        $typeResult = $conn->query($sqlType);

        if ($typeResult && $typeResult->num_rows > 0) {
            $typeData = $typeResult->fetch_assoc();
        } elseif ($typeResult && $typeResult->num_rows === 0) {
            Message("Nem található ilyen szobatípus.");
        } else {
            Message("Hiba történt a szobatípus lekérdezésekor: " . $conn->error);
        }

        if ($typeData) {
            $sqlRooms = "SELECT r.* FROM rooms r WHERE r.room_type_id = $typeId AND r.is_active = 1 AND r.price_per_night IS NOT NULL ORDER BY r.price_per_night ASC, r.room_number ASC";
            $roomsResult = $conn->query($sqlRooms);

            if ($roomsResult && $roomsResult->num_rows > 0) {
                while ($row = $roomsResult->fetch_assoc()) {
                    $rooms[] = $row;
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>
        <?php if ($typeData): ?>
            <?= $typeData['name'] ?> Foglalás
        <?php else: ?>
            Szobatípus részletek
        <?php endif; ?>
    </title>
    <meta name="description" content="Szobatípus részletek és foglalás vendégeknek">
    <meta name="keywords" content="Hotel, Szoba, Foglalás">
    <meta name="author" content="Csontos Kincső Anastázia">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../common/css/styles.css">
</head>
<body>
    <div class="page">
        <div class="wrapper">
            <a href="index.php">Vissza a szobatípusokhoz</a>
            <?php if ($typeData): ?>
                <h1><?= $typeData['name'] ?> (<?= $typeData['capacity'] ?> fő)</h1>
                <?php if (count($rooms) > 0): ?>
                    <h2>Elérhető szobák ebből a típusból</h2>
                    <table border="1" cellpadding="6">
                        <tr>
                            <th>Szobaszám</th>
                            <th>Emelet</th>
                            <th>Ár / éj</th>
                            <th>Foglalás</th>
                        </tr>
                        <?php foreach ($rooms as $r): ?>
                            <?php
                                $price = $r['price_per_night'];
                            ?>
                            <tr>
                                <td><?= $r['room_number'] ?></td>
                                <td><?= $r['floor'] ?></td>
                                <td><?= ($price !== '' ? $price . ' Ft/éjszaka' : 'Nincs megadva') ?></td>
                                <td>
                                    <form method="POST" style="margin:0;">
                                        <input type="hidden" name="room_id" value="<?= (int)$r['id'] ?>">
                                        <input type="text"  name="guest_name"   placeholder="Név" required>
                                        <input type="email" name="guest_email"  placeholder="Email" required>
                                        <input type="date"  name="checkin_date" required>
                                        <input type="date"  name="checkout_date" required>
                                        <input type="submit" name="book-room-btn" value="Foglalás">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>Jelenleg ebből a szobatípusból nincs elérhető szoba.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php include __DIR__ . "/../../common/templates/footer.php"; ?>
</body>
</html>
