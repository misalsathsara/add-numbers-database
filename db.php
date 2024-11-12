<?php
$servername = "localhost:3308"; // or your server details
$username = "root";
$password = "root";
$dbname = "numbersdb"; // or your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>