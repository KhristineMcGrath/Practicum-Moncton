<?php
define('DB_HOST', 'monctondbconnection.redirectme.net'); //or localhost if working  on mysql
define('DB_USER', 'root'); 
define('DB_PASS', 'MonctonDB2024094'); //or blank if working locally on mysql
define('DB_NAME', 'monctondb');
    
global $con;
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$con) {
    die('Could not connect: ' . mysqli_connect_error());
}
?>  