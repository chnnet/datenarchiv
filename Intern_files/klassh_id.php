<?php
        session_start();
?>
<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Klassifizierung - Hierarchie anlegen</title>
    </head>
    <body>
        <h1>Klassifizierung - Hierarchie anlegen</h1>
<?php

	// ***** Session infos auslesen *****
        $host = $_SESSION['host'];
        $benutzer = $_SESSION['benutzer'];
        $passwort = $_SESSION['passwort'];
        $dbname = $_SESSION['dbname'];

        if ($_POST['klassh_id'])
	{

	// ***** Parameter infos auslesen *****
	$klass_id = $_POST['klassh_id'];
	$bezeichnung = $_POST['bezeichnung'];

	// ***** Verbindugsaufbau zu MySQL *****

    $con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
    } catch (PDOException $ex) {
        die('Die Datenbank ist momentan nicht erreichbar!');
    }
    $datum = date("yyyy-mm-dd", $timestamp);

		$result = $con->execute('INSERT INTO std_klass_hierarchien values (' . $klassh_id . ',' . $bezeichnung . ',' . $datum . ',' . $datum . ')')
        	or die ('Fehler in der Abfrage. ' . htmlspecialchars($result->errorinfo()[2]));

	} else // Form
        {
		    $con = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $benutzer, $passwort);
    
    		} catch (PDOException $ex) {
        		die('Die Datenbank ist momentan nicht erreichbar!');
    		}

                // max klass_id suchen
                $timestamp = time();
                $datum = date("Y-m-d", $timestamp);
                echo "Datum: " . $datum;
                if (!$klassh_id)
                {
                    $result = $con->query("select max(klassh_id) from std_klassifizierung");
                    $klassh_id = $result->fetchColumn();
                    $klassh_id++;

                } else
                {
                    $klassh_id++;
                }
?>
           <form name="Klassifizierungen" action="klass_id.php" method="post">
            <table>
            <tr>
                    <td>klassh_id</td>
                    <td>
                    <input type=text name="klassh_id" size="10" maxlength="10" value="<?php echo $klassh_id ?>" readonly/>
                    </td>
            </tr>
            <tr>
                    <td>Bezeichnung</td>
                    <td>
                    <input type=text name="bezeichnung" size="10" maxlength="10"/>
                    </td>
            </tr>
            </table>

            <input type=hidden name="blgform" value="1"/>
            <input type=submit value="Speichern"/>
            </form>
 <?php
        }
?>
    </body>
</html>

