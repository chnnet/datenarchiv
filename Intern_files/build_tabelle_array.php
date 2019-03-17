 <?php
    session_start();
 
	function jsklassids ($tabelle, $id, $bezeichnung)
	{
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


//        $con = mysql_connect($host, $benutzer, $passwort);
//		mysql_select_db($dbname);
		// PHP7
        $con = new mysqli($host, $benutzer, $passwort, $dbname);
		$ergebnis = $con->query("select " . $id . "," . $bezeichnung . " from " . $tabelle ." order by " . $id);
        $num=mysqli_num_rows($ergebnis);		
		$jsstring = '';
	
        if (!$ergebnis) {
            exit('MySQL Fehler: (' . mysqli_errno($con) . ') ' . mysqli_error($con));
        }
        else
        {                    	// result

	    		while($row = $ergebnis->fetch_array()) {

					$jsstring .= "<option value=" . $row[0] . ">" . $row[1] . "</option>";

                }
				return $jsstring;
		}

	} // function

//debug	
	$optionen = jsklassids ("kontenstamm","ktonr","bezeichnung");
?>