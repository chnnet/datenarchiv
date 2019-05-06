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
    // DB-Connection
    try {
        $con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
    } catch (PDOException $ex) {
        die('Die Datenbank ist momentan nicht erreichbar!');
    }
	$result = $con->query('SELECT * from standort');
	i=0;
	while ( $row = $result->fetch() )
	{
		if ($i == 0) {
			echo "<tr><td><input type=\"radio\" name=\"standort\" value=\"" .  $row['standort_id'] . "\" checked>" .  $row['bezeichnung'] . "</td></tr>";
		} else {
			echo "<tr><td><input type=\"radio\" name=\"standort\" value=\"" .  $row['standort_id'] . "\">" .  $row['bezeichnung'] . "</td></tr>";
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
    // DB-Connection
    try {
        $con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
    } catch (PDOException $ex) {
        die('Die Datenbank ist momentan nicht erreichbar!');
    }
	$result = $con->query('SELECT * from sensorendaten');
	$i = 0;
	while ( $row = $result->fetch() )
	{
		if ($i == 0) {
			echo "<td><input type=\"radio\" name=\"datenart\" value=\"" .  $row['sensorendaten_id'] . "\" checked>" .  $row['tabelle'] . "</td>";
		} else {
			echo "<td><input type=\"radio\" name=\"datenart\" value=\"" .  $row['sensorendaten_id'] . "\">" .  $row['tabelle'] . "</td>";
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