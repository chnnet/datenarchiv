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
        
	// ***** Verbindugsaufbau zu MySQL *****

		$con = mysql_connect($host, $benutzer, $passwort);
		if (!$con) {
			exit('Connect Error (' . mysql_connect_errno() . ') ' . mysql_connect_error());
		}

		mysql_select_db($dbname);

		if ($filmID)
		{
			$result = mysql_query('SELECT * from spielfilme s, std_klassifizierung k where s.genre=k.klass_id and s.spielfilme_id = ' . $filmID);
		}
		
		if (!$result) {
			exit('Query Fehler (' . mysql_connect_errno() . ') ' . mysql_connect_error());
		} else
                {
                    $num=mysql_numrows($result);
                    echo "<table border=1>";

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
                            echo "<tr><td>Nummer</td><td>" . mysql_result($result,$i,"s.spielfilme_id") . "</td><tr><td>Titel</td><td>" . mysql_result($result,$i,"s.titel") . "</td></tr><tr><td>Jahr</td><td>" . mysql_result($result,$i,"s.jahr") . "</td></tr><tr><td>Genre</td><td>" . mysql_result($result,$i,"k.bezeichnung") . "</td></tr><tr><td>Beschreibung</td><td>" . mysql_result($result,$i,"s.beschreibung") . "</td></tr><tr><td>Originalversion</td><td align=center>" . $originaltab . "</td></tr>";
                            $i++;
                    }
                    echo "</table><br>";
                    echo "<a href=\"film_suche.php?titel=" . $titel . "&beschreibung=" . $schlagw . "&originalverion=" . $original . "\">Zur&uuml;ck zur Suche" . "</a>";
                    echo "<br>";               }

	} // else Form, Klammern rausgenommen und else auskommentiert
?>

</body>
</html>

