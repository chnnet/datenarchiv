<?php
        session_start();
?>
<html>
<head>
	<title>Film Details</title>

</head>

<body>

<h2>Film-Suche</h2>

<?php

	if ($_GET['spielfilme_id'])
	{

	// ***** Parameter infos auslesen *****
	$filmID = $_GET['spielfilme_id'];
	$titel = $_GET['titel'];
	//$klass_id = $_POST['klass_id'];
	$schlagw = $_GET['beschreibung'];
	$original = $_GET['originalversion'];

	//echo $filmID;

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

		if ($filmID)
		{
			$result = $con->prepare('SELECT * from spielfilme s, std_klassifizierung k where s.genre=k.klass_id and s.spielfilme_id = ' . $filmID);
			$result->execute(array($filmID));
		}
		
		if (!$result) {
			exit('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));
		} else
                {
                    echo "<table border=1>";

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
                            echo "<tr><td>Nummer</td><td>" . $row['spielfilme_id'] . "</td><tr><td>Titel</td><td>" . $row['titel'] . "</td></tr><tr><td>Jahr</td><td>" . $row['jahr'] . "</td></tr><tr><td>Genre</td><td>" . $row['bezeichnung'] . "</td></tr><tr><td>Beschreibung</td><td>" . $row['beschreibung'] . "</td></tr><tr><td>Originalversion</td><td align=center>" . $originaltab . "</td></tr>";
                            $i++;
                    }
                    echo "</table><br>";
                    echo "<a href=\"film_suche.php?titel=" . $titel . "&beschreibung=" . $schlagw . "&originalverion=" . $original . "\">Zur&uuml;ck zur Suche" . "</a>";
                    echo "<br>";               }

	} // else Form, Klammern rausgenommen und else auskommentiert
?>

</body>
</html>

