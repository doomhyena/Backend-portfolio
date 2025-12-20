<?php
    require __DIR__ . "/../../common/config.php";
    require __DIR__ . "/../../common/functions.php";

    $floor = '';
    $roomIndex = '';
    $roomTypeId = '';
    $priceInput = '';
    $isActive = 1;
    $roomTypes = [];

    $sqlTypes = "SELECT id, name, capacity FROM room_types ORDER BY name";
    $typesResult = $conn->query($sqlTypes);

    if ($typesResult && $typesResult->num_rows > 0) {
        while ($row = $typesResult->fetch_assoc()) {
            $roomTypes[] = $row;
        }
    }

    if (isset($_POST['save-room-btn'])) {

        do {
            $floor = $_POST['floor'];
            $roomIndex = $_POST['room_index'];
            $roomTypeId = $_POST['room_type_id'];
            $priceInput = $_POST['price_per_night'];
            $isActive = isset($_POST['is_active']) ? 1 : 0;

            if ($floor < 1 || $floor > 99) {
                Message("Az emeletnek 1 és 99 között kell lennie.");
            }

            if ($roomIndex < 1 || $roomIndex > 99) {
                Message("A szobaszám (emeleten belüli index) 1 és 99 között legyen.");
            }

            if ($roomTypeId <= 0) {
                Message("Válassz szobatípust.");
            }

            if ($priceInput < 0) {
                Message("Az ár nem lehet negatív.");
            }

            $sqlInsert = "INSERT INTO rooms (floor, room_index, room_type_id, price_per_night, is_active) VALUES ($floor, $roomIndex, $roomTypeId, $priceInput, $isActive)";

            if ($conn->query($sqlInsert)) {
                $newRoomId = $conn->insert_id;
                header("Location: room.php?roomid=" . $newRoomId);
            } else {
                Message("Adatbázis hiba: " . $conn->error);
            }

        } while (false);
    }
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <title>Admin új szoba létrehozása</title>
    <meta charset='UTF-8'>
    <meta name='description' content='Adminisztrációs felület új szoba hozzáadásához'>
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
                <a href="index.php">Vissza a főoldalra</a>
            </nav>
            <h1>Új szoba létrehozása</h1>

            <?php if (count($roomTypes) === 0): ?>
                <p style="color: darkred; font-weight: bold;">
                    Nincs még egyetlen szobatípus sem a <code>room_types</code> táblában.<br>
                    Először hozz létre legalább egy típust, különben nem tudsz szobát felvenni.
                </p>
            <?php endif; ?>

            <form method="POST" class="form-card">
                <div>
                    <label>Emelet (1–99):</label>
                    <input type="number" name="floor" min="1" max="99" value="<?= $floor ?>" required>
                </div>
                <div>
                    <label>Szoba index (emeleten belül, 1–99):</label>
                    <input type="number" name="room_index" min="1" max="99" value="<?= $roomIndex ?>" required>
                </div>
                <div>
                    <label>Szobatípus:</label>
                    <select name="room_type_id" required>
                        <option value="">-- Válassz típust --</option>
                        <?php foreach ($roomTypes as $type): ?>
                            <option value="<?= $type['id'] ?>" <?= ($roomTypeId === $type['id']) ? 'selected' : '' ?>>
                                <?= $type['name'] . " (" . $type['capacity'] . " fő)" ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Ár / éj (Ft):</label>
                    <input type="text" name="price_per_night" value="<?= $priceInput ?>" placeholder="pl. 15000 vagy 15000,50" required>
                </div>
                <div>
                    <label>Aktív szoba</label>
                    <input type="checkbox" name="is_active" <?= ($isActive ? 'checked' : '') ?>>
                </div>
                <br>
                <input type="submit" name="save-room-btn" value="Szoba létrehozása">
            </form>
            <?php include __DIR__ . "/../../common/templates/footer.php"; ?>
        </div>
    </div>
    <?php include __DIR__ . "/../../common/templates/footer.php"; ?>
</body>
</html>
