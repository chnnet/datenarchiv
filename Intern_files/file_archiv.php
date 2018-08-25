<?php
        session_start();
?>
<html>
<head>
	<title>File-Archiv erfassen</title>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

        global $benutzer;
        global $passwort;
        global $dbname;
        global $host;
        global $titel;
        global $klass_id;
        global $klass_group;
        global $schlagw1;
        global $schlagw2;
        global $schlagw3;
        global $filename;
        global $fileformat;
        global $fileextension;
        global $cdname;
        global $quelle;
        global $datum;
        global $rownum;
        global $fehler;
        global $message;

	// ***** Parameter auslesen session *****
        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];

        
	// ***** Parameter auslesen - Seite *****

        if ($titel) {
            $titel = $_POST['titel'];
            $klass_id = $_POST['klass_id'];
            $klass_group = $_POST['klass_group'];
            $schlagw1 = $_POST['schlagw1'];
            $schlagw2 = $_POST['schlagw2'];
            $schlagw3 = $_POST['schlagw3'];
            $filename = $_POST['filename'];
            $fileformat = $_POST['fileformat'];
            $fileextension = $_POST['fileextension'];
            $cdname = $_POST['cdname'];
            $quelle = $_POST['quelle'];

            if ( $klass_id )
            {
                    $sql = "INSERT INTO file_archiv (titel,klass_id,Schlagwort1,Schlagwort2,Schlagwort3,filename,fileformat,fileextension,cdname,datum,quelle) VALUES ('" . titel . "'," . klass_group . ",'" . schlagw1 . "','" . schlagw2 . "','" . schlagw3 . "','" . filename . "','" . fileformat . "','" . fileextension . "','" . cdname . "','" . datum . "','" . quelle . "')";
            }
            else
            {
                    $sql = "INSERT INTO file_archiv (titel,klass_id,Schlagwort1,Schlagwort2,Schlagwort3,filename,fileformat,fileextension,cdname,datum,quelle) VALUES ('" . titel . "'," . klass_id . ",'" . schlagw1 . "','" . schlagw2 . "','" . schlagw3 . "','" . filename . "','" . fileformat . "','" . fileextension . "','" . cdname . "','" . datum . "','" . quelle . "')";
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
<script language=\"JavaScript\" type="text/javascript">

<?php
        // ***** aufbauen von Javascript arrays fuer dyn Auswahl DB, Table *****
	echo "var groups = new Array(); ";
	echo "var klass_id = new Array(); ";
	echo "var klass_bez = new Array(); ";
	echo "var group_id = new Array(); ";

        // ***** Verbindugsaufbau zu MySQL *****
        // ***** Daten für JS arrays auslesen *****

        $con = mysql_connect($host,$benutzer,$passwort);
        mysql_select_db($dbname);
        $result = mysql_query("select s.klass_id,k.bezeichnung from std_klass_hier_strukturen s, std_klassifizierung k where k.klass_id=s.klass_id AND s.klassh_id=1 AND s.parent_id=0 group by k.bezeichnung");
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
                    echo "groups[" . $rownum . "] = \"" . mysql_result($result,$i,"k.bezeichnung") . "\";";
                    echo "group_id[" . $rownum . "] = \"" . mysql_result($result,$i,"s.klass_id") . "\";";
                    echo "klass_id[" . $rownum . "] = new Array();";
                    echo "klass_bez[" . $rownum . "] = new Array();";

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
                            echo "klass_id[" . $rownum . "][" . $cnt . "] = \"" . mysql_result($result2,$cnt,"s.klass_id") . "\";";
                            echo "klass_bez[" . $rownum . "][" . $cnt . "] = \"" . mysql_result($result2,$cnt,"k.bezeichnung") . "\";";
                            $cnt++;
                        }
                    }
            $i++;
            }

        }
?>
</script>

<script language="JavaScript" type="text/javascript">

function init()
{
	var dblen = groups.length;
	var selgr = 1;
	for ( i=1; i < dblen; i++ )
	{
		NeuerEintrag = new Option(groups[i]);
 		document.file_archiv.klass_group.options[i] = NeuerEintrag;
 		document.file_archiv.klass_group.options[i].text = groups[i];
 		document.file_archiv.klass_group.options[i].value = group_id[i];
 		<?php
                    if ($klass_group) {
                        echo "if (" . $klass_group . " == group_id[i]) selgr = i;}";
                    }
                ?>
	}
	document.file_archiv.klass_group.selectedIndex = selgr;

	if ( dblen > 0 )
	{
		var tblen = klass_id[1].length;
		var selkl = 0;
		for ( i=1; i < tblen; i++ )
		{
			NeuerEintrag = new Option(klass_id[1][i]);

 			document.file_archiv.klass_id.options[i] = NeuerEintrag;
 			document.file_archiv.klass_id.options[i].value = klass_id[selgr][i];
 			document.file_archiv.klass_id.options[i].text = klass_bez[selgr][i];
         		<?php
                        if ($klass_id) {
                            echo "if (" . $klass_id . " == klass_id[selgr][i]) { selkl = i;}";
                        }
                        ?>

		}

		document.file_archiv.klass_id.selectedIndex = selkl;

	}
	var fflen = document.file_archiv.fileformat.length;
	var selff = 0;
	for ( i=1; i < fflen; i++ )
	{
            <?php
                if ($fileformat)
                {
                    echo "if (" . $fileformat . " == ) document.file_archiv.fileformat.options[i].value) { selff = i;}";
                }
            ?>
	}

	document.file_archiv.fileformat.selectedIndex = selff;

	var felen = document.file_archiv.fileextension.length;
	var selfe = 0;
	for ( i=1; i < felen; i++ )
	{

            <?php
                if ($fileextension)
                {
                    echo "if (" . $fileextension . " == ) document.file_archiv.fileextension.options[i].value) { selfe = i;}";
                }
            ?>

	}

	document.file_archiv.fileextension.selectedIndex = selfe;

	var qulen = document.file_archiv.quelle.length;
	var selqu = 0;
	for ( i=1; i < felen; i++ )
	{

            <?php
                if ($quelle)
                {
                    echo "if (" . $quelle . " == ) document.file_archiv.quelle.options[i].value) { selqu = i;}";
                }
            ?>

	}

	document.file_archiv.quelle.selectedIndex = selqu;

}

function ongroupchange()
{

	var selIdx = document.file_archiv.klass_group.selectedIndex;

	var tbllen = klass_id[selIdx].length;

	var remAnz = document.file_archiv.klass_id.length;

	for ( i=0; i < remAnz; i++ )
	{

		document.file_archiv.klass_id.options[document.file_archiv.klass_id.length-1] = null;

	}
	for ( i=1; i < tbllen; i++ )
	{
		NeuerEintrag = new Option(klass_id[selIdx][i]);

 		document.file_archiv.klass_id.options[i] = NeuerEintrag;
 		document.file_archiv.klass_id.options[i].value = klass_id[selIdx][i];
		document.file_archiv.klass_id.options[i].text = klass_bez[selIdx][i];

	}
}

</script>

</head>

<body onload="init()">

<h2>File-Archiv erfassen</h2>
<?php echo $fehler ?>
<form name="file_archiv" action="file_archiv.php" target="main" method="post">
<table>
<tr>
	<td>Titel</td>
	<td>
	<input type=text name="titel" size="80" maxlength="80" />
	</td>
</tr>
<tr>
	<td>Klasse</td>
	<td>
	<select name="klass_group" onchange="ongroupchange()" />
	</td>
</tr>
<tr>
	<td>klass_id</td>
	<td>
	<select name="klass_id" />
	</td>
</tr>
<tr>
	<td>Schlagwort 1</td>
	<td>
	<input type=text name="schlagw1" size="25" maxlength="25"/>
	</td>
</tr>
<tr>
	<td>Schlagwort 2</td>
	<td>
	<input type=text name="schlagw2" size="25" maxlength="25"/>
	</td>
</tr>
<tr>
	<td>Schlagwort 3</td>
	<td>
	<input type=text name="schlagw3" size="25" maxlength="25"/>
	</td>
</tr>
<tr>
	<td>Filename</td>
	<td>
	<input type=text name="filename" size="10" maxlength="10" value="<?php echo $filename ?>" />
	</td>
</tr>
<tr>
	<td>Fileformat</td>
	<td>
	<select name="fileformat">
	<option value="AVI">AVI</option>
	<option value="BMP">BMP</option>
	<option value="CWK">CWK</option>
	<option value="GIF">GIF</option>
	<option value="HTML">HTML</option>
	<option value="JPEG">JPEG</option>
	<option value="MOV">MOV</option>
	<option value="MPG">MPG</option>
	<option value="PDF">PDF</option>
	<option value="PPT">PPT</option>
	<option value="PNG">PNG</option>
	<option value="PICT">PICT</option>
	<option value="RM">RM</option>
	<option value="RTF">RTF</option>
	<option value="TIFF">TIFF</option>
	<option value="Text">Text</option>
	<option value="WMV">WMV</option>
	<option value="ZIP">ZIP</option>
	</select>
	</td>
</tr>
<tr>
	<td>Fileextension</td>
	<td>
	<select name="fileextension">
	<option value=".avi">.avi</option>
	<option value=".bmp">.bmp</option>
	<option value=".cwk">.cwk</option>
	<option value=".gif">.gif</option>
	<option value=".hpg">.hpg</option>
	<option value=".htm">.htm</option>
	<option value=".html">.html</option>
	<option value=".jpg">.jpg</option>
	<option value=".jpeg">.jpeg</option>
	<option value=".mht">.mht</option>
	<option value=".mov">.mov</option>
	<option value=".mpg">.mpg</option>
	<option value=".pdf">.pdf</option>
	<option value=".pict">.pict</option>
	<option value=".png">.png</option>
	<option value=".pps">.pps</option>
	<option value=".ppt">.ppt</option>
	<option value=".rm">.rm</option>
	<option value=".rtf">.rtf</option>
	<option value=".tiff">.tiff</option>
	<option value=".txt">.txt</option>
	<option value=".tif">.tif</option>
	<option value=".wmv">.wmv</option>
	<option value=".zip">.zip</option>
	</select>
	</td>
</tr>
<tr>
	<td>cdname</td>
	<td>
	<input type=text name="cdname" size="10" maxlength="10" value="<?php echo $cdname ?>" />
	</td>
</tr>
<tr>
	<td>Datum (yyyy-mm-dd)</td>
	<td>
	<input type=text name="datum" size="10" maxlength="10" value="<?php echo $datum ?>" />
	</td>
</tr>
<tr>
       <td>Quelle</td>
       <td>
	<select name="quelle">
	<option value="Presse">Presse</option>
	<option value="Standard">Standard</option>
	<option value="ORF Teletext">ORF Teletext</option>
	<option value="Spiegel">Spiegel</option>
	<option value="Kurier">Kurier</option>
	<option value="TIME">TIME</option>
	<option value="ORF ON">ORF ON</option>
	<option value="ct">ct</option>
	<option value="Wall Street Journal">Wall Street Journal</option>
	<option value="Almuni News">Alumni News</option>
	<option value="Wirtschaftsblatt">Wirtschaftsblatt</option>
	<option value="trend">trend</option>
	<option value="Economist">Economist</option>
	<option value="zbp">zbp</option>
	<option value="APA">APA</option>
	<option value="CD Austria">CD Austria</option>
	<option value="TIM">TIM</option>
	<option value="VISA Magazin">VISA Magazin</option>
	<option value="profil">profil</option>
	<option value="New Scientist">New Scientist</option>
	<option value="iX">iX</option>
	<option value="preview">preview</option>
	<option value="Wohnen">Wohnen</option>
	<option value="Wiener">Wiener</option>
	<option value="Spektrum der Wissenschaft">Spektrum der Wissenschaft</option>
	<option value="PC Professional">PC Professional</option>
	<option value="Wikipedia">Wikipedia</option>
	<option value="The Times">The Times</option>
	<option value="Die Zeit">Die Zeit</option>
	<option value="heise.de">heise.de</option>
	<option value="Telepolis">Telepolis</option>
	<option value="Technology Review">Technology Review</option>
	<option value="Computerwelt">Computerwelt</option>
	<option value="Computerwoche">Computerwoche</option>
	<option value="Financial Times">Financial Times</option>
	<option value="CNN">CNN</option>
	<option value="New York Times">New York Times</option>
	<option value="NZZ">NZZ</option>
	</select>
	</td>
</tr>
</table>
<br>
<b>
<?php echo $message ?>
</b>
<br>
<input type=hidden name="blgform" value="1"/>
<input type=submit value="Satz speichern"/>
</form>

</body>
</html>
