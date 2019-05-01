<?php
        session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Verrechnung Bericht</title>
    </head>
    <body>
        <h2>Verrechnung Bericht</h2>
<?php

        // ***** Parameter auslesen session *****
        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];
        $benutzer_id = $_SESSION['keynr'];

	    // DB-Connection
    	try {
        	$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
    	} catch (PDOException $ex) {
        	die('Die Datenbank ist momentan nicht erreichbar!');
    	}

        // Datenbank
        //$result = mysql_query("select max(verrechnung_id) from verrechnung");
        $result = $con->query("select max(verrechnung_id) from verrechnung where status = 'V'");
        $row = $result->fetchColumn();
        $max_vid = $row[0];

        // Saldo errechnen und ausgeben
        //$result = mysql_query("SELECT b.login, round(sum( v.betrag ),2) FROM verrechnung v, benutzer b WHERE b.keynr=v.benutzer_id and v.benutzer_id in (2,3) GROUP BY v.benutzer_id");
        $result =$con->query("SELECT b.login, round(sum( v.betrag ),2) as betrag FROM verrechnung v, benutzer b WHERE b.keynr=v.benutzer_id and v.benutzer_id in (2,3) and v.verrechnung_id > " . $max_vid . " GROUP BY v.benutzer_id");
        echo "<table>";
		$i=0;
		$saldo=0;
	    while ($row = $result->fetch()) {
	    
			if ($i==0) $saldo = $saldo + $row['betrag'];
			if ($i==1) $saldo = $saldo - $row['betrag'];
	        //$saldo = round(mysql_result($result,1,1) - mysql_result($result,0,1), 2);
    	    echo "<tr>";
        	echo "<td>" . $row['login'] . "</td><td>" . $row['betrag'] . "</td>";
	        echo "</tr>";
	        echo "</tr>";
        	$i++;
		}
    	echo "</table>";
    	echo "<b>Saldo: " . round($saldo, 2) . "</b>";
    	echo "<br><br>";
        // Verrechnungspositionen ausgeben
        $result = $con->query("SELECT b.login, v.datum, round(v.betrag, 2) as betrag, v.text  FROM verrechnung v, benutzer b WHERE b.keynr=v.benutzer_id and v.benutzer_id in (2,3) and v.verrechnung_id > " . $max_vid . " ORDER BY v.datum desc");
        echo "<table border=\"1\"><tr><th>Bezahlt von</th><th>Datum</th><th>Betrag</th><th>Test</th></tr>";

                    while ($row = $result->fetch()) {

                            // Suchergebnis in Liste anzeigen
                            echo "<tr><td>" . $row['login'] . "</td><td>" . $row['datum'] . "</td><td align=\"right\">" . $row['betrag'] . "</td><td>" . $row['text'] . "</td></tr>";
                    }
                    echo "</table>";

?>
    </body>
</html>
