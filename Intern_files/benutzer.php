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
        <title>Administration - Benutzeranlage</title>
    </head>
    <body>
<?php
	if ($_POST['login'])
	{

	// ***** Parameter infos auslesen *****
	$keynr = $_POST['keynr'];
	$login = $_POST['login'];
	$passwort = $_POST['passwort'];
	$vorname = $_POST['Vorname'];
	$nachname = $_POST['Nachname'];
        $kuerzel = $_POST['kuerzel'];

	// ***** Session infos auslesen *****
        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];

	// ***** Verbindugsaufbau zu MySQL *****

		$con = mysql_connect($host, $benutzer, $passwort);
		if (!$con) {
			exit('Connect Error (' . mysql_errno() . ') ' . mysql_error());
		}

		mysql_select_db($dbname);
		$result = mysql_query('INSERT INTO benutzer values (' . $login . ',\'' . $passwort . '\',\'' . $vorname . '\',\'' . $nachname . '\',\'' . $kuerzel . '\')');
		if (!$result) {
			exit('Query Fehler (' . mysql_errno() . ') ' . mysql_error());
		}

	} else // Form
        {
?>
           <form name="name" action="benutzer.php" method="post">
            <table>
            <tr>
                    <td>login</td>
                    <td>
                    <input type=text name="titel" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>Passwort</td>
                    <td>
                    <input type=password name="paswort" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>Passwort (Wdh)</td>
                    <td>
                    <input type=password name="pwd2" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>Vorname</td>
                    <td>
                    <input type=text name="vorname" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>Nachname</td>
                    <td>
                    <input type=text name="nachname" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>K&uum;rzel</td>
                    <td>
                    <input type=text name="kuerzel" size="45" maxlength="45"/>
                    </td>
            </tr>
            </table>

            <input type=hidden name="blgform" value="1"/>
            <input type=submit value="Speichern"/>
            </form>
 <?php
        }
?>
    </body>
</html>
