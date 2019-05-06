<?php
        session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Vermögensbericht</title>
    </head>
    <body>
        <?php
        // put your code here

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
            $result = $con->query("SELECT * FROM vermoegen order by jahrmonat desc");
            $rownum=0;
			echo "<h1>Verm&ouml;gensbericht</h1>";
            echo "<table border=\"1\">";
            echo "<tr>";
            echo "<th>Monat</th><th>Bargeld</th><th>Bank</th><th>Kreditkarte</th><th>Wertpapiere</th><th>Sparkonto</th><th>Anita</th>";
            echo "</tr>";
            while ($row = $result->fetch()) {

                    $rownum++;
                    echo "<tr>";
                    echo "<td>" . $row[0] . "</td><td align=\"right\">" .$row[1] . "</td><td align=\"right\">" . $row[2] . "</td><td align=\"right\">" . $row[3] . "</td><td align=\"right\">" . $row[4] . "</td><td align=\"right\">" . $row[5] . "</td>" . "</td><td align=\"right\">" . $row[6] . "</td>";
                    echo "</tr>";
            }
            echo "</table>";
?>

    </body>
</html>
