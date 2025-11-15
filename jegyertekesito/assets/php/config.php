<?php

    $conn = new mysqli("localhost", "root", "", "portfolio_jegy");

    if($conn->connect_error){
        die("Sikertelen kapcsolódás!".$conn->connect_error);
    }

?>