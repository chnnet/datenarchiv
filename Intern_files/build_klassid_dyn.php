 <? php
 
	function jsklassids ($sql, $hierarchie)
	{

		$jsstring = "<script language=\"JavaScript\" type=\"text/javascript\">"
        // ***** aufbauen von Javascript arrays fuer dyn Auswahl DB, Table *****
		$jsstring =  "var groups = new Array(); ";
		$jsstring =  "var klass_id = new Array(); ";
		$jsstring =  "var klass_bez = new Array(); ";
		$jsstring =  "var group_id = new Array(); ";

		sqlstatement = ($hierarchie);
		mysql_select_db($dbname);
		$result = mysql_query($sql);
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
                    $jsstring = "groups[" . $rownum . "] = \"" . mysql_result($result,$i,"k.bezeichnung") . "\";";
                    $jsstring = "group_id[" . $rownum . "] = \"" . mysql_result($result,$i,"s.klass_id") . "\";";
                    $jsstring = "klass_id[" . $rownum . "] = new Array();";
                    $jsstring = "klass_bez[" . $rownum . "] = new Array();";

                    $result2 = mysql_query("select s.klass_id,k.bezeichnung from std_klass_hier_strukturen s, std_klassifizierung k where k.klass_id=s.klass_id AND parent_id=" . mysql_result($result,$i,"s.klass_id") . " group by k.bezeichnung");
                    if (!$result2)
                    {
                        $fehler = "MySQL Fehler: (" . mysql_errno() . ") " . mysql_error();
                    }
                    else
                    {
                        $num2 = mysql_num_rows($result2);
                        $cnt = 0;
                        while ( $cnt < $num2 )
                        {
                            //$fehler .= " Subquery: " . mysql_result($result2,$cnt,"k.bezeichnung");
                            $jsstring = "klass_id[" . $rownum . "][" . $cnt . "] = \"" . mysql_result($result2,$cnt,"s.klass_id") . "\";";
                            $jsstring = "klass_bez[" . $rownum . "][" . $cnt . "] = \"" . mysql_result($result2,$cnt,"k.bezeichnung") . "\";";
                            $cnt++;
                        }
                    }
            $i++;
            }

        }
	}
?>