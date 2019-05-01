 <? php
 
	function jsklassids ($sql, $hierarchie)
	{

		$jsstring = "<script language=\"JavaScript\" type=\"text/javascript\">"
        // ***** aufbauen von Javascript arrays fuer dyn Auswahl DB, Table *****
		$jsstring =  "var groups = new Array(); ";
		$jsstring =  "var klass_id = new Array(); ";
		$jsstring =  "var klass_bez = new Array(); ";
		$jsstring =  "var group_id = new Array(); ";

		$sqlstatement = ($hierarchie);
		// DB-Connection
		try {
			$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

		} catch (PDOException $ex) {
			die('Die Datenbank ist momentan nicht erreichbar!');
		}

		$result = $con->query($sql);
		    or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));

        $rownum=0;
		while ($row = $result->fetch()) {
		
                    $rownum++;
                    $jsstring = "groups[" . $rownum . "] = \"" . $row['bezeichnung'] . "\";";
                    $jsstring = "group_id[" . $rownum . "] = \"" . $row['klass_id'] . "\";";
                    $jsstring = "klass_id[" . $rownum . "] = new Array();";
                    $jsstring = "klass_bez[" . $rownum . "] = new Array();";

					// Check of SQL mit var auch ohne prepare funktioniert!
                    $result2 = $con->query("select s.klass_id,k.bezeichnung from std_klass_hier_strukturen s, std_klassifizierung k where k.klass_id=s.klass_id AND parent_id=" . $row['klass_id'] . " group by k.bezeichnung")
                    	    or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));
                        $cnt = 0;
                        while ( $row = $result->fetch() )
                        {
                            //$fehler .= " Subquery: " . mysql_result($result2,$cnt,"k.bezeichnung");
                            $jsstring = "klass_id[" . $rownum . "][" . $cnt . "] = \"" . $row['klass_id'] . "\";";
                            $jsstring = "klass_bez[" . $rownum . "][" . $cnt . "] = \"" . $row['bezeichnung'] . "\";";
                            $cnt++;
                        }
        }

	}
?>