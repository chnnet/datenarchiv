<?php
        session_start();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Film erfassen</title>

<?php
	include 'build_klassid_arrays.php';
?>

	<script language="JavaScript" type="text/javascript">
	

		var ursprung = new Array();
		var genre = new Array();
		var ursprung_id = new Array();
		var genre_id = new Array();

	</script>

	<script language="JavaScript" type="text/javascript">

		function init()
		{
			// auch zwei arrays notwendig, da ids auch werte
			var dblen = ursprung.length;
			var selgr = 1;
			for ( i=1; i < dblen; i++ )
			{
				NeuerEintrag = new Option(ursprung[i]);
				document.spielfilm_erfassen.ursprung.options = NeuerEintrag;
				document.spielfilm_erfassen.ursprung.options.text = ursprung[i];
				document.spielfilm_erfassen.ursprung.options.value = ursprung_id[i];
			}

			dblen = genre.length;
			selgr = 1;
			for ( i=1; i < dblen; i++ )
			{
				NeuerEintrag = new Option(genre[i]);
				document.spielfilm_erfassen.genre.options = NeuerEintrag;
				document.spielfilm_erfassen.genre.options.text = genre[i];
				document.spielfilm_erfassen.genre.options.value = genre_id[i];
			}
		}

	</script>
	
	</head>
    <body onload="init()" >
        <?php
        // put your code here

        if (isset($_POST['titel'])) // speichern
        {

            // ***** Parameter auslesen - Seite *****

            $titel = $_POST['titel'];
            $klass_id = $_POST['klass_id'];
            $genre = $_POST['genre'];
            $jahr = $_POST['jahr'];
            $original = $_POST['originalversion'];
            if ($original <> "true") $original = "false";
            $dateiname = $_POST['dateiname'];
			$beschreibung = $_POST['beschreibung'];

            // ***** Parameter auslesen session *****
            $host = $_SESSION['host'];
            $benutzer = $_SESSION['benutzer'];
            $passwort = $_SESSION['passwort'];
            $dbname = $_SESSION['dbname'];
            $benutzer_id = $_SESSION['keynr'];

            $con = mysql_connect($host, $benutzer, $passwort);
            mysql_select_db($dbname);

            // Datenbank
            if ($titel != null)
            {
                    $result = mysql_query("INSERT INTO spielfilme (titel,klass_id,genre,jahr,originalversion,dateiname,beschreibung) VALUES ('" . $titel . "'," . $klass_id . "," . $genre . ",'" . $jahr . "'," . $original . ",'" . $dateiname . "','" . $beschreibung . "')");
                    if (!$result) {
                        exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
                    }

            }
        }

?>

<h2>Spielfilm erfassen</h2>
<br>

<form name="spielfilm_erfassen" action="spielfilm.php" target="main" method="post">
<table>
<tr>
		<td>Titel</td>
		<td><input name="titel" type="text" size=100 maxlength=150></td>
</tr>
<tr>
	<td>Ursprung</td>
	<td>
	<select type=text name="klass_id">
		<?php
			$ursprung = jsklassids (9,1066,$dbname);
			echo $ursprung;
		?>
	</select>
	</td>
</tr>
<tr>
	<td>Genre</td>
	<td>
	<select name="genre">
		<?php
			$genre = jsklassids (10,0,$dbname);
			echo $genre;
		?>	
	</select>
	</td>
</tr>
<tr>
		<td>Jahr</td>
		<td><input name="jahr" type="text" size=4 maxlength=4></td>
</tr>
<tr>
		<td>OV</td>
		<td><input name="originalversion" type="checkbox" value="true"></td>
</tr>
<tr>
		<td>Dateiname</td>
		<td><input name="dateiname" type="text" size=100 maxlength=150></td>
</tr>
<tr>
		<td>Beschreibung</td>
		<td><textarea name="beschreibung" cols=80 rows=10>  </textarea></td>
</tr>
</table>

<input type=submit value="Film Speichern"/>
</form>

    </body>
</html>
