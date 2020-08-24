<?php
require_once '../include/hello.php';
// on se connecte à la base de données
require_once '../include/connect.php';

// on vérifie que POST n'est pas vide
if(!empty($_POST)){
    // POST n'est pas vide on vérifie tous les champs
    if(
        isset($_POST['mail']) && !empty($_POST['mail'])
        && isset($_POST['password']) && !empty($_POST['password'])
    ){
        if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){
            $_SESSION['message'][] = "Votre email est invalide";
        }
        else{
            
            $email = $_POST['mail'];
        }  
        $sql = 'SELECT * FROM `users` WHERE `email` = :email';
        $query = $db->prepare($sql);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        
        $password = $_POST['password'];
        if (password_verify($password, $user['password'])){
            // on ouvre la session
            $_SESSION['user'] =[
                'id' => $user['id'],
                'pseudo' => $user['pseudo'],
                'email' => $user['email'],
                'roles' => $user['roles']
            ];
        }
        else{
            $_SESSION['message'][] = "Votre mot de passe est invalide";
        }
    }
    else{
        $_SESSION['message'][] = "Votre email et/ou mot de passe est invalide";
    }

    // si il y a des messages d'erreur, on redirige
    if(!empty($_SESSION['message'])){
        header('Location: inscription.php');
        exit;
    }
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
    <h1>Connexion</h1>

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
            <label for="email">Veuillez rentrez votre email</label>
            <input type="email" id="email" name="mail">
        </div>        
        <div>
            <label for="password">Veuillez rentrer votre mot de passe</label>
            <input type="password" id="password" name="password">
        </div>
        <div>
            <button>Je me connecte</button>
        </div>
    </form>
</body>
</html>