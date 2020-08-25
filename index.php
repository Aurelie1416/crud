<?php
use PHPMailer\PHPMailer\Exception;
require_once 'include/hello.php';
require_once 'include/function.php';

if(!isset($_SESSION['user'])){
    $_SESSION['message'][] = "Vous devez vous connecter pour ajouter une annonce";
    header('Location: '.URL.'/users/connexion.php');
    exit;
}

require_once 'include/connect.php';

$sql = "SELECT * FROM `categories` ORDER BY `name` ASC";
$sql2 = 'SELECT * FROM `departements` ORDER BY `number`;';

// on exécute la requête
$query = $db->query($sql);
$query2 = $db->query($sql2);

$liste_categorie = $query->fetchAll(PDO::FETCH_ASSOC);
$liste_dep = $query2->fetchAll(PDO::FETCH_ASSOC);

$sql3 ='SELECT 
    a.*, 
    c.`name` AS categories_name, 
    u.`pseudo`, 
    d.`name` AS departements_name 
FROM `annonces` a 
LEFT JOIN `categories` c ON a.`categories_id` = c.`id` 
LEFT JOIN `users` u ON a.`users_id` = u.`id`
LEFT JOIN `departements` d ON a.`departements_number` = d.`number` ORDER BY a.`created_at` DESC;';

$query3 = $db->query($sql3);

// on recupère les données
$liste_annonce = $query3->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>

    <h1>Liste des annonces</h1>

    <?php
        foreach($liste_annonce as $annonce):
    ?>
    <h3><a href="annonces/annonce.php?id=<?= $annonce['id'] ?>"><?= $annonce['title'] ?></a></h3>
    <?php
    if(!is_null($annonce['image'])){ 
        $nomfichier = pathinfo($annonce['image'], PATHINFO_FILENAME);
        $extension = pathinfo($annonce['image'], PATHINFO_EXTENSION);
        $miniature = $nomfichier.'-200x200.'.$extension;
        ?>
        <img src="<?= URL.'/uploads/'.$miniature ?>" alt="<?= $annonce['title'] ?>">
    <?php
    }
    ?>
    <p><?= $annonce['price'] ?></p>
    <a href="annonces/annonce_departement.php?id=<?= $annonce['departements_number']?>"><?= $annonce['departements_name'] ?></a>
    <p>Annonce écrit par <?= $annonce['pseudo'] ?> dans la catégorie <?= $annonce['categories_name'] ?> le <?= formatDate($annonce['created_at']) ?></p>
    <?php
        endforeach;
    ?>
</body>
</html>