<?php
require_once 'config.php';

// Get the table name from the query parameter
$tableName = $_GET['table'];

// Fetch the columns of the table
$columns = [];
// $stmt = $conn->prepare('DESCRIBE ?');
// $stmt->bind_param('s', $tableName);
// $stmt->execute();
// $result = $stmt->get_result();
$sql = "DESCRIBE $tableName";
$result = $conn->query($sql);

while ($row = mysqli_fetch_assoc($result)) {
    $columns[] = $row['Field'];
}


// Fetch the rows from the table
$rows = [];
// $stmt = $conn->prepare('SELECT * FROM ?');
// $stmt->bind_param('s', $tableName);
// $stmt->execute();
// $result = $stmt->get_result();
$sql = "SELECT * FROM $tableName";
$result = $conn->query($sql);

while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

// Return the columns and rows as JSON response
$response = [
    'columns' => $columns,
    'rows' => $rows
];

echo json_encode($response);
