<?php
require 'load_data.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usrStatement = $pdo->prepare('
        SELECT
            id, stav_konta, dovod_zablokovania
        FROM pouzivatel
        WHERE CONCAT(meno, \' \', priezvisko) = :fullName
    ');
    $usrStatement->execute([
        'fullName' => $_POST['pouzivatel_id']
    ]);
    $user = $usrStatement->fetch(PDO::FETCH_ASSOC);

    $specimenInventoryNumber = explode(' ', $_POST['exemplar_id'])[0];
    $specimenStatement = $pdo->prepare('
        SELECT
            id
        FROM exemplar
        WHERE inventarne_cislo = :inventoryNumber
    ');
    $specimenStatement->execute([
        'inventoryNumber' => $specimenInventoryNumber
    ]);
    $specimen = $specimenStatement->fetch(PDO::FETCH_ASSOC);

    $borrowStatement = $pdo->prepare('
        BEGIN;

        INSERT INTO vypozicka
            (pouzivatel_id, exemplar_id, datum_vypozicky, termin_vratenia)
        VALUES
            (:userId, :specimenId, :startDate, :returnDate);

        UPDATE exemplar SET
            stav = \'vypozicany\'
        WHERE inventarne_cislo = :inventoryNumber;

        COMMIT
    ');
    $borrowStatement->execute([
        'userId' => $user['id'],
        'specimenId' => $specimen['id'],
        'startDate' => date('Y-m-d'),
        'returnDate' => date('Y-m-d', strtotime('+7 days')),
        'inventoryNumber' => $specimenInventoryNumber
    ]);
}
?>