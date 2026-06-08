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
    var_dump($user);
}
?>