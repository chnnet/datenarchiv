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

	// ***** Parameter infos auslesen *****
	$standort_id = $_POST['standort_id'];
	$berichtstyp = $_POST['berichtstyp'];
	$datum = $_POST['datum'];

	// ***** Session infos auslesen *****

        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];
        
	// ***** Verbindugsaufbau zu MySQL *****

		$con = mysql_connect($host, $benutzer, $passwort);
		if (!$con) {
			exit('Connect Error (' . mysql_connect_errno() . ') ' . mysql_connect_error());
		}

		mysql_select_db($dbname);
		if ($standort_id)
		{
			// Standort gewählt, Bericht nach gewählten Typ
			
			if ($berichtstyp) {
				
				// MinMax
				if ($berichtstyp == 1) {
						
//debug					echo "Typ: " . $berichtstyp;
					$result = mysql_query('select min(Temperatur),max(Temperatur), date(datum_zeit) from temperatur where standort = ' . $standort_id . ' group by date(datum_zeit) order by date(datum_zeit) desc');
					
					if (!$result) {
						exit('Query Fehler (' . mysql_connect_errno() . ') ' . mysql_connect_error());
					} else
					{
			        	//echo "<h3>" . $datum . "</h3>";
						echo "<a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a><br><br>";
						$num=mysql_numrows($result);
						echo "<table border=\"1\"><tr><th>Datum</th><th>Min</th><th>Max</th></tr>";
						$i=0;
						while ($i < $num) {
								
		                	// Suchergebnis in Liste anzeigen
							echo "<tr><td>" . mysql_result($result,$i,"date(datum_Zeit)") . "</td><td>" . mysql_result($result,$i,"min(Temperatur)") . "</td><td>" . mysql_result($result,$i,"max(Temperatur)") . "</td></tr>";
							$i++;
		            	}
						echo "</table>";
						echo "<br><br><a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a>";
		
					}		
				} // Ende MinMax
				
				// datum detail
				if ($berichtstyp == 2) {
						
//debug					echo "Typ: " . $berichtstyp;
					$result = mysql_query('select datum_zeit, temperatur from temperatur where standort = ' . $standort_id . ' and date(datum_zeit) = \'' . $datum . '\' order by datum_zeit');
					
					if (!$result) {
						exit('Query Fehler (' . mysql_connect_errno() . ') ' . mysql_connect_error());
					} else
					{
			        	echo "<h3>" . $datum . " " . $standort . "</h3>";
						echo "<a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a><br><br>";
						$num=mysql_numrows($result);
						echo "<table border=\"1\"><tr><th>Datum/Zeit</th><th>Temperatur</th></tr>";
						$i=0;
						while ($i < $num) {
								
		                	// Suchergebnis in Liste anzeigen
							echo "<tr><td>" . mysql_result($result,$i,"datum_Zeit") . "</td><td>" . mysql_result($result,$i,"temperatur") . "</td></tr>";
							$i++;
		            	}
						echo "</table>";
						echo "<br><br><a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a>";
		
					}		
				} // Ende Datum detail
								
				//Datum
				if ($berichtstyp == 3) {
						
//debug					echo $datum;
					$result2 = mysql_query('select s.bezeichnung, date(t.datum_zeit), min(t.Temperatur), max(t.Temperatur) from temperatur t, standort s where t.standort=s.standort_id and date(t.datum_zeit) = \'' . $datum . '\' group by date(t.datum_zeit),s.bezeichnung order by s.standort_id');
					
					if (!$result2) {
						exit('Query Fehler (' . mysql_connect_errno() . ') ' . mysql_connect_error());
					} else
					{
			        	//echo "<h3>" . $datum . "</h3>";
						echo "<a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a><br><br>";
						$num=mysql_numrows($result2);
//debug						echo $num;
						echo "<table border=\"1\"><tr><th>Standort</th><th>Datum</th><th>Min</th><th>Max</th></tr>";
						$i=0;
						while ($i < $num) {
								
		                	// Suchergebnis in Liste anzeigen
/*
							echo "<tr><td>". mysql_result($result2,$i,"s.bezeichnung") . "</td><td>" . mysql_result($result2,$i,"date(t.datum_zeit)") . "</td><td>" . mysql_result($result2,$i,"min(t.Temperatur)") . "</td><td>" . mysql_result($result2,$i,"max(t.Temperatur)") . "</td></tr>";
*/
							echo "<tr><td>". mysql_result($result2,$i,0) . "</td><td>" . mysql_result($result2,$i,1) . "</td><td>" . mysql_result($result2,$i,2) . "</td><td>" . mysql_result($result2,$i,3) . "</td></tr>";
							$i++;
		            	}
						echo "</table>";
						echo "<br><br><a href=\"Temperatur_minmax.php\"> Zur&uuml;ck</a>";
		
					}		
				} // Ende Datum
         	}
		} else
		{
				echo "<form name=\"temperatur\" action=\"Temperatur_minmax.php\" target = \"main\" method= \"post\">";
				echo "<table><tr><td><select name=\"standort_id\">";
				
				$result = mysql_query("select standort_id, bezeichnung from standort");
	                if ($result)
	                {
	                    $num=mysql_numrows($result);
	
	                    $i=0;
	                    while ($i < $num) {
	                            echo "<option value = \"" . mysql_result($result,$i,"standort_id") . "\" >" . mysql_result($result,$i,"bezeichnung") . "</option>";
	                            $i++;
	                    }
	                }
	
				echo "</select></td></tr>";
				
				echo "<select name=\"datum\">";
				
				$result = mysql_query("select distinct date(datum_zeit) from temperatur order by date(datum_zeit) desc");
	                if ($result)
	                {
	                    $num=mysql_numrows($result);
	
	                    $i=0;
	                    while ($i < $num) {
	                            echo "<option value = \"" . mysql_result($result,$i,"date(datum_zeit)") . "\" >" . mysql_result($result,$i,"date(datum_zeit)") . "</option>";
	                            $i++;
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

