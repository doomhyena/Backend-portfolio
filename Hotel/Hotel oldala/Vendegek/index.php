<?php
    require __DIR__ . "/../../common/config.php";

    $sql = "SELECT rt.id, rt.name, rt.capacity, MIN(r.price_per_night) AS min_price FROM room_types rt INNER JOIN rooms r ON r.room_type_id = rt.id WHERE r.is_active = 1     AND r.price_per_night IS NOT NULL GROUP BY rt.id, rt.name, rt.capacity HAVING MIN(r.price_per_night) IS NOT NULL ORDER BY rt.capacity, rt.name";
    $tipusok = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Vendég oldal</title>
    <meta name="description" content="Szobatípusok és árak vendégeknek">
    <meta name="keywords" content="Hotel, Szobák, Foglalás">
    <meta name="author" content="Csontos Kincső Anastázia">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../common/css/styles.css">
</head>
<body>
    <div class="page">
        <div class="wrapper">
        <h1>Szobáink</h1>
            <p>Válassz egy szobatípust, nézd meg a részleteket és foglalj!</p>
            <?php if ($tipusok && $tipusok->num_rows > 0): ?>
                <table border="1" cellpadding="6">
                    <tr>
                        <th>Szobatípus</th>
                        <th>Kapacitás</th>
                        <th>Legolcsóbb ár</th>
                        <th>Műveletek</th>
                    </tr>
                    <?php while ($t = $tipusok->fetch_assoc()): ?>
                        <?php
                            $price_display = '';
                            if ($t['min_price'] !== null) {
                                $price = $t['min_price'];
                            }
                        ?>
                        <tr>
                            <td><?= $t['name'] ?></td>
                            <td><?= $t['capacity'] ?> fő</td>
                            <td><?= ($price . " Ft/éjszaka") ?></td>
                            <td>
                                <a href="room_type_guest.php?typeid=<?= $t['id'] ?>">
                                    Részletek / foglalás
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Jelenleg nincs elérhető szobatípus.</p>
            <?php endif; ?>
            <?php include __DIR__ . "/../../common/templates/footer.php"; ?>
        </div>
    </div>
    <?php include __DIR__ . "/../../common/templates/footer.php"; ?>
</body>
</html>
