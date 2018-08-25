<?php
        session_start();
?>
<html>
<head>
	<title>Serien</title>

</head>

<body>

<h2>Serien</h2>

<?php

	// ***** Parameter infos auslesen *****
	$serien_id = $_GET['serien_id'];
	$serienname = $_GET['name']; 

	// ***** Session infos auslesen *****

        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];
        
	// ***** Verbindugsaufbau zu MySQL *****

		$con = mysql_connect($host, $benutzer, $passwort);
		if (!$con) {
			exit('Connect Error (' . mysql_connect_errno() . ') ' . mysql_connect_error());
		}

		mysql_select_db($dbname);
		if ($serien_id)
		{
			$result = mysql_query('SELECT * from serien_folgen where serie_id = \'' . $serien_id . '\'');
		} else {
			$result = mysql_query('SELECT * from serien');
		}
		
		if (!$result) {
			exit('Query Fehler (' . mysql_connect_errno() . ') ' . mysql_connect_error());
		} else
        {
            if ($serien_id)
            {
	            echo "<h3>" . $serienname . "</h3>";
	            echo "<a href=\"serien.php?serien_id=\"> Serien&uuml;bersicht</a><br>";
            	$num=mysql_numrows($result);
            	echo "<table border=\"1\"><tr><th>Folge</th><th>Titel</th><th>Premiere DE</th><th>Originaltitel</th><th>Premiere</th></tr>";

                $i=0;
                while ($i < $num) {
						
                        // Suchergebnis in Liste anzeigen
                        echo "<tr><td>" . mysql_result($result,$i,"folge_id") . "</td><td>" . mysql_result($result,$i,"titel_de") . "</td><td>" . mysql_result($result,$i,"premiere_de") . "</td><td>" . mysql_result($result,$i,"titel_en") . "</td><td>" . mysql_result($result,$i,"premiere_en") . "</td></tr>";
                        $i++;
                }
				echo "</table>";
	            echo "<br><a href=\"serien.php?serien_id=\"> Serien&uuml;bersicht</a>";

            } else
            {
	            echo "<h3>Serien&uumlbersicht</h3>";
            	$num=mysql_numrows($result);
            	echo "<table border=\"0\">";

                $i=0;
                while ($i < $num) {
						
                        // Suchergebnis in Liste anzeigen
                            echo "<tr><td> <a href=\"serien.php?serien_id=" . mysql_result($result,$i,"serien_id") . "&name=" . mysql_result($result,$i,"name") . "\">" . mysql_result($result,$i,"name") . "</a></td></tr>";
                        $i++;
                }
				echo "</table>";
            }
            echo "<br>";
        }
// else Form, Klammern rausgenommen und else auskommentiert
?>

<!-- 
           <form name="film_suche" action="film_suche.php" method="post">
            <table>
            <tr>
                    <td>Titel</td>
                    <td>
                    <input type=text name="titel" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>Beschreibung</td>
                    <td>
                    <input type=text name="beschreibung" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>Originalversion</td>
                    <td>
                    <input type=radio name="originalversion" value=0 checked> inkl.
                    <input type=radio name="originalversion" value=1> Liste
                    </td>
            </tr>
            </table>
            Link Übersicht leere serien_id
-->
           
            </form>
</body>
</html>

