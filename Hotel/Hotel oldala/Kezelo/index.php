<?php
    require __DIR__ . "/../../common/config.php";
    require __DIR__ . "/../../common/functions.php";
    
    if(isset($_POST['new-room-btn'])) {
        header("Location: new_room.php");
    }

    if(isset($_POST['del-room-btn'])) {
        $roomIdToDelete = $_POST['roomid'];
        $sqlDelete = "DELETE FROM rooms WHERE id = $roomIdToDelete";
        $conn->query($sqlDelete);
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang='hu'>
   <head>
       <title>Admin Főoldal</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Adminisztrációs felület főoldala'>
       <meta name='keywords' content='Kezelő, Adminisztráció, Szobák, Foglalások'>
       <meta name='author' content='Csontos Kincső Anastázia'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       <link rel="stylesheet" href="../../common/css/styles.css">
       <script src="https://code.jquery.com/jquery-latest.js"></script>
       <script src="/Hotel/common/js/script.js"></script>
   </head>
   <body>
       <div class="page">
           <div class="wrapper">
               <nav class="nav">
                   <a href="reservations.php">Foglalások kezelése</a>
               </nav>
                <form method="POST">
                    <label>Új szoba hozzáadása</label>
                    <input type="submit" name="new-room-btn" value="Hozzáadás">
                </form>
                <input type="text" class="search-box" id="search-box" placeholder="Szoba keresése...">
                <div id="rooms"></div>
           </div>
       </div>
       <?php include __DIR__ . "/../../common/templates/footer.php"; ?>
   </body>
</html>