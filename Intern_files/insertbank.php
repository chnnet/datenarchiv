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

	$con = mysql_connect($host, $benutzer, $passwort);
	if (!$con) {
		exit('Connect Error (' . mysql_errno() . ') ' . mysql_error());
	}
	mysql_select_db($dbname);
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
		$result = mysql_query("INSERT into kontobewegungen (transaktions_id,banktrans_id,datum,jahrmonat,konto,betrag,text,erstellt,status) values (" . $tid . "," . $id . ",'" . $datum . "'," . $jahrmonat . ",28000,'" . $betrag . "','" . $text . "','" . $erstellt . "','B')");
		if ($result)
		{
			$betrag = $betrag * -1;
			$res = mysql_query("INSERT into kontobewegungen (transaktions_id,banktrans_id,datum,jahrmonat,konto,betrag,text,erstellt,status) values (" . $tid . "," . $id . ",'" . $datum . "'," . $jahrmonat . "," . $konto . ",'" . $betrag . "','" . $text . "','" . $erstellt . "','B')");
			if (!$res)
			{
				echo "MySQL Error (" . mysql_errno() . "): " . mysql_error();
			}
			else
			{
				$res2 = mysql_query("UPDATE bank set status='V' where banktrans_id=" . $id);
				if (!$res2)
				{
					echo "MySQL Error (" . mysql_errno() . "): " . mysql_error();
				}
				else
				{
					echo "Daten erfolgreich gespeichert!";
				}
			}
		}
		else
		{
			echo "MySQL Error (" . mysql_errno() . "): " . mysql_error();
		}
	}
	else
	{
		$id = $_GET['banktrans_id'];
		$datum = $_GET['datum'];
		$betrag = $_GET['betrag'];
		$text = $_GET['text'];
		$result = mysql_query("SELECT max(transaktions_id) from kontobewegungen");
		if ($result) $max_tid = mysql_result($result,0,0);
		$max_tid = $max_tid + 1;

		echo "<br>";
		echo "<form name = \"bankdetails\" action = \"insertbank.php\" >";
		echo "<table>";
		echo "<tr><td>ID</td><td><input type=text name=\"tid\" size=10 value=" . $max_tid . " readonly></td></tr>";
		echo "<tr><td>Datum</td><td><input type=text name=\"datum\" size=10 value=\"" . $datum . "\" readonly></td></tr>";
		echo "<tr><td>Betrag</td><td><input type=text name=\"betrag\" size=10 value=\"" . $betrag . "\" readonly></td></tr>";
		echo "<tr><td>Text</td><td><textarea name=\"text\" cols=\"50\" rows=\"5\" readonly>" . $text . "</textarea></td></tr>";
		echo "<tr><td>Konto</td><td><select name=\"konto\" >";
		$result = mysql_query("SELECT ktonr, bezeichnung from kontenstamm order by bezeichnung");
		if ($result)
		{
			$num=mysql_numrows($result);
			$i=0;
			while ($i < $num)
			{
				echo "<option value=" . mysql_result($result,$i,"ktonr") . ">" .  mysql_result($result,$i,"bezeichnung") . "</option>";
				$i++;
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
