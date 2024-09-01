<?php
// Database connection details
$servername = "localhost:8889";
$username = "root";
$password = "root";  // Update if necessary
$dbname = "numbersdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$result = $conn->query("SELECT number FROM number");

if ($result->num_rows > 0) {
    // Prepare data for output
    $numbers = [];
    while ($row = $result->fetch_assoc()) {
        // Remove all spaces from the number
        $cleanNumber = preg_replace('/\s+/', '', $row['number']);
        $numbers[] = $cleanNumber;
    }
    
    // Combine all numbers into a single line separated by commas
    $numbersLine = implode(',', $numbers);

    // Output headers to prompt download
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="phone_numbers.txt"');

    // Output the combined numbers
    echo $numbersLine;
} else {
    echo "No records found";
}

// Close the database connection
$conn->close();
?>