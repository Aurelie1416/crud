<?php

// on se connecte à la base de données
require_once '../include/connect.php';
require_once '../include/hello.php';
require_once '../include/function.php';

// on vérifie si on a un id non vide dans l'URL
if(isset($_GET['id']) && !empty($_GET['id'])){
    // on a un id 
    // on va chercher l'article dans la base
    $sql ='SELECT 
        a.*, 
        c.`name` AS categories_name, 
        u.`pseudo`, 
        d.`name` AS departements_name 
    FROM `annonces` a 
    LEFT JOIN `categories` c ON a.`categories_id` = c.`id` 
    LEFT JOIN `users` u ON a.`users_id` = u.`id`
    LEFT JOIN `departements` d ON a.`departements_number` = d.`number` WHERE a.`id` = :id';

    // on prepare la requete 
    $query = $db->prepare($sql);

    // on jecte l'id
    $query->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

    // on exécute la requête
    $query->execute();

    // on recupère les données
    $annonce = $query->fetch(PDO::FETCH_ASSOC);

    if(!$annonce){
        header('location: '.URL);
    }
}
else{
    header('location: '.URL);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1><?= $annonce['title'] ?></h1>
    <?php
    if(!is_null($annonce['image'])){ // $article['image'] != NULL
    ?>
        <img src="<?= URL . '/uploads/' . $annonce['image'] ?>" alt="<?= $annonce['title'] ?>">
    <?php
    }
    ?>
    <p><?= htmlspecialchars_decode($annonce['content'])?></p>
    <p><?= $annonce['price'] ?></p>
    <p><?= $annonce['departements_name'] ?></p>
    <p>Annonce écrit par <?= $annonce['pseudo'] ?> dans la catégorie <?= $annonce['categories_name'] ?> le <?= formatDate($annonce['created_at']) ?></p>
</body>
</html>