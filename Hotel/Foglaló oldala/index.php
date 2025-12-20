<?php
    require __DIR__ . "/../common/config.php";
    require __DIR__ . "/../common/functions.php";

    $discountPercent = 5;
    $discountMultiplier = (100 - $discountPercent) / 100; 

    $sql = "SELECT rt.id, rt.name, rt.capacity, MIN(r.price_per_night) AS min_price FROM room_types rt INNER JOIN rooms r ON r.room_type_id = rt.id WHERE r.is_active = 1 AND r.price_per_night IS NOT NULL GROUP BY  rt.id, rt.name, rt.capacity  HAVING  MIN(r.price_per_night) IS NOT NULL  ORDER BY  rt.capacity, rt.name";

    $tipusok = $conn->query($sql);

    if (!$tipusok) {
        Message("Hiba történt a szobatípusok lekérése közben. Próbáld meg később újra!");
    } elseif ($tipusok->num_rows === 0) {
        Message("Jelenleg nincs elérhető szobatípus.");
    }

    if (!isset($_GET['typeid'])) {
        Message("Hiányzik a szobatípus azonosító.");
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
            <?php if ($tipusok && $tipusok->num_rows > 0): ?>
                <table border="1" cellpadding="6">
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
                            <td><?= $t['name'] ?></td>
                            <td><?= $t['capacity'] ?> fő</td>
                            <td><?= $origPrice . " Ft/éjszaka" ?></td>
                            <td><strong><?= $discPrice . " Ft/éjszaka" ?></strong></td>
                            <td>
                                <a href="external_room_type.php?typeid=<?= $t['id'] ?>">
                                    Részletek & foglalás kedvezményesen
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Jelenleg nincs elérhető szobatípus.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include __DIR__ . "/../common/templates/footer.php"; ?>
</body>
</html>