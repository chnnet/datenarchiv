<?php
    session_start();

	include_once "ausgabe_tabelle.php";
	include_once 'build_tabelle_array.php';

    if (isset($_POST['sendungsinfos'])) // speichern
    {

        $sendung_id = $_POST['sendung_id'];
		$sendedatum = $_POST['sendedatum'];
        $volltext = $_POST['sendungsinfos'];

        // ***** Parameter auslesen session *****
        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];
        $benutzer_id = $_SESSION['keynr'];

		// DB-Connection
		try {
			$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

		} catch (PDOException $ex) {
			die('Die Datenbank ist momentan nicht erreichbar!');
		}

		$fields = array("sendungsinfo_id", "sendung", "sendedatum", "sendung_titel");
		if ($sendung_id)
		{
			$sql = "SELECT i.sendungsinfo_id, s.sendung, i.sendedatum, i.sendung_titel from sendungsinfos i, sendungen s where s.sendung_id=i.sendung_id and s.sendung_id=" . $sendung_id;
			ausgabeTabelleIDLink($con, $sql, $sendung_id, $fields);
		}
    }


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Radiosendungen suchen</title>
    </head>
    <body>

<?php
?>

<h2>Radiosendungen suchen</h2>
<br>

<form name="radiosendungen_suchen" action="radiosendungen.php" target="main" method="post">
<table>
<tr>
	<td>Sendung</td>
	<td>
	<select type=text name="sendung_id">
		<?php
			$sendung = jsklassids("sendungen","sendung_id","sendung",true);
			echo $sendung;
		?>
	</select>
	</td>
</tr>
<tr>
	<td>Sendedatum (JJJJ-MM-TT)</td>
	<td>
	<input type=text name="sendedatum" type="text" size=10 maxlength=10>
	</td>
</tr>
<tr>
		<td>Volltextsuche</td>
		<td><input name="sendungsinfos" type="text" size=50 maxlength=50></td>
</tr>
</table>

<input type=submit value="Sendungen suchen"/>
</form>

    </body>
</html>