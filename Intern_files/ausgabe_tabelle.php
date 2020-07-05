<?php

	function ausgabeTabelleIDLink($con, $sql, $id, $fields)
	{

		echo "<table border=\"1\">";
		echo "<tr>";
		foreach ($fields as $feld)
		{
			echo "<th>" . $feld . "</th>";	    	
		}
		echo "</tr>";

		$result = $con->prepare($sql);
		if($result->execute()) {
    		while($row = $result->fetch()) {	
								
				// Suchergebnis in Liste anzeigen
	    		$feldnr = 0;
				foreach ($fields as $feld)
				{
					if ($feldnr == 0)
					{
						echo "<tr><td><a href=\"sendungen_details.php?sendung_id=" . $row[$feld] . "\">" . $row[$feld] . "</a></td>";
					} else {
					
						echo "<td>" . $row[$feld] . "</td>";				
					}
					$feldnr++;
				}
	    			echo "</tr>";
			}
			echo "</table>";
			echo "<br>";
		}
	}
?>