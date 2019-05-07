<?php

// var_dump($_POST);
// User pwd holen aus Form index.php
// Anspassungen an Hosting, da nur ein DB-User
$app_benutzer = $_POST['loginname'];
$app_passwort = $_POST['password'];
$host = $_POST['host'];
$dbname = $_POST['dbname'];
// an Hosting anpassen
$benutzer = "";
$passwort = "";

// DB-Connection
try {
	$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

} catch (PDOException $ex) {
	die('Die Datenbank ist momentan nicht erreichbar!');
}


// Anpassung an Hosting
$ben_menu = $con->prepare("SELECT keynr,password,berechtigungsgruppe FROM benutzer WHERE login='" . $app_benutzer . "'");
$ben_menu->execute(array($app_benutzer))
	or die('Fehler bei Abfrage Benutzer');
if ($ben_menu->rowCount() < 1) {
exit("Benutzer " . $_POST["loginname"] . " nicht gefunden.");
} else {
    // Passwort prüfen
    $row = $ben_menu->fetch();
    // var_dump($row);
    $chkpwd = $row[1];
    $keynr = $row[0];
    $ben_menu->closeCursor(); // check
    // Anpassung an Hosting
    if ($app_passwort == $chkpwd) {
        
        session_start();
        $_SESSION['benutzer'] = $benutzer;
        $_SESSION['host'] = $host;
        $_SESSION['passwort'] = $passwort;
        $_SESSION['dbname'] = $dbname;
        $_SESSION['keynr'] = $keynr;
 
        echo "<frameset rows=\"60,*\" border=\"0\">";
        echo "<frame src=\"menu.php\" name=\"menu\">";
        echo "<frame src=\"main.html\" name=\"main\">";
        echo "<noframes>";
        echo "Ihr Browser kann diese Seite leider nicht anzeigen!";
        echo "</noframes>";
        echo "</frameset>";

    }
    else {

        echo "<html>";
        echo "<head><title>Login-page</title><head>";
        echo "<body>";
        echo "Falsches Passwort/Login!";
        echo "</body>";
        echo "</html>";

    }
}
?>
