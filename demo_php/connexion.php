<?php
session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=instameme;charset=utf8','root', '');

if(isset($_POST['connexion'])){
    if(!empty($_POST['pseudo']) AND !empty($_POST['mot_de_passe'])){
        $pseudo = $_POST['pseudo'];
        
        // Récupère l'utilisateur par pseudo
        $recupUser = $bdd->prepare('SELECT * FROM utilisateurs WHERE pseudo = ?');
        $recupUser->execute(array($pseudo));
        
        if($recupUser->rowCount() > 0){
            $user = $recupUser->fetch();
            
            // Vérifie le mot de passe avec password_verify
            if(password_verify($_POST['mot_de_passe'], $user['mot_de_passe'])){
                $_SESSION['pseudo'] = $pseudo;
                $_SESSION['id'] = $user['id'];
                header('Location: index.php');
                exit;
            } else {
                echo "Pseudo ou mot de passe incorrect";
            }
        } else {
            echo "Pseudo ou mot de passe incorrect";
        }
    } else {
        echo "Veuillez compléter tous les champs";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <form method="POST" action="" align="center">
        <h1>Pseudo</h1>
        <input type="text" name="pseudo">
        <br>
        <h2>Mot de passe</h2>
        <input type="password" name="mot_de_passe">
        <br><br>
        <input type="submit" name="connexion">
        <a href='Inscription.php'> Inscrivez vous ici </a>
    </form>
</body>
</html>