<?php

$sprava = '';
$admin = true;

$db = mysqli_connect('localhost', 'root', 'ssnd', 'kniznica');

if (!$db)
    die('Chyba pripojenia k databáze!');


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!$admin)
    {
        $sprava = 'Nemáte oprávnenie.';
    }
    else
    {
        $meno = trim($_POST['meno']);
        $priezvisko = trim($_POST['priezvisko']);
        $email = trim($_POST['email']);
        $rola = $_POST['rola'];

        if ($rola == '')
            $rola = 'Čitateľ';

        if ($priezvisko == '' || $email == '')
        {
            $sprava = 'Priezvisko a email sú povinné údaje.';
        }
        else
        {
            $q = mysqli_query($db,
                "SELECT COUNT(*) 
                 FROM pouzivatelia
                 WHERE email = '$email'"
            );

            $pocet = mysqli_fetch_row($q)[0];

            if ($pocet > 0)
            {
                $sprava = 'Používateľ s týmto emailom už existuje.';
            }
            else
            {
                $heslo = substr(md5(rand()), 0, 8);

                $heslo_hash = password_hash(
                    $heslo,
                    PASSWORD_DEFAULT
                );

                mysqli_query($db,
                    "INSERT INTO pouzivatelia
                    (meno, priezvisko, email, heslo, rola)
                    VALUES
                    (
                        '$meno',
                        '$priezvisko',
                        '$email',
                        '$heslo_hash',
                        '$rola'
                    )"
                );

                $sprava =
                    'Používateľ bol zaregistrovaný.<br>' .
                    'Heslo: <b>' . $heslo . '</b>';
            }
        }
    }
}

echo $sprava;

mysqli_close($db);

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