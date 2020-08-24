<?php
use PHPMailer\PHPMailer\Exception;
// on se connecte à la base de données
require_once 'include/hello.php';

// on vérifie que POST n'est pas vide
if(!empty($_POST)){
    // POST n'est pas vide on vérifie tous les champs
    if(
        isset($_POST['mail']) && !empty($_POST['mail'])
        && isset($_POST['password']) && !empty($_POST['password'])
        && isset($_POST['mailverif']) && !empty($_POST['mailverif'])
        && isset($_POST['passwordverif']) && !empty($_POST['passwordverif'])
        && isset($_POST['nom']) && !empty($_POST['nom'])
        && isset($_POST['phone']) && !empty($_POST['phone'])
    ){

        if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){
            $_SESSION['message'][] = "Votre email est invalide";
        }
        else{
            if($_POST['mailverif'] === $_POST['mail']){
                $email = $_POST['mail'];
            }
            else{
                $_SESSION['message'][] = "Votre email est invalide";
            }
        }   
            
        if($_POST['password'] === $_POST['passwordverif']){
            $password = password_hash($_POST['password'], PASSWORD_ARGON2ID);
        }
        else{
            $_SESSION['message'][] = "les mots de passes ne sont pas identiques";
        }

        $nom = strip_tags($_POST['nom']);
        $tel = strip_tags($_POST['phone']);

        // si il y a des messages d'erreur, on redirige
        if(!empty($_SESSION['message'])){
            header('Location: inscription.php');
            exit;
        }

        // le formulaire est complet et les données nettoyées, on peut inscrire l'utilisateur
        require_once '../include/connect.php';

        // On écrit la requette
        $sql = "INSERT INTO `users`(`email`, `password`, `pseudo`, `tel`) VALUES (:email, :motdepasse, :nom, :phone);"; 
        // on prépare la requette
        $query = $db->prepare($sql);

        // on injecte les valeurs dans les parametres
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->bindValue(':motdepasse', $password, PDO::PARAM_STR);
        $query->bindValue(':nom', $nom, PDO::PARAM_STR);
        $query->bindValue(':phone', $tel, PDO::PARAM_STR);

        // on exécute la requête
        $query->execute();

        $num = $db->lastInsertId();

        $_SESSION['message'][] = "vous vous êtes escrit. Vous êtes l'utilisateur numéro $num";

        require_once '../include/config-mail.php';
        try{
            // on définit l'expéditeur du mail
            $sendmail->setFrom('annonce@domaine.fr', 'Blog');

            // on définit le ou les destinataires
            $sendmail->addAddress($email, $nom);

            // on définit le sujet du mail
            $sendmail->Subject = "inscription";

            // on active le HTML
            $sendmail->isHTML();

            // on écrit le contenu du mail en HTML
            $sendmail->Body = "<p>$nom, vous venez bien de vous inscrire sur ce site d'annonce. Vous êtes l'utilisateur numéro $num</p>";
    
            // en texte brut
            $sendmail->AltBody = "$nom, vous venez bien de vous inscrire sur ce site d'annonce. Vous êtes l'utilisateur numéro $num";

            // on envoi le mail
            $sendmail->send();
        }
        catch(Exception $e){
            // ici le mail n'est pas parti
            echo 'Erreur: '. $e->errorMessage();
        }
    }
    else{
        $_SESSION['message'][] = "Le formulaire est incomplet";
    }
}   
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>

    <h1>Inscription</h1>

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

    <form method="POST">
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="mail">
        </div>        
        <div>
            <label for="emailverif">Confirmer l'email</label>
            <input type="email" id="emailverif" name="mailverif">
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password">
        </div>
        <div>
            <label for="passwordverif">Confirmer le mot de passe</label>
            <input type="password" id="passwordverif" name="passwordverif">
        </div>
        <div>
            <label for="nom">Pseudo</label>
            <input type="text" id="nom" name="nom">
        </div>        
        <div>
            <label for="phone">Tel</label>
            <input type="tel" id="phone" name="phone">
        </div>
        <div>
            <button>Je m'inscris</button>
        </div>
    </form>
</body>
</html>