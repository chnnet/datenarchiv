<?php
        session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Erfassen menu</title>
    </head>
    <body>
        <?php

        if (isset($_POST['menu_id'])) // speichern
        {

            // ***** Parameter auslesen - Seite *****

            $menu_id = $_POST['menu_id'];
            $name = $_POST['name'];
            $pfad_file = $_POST['pfad_file'];
            $pfad_icon = $_POST['pfad_icon'];
            $target = $_POST['target'];

            // ***** Parameter auslesen session *****
            $host = $_SESSION['host'];
            $benutzer = $_SESSION['benutzer'];
            $passwort = $_SESSION['passwort'];
            $dbname = $_SESSION['dbname'];
            $benutzer_id = $_SESSION['keynr'];

            $con = mysql_connect($host, $benutzer, $passwort);
            mysql_select_db($dbname);

            // Datenbank
            if ($text != null)
            {
					// prüfen ob für menu_id schon Wert eingetragen
                    $result = mysql_query("select max(menu_id) from menu");
                    $row = mysql_fetch_row($result);
                    $max_menuid = $row[0];
                    mysql_free_result($result);

					$result = mysql_query("INSERT INTO menu VALUES (" . $menu_id . ",'" . $name . "','" . $pfad_file . "','" . $pfad_icon . "','" . $target . "')");
					if (!$result) {
						exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
					}
            }
		}

?>

<h2>menu erfassen</h2>
<br>

<form name="menu_erfassen" action="erf_menu.php" target="main" method="post">
<table>
<tr>
		<td>menu_id</td>
		<td><input name="menu_id" type="text" size="6" value="<?php echo $max_menuid; ?>" /></td>
</tr>
<tr>
		<td>name</td>
		<td><input name="name" type="text" size="20" /></td>
</tr>
<tr>
		<td>Pfad File</td>
		<td><input name="pfad_file" type="text" size="150" /></td>
</tr>
<tr>
		<td>Pfad icon</td>
		<td><input name="pfad_icon" type="text" size="50" /></td>
</tr>
<tr>
		<td>target</td>
		<td><input name="target" type="text" size="7" value="<?php echo $target ?>" /></td>
</tr>
</table>

<input type=submit value="Speichern"/>
</form>

    </body>
</html>
