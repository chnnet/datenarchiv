 <?php
 
	function distinctSelect ($tabelle, $feld)
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
		
		$result = $con->prepare("select distinct " . $feld . " from " . $tabelle . " order by " . $feld);
		$result->execute(array($feld, $tabelle))
		    or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()));

            $rownum=0;
            while ($row = $result->fetch()) {

                    $rownum++;
                    // check Syntax 0,1 bei fetch falls notwendig
                    $jsstring .= "<option value=\"" . $row[0] . "\">" . $row[0] . "</option>";

            }
            
		return $jsstring;
	}
?>