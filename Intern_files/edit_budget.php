<?php
//        session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Budgetposten erfassen</title>

<?php
	include 'build_tabelle_array.php';
?>

    </head>
    <body>
		<h2>Budgetposten editieren</h2>
		<br>

    <?php
        // put your code here

        if (isset($_POST['lfdnr'])) // speichern
        {

            // ***** Parameter auslesen - Seite *****

            $anmerkung = $_POST['lfdnr'];
            $anmerkung = $_POST['anmerkung'];
            $budget_id = $_POST['budget_id'];
            $konto = $_POST['konto'];
            $betrag_dec = $_POST['betrag_dec'];
            $betrag_int = $_POST['betrag_int'];
            $haeufigkeit = $_POST['haeufigkeit'];
            $gueltigab = $_POST['gueltigab'];
            $gueltigbis = $_POST['gueltigbis'];;
            $betrag = ($betrag_dec / 100 ) + $betrag_int;

            // String sql = "";
            // int test;
            //global $max_vid;
/*
            // ***** Parameter auslesen session *****
            $host = $_SESSION['host'];
            $benutzer = $_SESSION['benutzer'];
            $passwort = $_SESSION['passwort'];
            $dbname = $_SESSION['dbname'];
            $benutzer_id = $_SESSION['keynr'];
*/

//Test
            $host = 'localhost';
            $benutzer = 'root';
            $passwort = '';
            $dbname = 'test';

//            $con = mysqli_connect($host, $benutzer, $passwort);
//            mysql_select_db($dbname);
	        $con = new mysqli($host, $benutzer, $passwort, $dbname);




 // ToDo Link zu lfdnr hinzufügen
 				$summe = 0;
                $result = $con->query($sql);
                if (!$result) {
                    exit('MySQL Fehler: (' . mysqli_errno() . ') ' . mysqli_error());
                }  else   {
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
			    			// Typ!
			    			if ($row['ktonr'] < 20000)
			    			{
								$summe = $summe + $row['betrag'];		
			    			} else {
								$summe = $summe - $row['betrag'];		
			    			}
	   			    		$zaehler = 0;
	   			    		foreach ($row as $val) {

    			    			while ($zaehler < $feldanzahl) {
									if ($zaehler = 0) // key
									{
		    			    			echo '<a href=\"edit_budget.php?lfdnr=' . $row[$felder[$zaehler]] . '\">' . $row[$felder[$zaehler]] . '</a>';
		    			    			$zaehler++;
									} else {

		    			    			echo '<td>' . $row[$felder[$zaehler]] . '</td>';
		    			    			$zaehler++;
									
									}
    			    			}
    			    		}
			    			echo '</tr>';
	        			}
	        			echo '</table>';
	        			echo 'Gesamtsumme: ' . $summe;
	    			} else {
   		    			echo "0 results";
			    	}
                }


// ToDo auf Update ändern

            // Datenbank
            if ($lfdnr != null)
            {

				echo '<form name="budget_editieren" action="edit_budget.php" target="main" method="post">';
				echo '<table>';
				echo '<tr>';
					echo '<td>Budget-ID</td>';
					echo '<td><input name="budget_id" type="text" size="3" value="1" /></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>H&auml;ufigkeit</td>';
				echo '<td>';
					echo '<input name="haeufigkeit" type="radio" value="M">Monatlich';
					echo '<input name="haeufigkeit" type="radio" value="Z">Zweimonatlich';
					echo '<input name="haeufigkeit" type="radio" value="Q">Quartal';
					echo '<input name="haeufigkeit" type="radio" value="H">Halbj&auml;hrlich';
					echo '<input name="haeufigkeit" type="radio" value="J">J&auml;hrlich';
					echo '<input name="haeufigkeit" type="radio" value="E">Einmalig';
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>G&uuml;ltig ab</td>';
					echo '<td><input name="gueltigab" type="text" size="6" /></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>G&uuml;ltig bis</td>';
					echo '<td><input name="gueltigbis" type="text" size="6" /></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Konto</td>';
					echo '<td>';
						echo '<select name="konto">';

							$optionen = jsklassids ("kontenstamm","ktonr","bezeichnung");
							echo $optionen;			
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Betrag</td>';
					echo '<td><input name="betrag_int" type="text" size="7" />,<input name="betrag_dec" type="text" size="2" /></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Anmerkung</td>';
					echo '<td><input name="anmerkung" type="text" size=30 maxlength=50></td>';
				echo '</tr>';
			echo '</table>';
				echo '<input type="hidden" name="lfdnr"/>';
				echo '<input type=submit value="Posten Speichern"/>';
			echo '</form>';


                    $result = mysqli_query($con, "INSERT INTO budget (budget_id,gueltigab,gueltigbis,ktonr,betrag,haeufigkeit,anmerkung) VALUES (" . $budget_id . "," . $gueltigab . "," . $gueltigbis . ","  . $konto . "," . $betrag . ",'" . $haeufigkeit . "','" . $anmerkung . "')");
                    if (!$result) {
                        exit('MySQL Fehler: (' . mysqli_errno($con) . ') ' . mysqli_error($con));
                    }
                    else
                    {
                    	echo "Satz gespeichert: " . $haeufigkeit . " " . $konto . " " . $betrag . " " . $anmerkung;
                    }

            }
        }
// ToDo abhängig von lfdnr Liste oder edit-Form

?>

    </body>
</html>
