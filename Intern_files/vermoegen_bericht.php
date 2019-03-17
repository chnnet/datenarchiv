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


            $con = mysql_connect($host, $benutzer, $passwort);
            mysql_select_db($dbname);
            
            $result = mysql_query("SELECT * FROM vermoegen order by jahrmonat desc");
            $num=mysql_num_rows($result);
            $i=0;
            $rownum=0;
			echo "<h1>Verm&ouml;gensbericht</h1>";
            echo "<table border=\"1\">";
            echo "<tr>";
            echo "<th>Monat</th><th>Bargeld</th><th>Bank</th><th>Kreditkarte</th><th>Wertpapiere</th><th>Sparkonto</th><th>Anita</th>";
            echo "</tr>";
            while ($i < $num) {

                    $rownum++;
                    echo "<tr>";
                    echo "<td>" . mysql_result($result,$i,0) . "</td><td align=\"right\">" . mysql_result($result,$i,1) . "</td><td align=\"right\">" . mysql_result($result,$i,2) . "</td><td align=\"right\">" . mysql_result($result,$i,3) . "</td><td align=\"right\">" . mysql_result($result,$i,4) . "</td><td align=\"right\">" . mysql_result($result,$i,5) . "</td>" . "</td><td align=\"right\">" . mysql_result($result,$i,6) . "</td>";
                    echo "</tr>";
                    $i++;
            }
            echo "</table>";
?>

    </body>
</html>
