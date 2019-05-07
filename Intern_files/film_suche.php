<?php
        session_start();
?>
<html>
<head>
	<title>Film-Suche</title>

</head>

<body>

<h2>Film-Suche</h2>

<?php

	if (isset($_POST['titel']) || isset($_POST['originalversion']))
	{

	// ***** Parameter infos auslesen *****
	$titel = $_POST['titel'];
	//$klass_id = $_POST['klass_id'];
	$schlagw = $_POST['beschreibung'];
	$original = $_POST['originalversion'];

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
		
		if ($titel)
		{
				$result = $con->query('SELECT s.spielfilme_id, s.titel, k.bezeichnung, s.jahr, s.originalversion from spielfilme s, std_klassifizierung k where s.klass_id=k.klass_id and s.titel like \'%' . $titel . '%\'');
		}
		else {
				// switch
				switch ($original) {
					
					case 0:
						break;
					case 1:
						$result = $con->query('SELECT s.spielfilme_id, s.titel, k.bezeichnung, s.jahr, s.originalversion from spielfilme s, std_klassifizierung k where s.klass_id=k.klass_id and s.originalversion = 1 order by s.titel');
						break;
					case 2:
						$maxID = $con->query('SELECT max(spielfilme_id) from spielfilme');
						$top100 = $maxID->fetchColumn();
						$top100 = $top100 - 100;
						$result = $con->query('SELECT s.spielfilme_id, s.titel, k.bezeichnung, s.jahr, s.originalversion from spielfilme s, std_klassifizierung k where s.klass_id=k.klass_id and s.spielfilme_id > ' . $top100 . ' order by s.spielfilme_id desc');
						break;
					case 3:
						$result = $con->query('SELECT s.spielfilme_id, s.titel, k.bezeichnung, s.jahr, s.originalversion from spielfilme s, std_klassifizierung k where s.klass_id=k.klass_id and s.originalversion = 1 order by s.spielfilme_id desc limit 100');
						break;
					
				}
								
			}
			
			// Ausgabe
			if (!$result) {
				exit('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));
			} else
	                {
	                    echo "<table border=\"1\"><tr><th>Details</th><th>Ursprung</th><th>Titel</th><th>Jahr</th><th>OV</th></tr>";
	
	                    while ($row = $result->fetch()) {
								
								// Originalversion
								$originaltab = $row['originalversion'];
								if ($originaltab <> 0) { 
									$originaltab = "x";
									}
								else { 
									$originaltab = "";
									}
								
	                            // Suchergebnis in Liste anzeigen
	                            echo "<tr><td><a href=\"film_suche_details.php?spielfilme_id=" . $row['spielfilme_id'] . "\">" . $row['spielfilme_id'] . "</a></td><td>" . $row['bezeichnung'] . "</td><td>" . $row['titel'] . "</td><td>" . $row['jahr'] . "</td><td align=center>" . $originaltab . "</td></tr>";
	                    }
	                    echo "</table>";
	                    echo "<br>";               }

		} // zu if titel
?>
           <form name="film_suche" action="film_suche.php" method="post">
            <table>
            <tr>
                    <td>Titel</td>
                    <td>
                    <input type=text name="titel" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>Beschreibung</td>
                    <td>
                    <input type=text name="beschreibung" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>Listen</td>
                    <td>
                    <input type=radio name="originalversion" value=1> OV
                    <input type=radio name="originalversion" value=2> Last 100
                    <input type=radio name="originalversion" value=3> Last 100 OV
                    </td>
            </tr>
            </table>

            <input type=hidden name="blgform" value="1"/>
            <input type=submit value="Filme suchen"/>
            </form>
</body>
</html>

