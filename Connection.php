<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'native_test';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connection Failed');
}
