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
        <title>Klassifizierung anlegen</title>
    </head>
    <body>
        <h1>Klassifizierung anlegen</h1>
<?php
        global $klass_id;

	// ***** Session infos auslesen *****
        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];

        if ($klass_id)
	{
	// ***** Parameter infos auslesen *****
        	$klass_id = $_POST['klass_id'];
        	$bezeichnung = $_POST['bezeichnung'];

	// ***** Verbindugsaufbau zu MySQL *****

		$con = mysql_connect($host, $benutzer, $passwort);
		if (!$con) {
			exit('Connect Error (' . mysql_connect_errno() . ') ' . mysql_connect_error());
		}
                $datum = date("yyyy-mm-dd", $timestamp);

		mysql_select_db($dbname);
		$result = mysql_query('INSERT INTO std_klassifizierung values (' . $klass_id . ',' . $bezeichnung . ',' . $datum . ',' . $datum . ')');
		if (!$result) {
			exit('Query Fehler (' . mysql_connect_errno() . ') ' . mysql_connect_error());
		}

	} else // Form
        {

                $con = mysql_connect($host, $benutzer, $passwort);
		if (!$con) {
			exit('Connect Error (' . mysql_connect_errno() . ') ' . mysql_connect_error());
		}
		mysql_select_db($dbname);

                // max klass_id suchen
                $timestamp = time();
                $datum = date("Y-m-d", $timestamp);
                echo "Datum: " . $datum;
                if (!$klass_id)
                {
                    $result = mysql_query("select max(klass_id) from std_klassifizierung");
                    $row = mysql_fetch_row($result);
                    $klass_id = $row[0];
                    $klass_id++;
                    mysql_free_result($result);

                } else
                {
                    $klass_id++;
                }
?>
           <form name="Klassifizierungen" action="klass_id.php" method="post">
            <table><tr><td>Zuordnung</td><td>
      <?php
            // Hierarchie aus DB lesen
            $con = mysql_connect($host, $benutzer, $passwort);
            if (!$con) {
                    exit('Connect Error (' . mysql_errno() . ') ' . mysql_error());
            }
            mysql_select_db($dbname);
            $result = mysql_query('SELECT klassh_id, bezeichnung from std_klass_hierarchien;');
            if (!$result) {
                    exit('Query Fehler (' . mysql_errno() . ') ' . mysql_error());
            } else
            {
                $num=mysql_numrows($result);
                echo "<select name=\"klassh_id\">";

                $i=0;
                while ($i < $num) {

                        // Suchergebnis in Liste anzeigen
                        echo "<option value=\"" . mysql_result($result,$i,"klassh_id") . "\">" . mysql_result($result,$i,"bezeichnung") . "</option>";
                        $i++;
                }
                echo "</select>";
            }
      ?>
                    </td>
            <tr>
                    <td>klass_id</td>
                    <td>
                    <input type=text name="klass_id" size="10" maxlength="10" value="<?php echo $klass_id ?>" readonly/>
                    </td>
            </tr>
            <tr>
                    <td>Bezeichnung</td>
                    <td>
                    <input type=text name="bezeichnung" size="10" maxlength="10"/>
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
