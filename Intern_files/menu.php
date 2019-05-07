<?php
		session_start();
		
	// ***** Session infos auslesen *****
		$host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];
        $keynr = $_SESSION['keynr'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Datenarchiv f&uuml; </title>
    </head>
    <body>
<table>
<tr>
<?php

	// gewähltes Menü? wenn Paramter leer dann parent_id = 0
	// Fehler !!!
	if (isset($_GET['menu']))
	{
		$menu = $_GET['menu'];
		if ($menu)
		{
			if ( $menu == "standard" )
			{
				//$benutzer_id = "99999";
				$keynr = "99999";
			}
		}
	}
if (isset($_GET['parent_id']))
{
	$parent_id = $_GET['parent_id'];
}
else
{
	$parent_id = 0;
}

// DB-Connection
try {
	$con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);

} catch (PDOException $ex) {
	die('Die Datenbank ist momentan nicht erreichbar!');
}

//Query
$result = $con->prepare("SELECT m.name,m.pfad_file,m.target,m.pfad_icon FROM menu m, benutzer_menu b WHERE m.menu_id=b.menu_id and b.benutzer_id = $keynr and b.parent_id =  $parent_id order by b.menu_order");
$result->execute(array($keynr, $parent_id));

while ($row = $result->fetch()) {

		$icon = $row['pfad_icon'];
		$name = $row['name'];
		$pfadf = $row['pfad_file'];
		$target = $row['target'];
                // icon-pfad prüfen, ob vorhanden
		if ( $icon == "" )
		{
		
//					echo "<td><a href=\"http://192.168.64.2/datenarchiv/" . $pfadf . "\" target=\"" . $target . "\">" . $name . "</a></td>";
					echo "<td><a href=\"http://www.chnnet.at/chnnet.at//Intern_files/" . $pfadf . "\" target=\"" . $target . "\">" . $name . "</a></td>";
		}
		else
		{
//					echo "<td><a href=\"http://192.168.64.2/datenarchiv/" . $pfadf . "\" target=\"" . $target . "\"> <img src=\"" . $icon . "\" border=\"0\" alt=\"" . $name . "\"></a></td>";
					echo "<td><a href=\"http://www.chnnet.at/chnnet.at//Intern_files/" . $pfadf . "\" target=\"" . $target . "\"> <img src=\"http://www.chnnet.at/chnnet.at//Intern_files" . $icon . "\" border=\"0\" alt=\"" . $name . "\"></a></td>";
		}
}

echo "<td><a href=\"http://www.chnnet.at/chnnet.at//Intern_files/logout.php\" target=\"_parent\"><img src=\"/chnnet.at//Intern_files/icons/logout.jpg\" border=\"0\" alt=\"Logout\"></a></td>";
//echo "<td><a href=\"http://192.168.64.2/datenarchiv/logout.php\" target=\"_parent\"><img src=\"/chnnet.at//Intern_files/icons/logout.jpg\" border=\"0\" alt=\"Logout\"></a></td>";
echo "</tr>";
echo "</table>";
echo "</body></html>";
?>
