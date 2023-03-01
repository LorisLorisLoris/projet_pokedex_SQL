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

// 3. Mise à jour des données _________________________________________________________________________________________
if (isset($_POST["submit"])) {
    $nom = $_POST["nom"];
    $pokedexId = $_POST["pokedexId"];

    // Vérification si le champ PokedexID est un nombre et est positif  _________________________________
    if (!is_numeric($pokedexId) || $pokedexId <= 0) {
        echo "Le champ Pokedex ID doit être un nombre positif. ";
    } else {
        // Vérification si le PokedexID existe déjà dans la base de données  _________________________________
        $requete = $bdd->prepare('SELECT * FROM pokemon WHERE pokedexId = :pokedexId AND id <> :id');
        $requete->bindParam(':pokedexId', $pokedexId);
        $requete->bindParam(':id', $id);
        $requete->execute();
        $resultat = $requete->fetch();
        if ($resultat) {
            echo "Le champ Pokedex ID doit être unique.<br>Cet ID est déjà affecté.";
        } else {
            // Modification du nom et du Pokedex ID  _____________________________________________________________
            $requete = $bdd->prepare('UPDATE pokemon SET nom = :nom, pokedexId = :pokedexId WHERE id = :id');
            $requete->bindParam(':nom', $nom);
            $requete->bindParam(':pokedexId', $pokedexId);
            $requete->bindParam(':id', $id);
            $requete->execute();

            // Modification de la photo si une nouvelle photo est chargée  _________________________________________________________
            if ($_FILES["sprite"]["error"] == UPLOAD_ERR_OK) {
                $from = $_FILES["sprite"]["tmp_name"];
                $to = "images/".$_FILES["sprite"]["name"];
                move_uploaded_file($from,$to);

                $requete = $bdd->prepare('UPDATE pokemon SET sprite = :sprite WHERE id = :id');
                $requete->bindParam(':sprite', $to);
                $requete->bindParam(':id', $id);
                $requete->execute();
            }

            header("Location: index.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le pokemon</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="addpage">
        <h2>Modifier le pokemon</h2>
        <p class="button"><a href="index.php">Revenir au Pokedex</a></p>
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="nom">Name :</label><br>   
                <input type="text" name="nom" id="nom" value="<?= $pokemon['nom']?>">
            </div>
            <div>
                <label for="pokedexId">PokedexId :</label><br>   
                <input type="text" name="pokedexId" id="pokedexId" value="<?= $pokemon['pokedexId']?>">
            </div>
            <div>
                <label for="sprite">Sprite :</label><br>
                <input type="file" name="sprite" id="sprite">
            </div>
            <div>
                <button type="submit" name="submit">Modifier un pokemon</button>
            </div>
        </form>
    <section class="addpage">
</body>
</html>