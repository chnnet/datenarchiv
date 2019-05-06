<?php
        session_start();
?>
<html>
<head>
	<title>Kreditkartenbewegungen</title>

</head>

<body>

<h1>Kreditkartenbewegungen importieren</h1>
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
	$result = $con->query("SELECT kreditkarte_id, datum, betrag, text, status from kreditkarte where datum >= '" . $akt_monat . "' and betrag < 0 and status ='I'");

	if ($result)
	{
		if ($result->rowCount() > 0)
		{
			echo "<table border=1>";
			echo "<tr><th></th><th>Datum</th><th>Betrag</th><th>Text</th></tr>";
			while ($row = $result->fetch())
			{
				// status berücksichtigen
				$id = $row['kreditkarte_id'];
				echo "<tr><td><a href=\"insertkk.php?kreditkarte_id=" . $id . "&datum=" . $row['datum'] . "&betrag=" . $row['betrag'] . "&text=" . $row['text'] . "\" target=\"_blank\">" . $id . "</a></td><td>" . $row['datum'] . "</td><td align=right>" . $row['betrag'] . "</td><td>" . $row['text'] . "</td></tr>";
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
