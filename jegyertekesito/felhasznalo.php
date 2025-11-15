<?php

    require "assets/php/config.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }
    
    $user_id = intval($_COOKIE['id']);
    $sql = "SELECT * FROM felhasznalok WHERE id = $user_id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang='hu'>
   <head>
       <title>Profilom</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Koncertjegy értékesítő oldal'>
       <meta name='keywords' content='Koncert, Jegy, Értékesítés, Rendezvény, Zene'>
       <meta name='author' content='Csontos Kincső'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='assets/css/styles.css?v=<?=time()?>'>
   </head>
   <body>
    <?php include 'assets/php/navbar.php'; ?>
        <h1>Üdvözöljük, <?= htmlspecialchars($user['username']) ?>!</h1>
        <?php
        $events_sql = "SELECT * FROM rendezvenyek WHERE szervezo_id = $user_id";
        $events_result = $conn->query($events_sql);

        if ($events_result && $events_result->num_rows > 0) {
            echo "<h2>Az Ön rendezvényei:</h2><ul>";
            while ($event = $events_result->fetch_assoc()) {
                echo "<li>";
                echo "<strong>" . htmlspecialchars($event['nev']) . "</strong> ";
                echo "<a href='jegyhozzadasa.php?event_id=" . $event['id'] . "'>Jegyek kezelése</a> ";
                echo "<a href='rendezveny.php?id=" . $event['id'] . "'>Megtekintés</a>";

                $tickets_sql = "SELECT * FROM jegyek WHERE rendezveny_id = " . $event['id'];
                $tickets_result = $conn->query($tickets_sql);

                if ($tickets_result && $tickets_result->num_rows > 0) {
                    echo "<ul>";
                    while ($ticket = $tickets_result->fetch_assoc()) {
                        echo "<li>";
                        echo htmlspecialchars($ticket['nev']) . " - ";
                        if ($ticket['darabszam'] > 0) {
                            echo $ticket['darabszam'] . " db elérhető";
                        } else {
                            echo "<span style='color:red;'>Elfogyott</span>";
                        }
                        echo "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>Nincs jegy ehhez a rendezvényhez.</p>";
                }

                echo "<form method='post' action='assets/php/add_ticket.php'>
                        <input type='hidden' name='rendezveny_id' value='" . $event['id'] . "'>
                        <input type='text' name='ticket_name' placeholder='Jegy neve' required>
                        <input type='number' name='ticket_count' min='1' placeholder='Darabszám' required>
                        <button type='submit'>Jegy hozzáadása</button>
                      </form>";

                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Ön még nem hozott létre rendezvényt.</p>";
        }
        ?>
    <?php include 'assets/php/footer.php'; ?>
    </body>
</html>