<?php
        session_start();
?>
<html>
<head>
	<title>Klassifizierung Zuordnung</title>

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

        global $benutzer;
        global $passwort;
        global $dbname;
        global $host;
        global $klass_id;
        global $klassh_id;
        global $parent_id;
        global $rownum;
        global $fehler;
        global $message;
        global $sbutton;

	// ***** Parameter auslesen session *****
        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];


	// ***** Parameter auslesen - Seite *****
        if (isset($_POST['klassh_id'])) {
            $klassh_id = $_POST['klassh_id'];
        }
        if (isset($_POST['klass_id'])) {
            $klass_id = $_POST['klass_id'];
        }
        if (isset($_POST['parent_id'])) {
            $parent_id = $_POST['parent_id'];
        } else {
            $parent_id = 0;
        }

        // submit-value auslesen
        if (isset($_POST['Hierarchie'])) {
                    // parent_id bei klass_ids = 0
                    $parent_id = 0;

        }
        if (isset($_POST['speichern'])) {

                    if ( $klass_id )
                    {
                            $sql = "INSERT INTO std_hier_strukturen VALUES (" . $klassh_id . "," . $klass_id . "," . $parent_id . ",'" . $datum . "','" . $datum . "')";
                    }

				// DB-Connection
				try {
					$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

				} catch (PDOException $ex) {
					die('Die Datenbank ist momentan nicht erreichbar!');
				}
                    $result = $con->execute($sql);
					    or die ('Fehler ...');
                    $message = "Satz in File-Archiv wurde erfolgreich angelegt!";
        }
?>

</head>

<body>

<h2>Klassifizierung - Zuordnung</h2>

<form name="hier_zuo" action="hier_zuo.php" target="main" method="post">
<table>
<tr>
	<td>Hierarchie</td>
	<td>
	<select name="klassh_id" >
<?php
            // Hierarchiewerte laden bzw. Übergabewert selektieren
			// DB-Connection
			try {
				$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

			} catch (PDOException $ex) {
				die('Die Datenbank ist momentan nicht erreichbar!');
			}
            $result = mysql_query("SELECT klassh_id, bezeichnung FROM std_klass_hierarchien order by bezeichnung");
            if (!$result)
            {
                //exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
                $fehler = "MySQL Fehler: (" . mysql_errno() . ") " . mysql_error();
            }
            else
            {
                $rownum=0;
                while ($row = $result->fetch()) {

                        $rownum++;
                        if ($klassh_id == $row['klassh_id']) {
                            echo "<option value=\"" . $row['klassh_id'] . "\" selected>" . $row['bezeichnung'] . "</option>";
                        } else {
                            echo "<option value=\"" . $row['klassh_id'] . "\">" . $row['bezeichnung'] . "</option>";
                        }
                }
            }
?>
        </select>
	</td>
        <td>
        <input type=submit name="Hierarchie" value="Hierarchie laden"/>
        </td>
</tr>
<tr>
	<td>Parent ID</td>
        <td>
        <select name="parent_id" >
<?php
        if (isset($klassh_id)) {
           // Klass_ids laden $parent setzen und daten laden
			// DB-Connection
			try {
				$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

				} catch (PDOException $ex) {
					die('Die Datenbank ist momentan nicht erreichbar!');
			}
           $result2 = $con->prepare("SELECT h.klass_id, k.bezeichnung FROM std_klassifizierung k, std_klass_hier_strukturen h WHERE h.klass_id=k.klass_id and h.klassh_id=" . $klassh_id . " and parent_id=" . $parent_id . " order by k.bezeichnung");
			$result2->execute(array($klassh_id, $parent_id))
			    or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));
                while ($row = $result2->fetch()) {
                    echo "<option value=\"" . $row['klass_id'] . "\">" . $row['bezeichnung'] . "</option>";
                }
        }
?>
        </select>
	</td>
        <td>
        <input type=submit name="klassids" value="Ebene tiefer"/>
        </td>
</tr>
<tr>
	<td>klass_id</td>
	<td>
	<select name="klass_id">
<?php

           // Klass_ids laden $parent setzen und daten laden
			// DB-Connection
			try {
				$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

				} catch (PDOException $ex) {
					die('Die Datenbank ist momentan nicht erreichbar!');
			}

           if ( isset($_POST['alleids']) ) {
               $aktion = $_POST['alleids'];
           } else {
               $aktion = "Nicht zugeordnet";
           }
           if ($aktion == "Alle") {
               $result3 = $con->query("SELECT klass_id, bezeichnung FROM std_klassifizierung order by bezeichnung");
               $sbutton = "Nicht zugeordnete";
           } else {
               $result3 = $con->query("SELECT * FROM std_klassifizierung k LEFT JOIN std_klass_hier_strukturen h ON k.klass_id = h.klass_id WHERE h.klassh_id IS NULL");
               $sbutton = "Alle";
           }
            while ($row = $result3->fetch()) {

                echo "<option value=\"" . $row['klass_id'] . "\" selected>" . $row['bezeichnung'] . "</option>";

            }
?>
        </select>
	</td>
        <td>
        <input type=submit name="alleids" value="<?php echo $sbutton ?>"/>
        </td>
</tr>
</table>
<br>
<b>
<?php echo $message ?>
<?php echo $fehler ?>
</b>
<br>
<input type=submit name="speichern" value="Satz speichern"/>
</form>

</body>
</html>

