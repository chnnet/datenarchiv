<?php

// var_dump($_POST);
// User pwd holen aus Form index.php
// Anspassungen an Hosting, da nur ein DB-User
$app_benutzer = $_POST['loginname'];
$app_passwort = $_POST['password'];
$host = $_POST['host'];
$dbname = $_POST['dbname'];
$benutzer = "u25587";
$passwort = "dZrVKtMoz4V4Xq";

$con = mysqli_connect($host, $benutzer, $passwort);
if (!$con) {
var_dump($_POST);
exit('Connect Error (' . mysqli_connect_errno() . ') '
. mysqli_connect_error());
}
//set the default client character set
mysqli_set_charset($con, 'utf-8');
mysqli_select_db($con, $dbname);
// Anpassung an Hosting
$ben_menu = mysqli_query($con, "SELECT keynr,password,berechtigungsgruppe FROM benutzer WHERE login='" . $app_benutzer . "'");
if (mysqli_num_rows($ben_menu) < 1) {
exit("Benutzer " . $_POST["loginname"] . " nicht gefunden.");
}
else {
    // Passwort prüfen
    $row = mysqli_fetch_row($ben_menu);
    // var_dump($row);
    $chkpwd = $row[1];
    $keynr = $row[0];
    mysqli_free_result($ben_menu);
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
