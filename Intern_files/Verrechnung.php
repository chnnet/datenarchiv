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
            $status = $_POST['status'];
            // String sql = "";
            // int test;
            global $max_vid;

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
            if ($text != null)
            {

                    $result = $con->query("select max(verrechnung_id) from verrechnung");
                    $max_vid = $result->fetchColumn();
                    $max_vid++;
                    //mysql_free_result($result);

                    $result = $con->prepare("INSERT INTO verrechnung VALUES (?,?,?,?,?,?,?,?)");
                    $result->execute(array($benutzer_id, $betrag,'0', $max_vid, $datum, $text, $status, $konto))
						or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));

            }
            // Saldo errechnen und ausgeben
            $result = $con->query("select max(verrechnung_id) from verrechnung where status = 'V'");
            $max_vid = $result->fetchColumn();
            //mysql_free_result($result);


            $result = $con->query("SELECT b.login, round(sum( v.betrag ),2) as betrag FROM verrechnung v, benutzer b WHERE b.keynr=v.benutzer_id and v.benutzer_id in (2,3) AND v.verrechnung_id > " . $max_vid . " GROUP BY v.benutzer_id");
            $rownum=0;
            echo "<table>";
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
