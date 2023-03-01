<?php

// Afficher les erreurs  _________________________________________________________________________________________
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Connexion à la BDD _________________________________________________________________________________________
$bdd = new PDO("mysql:host=localhost;dbname=pokedex", "root", "root", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

// 2. Récupération des données du pokemon à modifier _____________________________________________________________
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $requete = $bdd->prepare('SELECT * FROM pokemon WHERE id = :id');
    $requete->bindParam(':id', $id);
    $requete->execute();
    $pokemon = $requete->fetch();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokedex SQL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a class="button" href="index.php">Retour accueil.</a>
    <div class="grid-element">
        <h2> <?= $pokemon["nom"]?> </h2>
        <p>Pokedex Id : <?= $pokemon["pokedexId"]?></p>
        <img src="<?= $pokemon["sprite"]?>" alt="<?= $pokemon["nom"]?>">
    </div>
</body>
</html>