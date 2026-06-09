<?php
session_start();

$DB_HOST = "localhost";
$DB_NAME = "kniznica";
$DB_USERNAME = "root";
$DB_PASSWORD = "password"; //TODO: oficiálne heslo

$GENERIC_SERVER_PROBLEM = 'Ľutujeme, niečo sa pokazilo na strane servera. Skúste ešte raz, prosím, neskôr.';
$SETTINGS_JSON_PATH = 'data/settings.json';

$settings;
$problems = [];
$dbError = null;

if (file_exists($SETTINGS_JSON_PATH)) {
    $settings = json_decode(file_get_contents($SETTINGS_JSON_PATH), true);
    try {
        $pdo = new PDO(
            "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
            $DB_USERNAME,
            $DB_PASSWORD
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch (PDOException $e) {
        $dbError = $e->getMessage();
        error_log('Error during DB connecting: '.$dbError);
        $problems[] = $GENERIC_SERVER_PROBLEM;
    }
}else {
    $problems[] = $GENERIC_SERVER_PROBLEM;
}
?>