<?php
        session_start();
?>
<html>
<head>
	<title>Saldenliste - Bestandskonten</title>

</head>

<body>

<b>Saldenliste - Bestandskonten</b>
<br>

<?php

	global $form_status;
	global $host;
	global $benutzer;
	global $passwort;
	global $dbname;
	global $tsaldo;
	global $betrag;
	global $ggbetrag;
	global $ggkonto;
	global $transaktions_id;
	global $datum;
	global $text;

	// Session-daten auslesen
	$host = $_SESSION['host'];
	$benutzer = $_SESSION['benutzer'];
	$passwort = $_SESSION['passwort'];
	$dbname = $_SESSION['dbname'];
/*
	$host = "localhost";
	$benutzer = "test";
	$passwort = "testchn";
	$dbname = "datenarchiv";
*/
    // DB-Connection
    try {
        $con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
    } catch (PDOException $ex) {
        die('Die Datenbank ist momentan nicht erreichbar!');
    }
	$akt_monat = date("Y-m-01");

	if (isset($_GET['konto']))
	{
		// Paramter auslesen
		$konto = $_GET['konto'];
		$jahrmonat = $_GET['jahrmonat'];

		$result = $con->query("SELECT * from kontobewegungen where jahrmonat=" . $jahrmonat . " and konto = " . $konto);
		$saldo = 0;

		echo "<br>";
		echo "<table border=1>";
		echo "<tr><th>Text</th><th>Betrag</th></tr>";
		if ($result)
		{
			while ($row = $result->fetch())
			{
				echo "<tr><td>" . $row['text'] . "</td><td>" .  $row['betrag'] . "</td></tr>";
				$saldo = $saldo + $row['betrag'];
			}
		}
		echo "<tr><td></td><td><b>" . $saldo . "</b></td>";
		echo "</table>";
	}
	else
	{
		echo "<br>";
		echo "<form name = \"salden_bkonten\" action = \"salden_bestkonten.php\" >";
		echo "<table>";
		echo "<tr><td>Konto</td><td><select name=\"konto\" >";
		$result = $con->query("SELECT ktonr, bezeichnung from kontenstamm where typ='B' order by bezeichnung");
		if ($result)
		{
			while ($row = $result->fetch())
			{
				echo "<option value=" . $row['ktonr'] . ">" .  $row['bezeichnung'] . "</option>";
				$i++;
			}
		}
		echo "</select></td></tr>";
		echo "<tr><td>Monat</td><td><select name=\"jahrmonat\" >";
		$res = $con->query("SELECT distinct jahrmonat from kontobewegungen");
		if ($res)
		{
			while ($row = $result->fetch())
			{
				echo "<option value=" . $row['jahrmonat'] . ">" . $row['jahrmonat'] . "</option>";
			}
		}
		echo "</select></td></tr>";
		echo "</table>";
		echo "<br>";
		echo "<input type=submit value=\"S&auml;tze laden\" />";
		echo "</form>";
	}


?>
</body>
</html>
