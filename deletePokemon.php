<?php

// Afficher les erreurs  _________________________________________________________________________________________
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Connexion à la BDD _________________________________________________________________________________________
$bdd = new PDO("mysql:host=localhost;dbname=pokedex", "root", "root", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

// 2. Suppression de données  _________________________________________________________________________________________
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    //Vérification si le pokemon existe dans la base de données  _________________________________
    $requete = $bdd->prepare('SELECT * FROM pokemon WHERE id = :id');
    $requete->bindParam(':id', $id);
    $requete->execute();
    $resultat = $requete->fetch();
    if (!$resultat) {
        echo "Le pokemon que vous voulez supprimer n'existe pas.";
    } else {
        //Affichage d'un message de confirmation de suppression et d'un formulaire de confirmation
        echo "Êtes-vous sûr de vouloir supprimer " . $resultat['nom'] . " ?<br>";
        echo "<form method='POST' action=''>";
        echo "<input type='hidden' name='id' value='" . $resultat['id'] . "'>";
        echo "<input type='submit' name='confirm_delete' value='Oui'>";
        echo "<input type='button' value='Non' onclick='history.back()'>";
        echo "</form>";
    }
}

// 3. Confirmation de suppression de données  _________________________________________________________________________________________
if (isset($_POST["confirm_delete"])) {
    $id = $_POST["id"];

    //Suppression du pokemon de la base de données  _________________________________
    $requete = $bdd->prepare('DELETE FROM pokemon WHERE id = :id');
    $requete->bindParam(':id', $id);
    $requete->execute();

    header("Location: index.php");
    exit;
}

?>
