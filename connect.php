<?php
define('DB_HOST', 'monctondbconnection.redirectme.net');
define('DB_USER', 'root');
define('DB_PASS', 'MonctonDB2024094');
define('DB_NAME', 'monctondb');
    
global $con;
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$con) {
    die('Could not connect: ' . mysqli_connect_error());
}
?>