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
<?php


?>
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

	// DB-Connection
    try {
        $con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
    } catch (PDOException $ex) {
        die('Die Datenbank ist momentan nicht erreichbar!');
    }
     $datum = date("yyyy-mm-dd", $timestamp);

		$result = $con->prepare('INSERT INTO std_klassifizierung values (?,?,?,?)');
        $result->execute(array($klass_id, $bezeichnung, $datum, $datum ))
        or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));

	} else // Form
        {

    try {
        $con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
    } catch (PDOException $ex) {
        die('Die Datenbank ist momentan nicht erreichbar!');
    }

                // max klass_id suchen
                $timestamp = time();
                $datum = date("Y-m-d", $timestamp);
                echo "Datum: " . $datum;
                if (!$klass_id)
                {
                    $result = $con->query("select max(klass_id) from std_klassifizierung");
                    $klass_id = $result->fetchColumn();
                    $klass_id++;

                } else
                {
                    $klass_id++;
                }
?>
           <form name="Klassifizierungen" action="klass_id.php" method="post">
            <table><tr><td>Struktur</td><td>
      <?php
            // Hierarchie aus DB lesen
		    try {
        		$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
		    } catch (PDOException $ex) {
        		die('Die Datenbank ist momentan nicht erreichbar!');
    		}
            $result = $con->query('SELECT klassh_id, bezeichnung from std_klass_hierarchien;');
            echo "<select name=\"klassh_id\">";

                while ($row = $result->fetch()) {

                        // Suchergebnis in Liste anzeigen
                        echo "<option value=\"" . $row['klassh_id'] . "\">" . $row['bezeichnung'] . "</option>";
                }
                echo "</select>";
            }
      ?>
                    </td>
                    <tr>
                    	<td>Parent</td>
                    <td>
			<select name="klass_parent">
            </select>
                    </td>

                    </tr>
                    <tr>
                    	<td>Klassifizierung</td>
                    <td>
			<select name="klass_id">
            </select>
                    </td>
               </tr>
            <tr>
                    <td>klass_id</td>
                    <td>
                    <input type=text name="klass_id" size="10" maxlength="10" value="<?php echo $klass_id ?>" readonly/>
                    </td>
                    <td>
            <input type=submit value="Speichern"/>    </td>             </tr>
            <tr>
                    <td>Bezeichnung</td>
                    <td>
                    <input type=text name="bezeichnung" size="30" maxlength="30"/>
                    </td>
            </tr>
            </table>

            <input type=hidden name="blgform" value="1"/>
            <input type=submit value="Zuordnen"/>
            </form>

    </body>
</html>
