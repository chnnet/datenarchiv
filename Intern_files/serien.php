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
	if (isset($_GET['serien_id'])) {

		$serien_id = $_GET['serien_id'];
		$serienname = $_GET['name']; 
	
	}

	// ***** Session infos auslesen *****

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
    
	if (isset($_GET['serien_id'])) {

			$result = $con->prepare('SELECT * from serien_folgen where serie_id = \'' . $serien_id . '\'');
			$result->execute(array($serien_id))
				or die('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));

	    echo "<h3>" . $serienname . "</h3>";
        echo "<a href=\"serien.php\"> Serien&uuml;bersicht</a><br>";
    	echo "<table border=\"1\"><tr><th>Folge</th><th>Titel</th><th>Premiere DE</th><th>Originaltitel</th><th>Premiere</th></tr>";

        while ($row = $result->fetch()) {
				
            // Suchergebnis in Liste anzeigen
            echo "<tr><td>" . $row['folge_id'] . "</td><td>" . $row['titel_de'] . "</td><td>" . $row['premiere_de'] . "</td><td>" . $row['titel_en'] . "</td><td>" . $row['premiere_en'] . "</td></tr>";
        }
		echo "</table>";
	    echo "<br><a href=\"serien.php\"> Serien&uuml;bersicht</a>";
	}    else {
			
		$result = $con->query('SELECT * from serien')
			or die('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));

	    echo "<h3>Serien&uumlbersicht</h3>";
        echo "<table border=\"0\">";

        while ($row = $result->fetch()) {
						
                // Suchergebnis in Liste anzeigen
                echo "<tr><td> <a href=\"serien.php?serien_id=" . $row['serien_id'] . "&name=" . $row['name'] . "\">" . $row['name'] . "</a></td></tr>";
        }
		echo "</table>";
    }


    echo "<br>";
        

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

