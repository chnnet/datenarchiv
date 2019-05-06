<?php
        session_start();
?>
<html>
<head>
	<title>Sensorendaten - Temperatur</title>

</head>

<body>

<h2>Temperatur</h2>

<?php

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
		if (isset($_POST['standort_id']))
		{
			// Standort gewählt, Bericht nach gewählten Typ
			// ***** Parameter infos auslesen *****
			$standort_id = $_POST['standort_id'];
			$berichtstyp = $_POST['berichtstyp'];
			$datum = $_POST['datum'];

			if ($berichtstyp) {
				
				// MinMax
				if ($berichtstyp == 1) {
						
//debug					echo "Typ: " . $berichtstyp;
					$result = $con->query('select min(Temperatur) as min,max(Temperatur) as max, date(datum_zeit) as datum from temperatur where standort = ' . $standort_id . ' group by date(datum_zeit) order by date(datum_zeit) desc');
					
					if (!$result) {
						exit('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));
					} else
					{
			        	//echo "<h3>" . $datum . "</h3>";
						echo "<a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a><br><br>";
						echo "<table border=\"1\"><tr><th>Datum</th><th>Min</th><th>Max</th></tr>";
						while ($row = $result->fetch()) {
								
		                	// Suchergebnis in Liste anzeigen
							echo "<tr><td>" . $row['datum'] . "</td><td>" . $row['min'] . "</td><td>" . $row['max'] . "</td></tr>";
		            	}
						echo "</table>";
						echo "<br><br><a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a>";
		
					}		
				} // Ende MinMax
				
				// datum detail
				if ($berichtstyp == 2) {
						
//debug					echo "Typ: " . $berichtstyp;
					$result = $con->query('select datum_zeit, temperatur from temperatur where standort = ' . $standort_id . ' and date(datum_zeit) = \'' . $datum . '\' order by datum_zeit');
					
					if (!$result) {
						exit('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));
					} else
					{
			        	echo "<h3>" . $datum . " " . $standort . "</h3>";
						echo "<a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a><br><br>";
						echo "<table border=\"1\"><tr><th>Datum/Zeit</th><th>Temperatur</th></tr>";
						while ($row = $result->fetch()) {
								
		                	// Suchergebnis in Liste anzeigen
							echo "<tr><td>" . $row['datum_Zeit'] . "</td><td>" . $row['temperatur'] . "</td></tr>";
		            	}
						echo "</table>";
						echo "<br><br><a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a>";
		
					}		
				} // Ende Datum detail
								
				//Datum
				if ($berichtstyp == 3) {
						
//debug					echo $datum;
					$result2 = $con->query('select s.bezeichnung, date(t.datum_zeit), min(t.Temperatur), max(t.Temperatur) from temperatur t, standort s where t.standort=s.standort_id and date(t.datum_zeit) = \'' . $datum . '\' group by date(t.datum_zeit),s.bezeichnung order by s.standort_id');
					
					if (!$result2) {
						exit('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));
					} else
					{
			        	//echo "<h3>" . $datum . "</h3>";
						echo "<a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a><br><br>";
						$num=mysql_numrows($result2);
//debug						echo $num;
						echo "<table border=\"1\"><tr><th>Standort</th><th>Datum</th><th>Min</th><th>Max</th></tr>";
						$i=0;
						while ($row = $result->fetch()) {
								
		                	// Suchergebnis in Liste anzeigen
/*
							echo "<tr><td>". mysql_result($result2,$i,"s.bezeichnung") . "</td><td>" . mysql_result($result2,$i,"date(t.datum_zeit)") . "</td><td>" . mysql_result($result2,$i,"min(t.Temperatur)") . "</td><td>" . mysql_result($result2,$i,"max(t.Temperatur)") . "</td></tr>";
*/
							echo "<tr><td>". $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>";
							$i++;
		            	}
						echo "</table>";
						echo "<br><br><a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a>";
		
					}		
				} // Ende Datum
         	}
		} else { // Standort nicht gesetzt
				echo "<form name=\"temperatur\" action=\"Temperatur_minmax.php\" target = \"main\" method= \"post\">";
				echo "<table><tr><td><select name=\"standort_id\">";
				
				$result = $con->query("select standort_id, bezeichnung from standort");
	                if ($result)
	                {
	                    while ($row = $result->fetch()) {
	                            echo "<option value = \"" . $row['standort_id'] . "\" >" . $row['bezeichnung'] . "</option>";
	                    }
	                }
	
				echo "</select></td></tr>";
				
				echo "<select name=\"datum\">";
				
				$result = $con->query("select distinct date(datum_zeit) as datum from temperatur order by date(datum_zeit) desc");
	                if ($result)
	                {
	                    while ($row = $result->fetch()) {
	                            echo "<option value = \"" . $row['datum'] . "\" >" . $row['datum'] . "</option>";
	                    }
	                }
	
				echo "</select></td></tr>";
				
				echo "<tr><td>
				<input type=\"radio\" name=\"berichtstyp\" value=\"1\">MinMax
				<input type=\"radio\" name=\"berichtstyp\" value=\"2\">Datum
				<input type=\"radio\" name=\"berichtstyp\" value=\"3\">Vergleich</td>";
				
				echo "<tr><td><input name=\"suchen\" type=submit value=\"Anzeigen\" /></td></tr></table></form>";
		}
		

// else Form, Klammern rausgenommen und else auskommentiert
?>

</body>
</html>

