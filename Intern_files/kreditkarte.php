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
	$con = mysql_connect($host, $benutzer, $passwort);
	if (!$con) {
		exit('Connect Error (' . mysql_errno() . ') ' . mysql_error());
	}

	mysql_select_db($dbname);
	$akt_monat = date("Y-m-01");
	$result = mysql_query("SELECT kreditkarte_id, datum, betrag, text, status from kreditkarte where datum >= '" . $akt_monat . "' and betrag < 0 and status ='I'");

	if ($result)
	{
		$num=mysql_numrows($result);
		if ($num > 0)
		{
			$i=0;
			echo "<table border=1>";
			echo "<tr><th></th><th>Datum</th><th>Betrag</th><th>Text</th></tr>";
			while ($i < $num)
			{
				// status berücksichtigen
				$id = mysql_result($result,$i,"kreditkarte_id");
				echo "<tr><td><a href=\"insertkk.php?kreditkarte_id=" . $id . "&datum=" . mysql_result($result,$i,"datum") . "&betrag=" . mysql_result($result,$i,"betrag") . "&text=" . mysql_result($result,$i,"text") . "\" target=\"_blank\">" . $id . "</a></td><td>" . mysql_result($result,$i,"datum") . "</td><td align=right>" . mysql_result($result,$i,"betrag") . "</td><td>" . mysql_result($result,$i,"text") . "</td></tr>";
				$i++;
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
