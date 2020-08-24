<?php

// on se connecte a la base de données
require_once '../include/connect.php';
require_once '../include/hello.php';

if(!isset($_SESSION['user'])){
    $_SESSION['message'][] = "Vous devez être connecté pour supprimé cette catégorie";
    header('Location: '.URL.'/users/connexion.php');
    exit;
}

// transforme une chaîne de caractère json en tableau php
$role = json_decode($_SESSION['user']['roles']);

// on vérifie si on a le role admin dans $role
if(!in_array('ROLE_ADMIN', $role)){
    header('Location: '.URL);
    exit;
}

$sql = "DELETE FROM `categories` WHERE `id` = :id";
$query = $db->prepare($sql);
$query->bindValue(':id', $_GET['id'], PDO::PARAM_STR);
$query->execute();
header('Location: ajoutcategorie.php');