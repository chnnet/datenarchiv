<?php
        session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Budgetposten Bericht</title>

<?php
//	include 'build_tabelle_array.php';
?>

    </head>
    <body>
        <?php
        // put your code here

echo '<h2>Budgetposten Details</h2>';
echo '<br>';

echo '<form name="budget_bericht" action="budgetsaetze_bericht.php" target="main" method="post">';
echo '<table>';
echo '<tr>';
		echo '<td>H&auml;ufigkeit</td>';
		echo '<td>';
			echo '<input name="haeufigkeit" type="radio" value="A" checked="checked" >Alle';
			echo '<input name="haeufigkeit" type="radio" value="M">Monatlich';
			echo '<input name="haeufigkeit" type="radio" value="Z">Zweimonatlich';
			echo '<input name="haeufigkeit" type="radio" value="Q">Quartal';
			echo '<input name="haeufigkeit" type="radio" value="H">Halbj&auml;hrlich';
			echo '<input name="haeufigkeit" type="radio" value="J">J&auml;hrlich';
			echo '<input name="haeufigkeit" type="radio" value="E">Einmalig';
		echo '</td>';
echo '</tr>';
echo '<tr>';
		echo '<td>G&uuml;ltig</td>';
		echo '<td>';
			echo '<input name="gueltig" type="radio" value="0" checked="checked" >aktiv';
			echo '<input name="gueltig" type="radio" value="1">Alle';
		echo '</td>';
echo '</tr>';
echo '</table>';


echo '<table>';
echo '<input type=submit value="Neu filtern"/>';
echo '</table>';

echo '<table border=1>';

            // ***** Parameter auslesen session *****
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

        if (isset($_POST['haeufigkeit'])) // Auswahl Häufigkeit als Filter berücksichtigen
        {

            // ***** Parameter auslesen - Seite *****
            $haeufigkeit = $_POST['haeufigkeit'];
            $gueltig = $_POST['gueltig'];
            $result = $con->prepare("select * from budget where gueltigbis >= YEAR(curdate())*100 + MONTH(curdate()) and haeufigkeit = '" . $haeufigkeit . "' order by haeufigkeit, ktonr");            
			$result->execute(array($haeufigkeit))
			    or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));

        } else { // Filter gesetzt

            
            $result = $con->query("select * from budget where gueltigbis >= YEAR(curdate())*100 + MONTH(curdate()) order by haeufigkeit, ktonr");
            
		} // isset

            // Datenbank
//            if ($anmerkung != null)
//            {
					$summe = 0;
                    if ($result) {
                    	// result

			    		if ($result->num_rows > 0) {
			    			// Headers
			    			echo '<tr>';
			    			$feldnamen = $result->fetch_fields();
			    			$feldanzahl = 0;
			    			foreach($feldnamen as  $val) {
			    				echo '<th>' . $val->name . '</th>';
			    				$felder[$feldanzahl] = $val->name;
			    				$feldanzahl++;
			    			}
			    			echo '</tr>';
    					    // output data of each row
	    			    	while($row = $result->fetch_assoc()) {
				    			echo '<tr>';
				    			if ($row['ktonr'] < 20000)
				    			{
									$summe = $summe + $row['betrag'];		
				    			} else {
									$summe = $summe - $row['betrag'];		
				    			}
	    			    		$zaehler = 0;
	    			    		foreach ($row as $val) {

	    			    			while ($zaehler < $feldanzahl) {
		    			    			echo '<td>' . $row[$felder[$zaehler]] . '</td>';
		    			    			$zaehler++;
	    			    			}
	    			    		}
				    			echo '</tr>';
// Feldwerte korrekt auslesen        		    			echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
		        			}
		        			echo '</table>';
		        			echo 'Gesamtsumme: ' . $summe;
		    			} else {
    		    			echo "0 results";
				    	}

                    }

//            }

// isset        }
        
        
?>

</form>

    </body>
</html>
