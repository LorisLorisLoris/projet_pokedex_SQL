<?php


// Afficher les erreurs  _________________________________________________________________________________________
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Connexion à la BDD _________________________________________________________________________________________
$bdd = new PDO("mysql:host=localhost;dbname=pokedex", "root", "root", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));



// 2. Ajout de données  _________________________________________________________________________________________
if (isset($_POST["submit"])) {

    $nom        =   $_POST["nom"];
    $pokedexId  =   $_POST["pokedexId"];
    $sprite     =   $_FILES["sprite"];

    //Vérification si le champ PokedexID est un nombre et est positif  _________________________________
    if (!is_numeric($pokedexId) || $pokedexId <= 0) {
        echo "Le champ Pokedex ID doit être un nombre positif. ";
    } else {
        //Vérification si le PokedexID existe déjà dans la base de données  _________________________________
        $requete = $bdd->prepare('SELECT * FROM pokemon WHERE pokedexId = :pokedexId');
        $requete->bindParam(':pokedexId', $pokedexId);
        $requete->execute();
        $resultat = $requete->fetch();
        if ($resultat) {
            echo "Le champ Pokedex ID doit être unique.<br>Cet ID est déjà affecté.";
        } else {
            //Verification si une photo est chargée  _________________________________________________________________________________________
            if ($sprite['error'] !== UPLOAD_ERR_OK) {
                echo "Le fichier sprite n'a pas été ajouté. ";
            } else {
                //Ajout d'une photo  _________________________________________________________________________________________
                $from = $sprite["tmp_name"];
                $to = "images/".$_FILES["sprite"]["name"];
                move_uploaded_file($from,$to);

                //prepare  _________________________________________________________________________________________
                $requete = $bdd->prepare('INSERT INTO pokemon(nom, pokedexId, sprite) VALUES(:nom,:pokedexId,:sprite)');
                $requete->bindParam(':nom', $nom);
                $requete->bindParam(':pokedexId', $pokedexId);
                $requete->bindParam(':sprite', $to); // Ajout de la variable $to contenant le chemin complet du fichier image

                //execute  _________________________________________________________________________________________
                $requete->execute();

                header("Location: index.php");
                exit;
            }
        }
    }
}


// 3. Formulaire  __________________________________________________________________________________________________________________________
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ajouter un pokemon</title>
</head>
<body>
    <section class="addpage">
        <h2>Ajouter un pokemon</h2>
        <p class="button"><a href="index.php">Revenir au Pokedex</a></p>
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nom">Nom</label><br>
                    <input type="text" name="nom" id="nom">
                </div>
                <div>
                    <label for="pokedexId">Pokedex ID</label><br>
                    <input type="text" name="pokedexId" id="pokedexId">
                </div>
                <div>
                    <label for="sprite">Sprite</label><br>
                    <input type="file" name="sprite" id="sprite">
                </div>

                <div>
                    <button type="submit" name="submit">Ajouter le pokemon</button>
                </div>
            </form>
    </section>
</body>
</html>