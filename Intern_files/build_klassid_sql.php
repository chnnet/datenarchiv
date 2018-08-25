<? php

	function sqlstatment ($hierarchie)
	{
        $sqlstring = ("select s.klass_id,k.bezeichnung from std_klass_hier_strukturen s, std_klassifizierung k where k.klass_id=s.klass_id AND s.klassh_id= " + $hierarchie + " AND s.parent_id=0 group by k.bezeichnung");
	}
	
	return $sqlstring;
?>