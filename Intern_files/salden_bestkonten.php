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
		$jahrmonat = $_GET['jahrmonat'];

		$result = mysql_query("SELECT * from kontobewegungen where jahrmonat=" . $jahrmonat . " and konto = " . $konto);
		$saldo = 0;

		echo "<br>";
		echo "<table border=1>";
		echo "<tr><th>Text</th><th>Betrag</th></tr>";
		if ($result)
		{
			$num=mysql_numrows($result);
			$i=0;
			while ($i < $num)
			{
				echo "<tr><td>" . mysql_result($result,$i,"text") . "</td><td>" .  mysql_result($result,$i,"betrag") . "</td></tr>";
				$saldo = $saldo + mysql_result($result,$i,"betrag");
				$i++;
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
		$result = mysql_query("SELECT ktonr, bezeichnung from kontenstamm where typ='B' order by bezeichnung");
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
		echo "<tr><td>Monat</td><td><select name=\"jahrmonat\" >";
		$res = mysql_query("SELECT distinct jahrmonat from kontobewegungen");
		if ($res)
		{
			$num=mysql_numrows($res);
			$i=0;
			while ($i < $num)
			{
			echo "<option value=" . mysql_result($res,$i,"jahrmonat") . ">" . mysql_result($res,$i,"jahrmonat") . "</option>";
				$i++;
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
