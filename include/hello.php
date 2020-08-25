<?php 
session_start();
// // on vérifie le cookie remember et on restaure la session si besoin
// if(isset($_COOKIE['remember']) && !empty($_COOKIE['remember'])){
//     // on récupère et on nettoie le token{$_COOKIE['remember']}
//     $token = strip_tags($_COOKIE['remember']);
//     // on se connecte à la base
//     require_once 'connect.php';
//     // on écrit la requête
//     $sql = "SELECT * FROM `users` WHERE `remember_token` = :token";
//     $query = $db->prepare($sql);
//     $query->bindValue(':token', $token, PDO::PARAM_STR);
//     // on exécute la requête
//     $query->execute();
//     // on récupère les données
//     $user = $query->fetch(PDO::FETCH_ASSOC);
//     // si un utilisateur existe
//     if($user){
//         // on restaure la session
//         $_SESSION['user'] =[
//         'id' => $user['id'],
//         'pseudo' => $user['pseudo'],
//         'email' => $user['email'],
//         'roles' => $user['roles']];   
//     }
//     else{
//         // on supprime le cookie
//         setcookie('remember', '', 1);
//     }
// }
// require_once 'function.php';
define('URL', 'http://localhost/crud');

if (isset($_SESSION['user'])){
    echo "Bonjour ".$_SESSION['user']['pseudo']."<a href='".URL."/users/deconnexion.php'>Déconnexion</a>";
}
else{
    echo '<a href="'.URL.'/users/connexion.php">Connexion</a> - <a href="'.URL.'/inscription.php">Inscription</a>';
}
?>
<a href="<?= URL ?>/annonces/ajoutannonce.php">Ajouter une annonce</a>