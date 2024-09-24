<?php
$host = 'localhost';
$database = 'blog';
$username = 'leen'; 
$password = 'leen1998'; 

$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>