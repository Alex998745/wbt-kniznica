<?php
session_start();

$DB_HOST = "localhost";
$DB_NAME = "kniznica";
$DB_USERNAME = "root";
$DB_PASSWORD = "ssnd";

try {

$pdo = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $username,
    $password
);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Chyba pripojenia k databáe: ".e->getMessage());
}
?>