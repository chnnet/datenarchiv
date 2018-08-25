<?php
        session_start();
?>
<html>
<head>
	<title>Archiv-Suche</title>

</head>

<body>

<h2>Archiv-Suche</h2>

<?php

	if ($_POST['titel'])
	{

	// ***** Parameter infos auslesen *****
	$titel = $_POST['titel'];
	$klass_id = $_POST['klass_id'];
	$schlagw = $_POST['schlagw1'];
	$quelle = $_POST['quelle'];

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
		$result = mysql_query('SELECT k.bezeichnung, f.Titel, f.Filename, f.datum, f.Quelle from file_archiv f, std_klassifizierung k where f.klass_id=k.klass_id and f.Titel like \'%' . $titel . '%\'');
		if (!$result) {
			exit('Query Fehler (' . mysql_connect_errno() . ') ' . mysql_connect_error());
		} else
                {
                    $num=mysql_numrows($result);
                    echo "<table border=\"1\"><tr><th>Klassifizierung</th><th>Titel</th><th>Filename</th><th>Datum</th><th>Quelle</th></tr>";

                    $i=0;
                    while ($i < $num) {

                            // Suchergebnis in Liste anzeigen
                            echo "<tr><td>" . mysql_result($result,$i,"k.bezeichnung") . "</td><td>" . mysql_result($result,$i,"f.Titel") . "</td><td>" . mysql_result($result,$i,"f.Filename") . "</td><td>" . mysql_result($result,$i,"f.datum") . "</td><td>" . mysql_result($result,$i,"f.Quelle") . "</td></tr>";
                            $i++;
                    }
                    echo "</table>";
                }

	} else // Form
        {
?>
           <form name="archiv_suche" action="archiv_suche.php" method="post">
            <table>
            <tr>
                    <td>Titel</td>
                    <td>
                    <input type=text name="titel" size="45" maxlength="45"/>
                    </td>
            </tr>
            <tr>
                    <td>klass_id</td>
                    <td>
                    <input type=text name="klass_id" size="5" maxlength="5"/>
                    </td>
            </tr>
            <tr>
                    <td>Schlagwort</td>
                    <td>
                    <input type=text name="schlagw1" size="25" maxlength="25"/>
                    </td>
            </tr>
            <tr>
                   <td>Quelle</td>
                   <td>
                    <input type="radio" name="quelle" value="Presse">Presse
                    <input type="radio" name="quelle"  value="Standard">Standard
                    <input type="radio" name="quelle"  value="ORF Teletext">ORF Teletext
                    <input type="radio" name="quelle"  value="Spiegel">Spiegel
                    <input type="radio" name="quelle"  value="Kurier">Kurier
                    <input type="radio" name="quelle"  value="TIME">TIME
                    <input type="radio" name="quelle"  value="ORF ON">ORF ON
                    <input type="radio" name="quelle"  value="ct">ct
                    </td>
            </tr>
            <tr>
                    <td></td><td>
                    <input type="radio" name="quelle"  value="Wall Street Journal">Wall Street Journal
                    <input type="radio" name="quelle"  value="Almuni News">Alumni News
                    <input type="radio" name="quelle"  value="Wirtschaftsblatt">Wirtschaftsblatt
                    <input type="radio" name="quelle"  value="trend">trend
                    <input type="radio" name="quelle"  value="Economist">Economist
                    <input type="radio" name="quelle"  value="zbp">zbp
                    <input type="radio" name="quelle"  value="APA">APA
                    <input type="radio" name="quelle"  value="CD Austria">CD Austria
                    </td>
            </tr>
            <tr>
                    <td></td><td>
                    <input type="radio" name="quelle"  value="TIM">TIM
                    <input type="radio" name="quelle"  value="VISA Magazin">VISA Magazin
                    <input type="radio" name="quelle"  value="profil">profil
                    <input type="radio" name="quelle"  value="New Scientist">New Scientist
                    <input type="radio" name="quelle"  value="iX">iX
                    <input type="radio" name="quelle"  value="preview">preview
                    <input type="radio" name="quelle"  value="Wohnen">Wohnen
                    <input type="radio" name="quelle"  value="Wiener">Wiener
                    </td>
            </tr>
            <tr>
                    <td></td><td>
                    <input type="radio" name="quelle"  value="Spektrum der Wissenschaft">Spektrum der Wissenschaft
                    <input type="radio" name="quelle"  value="PC Professional">PC Professional
                    <input type="radio" name="quelle"  value="Wikipedia">Wikipedia
                    <input type="radio" name="quelle"  value="The Times">The Times
                    <input type="radio" name="quelle"  value="Die Zeit">Die Zeit
                    <input type="radio" name="quelle"  value="Telepolis">Telepolis
                    </td>
            </tr>
            <tr>
                    <td></td><td>
                    <input type="radio" name="quelle"  value="Technology Review">Technology Review
                    <input type="radio" name="quelle"  value="Computerwelt">Computerwelt
                    <input type="radio" name="quelle"  value="Financial Times">Financial Times
                    <input type="radio" name="quelle"  value="CNN">CNN
                    <input type="radio" name="quelle"  value="New York Times">New York Times
                    <input type="radio" name="quelle"  value="NZZ">NZZ
                   </td>
            </tr>
            </table>

            <input type=hidden name="blgform" value="1"/>
            <input type=submit value="Files suchen"/>
            </form>
 <?php
        }
?>

</body>
</html>

