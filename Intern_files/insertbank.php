<?php
        session_start();
?>
<html>
<head>
	<title>Bankbewegungen - Details Import</title>

</head>

<body>

<b>Bankbewegung importieren - Details</b>
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
		$id = $_GET['banktrans_id'];
		$tid = $_GET['tid'];
		$datum = $_GET['datum'];
		$betrag = $_GET['betrag'];
		$text = $_GET['text'];

		// insert
		$erstellt = date("Y-m-d H:i:s");
		$jahrmonat = substr($datum,1,4) . substr($datum,5,2); //substring berechnen
		$result = $con->execute("INSERT into kontobewegungen (transaktions_id,banktrans_id,datum,jahrmonat,konto,betrag,text,erstellt,status) values (" . $tid . "," . $id . ",'" . $datum . "'," . $jahrmonat . ",28000,'" . $betrag . "','" . $text . "','" . $erstellt . "','B')");
		if ($result)
		{
			$betrag = $betrag * -1;
			$res = $con->execute("INSERT into kontobewegungen (transaktions_id,banktrans_id,datum,jahrmonat,konto,betrag,text,erstellt,status) values (" . $tid . "," . $id . ",'" . $datum . "'," . $jahrmonat . "," . $konto . ",'" . $betrag . "','" . $text . "','" . $erstellt . "','B')");
			if (!$res)
			{
				'Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));
			}
			else
			{
				$res2 = $con->execute("UPDATE bank set status='V' where banktrans_id=" . $id);
				if (!$res2)
				{
					echo 'Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]);
				}
				else
				{
					echo "Daten erfolgreich gespeichert!";
				}
			}
		}
		else
		{
			echo 'Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]);
		}
	}
	else
	{
		$id = $_GET['banktrans_id'];
		$datum = $_GET['datum'];
		$betrag = $_GET['betrag'];
		$text = $_GET['text'];
		$result = $con->execute("SELECT max(transaktions_id) from kontobewegungen");
		$max_tid = $result->fetchColumn() +1;

		echo "<br>";
		echo "<form name = \"bankdetails\" action = \"insertbank.php\" >";
		echo "<table>";
		echo "<tr><td>ID</td><td><input type=text name=\"tid\" size=10 value=" . $max_tid . " readonly></td></tr>";
		echo "<tr><td>Datum</td><td><input type=text name=\"datum\" size=10 value=\"" . $datum . "\" readonly></td></tr>";
		echo "<tr><td>Betrag</td><td><input type=text name=\"betrag\" size=10 value=\"" . $betrag . "\" readonly></td></tr>";
		echo "<tr><td>Text</td><td><textarea name=\"text\" cols=\"50\" rows=\"5\" readonly>" . $text . "</textarea></td></tr>";
		echo "<tr><td>Konto</td><td><select name=\"konto\" >";
		$result = $con->query("SELECT ktonr, bezeichnung from kontenstamm order by bezeichnung");
		if ($result)
		{
			while ($row = $result->fetch())
			{
				echo "<option value=" . $row['ktonr'] . ">" .  $row['bezeichnung'] . "</option>";
			}
		}
		echo "</select></td></tr>";
		echo "</table>";
		echo "<br>";
		echo "<input type=hidden name=\"banktrans_id\" value=" . $id . " />";
		echo "<input type=submit value=\"Speichern\" />";
		echo "</form>";
	}


?>
</body>
</html>
