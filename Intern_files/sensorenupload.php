<?php
	session_start();

	// ***** Session infos auslesen *****

    $host = $_SESSION['host'];
    $benutzer = $_SESSION['benutzer'];
    $passwort = $_SESSION['passwort'];
    $dbname = $_SESSION['dbname'];
       
?>

<form name="uploadformular" enctype="multipart/form-data" action="sensorenimport.php" method="post">
<table>
	<tr>
		<td>Standort</td>
<?php

	// Umschreiben auf SELECT mit $result
	$con = mysql_connect($host, $benutzer, $passwort);
	mysql_select_db($dbname);
	$result = mysql_query('SELECT * from standort');
	$num=mysql_numrows($result);
	$i = 0;
	while ( $i < $num )
	{
		if ($i == 0) {
			echo "<tr><td><input type=\"radio\" name=\"standort\" value=\"" .  mysql_result($result,$i,"standort_id") . "\" checked>" .  mysql_result($result,$i,"bezeichnung") . "</td></tr>";
		} else {
			echo "<tr><td><input type=\"radio\" name=\"standort\" value=\"" .  mysql_result($result,$i,"standort_id") . "\">" .  mysql_result($result,$i,"bezeichnung") . "</td></tr>";
		}
		$i++;
	}
	
?>
	</tr>
	<tr>
		<td>Datenart</td>
	</tr>
	<tr>
<?php
	// Umschreiben auf SELECT mit $result
	$con = mysql_connect($host, $benutzer, $passwort);
	mysql_select_db($dbname);
	$result = mysql_query('SELECT * from sensorendaten');
	$num=mysql_numrows($result);
	$i = 0;
	while ( $i < $num )
	{
		if ($i == 0) {
			echo "<td><input type=\"radio\" name=\"datenart\" value=\"" .  mysql_result($result,$i,"sensorendaten_id") . "\" checked>" .  mysql_result($result,$i,"tabelle") . "</td>";
		} else {
			echo "<td><input type=\"radio\" name=\"datenart\" value=\"" .  mysql_result($result,$i,"sensorendaten_id") . "\">" .  mysql_result($result,$i,"tabelle") . "</td>";
		}
		$i++;
	}
	
?>
	</tr>
</table>

<br>
Datei: <input type="file" name="uploaddatei" size="60" maxlength="255">
<input type="Submit" name="submit" value="Datei hochladen">
</form>