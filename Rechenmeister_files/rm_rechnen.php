<html>
	<head>
		<title>Rechnen</title>

		<script type="text/javascript">
			function init () {
				document.rm_rechnen.ergebnis.focus();
			}
		</script>

		</head>
	<body onload="init()">
<?php
	// prüfen ob Benutzer eingelogged ist
	// Session-daten auslesen
	$host = $_SESSION['host'];
	$benutzer = $_SESSION['benutzer'];
	$passwort = $_SESSION['passwort'];
	$dbname = $_SESSION['dbname'];

	if ($host && $benutzer)
	{

		// Abfragen ob leer, sonst Erg prüfen
		
		$ergebnis = $_POST['ergebnis'];
		$test1= $_POST['zahl1'];
		$test2= $_POST['zahl2'];
		$zahlenraum = $_POST['zahlenraum'];
		$zaehler = $_POST['zaehler'];
		$richtig = $_POST['richtig'];
		$art = $_POST['art'];
	
		// Ergebnis, Zaehler
		$zaehler = $zaehler + 1;
		if ($zaehler > 0)
		{
			switch ($art) {
				case 1:	// Addieren
					$test = $test1 + $test2;
					break;
				case 2:	// Subtrahieren
					$test = $test1 - $test2;
					break;
				case 3:	// Wieviel
					$test = abs($test1 - $test2);				
					break;
			}
			if ($test == $ergebnis)
			{
				$richtig = $richtig + 1;
			}
		}
		
		// Zufallszahlen generieren, cases unterscheiden
		switch ($art) {
			case 1:	// Addieren
				$zahl1 = rand(0,$zahlenraum);
				$max = $zahlenraum - $zahl1;
				$zahl2 = rand(0,$max);
				break;
			case 2:	// Subtrahieren
				$zahl1 = rand(0,$zahlenraum);
				$zahl2 = rand(0,$zahl1);
				break;
			case 3:	// Wieviel
				$zahl1 = rand(0,$zahlenraum);
				$zahl2 = rand(0,$zahlenraum);			
				break;
		}
	?>
		<h1>Los geht's</h1>
	
			<td><label>Zahlenraum: </label></td><td><?php echo $zahlenraum;	?></td>
	
			<form style="height: 369px;" method="post" action="rm_rechnen.php" name="rm_rechnen">
			<?php
				if ($zaehler > 0 )
				{
					echo "<table>";
					echo "<tbody>";
					echo "<tr>";
						echo "<td><label>Letzte Rechnung: </label></td>";
						switch ($art) {
							case 1:	// Addieren
								echo "<td> $test1 </td>";
								echo "<td>+</td>";
								echo "<td> $test2 </td><td>=</td><td> $test </td>";
							break;
							case 2:	// Subtrahieren
								echo "<td> $test1 </td>";
								echo "<td>-</td>";
								echo "<td> $test2 </td><td>=</td><td> $test </td>";
							break;
							case 3:	// Wieviel
								if ( ($zaehler % 2) == 0)
								{
									// letzte Rechnung war minus
									if ($test1 > $test2)
									{
										echo "<td> $test1 </td>";
										echo "<td>-</td>";
										echo "<td> $test </td><td>=</td><td> $test2 </td>";
									}
									else
									{
										echo "<td> $test2 </td>";
										echo "<td>-</td>";
										echo "<td> $test </td><td>=</td><td> $test1 </td>";
									}
								}
								else
								{
									// letzte Rechnung war plus
									if ($test1 > $test2)
									{
										echo "<td> $test2 </td>";
										echo "<td>+</td>";
										echo "<td> $test </td><td>=</td><td> $test1 </td>";
									}
									else
									{
										echo "<td> $test1 </td>";
										echo "<td>+</td>";
										echo "<td> $test </td><td>=</td><td> $test2 </td>";
									}
								}
							break;
						}
					echo "</tr>";
					echo "<tr>";
						echo "<td>Dein Ergebnis: </td><td> $ergebnis </td>";
					echo "</tr>";
					echo "</table>";
							if ($test == $ergebnis)
							{
								echo "<font size=\"24\">&#9786;</font>";
							}
							else
							{
								echo "<font size=\"24\">&#9785;</font>";
							}
						}
				?>
					<table>
					<tr>
					</tr>
					<tr>
						<td><label>Rechnungen: </label></td><td><?php echo $zaehler;	?></td>
						<td><label>Richtig: </label></td><td><?php echo $richtig;	?></td>
					</tr>
					<tr>
					</tr>
					<tr>
				<?php
					switch ($art) {
						case 1:	// Addieren
							echo "<td> $zahl1 </td>";
							echo "<td>+</td>";
							echo "<td> $zahl2 </td>";
						break;
						case 2:	// Subtrahieren
							echo "<td> $zahl1 </td>";
							echo "<td>-</td>";
							echo "<td> $zahl2 </td>";
						break;
						case 3:	// Wieviel
							if ( ($zaehler % 2) == 0)
							{
								if ($zahl1 > $zahl2)
								{
									echo "<td> $zahl2 </td>";
									echo "<td>+</td>";
									echo "<td><input type=\"number\" maxlength=\"3\" size=\"3\" name=\"ergebnis\"></td>";
									echo "<td>=</td>";
									echo "<td> $zahl1 </td>";
								}
								else
								{
									echo "<td> $zahl1 </td>";
									echo "<td>+</td>";
									echo "<td><input type=\"number\" maxlength=\"3\" size=\"3\" name=\"ergebnis\"></td>";
									echo "<td>=</td>";
									echo "<td> $zahl2 </td>";
								}
							}
							else
							{
								if ($zahl1 > $zahl2)
								{
									echo "<td> $zahl1 </td>";
									echo "<td>-</td>";
									echo "<td><input type=\"number\" maxlength=\"3\" size=\"3\" name=\"ergebnis\"></td>";
									echo "<td>=</td>";
									echo "<td> $zahl2 </td>";
								}
								else
								{
									echo "<td> $zahl2 </td>";
									echo "<td>-</td>";
									echo "<td><input type=\"number\" maxlength=\"3\" size=\"3\" name=\"ergebnis\"></td>";
									echo "<td>=</td>";
									echo "<td> $zahl1 </td>";
								}
							}
							break;
						}
					if ($art < 3)
					{
						echo "</tr>";
						echo "<tr>";
							echo"<td><label>Ergebnis: </label></td>";
							echo "<td><input type=\"number\" maxlength=\"3\" size=\"3\" name=\"ergebnis\"></td>";
						echo "</tr>";
					}
					
					?>
								</tbody>
							</table>
							<input name="zahlenraum" type="hidden" value="<?php echo $zahlenraum;	?>">
							<input name="zaehler" type="hidden" value="<?php echo $zaehler;	?>">
							<input name="richtig" type="hidden" value="<?php echo $richtig;	?>">
							<input name="zahl1" type="hidden" value="<?php echo $zahl1;	?>">
							<input name="zahl2" type="hidden" value="<?php echo $zahl2;	?>">
							<input name="art" type="hidden" value="<?php echo $art;	?>">
							<input value="Fertig" type="submit"></form>
						</form>
					<?php
					
					} // endif
			else // nicht eingelogged
			{
				echo "Sie müssen sich einloggen um die Site zu nutzen!";
			}	

			?>

	</body>
</html>