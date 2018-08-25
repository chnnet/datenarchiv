 <?php
    session_start();
 
	function jsklassids ($hierarchie, $parent, $dbname)
	{

        // ***** Parameter auslesen session *****
        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];
        $benutzer_id = $_SESSION['keynr'];

        $con = mysql_connect($host, $benutzer, $passwort);
		mysql_select_db($dbname);
		$result = mysql_query("select s.klass_id,k.bezeichnung from std_klass_hier_strukturen s, std_klassifizierung k where k.klass_id=s.klass_id AND s.klassh_id = " . $hierarchie . " AND s.parent_id= " . $parent . " group by k.bezeichnung");
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
                    $jsstring = $jsstring . "<option value=\"" . mysql_result($result,$i,"s.klass_id") . "\">" . mysql_result($result,$i,"k.bezeichnung") . "</option>";

            $i++;
            }
        }
		return $jsstring;
	}
?>