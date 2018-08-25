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

	// ***** Verbindugsaufbau zu MySQL *****

		$con = mysql_connect($host, $benutzer, $passwort);
		if (!$con) {
			exit('Connect Error (' . mysql_connect_errno() . ') ' . mysql_connect_error());
		}

		mysql_select_db($dbname);
		$result = mysql_query('INSERT INTO menu values (\'' . $name . '\',\'' . $pfad_file . '\',\'' . $pfad_icon . '\',\'' . $target . '\')');
		if (!$result) {
			exit('Query Fehler (' . mysql_connect_errno() . ') ' . mysql_connect_error());
		} else
                {
                    $num=mysql_numrows($result);
                    echo "<table border=\"1\"><tr><th>Klassifizierung</th><th>Titel</th><th>Filename</th><th>Datum</th><th>Quelle</th></tr>";

                    $i=0;
                    while ($i < $num) {

                            // Suchergebnis in Liste anzeigen
                            echo "<tr><td>" . mysql_result($result,$i,"k.bezeichnung") . "</td><td>" . mysql_result($result,$i,"f.Titel") . "</td><td>" . mysql_result($result,$i,"f.Filename") . "</td><td>" . mysql_result($result,$i,"f.datum") . "</td><td>" . mysql_result($result,$i,"f.Quelle") . "</td></tr>";
                            $i++;
                    }
                    echo "</table>";
                }

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

            <input type=hidden name="blgform" value="1"/>
            <input type=submit value="Speichern"/>
            </form>
 <?php
        }
?>
    </body>
</html>
