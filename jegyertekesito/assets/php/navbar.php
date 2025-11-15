<?php
    
    require "config.php";
    if (isset($_COOKIE['id'])) {
        $sql = "SELECT * FROM felhasznalok WHERE id = " . intval($_COOKIE['id']);
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
    } else {
        $user = ['id' => 0];
    }
    echo '
        <nav>
            <a href="index.php">Főoldal</a>
            ';
            if (isset($_COOKIE['id'])) {
                echo '
                <a href="felhasznalo.php?userid=' . $user['id'] . '">Profilom</a>
                <a href="rendezvény_letrehozasa.php">Rendezvény létrehozása</a>
                <a href="kosar.php">Kosár</a>
                ';
            
            }
            '
        ';
        if (isset($_COOKIE['id'])) {
            echo '<a href="kijelentkezes.php">Kijelentkezés</a>';
        } else {
            echo '<a href="reglog.php">Regisztráció / Bejelentkezés</a>';
        }
        echo '
        </nav>
    ';