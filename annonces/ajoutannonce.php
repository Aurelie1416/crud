<?php
use PHPMailer\PHPMailer\Exception;
require_once '../include/hello.php';
require_once '../include/function.php';

if(!isset($_SESSION['user'])){
    $_SESSION['message'][] = "Vous devez vous connecter pour ajouter une annonce";
    header('Location: '.URL.'/users/connexion.php');
    exit;
}

require_once '../include/connect.php';

// transforme une chaîne de caractère json en tableau php
$role = json_decode($_SESSION['user']['roles']);

// on vérifie si on a le role admin dans $role
if(!in_array('ROLE_ADMIN', $role)){
    header('Location: '.URL);
    exit;
}

$sql = "SELECT * FROM `categories` ORDER BY `name` ASC";
$sql2 = 'SELECT * FROM `departements` ORDER BY `number`;';

// on exécute la requête
$query = $db->query($sql);
$query2 = $db->query($sql2);

$liste_categorie = $query->fetchAll(PDO::FETCH_ASSOC);
$liste_dep = $query2->fetchAll(PDO::FETCH_ASSOC);

if(!empty($_POST)){
    $_SESSION['form'] = $_POST;
    // POST n'est pas vide on vérifie tous les champs
    if(
    isset($_POST['titre']) && !empty($_POST['titre'])
    && isset($_POST['contenu']) && !empty($_POST['contenu'])
    && isset($_POST['categories']) && !empty($_POST['categories'])
    && isset($_POST['price']) && !empty($_POST['price'])
    && isset($_POST['dep']) && !empty($_POST['dep'])
    ){ 
        $titre = htmlspecialchars($_POST['titre']);
        $contenu = htmlspecialchars($_POST['contenu']);
        $categorie = htmlspecialchars($_POST["categories"]); 
        $prix = htmlspecialchars($_POST["price"]); 
        $dep = htmlspecialchars($_POST["dep"]); 
        $user = $_SESSION['user']['id'];
        $nomImage;

        if(isset($_FILES['image']) && !empty($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE){ // si formulaire soumis
                
            // on vérifie qu'on a pas d'erreur
            if($_FILES['image']['error'] != UPLOAD_ERR_OK){
                // on ajoute un message de session
                $_SESSION['message'][] = "Une erreur est survenue lors du transfert du fichier";
            }
            // on génère un nouveau nom de fichier
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $nomImage = md5(uniqid()).'.'.$extension;

            $tabExtension = ['png','jpeg', 'jfif', 'jpg', 'pjpeg', 'pjp']; // Extensions autorisees
            $type = ['image/png', 'image/jpeg']; // type MIME authorisés  
        
            // verif de l'extention et du type
            if(!in_array(strtolower($extension), $tabExtension) || !in_array($_FILES['image']['type'], $type)){
                $_SESSION['message'][] = "Le type de l'image est incorrecte (PNG ou JPEG uniquement)";
            }

            $taillemax = 1048576; //1024*1024

            // On verifie la taille de l'image, si la taillem dépasse le maximum
            if($_FILES['image']['size'] > $taillemax){
                $_SESSION['message'][] = "Votre image est trop lourde (1Mo maximum)";
            }

            if(isset($_SESSION['message']) && !empty($_SESSION['message'])){
                header('Location: ajoutarticle.php');
                exit;
            }
        
            // on transfère le fichier
            if(!move_uploaded_file($_FILES['image']['tmp_name'], __DIR__.'/../uploads/'.$nomImage)){
                $_SESSION['message'][] = "L'image n'a pas été transférée";
            }

            mini(__DIR__.'/../uploads/'.$nomImage, 200);
            mini(__DIR__.'/../uploads/'.$nomImage, 300);
        }
        else{
            $nomImage = null;
        }
    
        // On écrit la requette
        $sql = "INSERT INTO `annonces`(`title`, `content`, `categories_id`, `users_id`, `image`, `price`, `departements_number`) VALUES (:title, :content, :categorie, :user, :image, :prix, :dep);"; 
        // on prépare la requette
        $query = $db->prepare($sql);
        
        // on injecte les valeurs dans les parametres
        $query->bindValue(':title', $titre, PDO::PARAM_STR);
        $query->bindValue(':content', $contenu, PDO::PARAM_STR);
        $query->bindValue(':categorie', $categorie, PDO::PARAM_INT);
        $query->bindValue(':user', $user, PDO::PARAM_INT);
        $query->bindValue(':image', $nomImage, PDO::PARAM_STR);
        $query->bindValue(':prix', $prix, PDO::PARAM_STR);
        $query->bindValue(':dep', $dep, PDO::PARAM_STR);

        // on exécute la requête
        $query->execute();
        
        require_once '../include/config-mail.php';
        try{
            // on définit l'expéditeur du mail
            $sendmail->setFrom('blog@domaine.fr', 'Blog');

            // on définit le ou les destinataires
            $sendmail->addAddress('destinataire@sondomaine.fr', 'Admin');

            // on définit le sujet du mail
            $sendmail->Subject = "ajout d'annonce";

            // on active le HTML
            $sendmail->isHTML();

            // on écrit le contenu du mail en HTML
            $sendmail->Body = "<p>{$_SESSION['user']['pseudo']} vient d'ajouter une annonce appelé \"$titre\"</p>";

            // en texte brut
            $sendmail->AltBody = "{$_SESSION['user']['pseudo']} vient d'ajouter une annonce appelé \"$titre\"";

            // on envoi le mail
            $sendmail->send();
        }
        catch(Exception $e){
            // ici le mail n'est pas parti
            echo 'Erreur: '. $e->errorMessage();
        }
    } 
    else{
        // au moins un champ est invalide
        $_SESSION['message'][] = "Error ! Vous n'avez pas remplis le formulaire";
    }  
}

$sql ='SELECT 
    a.*, 
    c.`name` AS categories_name, 
    u.`pseudo`, 
    d.`name` AS departements_name 
FROM `annonces` a 
LEFT JOIN `categories` c ON a.`categories_id` = c.`id` 
LEFT JOIN `users` u ON a.`users_id` = u.`id`
LEFT JOIN `departements` d ON a.`departements_number` = d.`number` ORDER BY a.`created_at` DESC;';

$query = $db->query($sql);

// on recupère les données
$liste_annonce = $query->fetchAll(PDO::FETCH_ASSOC);

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
    <h3><a href="annonce.php?id=<?= $annonce['id'] ?>"><?= $annonce['title'] ?></a></h3>
    <?php
    if(!is_null($annonce['image'])){ // $article['image'] != NULL
        $nomfichier = pathinfo($annonce['image'], PATHINFO_FILENAME);
        $extension = pathinfo($annonce['image'], PATHINFO_EXTENSION);
        $miniature = $nomfichier.'-200x200.'.$extension;
        ?>
        <img src="<?= URL.'/uploads/'.$miniature ?>" alt="<?= $annonce['title'] ?>">
    <?php
    }
    ?>
    <p><?= $annonce['price'] ?></p>
    <a href="annonce_departement.php?id=<?= $annonce['departements_number']?>"><?= $annonce['departements_name'] ?></a>
    <p>Annonce écrit par <?= $annonce['pseudo'] ?> dans la catégorie <?= $annonce['categories_name'] ?> le <?= formatDate($annonce['created_at']) ?></p>
    <?php
        endforeach;
    ?>

    <h2>Ajout d'une annonce</h2> 

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
   
    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" value="<?= isset($_SESSION['form']['titre']) ? $_SESSION['form']['titre']: ""?>">
        </div>        
        <div>
            <label for="contenu">Contenu</label>
            <textarea id="contenu" name="contenu"><?= isset($_SESSION['form']['contenu']) ? $_SESSION['form']['contenu']: ""?></textarea>
        </div>        
        <div>
            <label for="price">Prix</label>
            <input id="price" name="price"><?= isset($_SESSION['form']['price']) ? $_SESSION['form']['price']: ""?></input>
        </div>
        <div>
            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/png, image/jpeg">
        </div>
        <div>
            <label for="categorie">Catégorie</label>
            <select name="categories" id="categories" required>
                <option value="">--Choisissez une catégorie--</option>
                <?php foreach($liste_categorie as $liste): ?>
                <option value="<?= $liste['id'] ?>" 
                <?php if(isset($_SESSION['form']['categories']) 
                && $_SESSION['form']['categories'] == $liste['id']):
                    echo "selected";
                endif;
                ?>> 
                 <?php echo $liste['name'] ?>
                </option>
                 <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="dep">Département</label>
            <select name="dep" id="dep" required>
                <option value="">--Choisissez un département--</option>
                <?php foreach($liste_dep as $dep): ?>
                <option value="<?= $dep['number']?> - <?=$dep['name']?>" 
                <?php if(isset($_SESSION['form']['categories']) 
                && $_SESSION['form']['departements'] == $dep['number']):
                    echo "selected";
                endif;
                ?>> 
                 <?= $dep['number']?> - <?=$dep['name']?>
                </option>
                 <?php endforeach; ?>
            </select>
        </div>
        <div>
            <button>Ajouter l'annonce</button>
        </div>
    </form>
    <?php unset($_SESSION['form'])?>
</body>
</html>