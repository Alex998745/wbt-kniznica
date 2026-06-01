<?php

$administratorJePrihlaseny = true; 

$host = "localhost";
$dbname = "kniznica"; 
$username = "root";    
$password = "ssnd";    

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Chyba pripojenia k databáze: " . $e->getMessage());
}

$odkaz = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['priezvisko'])) {

    if ($administratorJePrihlaseny) {
        
        $meno = trim($_POST["meno"] ?? '');
        $priezvisko = trim($_POST["priezvisko"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $rola = $_POST["rola"] ?? 'Čitateľ';

        if (empty($priezvisko) || empty($email)) {
            $odkaz = "<p style='color:red'>Priezvisko a email sú povinné údaje.</p>";
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM pouzivatelia WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetchColumn() > 0) {
                $odkaz = "<p style='color:red'>Používateľ s rovnakým emailom už existuje.</p>";
            } else {
                $heslo = substr(bin2hex(random_bytes(4)), 0, 8);

                $stmt = $pdo->prepare("
                    INSERT INTO pouzivatelia 
                    (meno, priezvisko, email, heslo, rola) 
                    VALUES (?, ?, ?, ?, ?)
                ");

                $stmt->execute([
                    $meno,
                    $priezvisko,
                    $email,
                    password_hash($heslo, PASSWORD_DEFAULT),
                    $rola
                ]);

                $odkaz = "<div style='background:#d4edda; padding:10px; border:1px solid green; margin-bottom:10px;'>
                            <strong>Úspech!</strong> Používateľ zaregistrovaný.<br>
                            Iniciálne heslo: <strong>$heslo</strong>
                          </div>";
            }
        }
    } else {
        $odkaz = "<p style='color:red'>Nemáte oprávnenie na túto akciu.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
  <meta charset="UTF-8">
  <title>Správa používateľov</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
  <h1>Knižničný systém – PHP Verzia</h1>
  <p>Dynamický prototyp so správou používateľov a databázou.</p>
</header>
<nav>
    <a href="index.php">Prehľad</a>
    <a href="sprava-pouzivatelov.php">1. Správa používateľov</a>
</nav>
<main>

<section class="card">
  <h2>1. Správa používateľov</h2>
  <?= $odkaz ?> </section>

<section class="grid">
  <div class="card">
    <h3>UC-00 Prihlásiť sa do systému</h3>
    <form action="login.php" method="post">
      <label>Email</label>
      <input type="email" name="email" placeholder="meno@example.com">
      <label>Heslo</label>
      <input type="password" name="heslo">
      <button type="submit">Prihlásiť</button>
    </form>
  </div>

  <div class="card">
    <h3>UC-01 Registrovať používateľa</h3>
    <form action="" method="post"> <label>Meno</label>
      <input name="meno">
      <label>Priezvisko</label>
      <input name="priezvisko" required>
      <label>Email</label>
      <input type="email" name="email" required>
      <label>Rola</label>
      <select name="rola">
        <option value="Administrátor">Administrátor</option>
        <option value="Knihovník">Knihovník</option>
        <option value="Čitateľ">Čitateľ</option>
      </select>
      <button type="submit">Vytvoriť používateľa</button>
    </form>
  </div>

  <div class="card full">
    <h3>UC-05 Vyhľadať používateľa</h3>
    <form action="users.php" method="get" class="grid">
      <div><label>Meno</label><input name="meno"></div>
      <div><label>Priezvisko</label><input name="priezvisko"></div>
      <div><label>Email</label><input name="email"></div>
      <div>
        <label>Stav konta</label>
        <select name="stav">
          <option>Všetky</option>
          <option>Aktívny</option>
          <option>Zablokovaný</option>
        </select>
      </div>
      <div class="full"><button type="submit">Vyhľadať</button></div>
    </form>
  </div>
</section>

</main>
<footer>Školský prototyp – Implementované s PHP logikou.</footer>
</body>
</html>