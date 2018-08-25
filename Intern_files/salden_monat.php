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
        $con = mysql_connect($host, $benutzer, $passwort);
        if (!$con) {
                exit('Connect Error (' . mysql_errno() . ') ' . mysql_error());
        }

        global $summe;
        mysql_select_db($dbname);
        $result = mysql_query("SELECT k.bezeichnung, s.betrag from kontenstamm k, salden_monat_verrechnung s where k.ktonr=s.konto_id and s.konto_id > 39999 and s.konto_id < 80000 and jahrmonat=" . $_POST['monat']);
        if ($result)
        {
            $num=mysql_numrows($result);

            $i=0;
            while ($i < $num) {

                $betrag = mysql_result($result,$i,"s.betrag");
                echo "<tr><td>" . mysql_result($result,$i,"k.bezeichnung") . "</td><td align=right>" . $betrag . "</td></tr>";
                $summe = $summe + $betrag;
                $i++;
            }
            echo "<tr><td><b>Summe</td></b><td><b>" . $summe . "</b></td></tr>";
        }
        else
        {
            echo "SQL-Fehler: (" . mysql_errno() . ") " . mysql_error();
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
