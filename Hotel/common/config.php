<?php

    $conn = new mysqli("localhost", "root", "", "portfolio_hotel");
    
    if($conn->connect_error){
       die("Connection failed! ".$conn->connect_error);
    }

?>