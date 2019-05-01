<?php
        session_start();
?>
<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
<?php
	if ($_POST['name'])
	{

	// ***** Parameter infos auslesen *****
	$name = $_POST['name'];
	$pfad_file = $_POST['pfad_file'];
	$pfad_icon = $_POST['pfad_icon'];
	$target = $_POST['target'];

	// ***** Session infos auslesen *****
        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];

	// DB-Connection
	try {
		$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

	} catch (PDOException $ex) {
		die('Die Datenbank ist momentan nicht erreichbar!');
	}


		$ins = $con->prepare('INSERT INTO menu values (\'' . $name . '\',\'' . $pfad_file . '\',\'' . $pfad_icon . '\',\'' . $target . '\')');
		$result = $ins->execute(array($name, $pfad_file, $pfad_icon, $traget))
			or die('Fehler beim Einfügen!');
		if (!$result) {
			exit('Fehler beim Einfügen!');
	} else // Form
        {
?>
           <form name="name" action="action.php" method="post">
            <table>
            <tr>
                    <td>Name</td>
                    <td>
                    <input type=text name="name" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>Pfad File</td>
                    <td>
                    <input type=text name="pfad_file" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>Pfad Icon</td>
                    <td>
                    <input type=text name="pfad_icon" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>Target</td>
                    <td>
                        <input type="radio" name="target"  value="main">main
                        <input type="radio" name="target"  value="_parent">_parent
                    </td>
            </tr>
            </table>

            <input type=hidden name="admin_menu" value="1"/>
            <input type=submit value="Speichern"/>
            </form>
 <?php
        }
?>
    </body>
</html>
