<?php
        session_start();
?>
<html>
<head>
	<title>Bankbewegungen</title>

</head>

<body>

<h1>Bankbewegungen importieren</h1>
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
	try {
		$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

	} catch (PDOException $ex) {
		die('Die Datenbank ist momentan nicht erreichbar!');
	}

	$akt_monat = date("Y-m-01");
	$result = $con->prepare("SELECT banktrans_id, datum, betrag, text, status from bank where datum >= '" . $akt_monat . "' and status ='I'");
	$result->execute(array($$akt_monat))
    	or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));


	if ($result)
	{
		$num= $result->rowCount();
		if ($num > 0)
				echo "<table border=1>";
				echo "<tr><th></th><th>Datum</th><th>Betrag</th><th>Text</th></tr>";
			while ($row = $result->fetch()) {
				// status berücksichtigen
				$id = mysql_result($result,$i,"banktrans_id");
				echo "<tr><td><a href=\"insertbank.php?banktrans_id=" . $id . "&datum=" . mysql_result($result,$i,"datum") . "&betrag=" . mysql_result($result,$i,"betrag") . "&text=" . mysql_result($result,$i,"text") . "\" target=\"_blank\">" . $id . "</a></td><td>" . mysql_result($result,$i,"datum") . "</td><td align=right>" . mysql_result($result,$i,"betrag") . "</td><td>" . mysql_result($result,$i,"text") . "</td></tr>";
			}
			echo "<table border=1>";
		}
		else
		{
			echo "Keine Bewegungen vorhanden!";
		}
	}

?>
</body>
</html>
