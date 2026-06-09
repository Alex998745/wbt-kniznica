<!DOCTYPE html>

<?php
require 'load_data.php';
$success;
$problems = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = true;
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

    $startDate;
    if (empty($_POST['datum_vypozicky'])) {
        $startDate = date('Y-m-d');
    }else {
        $startDate = $_POST['datum_vypozicky'];
    }

    $returnDate;
    if (empty($_POST['termin_vratenia'])) {
        $returnDate = date('Y-m-d', strtotime('+30 days', strtotime($startDate)));
    }else {
        $returnDate = $_POST['termin_vratenia'];
    }

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
        'startDate' => $startDate,
        'returnDate' => $returnDate,
        'inventoryNumber' => $specimenInventoryNumber
    ]);
}else {
    $success = false;
    $problems[] = 'Formulár nebol vyplnený, vyplňte ho ešte raz, prosím.';
}
?>

<html lang="sk">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Výsledok registrovania požičania</title>
  </head>

  <body>
    <header>
        <h1>Knižničný systém – HTML/CSS prototyp</h1>
        <p>Statický návrh obrazoviek podľa use casov. Určené ako zadanie pre implementáciu v PHP a databáze.</p>
    </header>
    <nav><a href="index.html">Prehľad</a><a href="sprava-pouzivatelov.html">1. Správa používateľov</a><a href="evidencia-knih.html">2. Evidencia kníh</a><a href="poziciavanie-rezervacie.html">3. Požičiavanie a rezervácie</a></nav>
    <main>

        <section class="card">
            <?php if ($success): ?>
                <h2>Kniha sa úspešne vypožičala</h2>
            <?php else: ?>
                <h2>Kniha <strong>nebola</strong> úspešne vypožičaná</h2>
                <?php foreach ($problems as $problem): ?>
                    <p><?= $problem ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

    </main>
    <footer>Školský prototyp – bez JavaScriptu, pripravené na doplnenie PHP logiky.</footer>
  </body>
</html>