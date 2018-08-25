<?php
	session_start();

	global $form_status;
	global $host;
	global $benutzer;
	global $passwort;
	global $dbname;
	global $tsaldo;
	global $betrag;
	global $ggbetrag;
	global $ggkonto;
	global $transaktions_id;
	global $datum;
	global $text;

	// Session-daten auslesen
	$host = $_SESSION['host'];
	$benutzer = $_SESSION['benutzer'];
	$passwort = $_SESSION['passwort'];
	$dbname = $_SESSION['dbname'];
?>
<html>
<head>
	<title>Rechnung erfassen</title>

</head>

<body>

<h1>Rechnung erfassen</h1>
<br>

<?php
	$con = mysql_connect($host, $benutzer, $passwort);
	if (!$con) {
		exit('Connect Error (' . mysql_errno() . ') ' . mysql_error());
	}

	mysql_select_db($dbname);

	if (isset($_POST['transaktions_id']))
	{
            // Parameter auslesen
            $transaktions_id = $_POST['transaktions_id'];
            $tsaldo = $_POST['tsaldo'];
            $tag_Datum = $_POST['tag_Datum'];
            $monat_Datum = $_POST['monat_Datum'];
            $jahr_Datum = $_POST['jahr_Datum'];
            $datum = $jahr_Datum . "-" . $monat_Datum . "-" . $tag_Datum;
            $konto = $_POST['konto'];
            if (isset($ggkonto)) $ggkonto = $_POST['ggkonto'];
            $betrag_int = $_POST['betrag_int'];
            $betrag_dec = $_POST['betrag_dec'];
            $betrag = $betrag_int + ($betrag_dec / 100);
            $text = $_POST['text'];

		// submit-stati: 1-3 Gegenbuchungen, 4 Abbruch, 5 neuer Teilsatz
		if (isset($_POST['anita']))
		{
			$form_status = 1;
		}
		else
		{
			if (isset($_POST['bank']))
			{
				$form_status = 2;
			}
			else
			{
				if (isset($_POST['bar']))
				{
					$form_status = 3;
				}
				else
				{
					if (isset($_POST['abbruch']))
					{
						$form_status = 4;
					}
					else
					{
						if (isset($_POST['satz']))
						{
							$form_status = 5;
						}
						else
						{
							if (isset($_POST['neu']))
							{
								$form_status = 0;
							}
						}
					}
				}
			}
		}
		
		// sql-statement setzen
		switch ($form_status)
		{
			// Anita
			case 1:
				$tsaldo = $tsaldo + $betrag;
				$ggbetrag = $tsaldo * -1;
				$tsaldo = $tsaldo + $ggbetrag;
				$ggkonto = "31000";
			break;
			// Bank
			case 2:
				$tsaldo = $tsaldo + $betrag;
				$ggbetrag = $tsaldo * -1;
				$tsaldo = $tsaldo + $ggbetrag;
				$ggkonto = "28000";
			break;
			// Bar
			case 3:
				$tsaldo = $tsaldo + $betrag;
				$ggbetrag = $tsaldo * -1;
				$tsaldo = $tsaldo + $ggbetrag;
				$ggkonto = "27000";
			break;
                        // satz
			case 5:
				$tsaldo = $tsaldo + $betrag;
			break;

		}
                // Daten in DB schreiben - 2 sql-stmt für konto und ggkonto (falls notwendig)
		if ($form_status != 4 && $form_status != 0)
		{
                    $erstellt = date("Y-m-d");
                    $jahrmonat = ($jahr_Datum * 100) + $monat_Datum;

                    // journal_id über auto_increment
                    $sql1 = "insert into kontobewegungen (transaktions_id, datum, jahrmonat, konto, betrag, text, erstellt, status) values (" . $transaktions_id . ",'" . $datum . "'," . $jahrmonat . "," . $konto . "," . $betrag . ",'" . $text . "','" . $erstellt . "','N')";
                    if ($form_status != 5)
                    {
                    	$sql2 = "insert into kontobewegungen (transaktions_id, datum, jahrmonat, konto, betrag, text, erstellt, status) values (" . $transaktions_id . ",'" . $datum . "'," . $jahrmonat . "," . $ggkonto . "," . $ggbetrag . ",'" . $text . "','" . $erstellt . "','N')";
                    }
		}
		if ($form_status == 4) // Abbruch
		{
			$sql2 = "delete from kontobewegungen where transaktions_id=" . $transaktions_id;
		}
	
		if ( $form_status != 0 )
		{
                    if (isset($sql1))
                    {
                        echo "sql1: " . $sql1;
                        mysql_select_db($dbname);
                        $res=mysql_query($sql1);
                        if (!$res) {
                                echo "Fehler sql1: (" . mysql_errno() . ") " . mysql_error();
                        }
                    }
                    if (isset($sql2))
                    {
                        echo "sql2: " . $sql2;
                        mysql_select_db($dbname);
                        $res=mysql_query($sql2);
                        if (!$res) {
                                echo "Fehler sql2: (" . mysql_errno() . ") " . mysql_error();
                        }
                    }
		}
	}
	else
	{
			// ***** Verbindugsaufbau zu MySQL *****

                // Transkations_id
                global $jahr;
                $jahr = date("Y");
                $jahr = $jahr * 10000;
//echo "Jahr: " . $jahr;
		$result = mysql_query('SELECT max(transaktions_id) from kontobewegungen where transaktions_id >' . $jahr);
		if (!$result) {
			exit('Query Fehler (' . mysql_errno() . ') ' . mysql_error());
                        $transaktions_id = $jahr;
		}
		else
		{
			$transaktions_id = mysql_result($result,0,0);
         if ($transaktions_id != NULL)
         {
            $transaktions_id++;
         }
         else
         {
            $transaktions_id = $jahr;
         }
		}

	}
echo "tsaldo: " . $tsaldo;
?>

<form name="transaktion" action="rech_erf.php" target = "main" method= "post">
<table>
<tr>
<tr>
		<td><b>Transaktion Nu.</b></td>
		<td>
		<input type=text name="transaktions_id" size="10" maxlength="11" value="<?php echo $transaktions_id; ?>" readonly/>
		</td>
</tr>
<tr>
		<td><b>Datum</b></td>
		<td>
		<select name="tag_Datum">
		<?php
			$tag = date("d");
			$monat = date("m");
			$jahr = date("Y");

			$i = 1;
			while ($i < 32)
			{
				if ($i == $tag)
				{
					echo "<option value = \"" . $i . "\" selected>" . $i . "</option>";
				}
				else {
					echo "<option value = \"" . $i . "\" >" . $i . "</option>";
				}
				$i++;
			}
		?>
		</select>
		<select name="monat_Datum">
		<?php
			$i = 1;
			while ($i < 13)
			{
				if ($i == $monat)
				{
					echo "<option value = \"" . $i . "\" selected>" . $i . "</option>";
				}
				else {
					echo "<option value = \"" . $i . "\" >" . $i . "</option>";
				}
				$i++;
			}
		?>
		</select>
		<?php
			echo "<input type=text name=\"jahr_Datum\" size=\"5\" maxlength=\"4\" value=\"" . $jahr . "\" />";
		?>
		</td>
</tr>
</table>
<br>
<table>
<tr>
<th>Konto Nr.</th><th>Betrag</th><td>Text</th>
<?php
        echo "From Status: " . $form_status;
	if ($form_status == 5)
	{
            $result = mysql_query("select k.bezeichnung,b.betrag,b.text from kontobewegungen b, kontenstamm k where b.konto=k.ktonr and b.transaktions_id = " . $transaktions_id);
            if ($result)
            {
		$num=mysql_numrows($result);

		$i=0;
		while ($i < $num) {

			echo "<tr>";
			echo "<td>" . mysql_result($result,$i,"k.bezeichnung") . "</td><td align=right>" . mysql_result($result,$i,"b.betrag") . "</td><td>" . mysql_result($result,$i,"b.text") . "</td>";
			echo "</tr>";
			$i++;
		}
		echo "<tr>";
		echo "<td align=right>Saldo</td><td align=right>" . $tsaldo . "</td>";
		echo "</tr>";
            }
            else
            {
                echo "Fehler Seltrans: (" . mysql_errno() . ") " . mysql_error();
            }
	}		
?>
<tr>
<td>
<select name="konto">
<?php
		$result = mysql_query("select ktonr, bezeichnung from kontenstamm where ktonr > 39999 and ktonr < 80000");
                if ($result)
                {
                    $num=mysql_numrows($result);

                    $i=0;
                    while ($i < $num) {
                            echo "<option value = \"" . mysql_result($result,$i,"ktonr") . "\" >" . mysql_result($result,$i,"bezeichnung") . "</option>";
                            $i++;
                    }
                }
?>
</select>
</td>
       <td><input name="betrag_int" type="text" size="7" />,<input name="betrag_dec" type="text" size="2" /></td>
       <td><input type=text name="text" size=20 /></td>
</tr>
<tr>
</table>
<br>
<input type=hidden name="tsaldo" value="<?php if (isset($tsaldo)) echo $tsaldo; ?>" />
		<table>
		<td><b>Gegenbuchung</b></td>
		<td><input name="anita" type=submit value="Anita" /></td>
		<td><input name="bank" type=submit value="Bank" /></td>
		<td><input name="bar" type=submit value="Bar" /></td>
		<td><input name="satz" type=submit value="Satz" /></td>
		</tr>
		</table>
		<br>
		<input name="neu" type=submit value="neue Transaktion" />
		<input name="abbruch" type=submit value="Transaktion abbrechen (l&ouml;schen)" />
</form>
</body>
</html>
