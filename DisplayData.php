<?php
require_once './connection.php';
require_once 'connection.php';

$sql = "SELECT id, serial, code, name, date FROM excel_data";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; 
    }
} else {
    $data[] = []; 
}

$conn->close();

