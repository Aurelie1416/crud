<?php
require_once '../include/hello.php';
require_once '../include/connect.php';

    // on vérifie si on est connecté
    if(!isset($_SESSION['user'])){
        $_SESSION['message'][] = "Vous devez être connecté pour pouvoir modifier cette annonce";
        header('Location: '.$_SERVER['HTTP_REFERER']);
        exit;
    }
if(isset($_GET['id']) && !empty($_GET['id'])){
    // on a un id, on va chercher la catégorie dans la base
    // on se connecte
    
    // on écrit la requêtre
    $sql = 'SELECT * FROM `annonces` WHERE `id` = :id';

    // on prépare la requête
    $query = $db->prepare($sql);

    // on accroche les valeurs aux paramètres
    $query->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    
    // on exécute la requête
    $query->execute();
    
    // on récupère les donées
    $annonce = $query->fetch(PDO::FETCH_ASSOC);

    if(!$annonce){
        // $personne est vide
        header('Location: ajoutannonce.php');
    }

    if($annonce['users_id'] != $_SESSION['user']['id']){
        $_SESSION['message'][] = "Vous devez être l'auteur de cette annonce pour pouvoir la modifier";
        header('Location: '.$_SERVER['HTTP_REFERER']);        
        exit;
    }
    
    // on vérifie que POST contient des données
    if(!empty($_POST)){
        // on vérifie que tous les champs obligatoires sont remplis
        if(
            isset($_POST['titre']) && !empty($_POST['titre'] 
            && $_POST['contenu']) && !empty($_POST['contenu']
            && $_POST['prix']) && !empty($_POST['prix']
            && $_POST['categories']) && !empty($_POST['categories']
            && $_POST['dep']) && !empty($_POST['dep']) 
        ){
            $titre = strip_tags($_POST['titre']);
            $contenu = htmlspecialchars($_POST['contenu']);
            $categorie = strip_tags($_POST["categories"]); 
            if(!is_numeric($_POST["price"])){
                $_SESSION['message'][] = "Le prix est incorrect";
                header('Location: modifier.php');
                exit;
            }
            $prix = $_POST["price"];
            $dep = $_POST["dep"];
            $nomImage = $annonce['image'];
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
            
                $ancienne = $annonce['image'];
                unlink(__DIR__.'/../uploads/'.$ancienne);
                $nomfichier = pathinfo($annonce['image'], PATHINFO_FILENAME);
                $extension = pathinfo($annonce['image'], PATHINFO_EXTENSION);
                $miniature = $nomfichier.'-200x200.'.$extension;
                mini(__DIR__.'/../uploads/'.$nomImage, 200);
                mini(__DIR__.'/../uploads/'.$nomImage, 300);
            }
        
            // on stocke les données en base
            // on écrit la requête
            $sql = "UPDATE `annonces` SET `title` = :titre, `content` = :contenu, `price` = :prix, `categories_id` = :categorie, `departements_number` = :dep, `image` = :image WHERE `id` = {$annonce['id']}";

            // on la prépare
            $query = $db->prepare($sql);

            // on injecte les paramètres
            $query->bindValue(':titre', $titre, PDO::PARAM_STR);
            $query->bindValue(':contenu', $content, PDO::PARAM_INT);
            $query->bindValue(':prix', $prix, PDO::PARAM_STR);
            $query->bindValue(':categorie', $categorie, PDO::PARAM_INT);
            $query->bindValue(':dep', $dep, PDO::PARAM_STR);
            $query->bindValue(':image', $nomImage, PDO::PARAM_STR);

            // on exécute
            $query->execute();

            header('Location: '.URL.'/annonces/ajoutannonce.php.php');
        }
    }
    else{
        // le formulaire est incomplet
        $_SESSION['message'][] = "Error ! Vous n'avez pas remplis le formulaire";    
    }
}   
else{
    // Pas d'iD ou ID vide, on retourne à la page index
    header('Location: index.php');
}

$sql = "SELECT * FROM `categories` ORDER BY `name` ASC";
$sql2 = 'SELECT * FROM `departements` ORDER BY `number`;';

// on exécute la requête
$query = $db->query($sql);
$query2 = $db->query($sql2);

$liste_categorie = $query->fetchAll(PDO::FETCH_ASSOC);
$liste_dep = $query2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>

</head>
<body>
<h1>Modifier votre annonce
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
            <input type="text" id="titre" name="titre" value="<?= $annonce['title'] ?>">
        </div>        
        <div>
            <label for="contenu">Contenu</label>
            <textarea id="contenu" name="contenu" ><?= $annonce['content']?></textarea>
        </div>        
        <div>
            <label for="price">Prix</label>
            <input type="number" min="0" step="0.01" id="price" name="prix" value="<?= $annonce['price'] ?>">
        </div>
        <div>
            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/png, image/jpeg">
        </div>
        <div>
            <label for="categorie">Catégorie</label>
            <select name="categories" id="categories" required>
                <option value="">--Choisissez une catégorie--</option>
                <?php foreach($liste_categorie as $categorie): ?>
                <option value="<?= $categorie['id'] ?>" 
                <?= $annonce['categories_id'] == $categorie['id'] ? "selected" : "" ?>><?= $categorie['name'] ?></option>
                 <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="dep">Département</label>
            <select name="dep" id="dep" required>
                <option value="">--Choisissez un département--</option>
                <?php foreach($liste_dep as $dep): ?>
                <option value="<?= $dep['number'] ?>" 
                <?= $annonce['departements_number'] == $dep['number'] ? "selected" : "" ?>><?= $dep['name'] ?></option>
                </option>
                 <?php endforeach; ?>
            </select>
        </div>
        <div>
            <button>Ajouter l'annonce</button>
        </div>
    </form>
    <?php unset($_SESSION['form'])?>
</form>
</body>
</html>