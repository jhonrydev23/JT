<?php
require_once './connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $serial = $_POST["serial"] ?? '';
    $code = $_POST["code"] ?? '';
    $name = $_POST["name"] ?? '';
    $date = $_POST["date"] ?? '';


    $sql = "INSERT INTO 
                excel_data (serial, code, name, date) 
            VALUES 
                (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $dateInput = $date;

    $date = null;

    if (!empty($dateInput)) {

        if (preg_match('/^[A-Za-z]+\s+\d{4}$/', $dateInput)) {
            $dateInput = '1 ' . $dateInput;
        }

        $dt = DateTime::createFromFormat('F j, Y', $dateInput);

        if (!$dt) {
            $dt = DateTime::createFromFormat('m/d/Y', $dateInput);
        }

        if (!$dt) {
            $timestamp = strtotime($dateInput);
            $dt = $timestamp ? new DateTime("@$timestamp") : false;
        }

        if ($dt) {
            $date = $dt->format('Y-m-d');
        }
    }

    $stmt->bind_param("isss", $serial, $code, $name, $date);
    $stmt->execute();
}
