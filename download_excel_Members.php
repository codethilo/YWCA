<?php
// Database connection
$host = "10.0.22.75";
$username = "root";
$password = "";
$database = "ywca";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data from the database
$query = "SELECT * FROM members";
$result = $conn->query($query);

// Check if data exists
if ($result->num_rows > 0) {
    // Set headers to initiate file download
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Members.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Output column names
    $columns = $result->fetch_fields();
    $columnNames = [];
    foreach ($columns as $column) {
        $columnNames[] = '"' . $column->name . '"'; // Enclose column names in quotes
    }
    echo implode("\t", $columnNames) . "\n";

    // Output data
    while ($row = $result->fetch_assoc()) {
        $escapedData = [];
        foreach ($row as $value) {
            // Escape special characters and enclose values in quotes
            $escapedData[] = '"' . str_replace('"', '""', $value) . '"';
        }
        echo implode("\t", $escapedData) . "\n";
    }
} else {
    echo "No data available in the table.";
}

$conn->close();
?>



