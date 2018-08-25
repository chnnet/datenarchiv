<?php
        session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Rechnung erfassen</title>

<?php
	include 'build_tabelle_array.php';
?>

    </head>
    <body>
        <?php
        // put your code here

        if (isset($_POST['text'])) // speichern
        {

            // ***** Parameter auslesen - Seite *****

            $text = $_POST['text'];
            $konto = $_POST['konto'];
            $betrag_dec = $_POST['betrag_dec'];
            $betrag_int = $_POST['betrag_int'];
            $timestamp = time();
            $datum = $_POST['datum'];
            $betrag = ($betrag_dec / 100 ) + $betrag_int;
            $text = $_POST['text'];
            // String sql = "";
            // int test;
            global $max_vid;

            // ***** Parameter auslesen session *****
            $host = $_SESSION['host'];
            $benutzer = $_SESSION['benutzer'];
            $passwort = $_SESSION['passwort'];
            $dbname = $_SESSION['dbname'];
            $benutzer_id = $_SESSION['keynr'];

            $con = mysql_connect($host, $benutzer, $passwort);
            mysql_select_db($dbname);

            // Datenbank
            if ($text != null)
            {

                    $result = mysql_query("select max(verrechnung_id) from verrechnung");
                    $row = mysql_fetch_row($result);
                    $max_vid = $row[0];
                    $max_vid++;
                    mysql_free_result($result);

                    $result = mysql_query("INSERT INTO verrechnung VALUES (" . $benutzer_id . "," . $betrag . ",0," . $max_vid . ",'" . $datum . "','" . $text . "','N'," . $konto . ")");
                    if (!$result) {
                        exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
                    }

            }
            // Saldo errechnen und ausgeben
            $result = mysql_query("select max(verrechnung_id) from verrechnung where status = 'V'");
            $row = mysql_fetch_row($result);
            $max_vid = $row[0];
            mysql_free_result($result);


            $result = mysql_query("SELECT b.login, round(sum( v.betrag ),2) FROM verrechnung v, benutzer b WHERE b.keynr=v.benutzer_id and v.benutzer_id in (2,3) AND v.verrechnung_id > " . $max_vid . " GROUP BY v.benutzer_id");
            $num=mysql_num_rows($result);
            $i=0;
            $rownum=0;
            echo "<table>";
            while ($i < $num) {

                    $rownum++;
                    echo "<tr>";
                    echo "<td>" . mysql_result($result,$i,0) . "</td><td>" . mysql_result($result,$i,1) . "</td>";
                    echo "</tr>";
                    if ($i % 2 == 0)
					{
						$saldo = $saldo + mysql_result($result,$i,1);
                    }
                    else
                    {
	                    $saldo = $saldo - mysql_result($result,$i,1);
                    }
                    $i++;
            }
            echo "</table>";
            echo "Saldo: " . $saldo;
        }

?>

<h2>Rechnung erfassen</h2>
<br>

<form name="verrechnung_erfassen" action="Verrechnung.php" target="main" method="post">
<table>
<tr>
		<td>Bezahlt von</td>
		<td>
			<input type="radio" value="2">Christian
			<input type="radio" value="3">Anita
		</td>
</tr>
<tr>

<?php
		$timestamp = time();
		$jahr = date("Y",$timestamp);
		$monat = date("m",$timestamp);
		$akt_datum = $jahr . "-" . $monat . "-";
?>
		<td>Datum</td>
		<td><input name="datum" type="text" size="8" value ="<?php echo $akt_datum; ?>" /></td>
</tr>
<tr>
		<td>Konto</td>
		<td>
			<select name="konto">
<?php

		$optionen = jsklassids ("kontenstamm","ktonr","bezeichnung");
		echo $optionen;			
?>

			</select>
		</td>
</tr>
<tr>
		<td>Betrag</td>
		<td><input name="betrag_int" type="text" size="7" />,<input name="betrag_dec" type="text" size="2" /></td>
</tr>
<tr>
		<td>Text</td>
		<td><input name="text" type="text" size=30 maxlength=50></td>
</tr>
<tr>
		<td>Typ</td>
		<td>
			<select name="status">
				<option value ="N" selected>Normal</option>
				<option value ="S">Saldo</option>
				<option value ="V">Verrechnung</option>
			</select>
		</td>
</tr>
</table>

<input type=hidden name="vid" value="<?php $max_vid ?>"/>
<input type=submit value="Rechnung Speichern"/>
</form>

    </body>
</html>
