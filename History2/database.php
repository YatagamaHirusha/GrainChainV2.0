<?php
$host = 'localhost';
$user = 'root';
$pass = 'sqlroot'; // update if your MySQL has a password
$db = 'grainchainV2';

$con = new mysqli($host, $user, $pass, $db);
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
