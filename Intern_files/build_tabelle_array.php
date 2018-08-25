 <?php
    session_start();
 
	function jsklassids ($tabelle, $id, $bezeichnung)
	{

        // ***** Parameter auslesen session *****
        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];
        $benutzer_id = $_SESSION['keynr'];

        $con = mysql_connect($host, $benutzer, $passwort);
		mysql_select_db($dbname);
		$result = mysql_query("select " . $id . "," . $bezeichnung . " from " . $tabelle ." order by " . $id);
        $num=mysql_num_rows($result);
        if (!$result)
        {
            //exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
            $fehler = "MySQL Fehler: (" . mysql_errno() . ") " . mysql_error();
        }
        else
        {
            // $fehler = "Query: " . $num;
            $i=0;
            $rownum=0;
            while ($i < $num) {

                    $rownum++;
                    $jsstring = $jsstring . "<option value=\"" . mysql_result($result,$i,0) . "\">" . mysql_result($result,$i,1) . "</option>";

            $i++;
            }
        }
		return $jsstring;
	}
?>