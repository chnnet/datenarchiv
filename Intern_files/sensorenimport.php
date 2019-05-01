<?php

echo "<pre>";
echo "FILES:<br>";
print_r ($_FILES );
echo "</pre>";

if ( $_FILES['uploaddatei']['name']  <> "" )
{
    // Datei wurde durch HTML-Formular hochgeladen
    // und kann nun weiterverarbeitet werden
    // *** Form auslesen
    $standort = $_POST['standort'];
    $datenart = $_POST['datenart'];
    // !!! Tabelle noch dynamisch belegen
    $tabelle = 'temperatur';
 
    // Kontrolle, ob Dateityp zulässig ist
    $zugelassenedateitypen = array("text/csv", "text/xml");
 
    if ( ! in_array( $_FILES['uploaddatei']['type'] , $zugelassenedateitypen ))
    {
        echo "<p>Dateitype ist NICHT zugelassen</p>";
    }
    else
    {
        // Test ob Dateiname in Ordnung
        $_FILES['uploaddatei']['name'] = dateiname_bereinigen($_FILES['uploaddatei']['name']);
 
        if ( $_FILES['uploaddatei']['name'] <> '' )
        {
            move_uploaded_file (
                 $_FILES['uploaddatei']['tmp_name'] ,
                 'import/'. $_FILES['uploaddatei']['name'] );
 
            echo "<p>Hochladen war erfolgreich: ";
            echo '<a href="import/'. $_FILES['uploaddatei']['name'] .'">';
            echo 'import/'. $_FILES['uploaddatei']['name'];
            echo '</a>';
        }
        else
        {
            echo "<p>Dateiname ist nicht zulässig</p>";
        }
    }
    

	//parsen, Import-Tabelle schreiben
	if ( $file = fopen( "import/" . $_FILES['uploaddatei']['name'] , 'r' ) ) {

    	echo "<br />File opened.<br />";

	    $firstline = fgets ($file, 4096 );
    	    //Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
	    $num = strlen($firstline) - strlen(str_replace(";", "", $firstline));

        //save the different fields of the firstline in an array called fields
		$fields = array();
    	$fields = explode( ";", $firstline, ($num+1) );

	    $line = array();
	    $i = 0;

        //CSV: one line is one record and the cells/fields are seperated by ";"
        //so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]
    	while ( $line[$i] = fgets ($file, 4096) ) {

        	$dsatz[$i] = array();
        	$dsatz[$i] = explode( ";", $line[$i], ($num+1) );

	        $i++;
    	}

	// xxx hier Import implementieren, checks?
	// initialStruktur: Datum, Konto Soll, Konto Haben, Betrag, Text


// mysql
$servername = "localhost";
$username = "root";
$password = "ManzTest";
$dbname = "datenarchiv";


/* nicht notwendig?
    		for ( $k = 0; $k != ($num+1); $k++ ) {
        		echo "<td>" . $fields[$k] . "</td>";
    		}
*/	

	// datum_zeit, wert; standort und tabelle aus form
	$sql = ''; // für eine Transaktion
	$satz = 0; // Zähler Zeilen,Datensätze
	$datum_zeit = '';
	$wert = 0;
	
		    foreach ($dsatz as $key => $number) {
        	       // Aktionen bei neuem Satz
        	       $satz++;

        	       if (($satz % 1000) == 0 || $satz == 1)
        	       {
        	       		// in DB schreiben
						if ($satz != 1)
						{
							// Create connection, write data
    						// DB-Connection
    						try {
        						$conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
						    } catch (PDOException $ex) {
        						die('Die Datenbank ist momentan nicht erreichbar!');
    						}
    							if ($conn->query($sql) === TRUE) {
    							echo "New record created successfully";
    							echo "Satz: " . $satz;
							} else {
  							echo "Error: " . $sql . "<br>" . $conn->error;
							}

							$conn->close();
						}
						$sql = 'insert into ' . $tabelle . ' values (';
        	       
        	       } else {
        	       
        	       		$sql .= ", (";
        	       }
		        	
        		foreach ($number as $k => $content) {
                    // Felder des Datensatzes
			                    
                	switch ($k) {
                		case 0: // datum_zeit
                			$sql .= '\'' . $content . '\',' . $standort . ',';
					    break;
                		case 1: // wert
                			$sql .= '\'' . str_replace(',','.',$content) . '\'';
						    break;
					    
					}
					
				} // Ende Felder
				$sql .= ')';
				

			} // Ende Datensätze
			// letzte Sätze rausschreiben
    		// DB-Connection
    		try {
        		$conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
		    } catch (PDOException $ex) {
        		die('Die Datenbank ist momentan nicht erreichbar!');
   			}
   			if ($conn->query($sql) === TRUE) {
				echo "<br>Satz: " . $satz;
				echo "<br>New record created successfully<br>";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}

			$conn->close();
		}
}
 
function dateiname_bereinigen($dateiname)
{
    // erwünschte Zeichen erhalten bzw. umschreiben
    // aus allen ä wird ae, ü -> ue, ß -> ss (je nach Sprache mehr Aufwand)
    // und sonst noch ein paar Dinge (ist schätzungsweise mein persönlicher Geschmach ;)
    $dateiname = strtolower ( $dateiname );
    $dateiname = str_replace ('"', "-", $dateiname );
    $dateiname = str_replace ("'", "-", $dateiname );
    $dateiname = str_replace ("*", "-", $dateiname );
    $dateiname = str_replace ("ß", "ss", $dateiname );
    $dateiname = str_replace ("ß", "ss", $dateiname );
    $dateiname = str_replace ("ä", "ae", $dateiname );
    $dateiname = str_replace ("ä", "ae", $dateiname );
    $dateiname = str_replace ("ö", "oe", $dateiname );
    $dateiname = str_replace ("ö", "oe", $dateiname );
    $dateiname = str_replace ("ü", "ue", $dateiname );
    $dateiname = str_replace ("ü", "ue", $dateiname );
    $dateiname = str_replace ("Ä", "ae", $dateiname );
    $dateiname = str_replace ("Ö", "oe", $dateiname );
    $dateiname = str_replace ("Ü", "ue", $dateiname );
    $dateiname = htmlentities ( $dateiname );
    $dateiname = str_replace ("&", "und", $dateiname );
    $dateiname = str_replace ("+", "und", $dateiname );
    $dateiname = str_replace ("(", "-", $dateiname );
    $dateiname = str_replace (")", "-", $dateiname );
    $dateiname = str_replace (" ", "-", $dateiname );
    $dateiname = str_replace ("\'", "-", $dateiname );
    $dateiname = str_replace ("/", "-", $dateiname );
    $dateiname = str_replace ("?", "-", $dateiname );
    $dateiname = str_replace ("!", "-", $dateiname );
    $dateiname = str_replace (":", "-", $dateiname );
    $dateiname = str_replace (";", "-", $dateiname );
    $dateiname = str_replace (",", "-", $dateiname );
    $dateiname = str_replace ("--", "-", $dateiname );
 
    // und nun jagen wir noch die Heilfunktion darüber
    $dateiname = filter_var($dateiname, FILTER_SANITIZE_URL);
    return ($dateiname);
}
?>