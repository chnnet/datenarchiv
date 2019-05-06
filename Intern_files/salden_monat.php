<?php
        session_start();
?>
<html>
<head><title>Monatsbericht</title>

</head>
<body>

<h1> Monatsbericht <?php echo $_POST['monat']; ?> </h1>
<form name="abschluss_bericht" action="salden_monat.php" method="post">
<table border="1">
<tr><th>Konto</th><th>Saldo</th></tr>
<?php
	// Session-daten auslesen
	$host = $_SESSION['host'];
	$benutzer = $_SESSION['benutzer'];
	$passwort = $_SESSION['passwort'];
	$dbname = $_SESSION['dbname'];

    // DB-Connection
    try {
        $con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
    } catch (PDOException $ex) {
        die('Die Datenbank ist momentan nicht erreichbar!');
    }
        global $summe;
        $result = $con->query("SELECT k.bezeichnung, s.betrag from kontenstamm k, salden_monat_verrechnung s where k.ktonr=s.konto_id and s.konto_id > 39999 and s.konto_id < 80000 and jahrmonat=" . $_POST['monat']);
        if ($result)
        {
            while ($row = $result->fetch()) {

                $betrag = $row['betrag'];
                echo "<tr><td>" . $row['bezeichnung'] . "</td><td align=right>" . $betrag . "</td></tr>";
                $summe = $summe + $betrag;
                $i++;
            }
            echo "<tr><td><b>Summe</td></b><td><b>" . $summe . "</b></td></tr>";
        }
        else
        {
            echo 'Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]);
        }

?>
</table>
<br>
<table>
<tr>
<td><input name="mb" type=submit value="Monatsbericht"/></td>
<td><input name="siv" type=submit value="Soll/Ist-Vergleich"/></td>
</tr>
</table>
</form>
</body>
</html>
