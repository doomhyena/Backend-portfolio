<?php

    require "assets/php/config.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
    }
    
    if (isset($_POST['rendezveny-letrehozasa'])) {
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];
        $event_description = $_POST['event_description'];
        $event_image = $_FILES['event_image']['name'];
        $szervezo_id = $_COOKIE['id'];

        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        move_uploaded_file($_FILES['event_image']['tmp_name'], $upload_dir . $event_image);

        $sql = $conn->query("INSERT INTO rendezvenyek (nev, kep, datum, leiras, szervezo_id) VALUES('$event_name', '$event_image', '$event_date', '$event_description', '$szervezo_id')");
        echo "<script>alert('Rendezvény sikeresen létrehozva!')</script>";

    }

?>
<!DOCTYPE html>
<html lang='hu'>
   <head>
       <title>Rendezvény hozzáadása</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Koncertjegy értékesítő oldal'>
       <meta name='keywords' content='Koncert, Jegy, Értékesítés, Rendezvény, Zene'>
       <meta name='author' content='Csontos Kincső'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='assets/css/styles.css?v=<?=time()?>'>
   </head>
   <body>
    <?php include 'assets/php/navbar.php'; ?>
        <h1>Rendezvény létrehozása</h1>
        <form method="post" enctype="multipart/form-data">
            <label>Rendezvény neve:</label>
            <input type="text" id="event_name" name="event_name" required>
            <label>Dátum:</label>
            <input type="datetime-local" id="event_date" name="event_date" required>
            <label>Leírás:</label>
            <textarea id="event_description" name="event_description" required></textarea>
            <label>Kép feltöltése:</label>
            <input type="file" id="event_image" name="event_image" accept="image/*" required>
            <input type="submit" name="rendezveny-letrehozasa" value="Rendezvény létrehozása">
        </form>
    <?php include 'assets/php/footer.php'; ?>
    </body>
</html>