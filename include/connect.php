<?php
// on se connecte à la base de donées
try{
    // On essaie de se connecter
    $dsn = 'mysql:dbname=annonce;host=localhost';

    // DSN, Utilisateur, Mot de passe
    $db = new PDO($dsn, 'root', '1234');

}catch(Exception $erreur){
    die;
}
?>