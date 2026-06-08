<?php
session_start();

$DB_HOST = "localhost";
$DB_NAME = "kniznica";
$DB_USERNAME = "root";
$DB_PASSWORD = "password"; //TODO: oficiálne heslo

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USERNAME,
        $DB_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e) {
    die("Chyba pripojenia k databáze: ".$e->getMessage());
}
?>