<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/f1daa21f42.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="index.css">
    
</head>
<body>





    

</body>
</html>

<?php
session_start();
echo "Bienvenue " . $_SESSION['pseudo'];
require_once 'affichage.php';
require_once 'db.php';
?>


<?php




$bdd = new PDO('mysql:host=localhost;dbname=instameme;charset=utf8', 'root', '');


$stmt = db()->prepare("SELECT
contenus.id,
contenus.description,
contenus.chemin_image,
utilisateurs.pseudo,
likes_par_contenu.nb_likes,
commentaires_par_contenu.nb_commentaires,
GROUP_CONCAT(DISTINCT commentaires.message SEPARATOR '/') AS message
FROM
contenus
LEFT JOIN
utilisateurs ON utilisateurs.id = contenus.id_utilisateur
LEFT JOIN (
SELECT
    id_contenu,
    COUNT(id_utilisateur) AS nb_likes
FROM
    likes
GROUP BY
    id_contenu
) AS likes_par_contenu ON likes_par_contenu.id_contenu = contenus.id
LEFT JOIN (
SELECT
    id_contenu,
    COUNT(id) AS nb_commentaires
FROM
    commentaires
GROUP BY
    id_contenu
) AS commentaires_par_contenu ON commentaires_par_contenu.id_contenu = contenus.id
LEFT JOIN
commentaires ON commentaires.id_contenu = contenus.id
GROUP BY
contenus.id;");



if(isset($_GET['id']) AND !empty($_GET['id'])){
    $get_id = $_GET['id'];

    $contenu = $bdd->prepare('SELECT * FROM contenus WHERE id = ?');
    $contenu->execute(array($get_id));

    if($contenu->rowCount()== 1){
        $contenu = $contenu->fetch();
        $id = $contenu['id'];
        $description = $contenu['description'];

        $likes = $bdd->prepare('SELECT * from likes where id_contenu = ?');
        $likes->execute(array($id));
        $likes= $likes->rowCount();
    }
}

$stmt->execute();
$contenus = $stmt->fetchAll();

?>




<?php
echo pageHeader("Bonjour");
echo "Hello World !";
?>
<br>
<br>
<br>
<br>

<div class="tableau">
    <?php foreach ($contenus as $contenu): ?>
    
        <div class="meme">
            <div class="pseudo">
                <?php echo  $contenu['pseudo'] . '<br>' ?>
            </div>
            <div class="meme-image">
                <img src="images/<?php echo $contenu['chemin_image']; ?>" class="h-40" />
            </div>
            <div class ="bouton">
            <?php if (isset($_SESSION['pseudo'])): ?>
                <a href="action.php?t=1&id=<?= $contenu['id'] ?>">J'aime</a> (<?= $contenu['nb_likes'] ?>)
                <br>
                <a href="action.php?t=2&id=<?= $contenu['id'] ?>">Partage</a> 
                <?php endif; ?>
            </div> 
            <div class="like">
                <i class="fas fa-heart"></i>
               Aimé par <?php echo $contenu['nb_likes'] ; ?> utilisateurs
            </div>
            <div class="meme-description">
                Description : <?php echo $contenu['description']; ?>
            </div>
            <div class="comment">
                <i class="fas fa-comment"></i>
               Commentaire:
                <?php echo $contenu['message'] ; ?> 
            </div>
        </div>
    <?php endforeach; ?>
    
</div>
    
    







<?php echo pageFooter(); ?> 