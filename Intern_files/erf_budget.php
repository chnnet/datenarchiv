<?php
        session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Budgetposten erfassen</title>

<?php
	include 'build_tabelle_array.php';
?>

    </head>
    <body>
        <?php
        // put your code here

        if (isset($_POST['anmerkung'])) // speichern
        {

            // ***** Parameter auslesen - Seite *****

            $anmerkung = $_POST['anmerkung'];
            $budget_id = $_POST['budget_id'];
            $konto = $_POST['konto'];
            $betrag_dec = $_POST['betrag_dec'];
            $betrag_int = $_POST['betrag_int'];
            $haeufigkeit = $_POST['haeufigkeit'];
            $gueltigab = $_POST['gueltigab'];
            $gueltigbis = $_POST['gueltigbis'];;
            $betrag = ($betrag_dec / 100 ) + $betrag_int;

            // String sql = "";
            // int test;
            //global $max_vid;

            // ***** Parameter auslesen session *****
            $host = $_SESSION['host'];
            $benutzer = $_SESSION['benutzer'];
            $passwort = $_SESSION['passwort'];
            $dbname = $_SESSION['dbname'];
            $benutzer_id = $_SESSION['keynr'];

            $con = mysql_connect($host, $benutzer, $passwort);
            mysql_select_db($dbname);

            // Datenbank
            if ($anmerkung != null)
            {

                    $result = mysql_query("INSERT INTO budget VALUES (" . $budget_id . "," . $gueltigab . "," . $gueltigbis . ","  . $konto . "," . $betrag . ",'" . $haeufigkeit . "','" . $anmerkung . "')");
                    if (!$result) {
                        exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
                    }
                    else
                    {
                    	echo "Satz gespeichert: " . $haeufigkeit . " " . $konto . " " . $betrag . " " . $anmerkung;
                    }

            }
        }
?>

<h2>Budgetposten erfassen</h2>
<br>

<form name="budget_erfassen" action="erf_budget.php" target="main" method="post">
<table>
<tr>
		<td>Budget-ID</td>
		<td><input name="budget_id" type="text" size="3" value="1" /></td>
</tr>
<tr>
		<td>Häufigkeit</td>
		<td>
			<input name="haeufigkeit" type="radio" value="M">Monatlich
			<input name="haeufigkeit" type="radio" value="Z">Zweimonatlich
			<input name="haeufigkeit" type="radio" value="Q">Quartal
			<input name="haeufigkeit" type="radio" value="H">Halbjährlich
			<input name="haeufigkeit" type="radio" value="J">Jährlich
			<input name="haeufigkeit" type="radio" value="E">Einmalig
		</td>
</tr>
<tr>
		<td>Gültig ab</td>
		<td><input name="gueltigab" type="text" size="6" /></td>
</tr>
<tr>
		<td>Gültig bis</td>
		<td><input name="gueltigbis" type="text" size="6" /></td>
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
		<td>Anmerkung</td>
		<td><input name="anmerkung" type="text" size=30 maxlength=50></td>
</tr>
</table>

<input type=submit value="Posten Speichern"/>
</form>

    </body>
</html>
