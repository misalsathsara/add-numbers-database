<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Database connection file

$duplicate = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['phone'])) {
    $phone_number = $_POST['phone'];

    // Validate phone number format if needed
    if (preg_match('/^\+?[0-9\s-]+$/', $phone_number)) {
        // Check if the phone number already exists
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM number WHERE number = ?");
        $stmt->bind_param("s", $phone_number);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $duplicate = true;
        } else {
            // Insert the new phone number
            $stmt = $conn->prepare("INSERT INTO number (number) VALUES (?)");
            $stmt->bind_param("s", $phone_number);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "<p class='error'>Error adding phone number: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
    } else {
        echo "<p class='error'>Invalid phone number format.</p>";
    }
}

// Handle update and delete actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] === 'update' && isset($_POST['id']) && isset($_POST['new_number'])) {
        $id = $_POST['id'];
        $new_number = $_POST['new_number'];

        // Validate new phone number format if needed
        if (preg_match('/^\+?[0-9\s-]+$/', $new_number)) {
            // Update the phone number
            $stmt = $conn->prepare("UPDATE number SET number = ? WHERE id = ?");
            $stmt->bind_param("si", $new_number, $id);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "<p class='error'>Error updating phone number: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='error'>Invalid phone number format.</p>";
        }
    } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];

        // Delete the phone number
        $stmt = $conn->prepare("DELETE FROM number WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "<p class='error'>Error deleting phone number: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

// Fetch total count of phone numbers
$total_query = "SELECT COUNT(*) AS total FROM number";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_count = $total_row['total'];
$total_result->free();

// Calculate total pages
$rowsPerPage = 20;
$totalPages = ceil($total_count / $rowsPerPage);

// Pagination settings
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // Ensure current page is within range
$offset = ($currentPage - 1) * $rowsPerPage;

// Fetch phone numbers with pagination
$query = "SELECT id, number FROM number ORDER BY id DESC LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $offset, $rowsPerPage);
$stmt->execute();
$result = $stmt->get_result();
$numbers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle export to TXT request
if (isset($_GET['export']) && $_GET['export'] === 'txt') {
    $file_name = 'phone_numbers.txt';
    $file_content = '';

    $query = "SELECT number FROM number";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $file_content .= $row['number'] . ',';
    }

    $result->free();
    $file_content = rtrim($file_content, ',');

    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    echo $file_content;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn With Codepanda - Numbers Database</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #e8f5e9;
        color: #333;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin-top: 30px;
        position: relative;
    }

    h1 {
        text-align: center;
        color: #388e3c;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #66bb6a;
        color: white;
    }

    .search-container {
        margin-top: 20px;
        text-align: right;
    }

    .search-input {
        padding: 10px;
        width: 250px;
        border: 1px solid #ddd;
        border-radius: 4px;
        outline: none;
    }

    .search-input:focus {
        border-color: #388e3c;
    }

    .action-btn {
        padding: 8px 12px;
        margin: 0 5px;
        cursor: pointer;
        border-radius: 4px;
        font-size: 14px;
        border: none;
    }

    .update-btn {
        background-color: #388e3c;
        color: white;
    }

    .delete-btn {
        background-color: #f44336;
        color: white;
    }

    form {
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    form label {
        font-size: 16px;
    }

    input[type="tel"] {
        padding: 8px;
        width: 250px;
        border: 1px solid #ddd;
        border-radius: 4px;
        outline: none;
    }

    input[type="tel"]:focus {
        border-color: #388e3c;
    }

    button[type="submit"] {
        padding: 8px 15px;
        background-color: #4caf50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    button[type="submit"]:hover {
        background-color: #388e3c;
    }

    .export-container,
    .load-more-container {
        margin-top: 20px;
        text-align: right;
    }

    .export-btn,
    .load-more-btn {
        padding: 8px 12px;
        background-color: #2196F3;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
    }

    .export-btn:hover,
    .load-more-btn:hover {
        background-color: #1976D2;
    }

    .logout-container {
        position: absolute;
        top: 20px;
        right: 20px;
    }

    .logout-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #f44336;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .logout-button:hover {
        background-color: #e53935;
    }

    .total-count {
        margin-bottom: 20px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        background-color: #4caf50;
        margin-top: 10px;
        margin-bottom: -10px;
        color: white;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: inline-block;

    }


    .pagination {
        text-align: center;
        margin-top: 20px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        margin: 0 5px;
        background-color: #4caf50;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
    }

    .pagination a:hover {
        background-color: #388e3c;
    }

    .pagination span {
        padding: 8px 12px;
        margin: 0 5px;
        font-size: 14px;
    }

    .error {
        color: red;
        font-weight: bold;
        text-align: center;
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Learn With Codepanda - Numbers Database</h1>

        <div class="logout-container">
            <a href="logout.php" class="logout-button">Logout</a>
        </div>

        <form action="index.php" method="post">
            <label for="phone">Add New Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>
            <button type="submit">Add Phone Number</button>
        </form>

        <?php if ($duplicate): ?>
        <p class="error">This phone number already exists.</p>
        <?php endif; ?>

        <div class="total-count">
            Total Phone Numbers: <?php echo htmlspecialchars($total_count); ?>
        </div>

        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="Search phone numbers...">
        </div>

        <table id="itemsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($numbers)): ?>
                <?php foreach ($numbers as $number): ?>
                <tr>
                    <td><?php echo htmlspecialchars($number['id']); ?></td>
                    <td><?php echo htmlspecialchars($number['number']); ?></td>
                    <td>
                        <form action="index.php" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($number['id']); ?>">
                            <input type="tel" name="new_number"
                                value="<?php echo htmlspecialchars($number['number']); ?>" required>
                            <button type="submit" class="action-btn update-btn">Update</button>
                        </form>

                        <form action="index.php" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($number['id']); ?>">
                            <button type="submit" class="action-btn delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="3">No phone numbers found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php
            // Calculate the range of pages to display
            $range = 2;
            $start = max(1, $currentPage - $range);
            $end = min($totalPages, $currentPage + $range);

            if ($start > 1) {
                echo '<a href="?page=1">1</a>';
                if ($start > 2) {
                    echo '<span>...</span>';
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                if ($i == $currentPage) {
                    echo '<span>' . $i . '</span>';
                } else {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
            }

            if ($end < $totalPages) {
                if ($end < $totalPages - 1) {
                    echo '<span>...</span>';
                }
                echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
            }
            ?>

            <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo $currentPage + 1; ?>">Next</a>
            <?php endif; ?>
        </div>

        <div class="export-container">
            <a href="index.php?export=txt" class="export-btn">Export to TXT</a>
        </div>
    </div>

    <script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#itemsTable tbody tr');

        rows.forEach(row => {
            const phoneNumber = row.cells[1].textContent.toLowerCase();
            row.style.display = phoneNumber.includes(query) ? '' : 'none';
        });
    });
    </script>
</body>

</html>