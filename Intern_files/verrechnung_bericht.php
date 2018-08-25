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

        $con = mysql_connect($host, $benutzer, $passwort);
        mysql_select_db($dbname);

        // Datenbank
        //$result = mysql_query("select max(verrechnung_id) from verrechnung");
        $result = mysql_query("select max(verrechnung_id) from verrechnung where status = 'V'");
        $row = mysql_fetch_row($result);
        $max_vid = $row[0];

        // Saldo errechnen und ausgeben
        //$result = mysql_query("SELECT b.login, round(sum( v.betrag ),2) FROM verrechnung v, benutzer b WHERE b.keynr=v.benutzer_id and v.benutzer_id in (2,3) GROUP BY v.benutzer_id");
        $result = mysql_query("SELECT b.login, round(sum( v.betrag ),2) FROM verrechnung v, benutzer b WHERE b.keynr=v.benutzer_id and v.benutzer_id in (2,3) and v.verrechnung_id > " . $max_vid . " GROUP BY v.benutzer_id");
        $num=mysql_num_rows($result);
        $i=0;
        $rownum=0;
        echo "<table>";
        $saldo = round(mysql_result($result,1,1) - mysql_result($result,0,1), 2);
        echo "<tr>";
        echo "<td>" . mysql_result($result,0,0) . "</td><td>" . mysql_result($result,0,1) . "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>" . mysql_result($result,1,0) . "</td><td>" . mysql_result($result,1,1) . "</td>";
        echo "</tr>";
        echo "</table>";
        echo "<b>Saldo: " . $saldo . "</b>";
        echo "<br><br>";

        // Verrechnungspositionen ausgeben
        $result = mysql_query("SELECT b.login, v.datum, round(v.betrag, 2), v.text  FROM verrechnung v, benutzer b WHERE b.keynr=v.benutzer_id and v.benutzer_id in (2,3) and v.verrechnung_id > " . $max_vid . " ORDER BY v.benutzer_id");
        $num=mysql_num_rows($result);
        $i=0;
        $rownum=0;
        $num=mysql_numrows($result);
        echo "<table border=\"1\"><tr><th>Bezahlt von</th><th>Datum</th><th>Betrag</th><th>Test</th></tr>";

                    $i=0;
                    while ($i < $num) {

                            // Suchergebnis in Liste anzeigen
                            echo "<tr><td>" . mysql_result($result,$i,"b.login") . "</td><td>" . mysql_result($result,$i,"v.datum") . "</td><td align=\"right\">" . mysql_result($result,$i,2) . "</td><td>" . mysql_result($result,$i,"v.text") . "</td></tr>";
                            $i++;
                    }
                    echo "</table>";

?>
    </body>
</html>
