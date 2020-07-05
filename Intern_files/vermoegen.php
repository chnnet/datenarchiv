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
		function setAmount($zahl_int, $zahl_dec) {
		
            if ($zahl_int < 0)
            {
            	$zahl_ges = $zahl_int - ($zahl_dec / 100 );
            } else {
            	$zahl_ges = ($zahl_dec / 100 ) + $zahl_int;
            }
            $zahl_ges = number_format ($zahl_ges, 2, '.', '');
            return $zahl_ges;
		}

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
            $bank_betrag = setAmount($bank_betrag_int, $bank_betrag_dec);
            $kk_betrag = setAmount($kk_betrag_int, $kk_betrag_dec);
            $spar_betrag = setAmount($spar_betrag_int, $spar_betrag_dec);
			$wp_betrag = setAmount($wp_betrag_int, $wp_betrag_dec);
			$av_betrag = setAmount($av_betrag_int, $av_betrag_dec);

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
            if ($jahrmonat != null)
            {
					// prüfen ob für Jahrmonat schon Wert eingetragen
                    $row = $con->query("select max(jahrmonat) from vermoegen")->fetch();
                    $max_jahrmonat = $row[0];


					if ($max_jahrmonat < $jahrmonat)
					{
                    	$result = $con->prepare("INSERT INTO vermoegen (jahrmonat,saldo_bargeld,saldo_bank,saldo_kreditkarte,saldo_wertpapiere,saldo_sparbuch,saldo_verrAnita) VALUES (?,?,?,?,?,?,?)");
                    	$result->execute(array($jahrmonat,$bar_betrag,$bank_betrag,$kk_betrag,$wp_betrag,$spar_betrag, $av_betrag ))
        				or die ('Fehler beim INSERT: ' . htmlspecialchars($result->errorinfo()[2]));

					}
					else
					{
						echo "Salden für " . $jahrmonat . " bereits eingetragen!!!";
					}
	            // Werte des aktuellen Jahres werden ausgeben
			    $jahr = date("Y", time());
				$result = $con->prepare("SELECT * FROM vermoegen WHERE jahrmonat > " . $jahr . "00");
				$result->execute([$jahr]); 
            
				echo "<table border=\"1\"><tr><th>Jahr/Monat</th><th>Bar</th><th>Bank</th><th>Kreditkarte</th><th>Wertpapiere</th><th>Sparbuch</th><th>Anita Verr</th></tr>";
	
				while ($row = $result->fetch()) {
								
	 				// Suchergebnis in Liste anzeigen
	    			echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] . "</td></tr>";	    	
	    		}
		    	echo "</tr>";
    	        echo "</table>";
            }

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
