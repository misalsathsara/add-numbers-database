<?php
// Database connection details
$servername = "localhost:8889";
$username = "root";
$password = "root";
$dbname = "numbersdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];
$number = $_POST['number'];

// Validate input
if (preg_match('/^\+?[0-9\s-]+$/', $number)) {
    // Prepare and bind
    $stmt = $conn->prepare("UPDATE number SET number = ? WHERE id = ?");
    $stmt->bind_param("si", $number, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid phone number format";
}

$conn->close();
?>