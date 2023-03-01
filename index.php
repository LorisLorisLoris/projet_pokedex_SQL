<?php



// Afficher les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Connexion à la BDD
$bdd = new PDO("mysql:host=localhost;dbname=pokedex", "root", "root", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

// 2. Creation de la requete
$requete = $bdd->prepare("SELECT * FROM pokemon");

// 3. Execution de la requete
$requete->execute();


// 4. Affichage des données
// while ($pokemon = $requete->fetch()) {
//     echo $pokemon["nom"].' '.$pokemon["pokedexId"].'<br>';
// }

// 5. Formulaire

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
    <h1>Pokedex SQL</h1>
    <p class="button"><a href="addPokemon.php">Ajouter un pokemon</a></p>
    <section class="cards">
        <?php while ($pokemon = $requete->fetch()): ?>
        <div class="card">
            <h2><a href="detailsPokemon.php?id=<?= $pokemon["id"] ?>"><?= $pokemon["nom"]?></a></h2>
            <p>Pokedex ID : <?= $pokemon["pokedexId"]?></p>
            <img src="<?= $pokemon["sprite"]?>" alt="<?= $pokemon["nom"]?>">
            <p class="button"><a href="updatePokemon.php?id=<?= $pokemon["id"] ?>">Modifier</a></p>
            <p class="delete"><a href="deletePokemon.php?id=<?= $pokemon["id"] ?>">Supprimer</a></p>

        </div>
        <?php endwhile;?>
    </section>

</body>
</html>

