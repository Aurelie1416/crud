<?php

// on se connecte à la base de données
require_once '../include/connect.php';
require_once '../include/hello.php';
require_once '../include/function.php';

$sql ='SELECT d.*, (SELECT COUNT(*) FROM `annonces` WHERE `annonces`.`departements_number` = d.`number`) AS nombre FROM `departements` d ORDER BY `number`;';

$query = $db->query($sql);

// on recupère les données
$liste_dep = $query->fetchAll(PDO::FETCH_ASSOC);

// on vérifie si liste_dep n'existe pas
if(!$liste_dep){
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
    
    <h1>Liste des départements</h1>

    <?php
        foreach($liste_dep as $dep):
    ?>
    <p><?= $dep['number'] ?> - <a href="annonce_departement.php?id=<?= $dep['number']?>"><?= $dep['name'] ?></a> : <?= $dep['nombre']?> annonces</p>
    <?php
        endforeach;
    ?>
</body>
</html>