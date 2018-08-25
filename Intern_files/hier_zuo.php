<?php
        session_start();
?>
<html>
<head>
	<title>Klassifizierung Zuordnung</title>

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

        global $benutzer;
        global $passwort;
        global $dbname;
        global $host;
        global $klass_id;
        global $klassh_id;
        global $parent_id;
        global $rownum;
        global $fehler;
        global $message;
        global $sbutton;

	// ***** Parameter auslesen session *****
        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];


	// ***** Parameter auslesen - Seite *****
        if (isset($_POST['klassh_id'])) {
            $klassh_id = $_POST['klassh_id'];
        }
        if (isset($_POST['klass_id'])) {
            $klass_id = $_POST['klass_id'];
        }
        if (isset($_POST['parent_id'])) {
            $parent_id = $_POST['parent_id'];
        } else {
            $parent_id = 0;
        }

        // submit-value auslesen
        if (isset($_POST['Hierarchie'])) {
                    // parent_id bei klass_ids = 0
                    $parent_id = 0;

        }
        if (isset($_POST['speichern'])) {

                    if ( $klass_id )
                    {
                            $sql = "INSERT INTO std_hier_strukturen VALUES (" . $klassh_id . "," . $klass_id . "," . $parent_id . ",'" . $datum . "','" . $datum . "')";
                    }

                    mysql_connect($host,$benutzer,$passwort);
                    mysql_select_db($dbname);
                    $result = mysql_query($sql);
                    if (!$result)
                    {
                        //exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
                        $fehler = "MySQL Fehler: (" . mysql_errno() . ") " . mysql_error();
                    }
                    else
                    {
                        $message = "Satz in File-Archiv wurde erfolgreich angelegt!";
                    }
        }
?>

</head>

<body>

<h2>Klassifizierung - Zuordnung</h2>

<form name="hier_zuo" action="hier_zuo.php" target="main" method="post">
<table>
<tr>
	<td>Hierarchie</td>
	<td>
	<select name="klassh_id" >
<?php
            // Hierarchiewerte laden bzw. Übergabewert selektieren
            mysql_connect($host,$benutzer,$passwort);
            mysql_select_db($dbname);
            $result = mysql_query("SELECT klassh_id, bezeichnung FROM std_klass_hierarchien order by bezeichnung");
            if (!$result)
            {
                //exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
                $fehler = "MySQL Fehler: (" . mysql_errno() . ") " . mysql_error();
            }
            else
            {
                $num=mysql_num_rows($result);
                $i=0;
                $rownum=0;
                while ($i < $num) {

                        $rownum++;
                        if ($klassh_id == mysql_result($result,$i,"klassh_id")) {
                            echo "<option value=\"" . mysql_result($result,$i,"klassh_id") . "\" selected>" . mysql_result($result,$i,"bezeichnung") . "</option>";
                        } else {
                            echo "<option value=\"" . mysql_result($result,$i,"klassh_id") . "\">" . mysql_result($result,$i,"bezeichnung") . "</option>";
                        }
                        $i++;
                }
            }
?>
        </select>
	</td>
        <td>
        <input type=submit name="Hierarchie" value="Hierarchie laden"/>
        </td>
</tr>
<tr>
	<td>Parent ID</td>
        <td>
        <select name="parent_id" >
<?php
        if (isset($klassh_id)) {
           // Klass_ids laden $parent setzen und daten laden
           mysql_connect($host,$benutzer,$passwort);
           mysql_select_db($dbname);
           $result2 = mysql_query("SELECT h.klass_id, k.bezeichnung FROM std_klassifizierung k, std_klass_hier_strukturen h WHERE h.klass_id=k.klass_id and h.klassh_id=" . $klassh_id . " and parent_id=" . $parent_id . " order by k.bezeichnung");
           if (!$result2)
            {
                //exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
                $fehler = "MySQL Fehler: (" . mysql_errno() . ") " . mysql_error();
            }
            else
            {
                $num2=mysql_num_rows($result2);
                $i=0;
                while ($i < $num2) {
                    echo "<option value=\"" . mysql_result($result2,$i,"h.klass_id") . "\">" . mysql_result($result2,$i,"k.bezeichnung") . "</option>";
                    $i++;
                }
            }
        }
?>
        </select>
	</td>
        <td>
        <input type=submit name="klassids" value="Ebene tiefer"/>
        </td>
</tr>
<tr>
	<td>klass_id</td>
	<td>
	<select name="klass_id">
<?php

           // Klass_ids laden $parent setzen und daten laden
           mysql_connect($host,$benutzer,$passwort);
           mysql_select_db($dbname);

           if ( isset($_POST['alleids']) ) {
               $aktion = $_POST['alleids'];
           } else {
               $aktion = "Nicht zugeordnet";
           }
           if ($aktion == "Alle") {
               $result3 = mysql_query("SELECT klass_id, bezeichnung FROM std_klassifizierung order by bezeichnung");
               $sbutton = "Nicht zugeordnete";
           } else {
               $result3 = mysql_query("SELECT * FROM std_klassifizierung k LEFT JOIN std_klass_hier_strukturen h ON k.klass_id = h.klass_id WHERE h.klassh_id IS NULL");
               $sbutton = "Alle";
           }
           if (!$result3)
           {
                //exit('MySQL Fehler: (' . mysql_errno() . ') ' . mysql_error());
                $fehler = "MySQL Fehler: (" . mysql_errno() . ") " . mysql_error();
           }
           else
           {
                $num=mysql_num_rows($result3);
                $i=0;
                while ($i < $num) {

                    echo "<option value=\"" . mysql_result($result3,$i,"klass_id") . "\" selected>" . mysql_result($result3,$i,"bezeichnung") . "</option>";
                    $i++;
                }
           }
?>
        </select>
	</td>
        <td>
        <input type=submit name="alleids" value="<?php echo $sbutton ?>"/>
        </td>
</tr>
</table>
<br>
<b>
<?php echo $message ?>
<?php echo $fehler ?>
</b>
<br>
<input type=submit name="speichern" value="Satz speichern"/>
</form>

</body>
</html>

