<?php
    require "config.php";

    if (!isset($_COOKIE['id'])) {
        header("Location: reglog.php");
        exit;
    }

    if (isset($_POST['ticket_name'])) {
        $user_id = intval($_COOKIE['id']);
        $rendezveny_id = intval($_POST['rendezveny_id'] ?? 0);
        $ticket_name = trim($_POST['ticket_name'] ?? '');
        $ticket_count = intval($_POST['ticket_count'] ?? 0);

        $check_sql = "SELECT * FROM rendezvenyek WHERE id = $rendezveny_id AND szervezo_id = $user_id";
        $check_result = $conn->query($check_sql);
        if ($check_result && $check_result->num_rows > 0 && $ticket_name !== '' && $ticket_count > 0) {
            $stmt = $conn->prepare("INSERT INTO jegyek (nev, darabszam, rendezveny_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sii", $ticket_name, $ticket_count, $rendezveny_id);
            $stmt->execute();
            $stmt->close();
            header("Location: ../../felhasznalo.php");
            exit;
        } else {
            $error = "Hibás adatok vagy nincs jogosultságod ehhez a rendezvényhez.";
        }
    }
?>
<!DOCTYPE html>
<html lang='hu'>
   <head>
       <title>Jegy hozzáadása</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Koncertjegy értékesítő oldal'>
       <meta name='keywords' content='Koncert, Jegy, Értékesítés, Rendezvény, Zene'>
       <meta name='author' content='Csontos Kincső'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       <link rel='stylesheet' href='../assets/css/styles.css'>
   </head>
   <body>
    <?php include '../assets/php/navbar.php'; ?>
    <div class="add-ticket-container">
        <h1>Jegy hozzáadása</h1>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="rendezveny_id" value="<?= htmlspecialchars($_POST['rendezveny_id'] ?? $_GET['rendezveny_id'] ?? '') ?>">
            <label>Jegy neve:</label>
            <input type="text" name="ticket_name" required>
            <label>Darabszám:</label>
            <input type="number" name="ticket_count" min="1" required>
            <button type="submit">Hozzáadás</button>
        </form>
    </div>
    <?php include '../assets/php/footer.php'; ?>
   </body>
</html>