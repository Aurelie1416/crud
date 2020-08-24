<?php

// on se connecte à la base de données
require_once '../include/hello.php';
require_once '../include/connect.php';

// on vérifie que POST n'est pas vide
if(!empty($_POST)){
    // POST n'est pas vide on vérifie tous les champs
    if(isset($_POST['nom']) && !empty($_POST['nom'])){
        // tous les champs sont valides
        // on récupère et on nettoie les données
        $nom = htmlspecialchars($_POST['nom']); 

        // On écrit la requette
        $sql = "INSERT INTO `categories`(`name`) VALUES (:nom);"; 

        // on prépare la requette
        $query = $db->prepare($sql);

        // on injecte les valeurs dans les parametres
        $query->bindValue(':nom', $nom, PDO::PARAM_STR);

        // on exécute la requête
        $query->execute();
    }
    else{
        // au moins un champ est invalide
        $_SESSION['message'][] = "Le formulaire est incomplet";
        header('Location: inscription.php');
        exit;
    }
}   

// on écrit la requête
$sql = 'SELECT * FROM `categories` ORDER BY `id`;';

// on exécute la requête
$query = $db->query($sql);

$liste_de_categorie = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>

    <h1>Liste des catégories</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom des catégories</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach($liste_de_categorie as $categorie):
            ?>
                <tr>
                    <td><?= $categorie['id'] ?></td>
                    <td><a href="../annonces/annonces_categorie.php?id=<?= $categorie['id'] ?>"><?= $categorie['name'] ?></a></td>
                    <td><a href="modifier.php?id=<?= $categorie['id'] ?>">Modifier</a></td>
                    <td><a href="supprimer.php?id=<?= $categorie['id'] ?>">Supprimer</a></td>               
                </tr>       
                <?php
                    endforeach;
                ?> 
            </tbody>
        </table>

    <h2>Ajouter une catégorie</h2>

    <?php 
    if(isset($_SESSION['message']) && !empty($_SESSION['message'])):
        foreach($_SESSION['message'] as $message):
        ?>
        <p><?= $message ?></p> 
        <?php
        endforeach; 
    unset($_SESSION['message']);
    endif;   
    ?>

    <form method = "POST">
        <div>
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom">
        </div>
        <div>
            <button>Valider</button>
        </div>
    </form>
</body>
</html>