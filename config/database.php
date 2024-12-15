<?php

$host = "localhost";
$dbname = "mylibrary";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    die("Connection failed: " . $e->getMessage());
}