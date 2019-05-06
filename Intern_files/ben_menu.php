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
        <title>Zuordnung Benutzer - Men&uuml;</title>

<?php
        global $benutzer;
        global $passwort;
        global $dbname;
        global $host;
        global $benutzer_id;
        global $menu_id;
        global $parent_id;
        global $rownum;
        global $fehler;
        global $message;
        global $sbutton;
        global $reihenf;

	// ***** Parameter auslesen session *****
        if (isset($_SESSION['host'])) {
        
            $host = $_SESSION['host'];
	        $benutzer = $_SESSION['benutzer'];
    	    $passwort = $_SESSION['passwort'];
        	$dbname = $_SESSION['dbname'];
		}

	// ***** Parameter auslesen - Seite *****
        if (isset($_POST['benutzer_id'])) {
            $benutzer_id = $_POST['benutzer_id'];
        }
        if (isset($_POST['menu_id'])) {
            $menu_id = $_POST['menu_id'];
        }
        if (isset($_POST['parent_id'])) {
            $parent_id = $_POST['parent_id'];
        } else {
            $parent_id = 0;
        }

        // submit-value auslesen
        if (isset($_POST['Benutzer'])) {
                    // parent_id bei klass_ids = 0
                    $parent_id = 0;

        }
        if (isset($_POST['speichern'])) {

                    if ( $klass_id )
                    {
                            // $sql = "INSERT INTO benutzer_menu VALUES (" . $benutzer_id . "," . $menu_id . "," . $parent_id . ",'" . $reihenf . ")";
                            $sql = "INSERT INTO benutzer_menu VALUES ('?,?,?,?')";
                    }

					// DB-Connection
					try {
						$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

					} catch (PDOException $ex) {
						die('Die Datenbank ist momentan nicht erreichbar!');
					}
                    $result = $con->prepare($sql);
                    $result->execute(array($benutzer_id, $menu_id, $parent_id, $reihenf))
                    	or die ('Fehler beim INSERT!' . htmlspecialchars($result->errorinfo()[2]));
//                        $message = "Satz in Benutzer-Men&uuml; wurde erfolgreich angelegt!";
        }
?>

</head>

<body>

<h2>Benutzer - Zuordnung Men&uuml;punkte</h2>
<?php var_dump($_POST); ?>
<form name="ben_menu" action="ben_menu.php" target="main" method="post">
<table>
<tr>
	<td>Benutzer</td>
	<td>
	<select name="benutzer_id" >
<?php
            // Hierarchiewerte laden bzw. Übergabewert selektieren
			// DB-Connection
			try {
				$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

			} catch (PDOException $ex) {
				die('Die Datenbank ist momentan nicht erreichbar!');
			}
            foreach ($con->query("SELECT keynr, login FROM benutzer order by login") as $row) {
            
                if ($benutzer_id == $row['keynr']) {
                	echo "<option value=\"" . $row['keynr') . "\" selected>" . $row['login'] . "</option>";
                } else {
                    echo "<option value=\"" . $row['keynr'] . "\">" . $row['login'] . "</option>";
                }
            }

?>
        </select>
	</td>
        <td>
        <input type=submit name="Benutzer" value="Benutzermen&uuml; laden"/>
        </td>
</tr>
<tr>
	<td>Parent ID</td>
        <td>
        <select name="parent_id" >
            <option value="0">Top</option>
<?php
        if (isset($benutzer_id)) {
           // Menüeinträge laden $parent setzen und daten laden
			// DB-Connection
			try {
				$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

			} catch (PDOException $ex) {
				die('Die Datenbank ist momentan nicht erreichbar!');
			}
           $result2 = $con->prepare("SELECT m.menu_id, m.name FROM menu m, benutzer_menu b WHERE m.menu_id=b.menu_id and b.benutzer_id=" . $benutzer_id . " and b.parent_id=" . $parent_id . " order by m.name");
           $result2->execute(array($benutzer_id, $parent_id))
		    or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));

				while ($row = $result->fetch()) {
                    echo "<option value=\"" . $row['menu_id'] . "\">" . $row['name'] . "</option>";
                }
        }
?>
        </select>
	</td>
        <td>
        <input type=submit name="menus" value="Ebene tiefer"/>
        </td>
</tr>
<tr>
	<td>Men&uuml;eintrag</td>
	<td>
	<select name="menu_id">
<?php

           // Klass_ids laden $parent setzen und daten laden
			// DB-Connection
			try {
				$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

			} catch (PDOException $ex) {
				die('Die Datenbank ist momentan nicht erreichbar!');
			}
           if ( isset($_POST['menus']) ) {
               $aktion = $_POST['menus'];
           } else {
               $aktion = "Nicht zugeordnet";
           }
           if ($aktion == "Alle") {
               $sql = "SELECT menu_id, name FROM menu order by name";
               $sbutton = "Nicht zugeordnete";
           } else {
               $sql = "SELECT * FROM menu m LEFT JOIN benutzer_menu b ON b.menu_id = m.menu_id WHERE b.benutzer_id IS NULL";
               $sbutton = "Alle";
           }
				$result3 = $con->prepare($sql);
				$result3->execute(array($var1, $var2))
    				or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));

				while ($row = $result3->fetch()) {

                    echo "<option value=\"" . $row['menu_id'] . "\" selected>" . $row['name'] . "</option>";
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
