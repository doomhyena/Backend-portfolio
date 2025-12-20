<?php
    require __DIR__ . "/../common/config.php";
    require __DIR__ . "/../common/functions.php";

    $discountPercent = 5;
    $discountMultiplier = (100 - $discountPercent) / 100.0;
    $typeId= $_GET['typeid'];
    $typeData = null;
    $rooms = [];

    if (isset($_POST['book-room-btn'])) {

        do {
            $roomId = $_POST['room_id'];
            $guestName = $_POST['guest_name'];
            $guestEmail = $_POST['guest_email'];
            $checkinDate = $_POST['checkin_date'];
            $checkoutDate = $_POST['checkout_date'];

            if ($typeId <= 0) {
                Message("Nem megfelelő szobatípus azonosító.");
                break;
            }

            if ($roomId <= 0) {
                Message("Válassz szobát a foglaláshoz.");
                break;
            }

            if ($guestName === '') {
                Message("A név megadása kötelező.");
                break;
            }

            if ($guestEmail === '') {
                Message("Adj meg egy érvényes email címet.");
                break;
            }

            if ($checkinDate === '' || $checkoutDate === '') {
                Message("Add meg az érkezés és távozás dátumát.");
                break;
            }

            if ($checkinDate >= $checkoutDate) {
                Message("A távozás dátumának az érkezés után kell lennie.");
                break;
            }

            $sqlCheck = "SELECT id FROM reservations WHERE room_id = $roomId AND NOT (checkout_date <= '$checkinDate' OR checkin_date >= '$checkoutDate') LIMIT 1";
            $resCheck = $conn->query($sqlCheck);

            if ($resCheck && $resCheck->num_rows > 0) {
                Message("Erre az időszakra már van foglalás erre a szobára, kérjük válassz másik dátumot.");
                break;
            }

            $sqlInsert = "INSERT INTO reservations (room_id, guest_name, guest_email, checkin_date, checkout_date, created_at, status) VALUES ($roomId, '$guestName', '$guestEmail', '$checkinDate', '$checkoutDate', NOW(), 'lefoglalva')";

            if ($conn->query($sqlInsert)) {
                Message("Foglalásod rögzítettük kedvezményes áron! Hamarosan felvesszük veled a kapcsolatot.");
            } else {
                Message("Hiba történt a foglalás mentésekor: " . $conn->error);
            }

        } while (false);
    }

    if ($typeId <= 0) {
        Message("Nem megfelelő szobatípus azonosító.");
    } else {
        $sqlType = "SELECT id, name, capacity, description FROM room_types WHERE id = $typeId";
        $typeResult = $conn->query($sqlType);

        if ($typeResult && $typeResult->num_rows > 0) {
            $typeData = $typeResult->fetch_assoc();
        } else {
            Message("Nem található ilyen szobatípus.");
        }

        if ($typeData) {
            $sqlRooms = "SELECT r.* FROM rooms r WHERE r.room_type_id = $typeId AND r.is_active = 1 AND r.price_per_night IS NOT NULL ORDER BY r.price_per_night ASC, r.room_number ASC";
            $roomsResult = $conn->query($sqlRooms);

            if ($roomsResult && $roomsResult->num_rows > 0) {
                while ($row = $roomsResult->fetch_assoc()) {
                    $rooms[] = $row;
                }
            } else {
                Message("Jelenleg ebből a szobatípusból nincs elérhető szoba.");
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Külső foglaló oldal – Szobák</title>
    <meta name="description" content="Külső partner oldal – szobafoglalás kedvezményes áron">
    <meta name="keywords" content="Hotel, Foglalás, Kedvezményes, Külső oldal">
    <meta name='author' content='Csontos Kincső Anastázia'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="stylesheet" href="../common/css/styles.css">
    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="/Hotel/common/js/script.js"></script>
</head>
<body>
    <div class="page">
        <div class="wrapper">
            <h1>Kedvezményes szobafoglalás – Partner oldal</h1>
            <p>Az itt foglalható szobák <strong><?= $discountPercent ?>%</strong>-kal olcsóbbak, mint a hotel saját oldalán.</p>
            <small>Válassz egy szobatípust, aztán mehet a foglalás. ✨</small>
            <?php if ($tipusok && $tipusok->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Szobatípus</th>
                        <th>Kapacitás</th>
                        <th>Hotel ára (legalább)</th>
                        <th>Kedvezményes ár (legalább)</th>
                        <th>Művelet</th>
                    </tr>

                    <?php while ($t = $tipusok->fetch_assoc()): ?>
                        <?php
                        $origPrice = $t['min_price'];
                        $discPrice = $origPrice * $discountMultiplier;
                        ?>
                        <tr>
                            <td><strong><?= $t['name'] ?></strong></td>
                            <td><?= $t['capacity'] ?> fő</td>
                            <td><?= $origPrice . " Ft/éjszaka" ?></td>
                            <td><strong><?= (int)$discPrice . " Ft/éjszaka" ?></strong></td>
                            <td>
                                <a class="btn" href="external_room_type.php?typeid=<?= (int)$t['id'] ?>">
                                    Részletek & foglalás →
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <div class="wrapper">
                    <p>Jelenleg nincs elérhető szobatípus.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php include __DIR__ . "/../../common/templates/footer.php"; ?>
</body>
</html>
