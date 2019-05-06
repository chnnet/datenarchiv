<?php
        session_start();
?>
<html>
<head><title>Monatsbericht</title>

<?php
	// Session-daten auslesen
	$host = $_SESSION['host'];
	$benutzer = $_SESSION['benutzer'];
	$passwort = $_SESSION['passwort'];
	$dbname = $_SESSION['dbname'];
    // DB-Connection
    try {
        $con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
    } catch (PDOException $ex) {
        die('Die Datenbank ist momentan nicht erreichbar!');
    }

	$sJSLogic = "<script language=\"JavaScript\">";

        // JS-Array aufbauen für Auswahl
        $sJSLogic .= "var monat = new Array(); ";

// ***** Verbindugsaufbau zu MySQL *****

        $result = $con->query("SELECT DISTINCT jahrmonat FROM salden_monat_verrechnung order by jahrmonat desc");
        if ($result)
        {
			i=0;
            while ($row = $result->fetch()) {

                $sJSLogic .= "monat[" . $i . "] = \"" . $row['jahrmonat'] . "\";";
                $i++;
            }
        }
        else
        {
            echo 'Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]);
        }


        $sJSLogic .= "</script>";

        echo $sJSLogic;
?>

<script language="JavaScript">

function init()
{
	var tblen = monat.length;
	for ( i=0; i < tblen; i++ )
	{
		NeuerEintrag = new Option(monat[i]);
 		document.abschluss_bericht.monat.options[i] = NeuerEintrag;
 		document.abschluss_bericht.monat.options[i].value = monat[i];
	}
}

</script>

</head>
<body onLoad="init()">

<h1> Monatsbericht erstellen </h1>
<form name="abschluss_bericht" action="salden_monat.php" method="post">
<table>
<tr>
		<td>Monat</td>
		<td><select name="monat"></select></td>
</tr>
</table>
<br>
<table>
<tr>
<td><input name="mb" type=submit value="Monatsbericht"/></td>
<td><input name="siv" type=submit value="Soll/Ist-Vergleich"/></td>
</tr>
</table>
</form>
</body>
</html>