<?php 

    require "assets/php/config.php";

    if(isset($_POST['reg-btn'])){
        
        $lekerdezes = "SELECT * FROM felhasznalok WHERE username='$_POST[username]'";
        $talalt_felhasznalo = $conn->query($lekerdezes);
        
        if(mysqli_num_rows($talalt_felhasznalo) == 0){
            
            if($_POST['pass1'] == $_POST['pass2']){
                
                $titkositott_jelszo = password_hash($_POST['pass1'], PASSWORD_DEFAULT);
                
                $conn->query("INSERT INTO felhasznalok VALUES(id, '$_POST[username]', '$_POST[email]', '$titkositott_jelszo')");
                
                echo "<script>alert('Sikeres regisztráció!')</script>";
                
            } else {
                echo "<script>alert('A két jelszó nem egyezik!')</script>";
            }
            
        } else {
            echo "<script>alert('A felhasználónév már foglalat!')</script>";
        }
        
    }
    
    if(isset($_POST['login-btn'])){
        
        $lekerdezes = "SELECT * FROM felhasznalok WHERE username='$_POST[username]'";
        $talalt_felhasznalo = $conn->query($lekerdezes);
        
        if(mysqli_num_rows($talalt_felhasznalo) == 1){
            
            $felhasznalo = $talalt_felhasznalo->fetch_assoc();
            
            if(password_verify($_POST['password'], $felhasznalo['jelszo'])){
                
                setcookie("id", $felhasznalo['id'], time() + 3600, "/");
                
                header("Location: index.php");
                
            } else {
                echo "<script>alert('Hibás jelszó!')</script>";
            }
            
        } else {
            echo "<script>alert('Nincs ilyen felhasználó!')</script>";
        }
        
    }

?>
<!DOCTYPE html>
<html lang='hu'>
   <head>
       <title>Bejelentkezés/Regisztráció</title>
       <meta charset='UTF-8'>
       <meta name='description' content='Koncertjegy értékesítő oldal'>
       <meta name='keywords' content='Koncert, Jegy, Értékesítés, Rendezvény, Zene'>
       <meta name='author' content='Csontos Kincső'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='assets/css/styles.css?v=<?=time()?>'>
   </head>
   <body>
    <?= include 'assets/php/navbar.php'; ?>
        <form id="login" method="post">
            <label class="form-header">Bejelentkezés</label>
            <input type="text" name="username" placeholder="Felhasználónév">
            <input type="password" name="password" placeholder="Jelszó">
            <input type="submit" name="login-btn" value="Bejelentkezés">
            <label class="form-link">Még nincs fiókod? <a href="#" onclick="showForm('reg')">Regisztrálj!</a></label>
        </form>
            
        <form id="reg" style="display: none;" method="post">
            <label class="form-header">Regisztráció</label>
            <input type="text" name="username" placeholder="Felhasználónév">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="pass1" placeholder="Jelszó">
            <input type="password" name="pass2" placeholder="Jelszó újra">
            <input type="submit" name="reg-btn" value="Regisztrálok!">
            <label class="form-link">Már van fiókod? <a href="#" onclick="showForm('login')">Lépj be!</a></label>
        </form>
        <script src="assets/js/script.js"></script>
    <?= include 'assets/php/footer.php'; ?>
    </body>
</html>