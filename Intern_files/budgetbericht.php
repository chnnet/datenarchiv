<?php
	session_start();
?>
<html>
<head><title>Budgetbericht</title>

</head>
<body>

<h1> Budgetbericht 12 Monate </h1>
<?php

	// Session-daten auslesen
	$host = $_SESSION['host'];
	$benutzer = $_SESSION['benutzer'];
	$passwort = $_SESSION['passwort'];
	$dbname = $_SESSION['dbname'];
	
function bestimmenFaktor($haeufigkeit)
{
	$faktor = 0;
	if ( $haeufigkeit == "M" )
	{
		$faktor = 1;
	} elseif ( $haeufigkeit == "Z" ) {
		$faktor = 2;
	} elseif ( $haeufigkeit == "Q" ) {
		$faktor = 3;
	} elseif ( $haeufigkeit == "H" ) {
		$faktor = 6;
	} elseif ( $haeufigkeit == "J" ) {
		$faktor = 12;
	}
	return $faktor;
}

function gueltigesMonat($monat) {

	// Jahr aus aktueller Periode verursacht Endlosschleife
	$akt_jahr = $monat/100;
	$akt_jahr = round($akt_jahr,0);

	if ($monat % 100 > 12)
	{
		$monat = (($akt_jahr + 1) * 100) + (($monat % 100) - 12);
	}
	return $monat;
}

function checkGueltigAb($gueltigab, $akt_periode, $faktor) {

		if ( $gueltigab < $akt_periode )
		{
			// erstes gültiges Monat im Budgetzeitraum ermitteln
			$monat = $gueltigab;
			$startmonat = 0;
			while ( $startmonat < $akt_periode )
			{
				$monat = $monat + $faktor;
				$monat = gueltigesMonat($monat);

				$startmonat = $monat;
			}
			$monat = $startmonat;
		}
		else
		{
			$monat = $gueltigab;
		}
		return $monat;
}

function gueltigAb($haeufigkeit, $akt_periode, $gueltigab) {

	$faktor = bestimmenFaktor($haeufigkeit);
	if ( $haeufigkeit == "M" || $haeufigkeit == "E" )
	{
		$monat = $akt_periode;
	}
	else
	{
		$monat = checkGueltigAb($gueltigab, $akt_periode, $faktor);
	}

	return $monat;
}

// DB-Connection
try {
	$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

} catch (PDOException $ex) {
	die('Die Datenbank ist momentan nicht erreichbar!');
}

global $saldo;
global $konto;
global $ausgabe;
global $ktostr;
global $wertepaare;
global $budgetmatrix;
global $akt_periode;
$akt_periode = date("Ym");
// Jahresübergang beachten
global $zaehler;
global $monat;
global $akt_jahr;
global $end_periode;
global $bargeld;
global $bank;
global $kreditkarte;
global $anita_verr;
// Funktion verwenden?
$end_periode = $akt_periode + 12;
$monat = $end_periode % 100;
$akt_jahr = $akt_periode/100;
$akt_jahr = round($akt_jahr,0);
if ($monat > 12)
{
	$end_periode = (($akt_jahr + 1) * 100) + ($monat - 13);
}
	// Anfangssaldo aus Vermögen errechnen, array für salden errechnen
	// Anfangssaldo holen
	$vormonat = $akt_periode - 1;
	$res_saldo = $con->prepare("SELECT saldo_bargeld, saldo_bank, saldo_kreditkarte, saldo_verrAnita from vermoegen where jahrmonat=" . $vormonat);
	$res_saldo->execute(array($vormonat))
		or die ('Fehler in der Abfrage. ');
	if ($res_saldo)
	{
		$row = $res_saldo->fetch();
		$bargeld = $row['$saldo_bargeld'];
		$bank = $row['$saldo_bank'];
		$kreditkarte = $row['$saldo_kreditkarte'];
		$anita_verr = $row['$saldo_verrAnita'];
	}
	else
	{
		echo "MySQL Error (" . mysql_errno() . ") :" . mysql_error();
		$bargeld = 0;
		$bank = 0;
		$kreditkarte = 0;
	}

	// Budgetsätze nach Konto sortiert
	$result = $con->prepare("SELECT * from budget b, kontenstamm k where b.ktonr = k.ktonr and b.gueltigab <= " . $end_periode .  " and (b.gueltigbis >= " . $end_periode .  " or b.gueltigbis between " . $akt_periode . " and " . $end_periode . ") and b.budget_id=1 order by b.ktonr, b.gueltigab");
	$result->execute(array($end_periode, $end_periode, $akt_periode, $end_periode));
        if ($result)
        {
	    	$summe = 0;
			while ($row = $result->fetch()) {

					// Felder belegen
					$bezeichnung = $row['bezeichnung'];
					$gueltigab = $row['gueltigab'];
					$gueltigbis = $row['gueltigbis'];
					$haeufigkeit = $row['haeufigkeit'];
					$vorzeichen = $row['vorzeichen'];
					$betrag = $row['betrag'];

					// kontonr, Periode noch berücksichtigen?
					$zaehler = 1;
					if ( $konto != $bezeichnung )
					{
						// Ausgabe
						if ($konto) $budgetmatrix[$konto] = $wertepaare;
						// konto neu belegen
						$konto = $bezeichnung;
						unset($wertepaare);
					}

					// Anfangsmonat bestimmen, nur jene kleiner akt_periode?
					if ( $gueltigab < $akt_periode )
					{
						// Häufigkeit berücksichtigen
						if ($haeufigkeit && $akt_periode && $gueltigab)
						{
							$monat = gueltigAb($haeufigkeit, $akt_periode, $gueltigab);
						}
						else
						{
							$monat = $akt_periode;
						}
					}
					else
					{
						$monat = $gueltigab;
					}
					global $faktor;
					$faktor = bestimmenFaktor($haeufigkeit);

					if ( $haeufigkeit != "E" && $monat < $end_periode)
					{
						while ($monat <= $end_periode)
						{
							// monatswerte - erstes if rausnehmen wenn Funktion gueltig ab fertig
							if ($monat >= $gueltigab)
							{
								if ($monat <= $gueltigbis)
								{
									// Monatswerte aufsummieren
									if ( isset($wertepaare[$monat]) )
									{
										$wert = $wertepaare[$monat];
										$akt_wert = $vorzeichen . $betrag;
										$wertepaare[$monat] = $wert + $akt_wert;
									}
									else
									{
										$wertepaare[$monat] = $vorzeichen . $betrag;
									}
								}
							}
							$monat = $monat + $faktor; // abhängig von Häufigkeit
							$monat = gueltigesMonat($monat);
							if ($monat < $akt_periode)
							{
								echo "<br>Fehler bei " . $bezeichnung . " Monatswert: " . $monat . "<br>";
								$monat = $end_periode +1;
							}
							$zaehler++;
						} // while Monat

					}
					else
					{
						$wertepaare[$gueltigab] = $vorzeichen . $betrag;
					}

            } // while resultset

			// Wert aus letzdem while-Durchlauf hinzufügen
			$budgetmatrix[$konto] = $wertepaare;
//var_dump($budgetmatrix);
			// HTML-Ausgabe
			$arr_len = count($budgetmatrix);
			
			$i=1;
			reset($budgetmatrix);
			echo "<table border=1><tr><th>Konto</th>";
			$monat = $akt_periode;
			while ( $monat <= $end_periode )
			{
				echo "<th>" . $monat . "</th>";
				// monatssalden bilden
				$saldo = 0;
				foreach ($budgetmatrix as $wert)
				{
					if (isset($wert[$monat]))
					{
						$summe = $wert[$monat];
						$saldo = $saldo + $summe;
					}
				}
				// KK berücksichtigen, verr_Anita
				if ($i=1 ) $saldo = $saldo + $kreditkarte + $anita_verr;
				$monatssaldo[$monat] = $saldo;
				// nächstes Monat setzen
				$monat = $monat + 1;
				if ($monat % 100 > 12)
				{
					$monat = (($akt_jahr + 1) * 100) + (($monat % 100) - 12);
				}
			}
			echo "</tr><tr><td></td>";
			// Anfangssaldo
			$saldo = $bargeld + $bank;
			foreach ($monatssaldo as $wert)
			{
				echo "<td align=right><b>" . number_format($saldo,2,",",".") . "</b></td>";
				$saldo = $saldo + $wert;
			}

			echo "</tr>";
			reset($budgetmatrix);

			while ( $i <= $arr_len )
			{
				echo "<tr>";
				echo "<td>" . key($budgetmatrix) . "</td>";
				$monat = $akt_periode;
				while ( $monat <= $end_periode )
				{
					if ( isset($budgetmatrix[key($budgetmatrix)][$monat]) )
					{
						echo "<td align=right>" . number_format($budgetmatrix[key($budgetmatrix)][$monat],2,",",".") . "</td>";
						// $saldo += $budgetmatrix[key($budgetmatrix)][$monat];
					}
					else
					{
						echo "<td align=right>" . number_format(0.00,2,",",".") . "</td>";
					}
					$monat = $monat + 1;
					if ($monat % 100 > 12)
					{
						$monat = (($akt_jahr + 1) * 100) + (($monat % 100) - 12);
					}
				}
				echo "</tr>";
				next($budgetmatrix);
				$i++;
			}
			// Endbestände
//			$saldo = $bargeld+$bank+$kreditkarte; //Anfangsbestand
			$saldo = $bargeld+$bank; //Anfangsbestand
			// Kreditkarte im aktuellen Monat als Zahlung berücksichtigen
			echo "<tr><td>Kreditkarte</td><td align=right>" . number_format($kreditkarte,2,",",".") . "</td>";			
			echo "<tr><td>Anita Verr</td><td align=right>" . number_format($anita_verr,2,",",".") . "</td>";			
			for ($i=1;$i<12;$i++)
				echo "<td align=right>0</td>";
			echo "</tr><tr><td></td>";			
			foreach ($monatssaldo as $wert)
			{
				$saldo = $saldo + $wert;
				echo "<td align=right><b>" . number_format($saldo,2,",",".") . "</b></td>";
			}
			echo "</tr></table>";

        }
        else {
        	echo "Keine Daten!<br>";
			echo 'Fehler in der Abfrage. ';

        }
?>
</body>
</html>
