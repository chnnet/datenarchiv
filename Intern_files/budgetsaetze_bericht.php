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

        if (isset($_POST['haeufigkeit'])) // Auswahl Häufigkeit als Filter berücksichtigen
        {

            // ***** Parameter auslesen - Seite *****

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
} // isset
echo '<h2>Budgetposten Details</h2>';
echo '<br>';

echo '<form name=\"budget_erfassen\" action=\"erf_budget.php\" target=\"main\" method=\"post\">';
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
echo '<table border=1>';
            // ***** Parameter auslesen session *****
            $host = 'localhost';
//Test
            $benutzer = 'root';
            $passwort = '';
            $dbname = 'test';
//            $benutzer_id = $_SESSION['keynr'];

            $con = new mysqli($host, $benutzer, $passwort, $dbname);
//            mysqli_select_db($dbname);

            // Datenbank
//            if ($anmerkung != null)
//            {

                    $result = $con->query("select * from budget where gueltigbis >= YEAR(curdate())*100 + MONTH(curdate())");
                    if (!$result) {
                        exit('MySQL Fehler: (' . mysqli_errno() . ') ' . mysqli_error());
                    }
                    else
                    {
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
		    			} else {
    		    			echo "0 results";
				    	}

                    }

//            }

// isset        }
        
        
?>


</table>
<input type=submit value="Neu filtern"/>

<table>
<?php

?>

</form>

    </body>
</html>
