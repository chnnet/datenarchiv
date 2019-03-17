<?php
        session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Vermögenssalden erfassen</title>
    </head>
    <body>
        <?php
        // put your code here

        if (isset($_POST['jahrmonat'])) // speichern
        {

            // ***** Parameter auslesen - Seite *****

            $jahrmonat = $_POST['jahrmonat'];
            $bar_betrag_dec = $_POST['bar_betrag_dec'];
            $bar_betrag_int = $_POST['bar_betrag_int'];
            $bank_betrag_dec = $_POST['bank_betrag_dec'];
            $bank_betrag_int = $_POST['bank_betrag_int'];
            $kk_betrag_dec = $_POST['kk_betrag_dec'];
            $kk_betrag_int = $_POST['kk_betrag_int'];
            $spar_betrag_dec = $_POST['spar_betrag_dec'];
            $spar_betrag_int = $_POST['spar_betrag_int'];
            $wp_betrag_dec = $_POST['wp_betrag_dec'];
            $wp_betrag_int = $_POST['wp_betrag_int'];
            $av_betrag_dec = $_POST['av_betrag_dec'];
            $av_betrag_int = $_POST['av_betrag_int'];
            $bar_betrag = ($bar_betrag_dec / 100 ) + $bar_betrag_int;
            $bar_betrag = number_format ($bar_betrag, 2, '.', '');
            $bank_betrag = ($bank_betrag_dec / 100 ) + $bank_betrag_int;
            $bank_betrag = number_format ($bank_betrag, 2, '.', '');
            $kk_betrag = ($kk_betrag_dec / 100 ) + $kk_betrag_int;
            $kk_betrag = number_format ($kk_betrag, 2, '.', '');
            $spar_betrag = ($spar_betrag_dec / 100 ) + $spar_betrag_int;
            $spar_betrag = number_format ($spar_betrag, 2, '.', '');
			$wp_betrag = ($wp_betrag_dec / 100 ) + $wp_betrag_int;
            $wp_betrag = number_format ($wp_betrag, 2, '.', '');
			$av_betrag = ($av_betrag_dec / 100 ) + $av_betrag_int;
            $av_betrag = number_format ($av_betrag, 2, '.', '');

            // ***** Parameter auslesen session *****
            $host = $_SESSION['host'];
            $benutzer = $_SESSION['benutzer'];
            $passwort = $_SESSION['passwort'];
            $dbname = $_SESSION['dbname'];
            $benutzer_id = $_SESSION['keynr'];


            $con = mysql_connect($host, $benutzer, $passwort);
            mysql_select_db($dbname);
            
            // Datenbank
            if ($jahrmonat != null)
            {
					// prüfen ob für Jahrmonat schon Wert eingetragen
                    $result = mysql_query("select max(jahrmonat) from vermoegen");
                    $row = mysql_fetch_row($result);
                    $max_jahrmonat = $row[0];
                    mysql_free_result($result);

					if ($max_jahrmonat < $jahrmonat)
					{
						$result = mysql_query("INSERT INTO vermoegen VALUES (" . $jahrmonat . ",'" . $bar_betrag . "','" . $bank_betrag . "','" . $kk_betrag . "','" . $wp_betrag . "','" . $spar_betrag . "','" . $av_betrag ."')");
						if (!$result) {
							exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
						}
					}
					else
					{
						echo "Salden für " . $jahrmonat . " bereits eingetragen!!!";
					}
            }
            // Werte des aktuellen Jahres werden ausgeben
		    $jahr = date("Y", $timestamp);
echo "jahr: " . $jahr;
            $result = mysql_query("SELECT * FROM vermoegen WHERE jahrmonat > " . $jahr . "00");
            $num=mysql_num_rows($result);
            $i=0;
            $rownum=0;
            echo "<table border=\"1\">";
            while ($i < $num) {

                    $rownum++;
                    echo "<tr>";
                    echo "<td>" . mysql_result($result,$i,0) . "</td><td>" . mysql_result($result,$i,1) . "</td><td>" . mysql_result($result,$i,2) . "</td><td>" . mysql_result($result,$i,3) . "</td><td>" . mysql_result($result,$i,4) . "</td><td>" . mysql_result($result,$i,5) . "</td>";
                    echo "</tr>";
                    $i++;
            }
            echo "</table>";
        }
		else
		{
            $timestamp = time();
            $jahrmonat = date("Ym", $timestamp);
		}

?>

<h2>Vermögenssalden erfassen</h2>
<br>

<form name="vermoegen_erfassen" action="vermoegen.php" target="main" method="post">
<table>
<tr>
		<td>Monat (JJJJMM)</td>
		<td><input name="jahrmonat" type="text" size="6" value="<?php echo $jahrmonat ?>" /></td>
</tr>
<tr>
		<td>Betrag Bargeld</td>
		<td><input name="bar_betrag_int" type="text" size="7" />,<input name="bar_betrag_dec" type="text" size="2" /></td>
</tr>
<tr>
		<td>Betrag Bank</td>
		<td><input name="bank_betrag_int" type="text" size="7" />,<input name="bank_betrag_dec" type="text" size="2" /></td>
</tr>
<tr>
		<td>Betrag Kreditkarte</td>
		<td><input name="kk_betrag_int" type="text" size="7" />,<input name="kk_betrag_dec" type="text" size="2" /></td>
</tr>
<tr>
		<td>Betrag Anita Verrechnung</td>
		<td><input name="av_betrag_int" type="text" size="7" />,<input name="av_betrag_dec" type="text" size="2" /></td>
</tr>
<tr>
		<td>Betrag Wertpapiere</td>
		<td><input name="wp_betrag_int" type="text" size="7" />,<input name="wp_betrag_dec" type="text" size="2" /></td>
</tr>
<tr>
		<td>Betrag Sparbuch</td>
		<td><input name="spar_betrag_int" type="text" size="7" />,<input name="spar_betrag_dec" type="text" size="2" /></td>
</tr>
</table>

<input type=submit value="Salden Speichern"/>
</form>

    </body>
</html>
