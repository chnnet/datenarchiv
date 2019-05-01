 <?php
    //session_start();
 
	function jsklassids ($hierarchie, $parent, $dbname)
	{

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
		$result = $con->prepare("select s.klass_id,k.bezeichnung from std_klass_hier_strukturen s, std_klassifizierung k where k.klass_id=s.klass_id AND s.klassh_id = " . $hierarchie . " AND s.parent_id= " . $parent . " group by k.bezeichnung");
		$result->execute(array($hierarchie, $parent))
		    or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));

		while ($row = $result->fetch()) {

                    $rownum++;
                    $jsstring = $jsstring . "<option value=\"" . $row['klass_id'] . "\">" . $row['bezeichnung'] . "</option>";

        }
		return $jsstring;
	}
?>