<?php
    $con = new mysqli("localhost", "root", "", "paystack");
    if(!$con){
        echo "Not connected to database".mysqli_error($con);
    }
?>