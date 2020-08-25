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
    LEFT JOIN `departements` d ON a.`departements_number` = d.`number` WHERE d.`number` = :id';
    $sql2 = 'SELECT * FROM `departements` c WHERE d.`number` = :id';

    // on prepare la requete 
    $query = $db->prepare($sql);
    $query2 = $db->prepare($sql2);

    // on jecte l'id
    $query->bindValue(':id', $_GET['id'], PDO::PARAM_INT); 
    $query2->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

    // on exécute la requête
    $query->execute();
    $query2->execute();

    // on recupère les données
    $liste_annonce = $query->fetchAll(PDO::FETCH_ASSOC);
    $dep = $query2->fetch(PDO::FETCH_ASSOC);

    // on vérifie si l'article n'existe pas
    if(!$liste_annonce){
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }
}
else{
    header('Location: '.$_SERVER['HTTP_REFERER']);
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
    
    <h1><?= $dep['name'] ?></h1>

    <?php
        foreach($liste_annonce as $annonce):
    ?>
    <h2><?= $annonce['title'] ?></h2>
    <?php
    if(!is_null($annonce['image'])){ // $article['image'] != NULL
    ?>
        <img src="<?= URL . '/uploads/' . $annonce['image'] ?>" alt="<?= $annonce['title'] ?>">
    <?php
    }
    ?>
    <p><?= htmlspecialchars_decode($annonce['content'])?></p>
    <p><?= $annonce['price'] ?></p>
    <p>Annonce écrit par <?= $annonce['pseudo'] ?> dans la catégorie <?= $annonce['categories_name'] ?> le <?= formatDate($annonce['created_at']) ?></p>
    <?php
        endforeach;
    ?>
</body>
</html>