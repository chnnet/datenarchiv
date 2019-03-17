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
        <?php
        // put your code here

        if (isset($_POST['lfdnr'])) // speichern
        {

            // ***** Parameter auslesen - Seite *****

            $lfdnr = $_POST['lfdnr'];
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

            $sql = ("select * from budget where gueltigbis >= YEAR(curdate())*100 + MONTH(curdate()) and lfdnr = '" . $lfdnr . "' order by ktonr, haeufigkeit");            
            $result = $con->query($sql);
            if (!$result) {
                exit('MySQL Fehler: (' . mysqli_errno() . ') ' . mysqli_error());
            }  else   {

// edit
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

// ToDo auf Update ändern

            // Datenbank
            if ($lfdnr != null)
            {

					$sql = "UPDATE budget  set budget_id=" . $budget_id . ", gueltigab=" . $gueltigab . ", gueltigbis=" . $gueltigbis  . ", ktonr=" . $konto  . ",betrag=" . $betrag  . ",haeufigkeit=" . $haeufigkeit  . ",anmerkung=" . $anmerkung . ")";
                    $result = mysqli_query($con, $sql);
                    if (!$result) {
                        exit('MySQL Fehler: (' . mysqli_errno($con) . ') ' . mysqli_error($con));
                    }
                    else
                    {
                    	echo "Satz ge&auml;ndert: " . $lfdnr . " " . $haeufigkeit . " " . $konto . " " . $betrag . " " . $anmerkung;
                    }



		} else {

            $sql = ("select * from budget where gueltigbis >= YEAR(curdate())*100 + MONTH(curdate()) order by ktonr, haeufigkeit");            
				
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
	        			}
	        			echo '</table>';
	        			echo 'Gesamtsumme: ' . $summe;
	    			} else {
   		    			echo "0 results";
			    	}
                }
		} // Ende Liste
 
            }
        }
// ToDo abhängig von lfdnr Liste oder edit-Form

?>

    </body>
</html>
