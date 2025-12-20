<?php
    require __DIR__ . "/../../common/config.php";
    require __DIR__ . "/../../common/functions.php";

    $roomNumber = null;
    $sor = null;
    $roomId = null;

    $hasRoomId = $_GET['roomid'];
    if ($hasRoomId <= 0) {
        Message("Nincs megadva / érvénytelen szoba azonosító.");
    }

    if ($hasRoomId) {
        $roomId = $_GET['roomid'];

        if ($roomId > 0) {
            $sql = "SELECT r.*, rt.name AS room_type, rt.capacity AS room_capacity, rt.description AS type_description FROM rooms r LEFT JOIN room_types rt ON r.room_type_id = rt.id WHERE r.id = $roomId";
            $szobaszam = $conn->query($sql);

            if ($szobaszam && $szobaszam->num_rows > 0) {
                $sor = $szobaszam->fetch_assoc();
                $roomNumber = $sor['room_number'] ?? null;
            } elseif ($szobaszam && $szobaszam->num_rows === 0) {
                Message("Nem található szoba ezzel az azonosítóval.");
            } else {
                Message("Hiba történt a szoba adatainak lekérdezésekor: " . $conn->error);
            }
        } else {
            Message("Érvénytelen szoba azonosító.");
        }
    } else {
        Message("Nincs megadva szoba azonosító.");
    }

    if (isset($_POST['save-btn'])) {

        do {
            if (!$sor || !$roomId || $roomId <= 0) {
                Message("Érvénytelen szoba azonosító mentésnél.");
                break;
            }

            $roomPrice = null;

            if ($roomPriceRaw !== '') {
                $roomPrice = $roomPriceRaw;
                if ($roomPrice < 0) {
                    Message("Az ár nem lehet negatív.");
                    break;
                }
            }

            $isActive = $_POST['is-active'] ? 1 : 0;
            $roomTypeName = $_POST['room-type'];
            $typeDesc = $_POST['type_description'];
            $roomCapacityRaw = $_POST['room-capacity'];
            $roomCapacity = $roomCapacityRaw;

            if ($roomPrice !== null) {
                $sqlUpdateRoom = "UPDATE rooms SET price_per_night = $roomPrice, is_active = $isActive WHERE id = $roomId";
            } else {
                $sqlUpdateRoom = "UPDATE rooms SET is_active = $isActive WHERE id = $roomId";
            }

            $conn->query($sqlUpdateRoom);
            Message("Szoba adatai sikeresen frissítve.");

            if ($sor['room_type_id']) {
                $roomTypeId = $sor['room_type_id'];
                $sqlUpdateType = "UPDATE room_types SET name = '$roomTypeName',capacity = $roomCapacity,description = '$typeDesc' WHERE id = $roomTypeId";
                $conn->query($sqlUpdateType);
            }

            $sqlReload = "SELECT r.*, rt.name AS room_type, rt.capacity AS room_capacity, rt.description AS type_description FROM rooms r LEFT JOIN room_types rt ON r.room_type_id = rt.id WHERE r.id = $roomId";
            $szoba = $conn->query($sqlReload);
            $sor = $szoba->fetch_assoc();
        } while (false);
    }
?>
<!DOCTYPE html>
<html lang='hu'>
<head>
    <meta charset='UTF-8'>
    <title><?= $roomNumber . " szoba" ?></title>
    <meta name='description' content='Szoba részletek és szerkesztése'>
    <meta name='keywords' content='Kezelő, Adminisztráció, Szobák, Foglalások'>
    <meta name='author' content='Csontos Kincső Anastázia'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="stylesheet" href="../../common/css/styles.css">
    <script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>
    <div class="page">
        <div class="wrapper">
            <nav class="nav">
                <a href='index.php'>Vissza a főoldalra</a>
            </nav>
            <?php if (isset($_POST['edit-btn']) && $sor): ?>
                <form method="POST">
                    <input type="text" name="room-price" value="<?= $sor['price_per_night'] ?>" placeholder="Szoba ára">
                    <label>
                        <input type="checkbox" name="is-active" <?= ($sor['is_active'] == 1 ? 'checked' : '') ?>>
                        Aktív
                    </label>
                    <br>
                    <input type="text" name="room-type" value="<?= $sor['room_type'] ?>" placeholder="Szobatípus">
                    <input type="text" name="type_description" value="<?= $sor['type_description'] ?>" placeholder="Szoba leírása">
                    <input type="text" name="room-capacity" value="<?= $sor['room_capacity'] ?>" placeholder="Szoba kapacitása">
                    <input type="submit" name="save-btn" value="Mentés">
                </form>
            <?php endif; ?>

            <?php
                if ($sor) {

                    $price = $sor['price_per_night'];
                    $is_active_text = ($sor['is_active'] == 1 ? 'Aktív' : 'Inaktív');

                    echo "<h1>Szoba részletek</h1>";
                    echo "<table border='1' cellpadding='6'>";

                    $fields = [
                        'ID' => $sor['id'],
                        'Szobaszám' => $sor['room_number'],
                        'Emelet' => $sor['floor'],
                        'Szobatípus' => $sor['room_type'],
                        'Típus kapacitás' => $sor['room_capacity'],
                        'Típus leírás' => $sor['type_description'],
                        'Ár / éj' => ($price !== '' ? $price . ' Ft/éjszaka' : 'Nincs megadva'),
                        'Állapot' => $is_active_text,
                        'Létrehozva' => $sor['created_at'],
                    ];

                    foreach ($fields as $label => $value) {
                        echo "<tr><th style='text-align:left;'>" . $label . "</th><td>" . $value . "</td></tr>";
                    }

                    echo "</table>";

                } else {

                    echo "<p>Nem sikerült szoba adatokat megjeleníteni.</p>";
                }
            ?>
            <br>
            <form method="POST">
                <input type="submit" name="edit-btn" value="Szerkesztés">
            </form>
        </div>
    </div>
    <?php include __DIR__ . "/../../common/templates/footer.php"; ?>
</body>
</html>
