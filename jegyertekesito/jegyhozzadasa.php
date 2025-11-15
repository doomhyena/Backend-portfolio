<?php

    require "assets/php/config.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
    }

    if (!isset($_GET['event_id'])) {
        die("Hiányzó rendezvény azonosító.");
    }

    $event_id = intval($_GET['event_id']);

    if (isset($_POST['jegybtn'])) {
        $nev = trim($_POST['nev']);
        $darabszam = intval($_POST['darabszam']);
        $rendezveny_id = intval($_POST['rendezveny_id']);

        if ($nev !== '' && $darabszam > 0) {
            $sql = $conn->query("INSERT INTO jegyek VALUES(id, '$nev', $darabszam, $rendezveny_id)");
            header("Location: jegyhozzadasa.php?event_id=".$event_id);
        }
    }

    $sql = $conn->query("SELECT id, nev, darabszam FROM jegyek WHERE rendezveny_id = $event_id");

    $jegyek = [];
    while ($row = $sql->fetch_assoc()) {
        $jegyek[] = $row;
    }

?>
<!DOCTYPE html>
<html lang='hu'>
   <head>
       <title>Jegyhozzáadás</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Koncertjegy értékesítő oldal'>
       <meta name='keywords' content='Koncert, Jegy, Értékesítés, Rendezvény, Zene'>
       <meta name='author' content='Csontos Kincső'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='assets/css/styles.css?v=<?=time()?>'>
   </head>
   <body>
    <?php include 'assets/php/navbar.php'; ?>
        <form method="post" class="jegy-form">
            <input type="text" name="nev" placeholder="Jegy neve" required>
            <input type="number" name="darabszam" placeholder="Darabszám" required>
            <input type="hidden" name="rendezveny_id" value="<?php echo htmlspecialchars($event_id); ?>">
            <button type="submit" name="jegybtn">Jegy hozzáadása</button>
        </form>
    <?php include 'assets/php/footer.php'; ?>
</body>
</html>