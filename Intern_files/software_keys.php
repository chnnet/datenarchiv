<?php
        session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Rechnung erfassen</title>
    </head>
    <body>
        <?php
 
        if (isset($_POST['software'])) // speichern
        {

            // ***** Parameter auslesen - Seite *****

            $software = $_POST['software'];
            $version = $_POST['version'];
            $lickey = $_POST['lickey'];
            $betriebssystem = $_POST['betriebssystem'];

            // ***** Parameter auslesen session *****
            $host = $_SESSION['host'];
            $benutzer = $_SESSION['benutzer'];
            $passwort = $_SESSION['passwort'];
            $dbname = $_SESSION['dbname'];
            $benutzer_id = $_SESSION['keynr'];

            $con = mysql_connect($host, $benutzer, $passwort);
            mysql_select_db($dbname);

            // Datenbank
            if ($software != null)
            {

                    $result = mysql_query("INSERT INTO software_keys VALUES ('" . $software . "','" . $version . "','" . $lickey . "','" . $betriebssystem . "')");
                    if (!$result) {
                        exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
                    }

            }
            else
            {
	            
            }
        }

?>

<h2>Software Key erfassen</h2>
<br>

<form name="softwarekey_erfassen" action="software_keys.php" target="main" method="post">
<table>
<tr>
		<td>Software</td>
		<td><input name="software" type="text" size="30" maxlength=50></td>
</tr>
<tr>
		<td>Version</td>
		<td><input name="version" type="text" size=30 maxlength=50></td>
</tr>
<tr>
		<td>Key</td>
		<td><input name="lickey" type="text" size=30 maxlength=50></td>
</tr>
<tr>
		<td>Betriebssystem</td>
		<td><input name="betriebssystem" type="text" size=30 maxlength=50></td>
</tr>
</table>

<input type=submit value="Key Speichern"/>
</form>

    </body>
</html>
