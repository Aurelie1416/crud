<?php
    require_once '../include/hello.php';
    // On vérifie si on a un id dans l'URL
    if(isset($_GET['id']) && !empty($_GET['id'])){
        // on a un id, on va chercher la catégorie dans la base
        // on se connecte
        require_once '../include/connect.php';

        // on écrit la requêtre
        $sql = 'SELECT * FROM `categories` WHERE `id` = :id';

        // on prépare la requête
        $query = $db->prepare($sql);

        // on accroche les valeurs aux paramètres
        $query->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        
        // on exécute la requête
        $query->execute();
        
        // on récupère les donées
        $categorie = $query->fetch(PDO::FETCH_ASSOC);

        if(!$categorie){
            // $personne est vide
            header('Location: inscription.php');
        }
        
        // on vérifie que POST contient des données
        if(!empty($_POST)){
            // on vérifie que tous les champs obligatoires sont remplis
            if(
                isset($_POST['nom']) && !empty($_POST['nom']) 
            ){
                // Le formulaire est complet
                // on récupère et on nettoie les données
                $nom = htmlspecialchars($_POST['nom']); 

                // on stocke les données en base
                // on écrit la requête
                $sql = "UPDATE `categories` SET `name` = :nom WHERE `id` = {$categorie['id']}";

                // on la prépare
                $query = $db->prepare($sql);

                // on injecte les paramètres
                $query->bindValue(':nom', $nom, PDO::PARAM_STR);

                // on exécute
                $query->execute();

                // on redirige l'utilisateur vers index.php
                header('Location: ajoutcategorie.php');
            }
            else{
                // le formulaire est incomplet
                $_SESSION['message'][] = "Le formulaire est incomplet";
            }
        }   
    }
    else{
        // Pas d'iD ou ID vide, on retourne à la page index
        $_SESSION['message'][] = "Vous devez être connecté pour supprimé cette catégorie";
        header('Location: inscription.php');
        exit;    
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>
    <h1>Modifier une catégorie</h1>

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
            <input type="text" id="nom" name="nom" value="<?= $categorie['name'] ?>">
        </div>
        <div>
            <button>Valider</button>
        </div>
    </form>
</body>
</html>