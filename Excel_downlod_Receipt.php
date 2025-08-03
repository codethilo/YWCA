<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "ywca";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data from the database
$query = "SELECT * FROM your_table_name";
$result = $conn->query($query);

// Check if data exists
if ($result->num_rows > 0) {
    // Set headers to initiate file download
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Receipt.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Output column names
    $columns = $result->fetch_fields();
    foreach ($columns as $column) {
        echo $column->name . "\t";
    }
    echo "\n";

    // Output data
    while ($row = $result->fetch_assoc()) {
        echo implode("\t", $row) . "\n";
    }
} else {
    echo "No data available in the table.";
}

$conn->close();
?>
