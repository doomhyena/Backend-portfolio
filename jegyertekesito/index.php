<!DOCTYPE html>
<html lang='hu'>
   <head>
       <title>Főoldal</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Koncertjegy értékesítő oldal'>
       <meta name='keywords' content='Koncert, Jegy, Értékesítés, Rendezvény, Zene'>
       <meta name='author' content='Csontos Kincső'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       <link rel='stylesheet' href='assets/css/styles.css?v=<?=time()?>'>
   </head>
   <body>
    <?php include 'assets/php/navbar.php'; ?>
        <?php

        require "assets/php/config.php";

        $sql = "SELECT * FROM rendezvenyek ORDER BY datum ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $imagePath = 'uploads/' . htmlspecialchars($row['kep']);
                if (file_exists($imagePath) && !empty($row['kep'])) {
                    echo "<div class='rendezveny'>";
                    echo "<img src='" . $imagePath . "' alt='" . htmlspecialchars($row['nev']) . "'>";
                } else {
                    echo "<div class='rendezveny'>";
                    echo "<img src='assets/img/no-image.jpg' alt='Nincs kép'>";
                }
                echo "<h2>" . htmlspecialchars($row['nev']) . "</h2>";
                echo "<p>" . htmlspecialchars($row['datum']) . "</p>";
                echo "<a href='rendezveny.php?id=" . urlencode($row['id']) . "'>További részletek</a>";
                echo "</div>";
            }
        } else {
            echo "Nincs rendezvény.";
        }
    ?>
    <?php include 'assets/php/footer.php'; ?>
   </body>
</html>