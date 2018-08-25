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

	if ($_POST['titel'] || $_POST['originalversion'])
	{

	// ***** Parameter infos auslesen *****
	$titel = $_POST['titel'];
	//$klass_id = $_POST['klass_id'];
	$schlagw = $_POST['beschreibung'];
	$original = $_POST['originalversion'];
	if (!$original) $original = 0; 

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
		
		if ($titel)
		{
				$result = mysql_query('SELECT s.spielfilme_id, s.titel, k.bezeichnung, s.jahr, s.originalversion from spielfilme s, std_klassifizierung k where s.klass_id=k.klass_id and s.titel like \'%' . $titel . '%\'');
		}
		else {
				// switch
				switch ($original) {
					
					case 0:
						break;
					case 1:
						$result = mysql_query('SELECT s.spielfilme_id, s.titel, k.bezeichnung, s.jahr, s.originalversion from spielfilme s, std_klassifizierung k where s.klass_id=k.klass_id and s.originalversion = 1 order by s.titel');
						break;
					case 2:
						$maxID = mysql_query('SELECT max(spielfilme_id) from spielfilme');
						$top25 = mysql_result($maxID, 0);
						$top25 = $top25 - 25;
						$result = mysql_query('SELECT s.spielfilme_id, s.titel, k.bezeichnung, s.jahr, s.originalversion from spielfilme s, std_klassifizierung k where s.klass_id=k.klass_id and s.spielfilme_id > ' . $top25 . ' order by s.spielfilme_id desc');
						break;
					case 3:
						$result = mysql_query('SELECT s.spielfilme_id, s.titel, k.bezeichnung, s.jahr, s.originalversion from spielfilme s, std_klassifizierung k where s.klass_id=k.klass_id and s.originalversion = 1 order by s.spielfilme_id desc limit 25');
						break;
					
				}
								
			}
			
			// Ausgabe
			if (!$result) {
				exit('Query Fehler (' . mysql_connect_errno() . ') ' . mysql_connect_error());
			} else
	                {
	                    $num=mysql_numrows($result);
	                    echo "<table border=\"1\"><tr><th>Details</th><th>Ursprung</th><th>Titel</th><th>Jahr</th><th>OV</th></tr>";
	
	                    $i=0;
	                    while ($i < $num) {
								
								// Originalversion
								$originaltab = mysql_result($result,$i,"s.originalversion");
								if ($originaltab <> 0) { 
									$originaltab = "x";
									}
								else { 
									$originaltab = "";
									}
								
	                            // Suchergebnis in Liste anzeigen
	                            echo "<tr><td><a href=\"film_suche_details.php?spielfilme_id=" . mysql_result($result,$i,"s.spielfilme_id") . "\">" . mysql_result($result,$i,"s.spielfilme_id") . "</a></td><td>" . mysql_result($result,$i,"k.bezeichnung") . "</td><td>" . mysql_result($result,$i,"s.Titel") . "</td><td>" . mysql_result($result,$i,"s.jahr") . "</td><td align=center>" . $originaltab . "</td></tr>";
	                            $i++;
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
                    <input type=radio name="originalversion" value=2> Last 25
                    <input type=radio name="originalversion" value=3> Last 25 OV
                    </td>
            </tr>
            </table>

            <input type=hidden name="blgform" value="1"/>
            <input type=submit value="Filme suchen"/>
            </form>
</body>
</html>

