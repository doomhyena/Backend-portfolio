<?php

    $conn = new mysqli("localhost", "root", "", "portfolio_webshop");
    
    if($conn->connect_error){
       die("Connection failed! ".$conn->connect_error);
    }

?>