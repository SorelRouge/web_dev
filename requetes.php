<?php
include ("dbh.inc.php"); 

/***************** Ajout de champs dans la tabble *******************/
function ajouterMachine ($uneMachine)
{ 
    $mysqli = Dbh::connexion();
    /* utilisation */
    $SQL = "INSERT INTO machines (name, os, status, type, comment, port) VALUES (?,?,?,?,?,?)";
    /* préparation */
    $stmt=$mysqli->prepare($SQL);

    /* bind variable à la préparation */
    $stmt->bind_param('sssssi', $uneMachine ["name"], $uneMachine ["os"], $uneMachine ["status"], $uneMachine ["type"], $uneMachine ["comment"], $uneMachine ["port"]);
    /* execution */
    $ok = $stmt->execute ();
    /* fermeture de l'ordre */
    $stmt->close();
    if($ok)
    {
        $mysqli->commit();
    }
}; 

/******************* Lecture d'une table ***********************/
function listerMachines ()
{
	$myDB = Dbh::connexion ();

	$SQL = "SELECT * FROM machines";
	$result = $myDB->query($SQL);
	$rows = array();

	while($row = $result->fetch_assoc())
	{
		$rows[] = $row;
	}
    return $rows;
    
}

/******************* Afiicher ligne *********************/
function afficherLigne ($ligne, $premiereLigne=FALSE)
{
    echo indenter (TRUE), "<tr>", PHP_EOL;
    $tagCell = ($premiereLigne ? "th": "td");
    foreach ($ligne as $nomColonne => $valeurColonne)
    {
        echo indenter (), "<", $tagCell, ">",
        ($premiereLigne ? $nomColonne : $valeurColonne ),
        "</", $tagCell, ">"; PHP_EOL;
    }
    echo indenter (FALSE), "</tr>", PHP_EOL;
}
/**************** indenter ****************/
function indenter ($x = null)
{
    $tab=4;
    static $increment = 0;
    $inc = 0;
    if(is_null($x))
    {
        $inc = $increment;
    }
    elseif($x)
    {
        $inc = $increment++;
    }
    elseif(!$x)
    {
        $inc = --$increment;
    }
    return str_repeat(" ", $inc * $tab);
};

/*************** afficher un tableau *********************/
function afficherTableau ($donnees, $attributs=array())
{
    $premiereLigne= TRUE;

    echo indenter (TRUE), "<table", ">";
    foreach ($donnees as $numeroLigne => $ligne)
    {
        if ($premiereLigne)
        {
            afficherLigne ($ligne, $premiereLigne);
            $premiereLigne = FALSE;
        }
        afficherLigne ($ligne);
    }
    echo indenter (FALSE), "</table>", PHP_EOL;
}

/*************** Incrémentation du menu dropdown depuis la BDD ***************/

function insertIntoDropdown()
{
    $mysqli = Dbh::connexion();
    $records = $mysqli->query("SELECT name FROM machines");

    echo "<select>";

    while ($row = $records->fetch_assoc())
    {
        unset($name);
        $name = $row['name'];
        echo "<option value=\"\">" . $name . "</option>";
    }

    echo "</select>";
}

?> 
