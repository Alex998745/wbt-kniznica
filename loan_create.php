<!DOCTYPE html>

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

    $startDate;
    if (empty($_POST['datum_vypozicky'])) {
        $startDate = date('Y-m-d');
    }else {
        $startDate = $_POST['datum_vypozicky'];
    }

    $returnDate;
    if (empty($_POST['termin_vratenia'])) {
        $returnDate = date('Y-m-d', strtotime('+30 days', $startDate));
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
            <h2>3. Požičiavanie a rezervácie</h2>
            <p>Táto časť pokrýva vypožičanie knihy, vrátenie knihy a rezerváciu aktuálne nedostupnej knihy.</p>
        </section>

        <section class="grid">
            <div class="card full">
                <h3>UC-20 Vypožičať knihu</h3>
                <form action="loan_create.php" method="post" class="grid">
                    <div><label>Používateľ</label><select name="pouzivatel_id"><option>Jana Nováková</option></select></div>
                    <div><label>Dostupný exemplár</label><select name="exemplar_id"><option>INV-2026-001 – Databázové systémy</option></select></div>
                    <div><label>Dátum výpožičky</label><input type="date" name="datum_vypozicky"></div>
                    <div><label>Termín vrátenia</label><input type="date" name="termin_vratenia"></div>
                    <div class="full"><button>Vypožičať knihu</button></div>
                </form>
            </div>

            <div class="card full">
                <h3>Aktívne výpožičky</h3>
                <table class="table">
                    <tr><th>Používateľ</th><th>Kniha</th><th>Exemplár</th><th>Termín vrátenia</th><th>Stav</th><th>Akcia</th></tr>
                    <tr>
                        <td>Jana Nováková</td>
                        <td>Databázové systémy</td>
                        <td>INV-2026-001</td>
                        <td>2026-06-10</td>
                        <td><span class="badge warn">vypožičaná</span></td>
                        <td><button class="success">Vrátiť</button></td>
                    </tr>
                </table>
            </div>

            <div class="card">
                <h3>UC-21 Vrátiť knihu</h3>
                <form action="loan_return.php" method="post">
                    <label>Aktívna výpožička</label>
                    <select name="vypozicka_id"><option>Jana Nováková – Databázové systémy – INV-2026-001</option></select>
                    <label>Dátum vrátenia</label>
                    <input type="date" name="datum_vratenia">
                    <label>Stav exemplára po vrátení</label>
                    <select name="stav_exemplara"><option>Dostupný</option><option>Poškodený</option><option>Vyradený</option></select>
                    <button class="success">Potvrdiť vrátenie</button>
                </form>
            </div>

            <div class="card">
                <h3>UC-30 Rezervovať knihu</h3>
                <form action="reservation_create.php" method="post">
                    <label>Čitateľ</label>
                    <select name="pouzivatel_id"><option>Jana Nováková</option></select>
                    <label>Kniha</label>
                    <select name="kniha_id"><option>Programovanie v PHP – všetky exempláre vypožičané</option></select>
                    <label>Dátum rezervácie</label>
                    <input type="date" name="datum_rezervacie">
                    <label>Expirácia rezervácie</label>
                    <input type="date" name="expiracia">
                    <button>Vytvoriť rezerváciu</button>
                </form>
            </div>

            <div class="card full">
                <h3>Zoznam rezervácií</h3>
                <table class="table">
                    <tr><th>Čitateľ</th><th>Kniha</th><th>Dátum rezervácie</th><th>Expirácia</th><th>Stav</th></tr>
                    <tr><td>Jana Nováková</td><td>Programovanie v PHP</td><td>2026-05-27</td><td>2026-06-03</td><td><span class="badge warn">čaká</span></td></tr>
                </table>
            </div>
        </section>

    </main>
    <footer>Školský prototyp – bez JavaScriptu, pripravené na doplnenie PHP logiky.</footer>
  </body>
</html>