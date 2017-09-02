<?php

// Credentials
    $mysql_host = 'localhost';
    $mysql_username = 'id2494217_androidapp';
    $mysql_password = 'androidapp';

// Database to use
    $mysql_db = 'id2494217_app';

// Establish connection
    $conn = @mysqli_connect($mysql_host,$mysql_username,$mysql_password,$mysql_db);

// Check connection
    if(mysqli_connect_errno())
    {
        echo 'Failed to connect to MySQL. Error: '.mysqli_connect_errno();
    }
    
?>