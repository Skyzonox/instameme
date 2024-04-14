<?php



session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=instameme;charset=utf8','root',
'');

if(isset($_POST['connexion'])){

    if(!empty($_POST['pseudo']) AND !empty($_POST['mot_de_passe'])){

        


        $pseudo = $_POST['pseudo'];
        $mot_de_passe = md5($_POST['mot_de_passe']);

        $recupUser = $bdd->prepare('SELECT * FROM utilisateurs WHERE pseudo = ? AND mot_de_passe = ?');
        $recupUser->execute(array($pseudo, $mot_de_passe));

        if($recupUser->rowCount()>0){
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['mot_de_passe'] = $mot_de_passe;
            $_SESSION['id'] = $recupUser->fetch()['id'];
            echo $_SESSION['id'];
            header('Location: index.php');
            
        
        }else{
            echo "Pseudo ou mot de passe incorrect";
        }
    }else{
        echo "Veuillez compléter tous les champs";
    
    }

}
    

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    

    <form method="POST" action="" align="center">

    <h1>Pseudo</h1>
        <input type="text" name ="pseudo">
        <br>
    <h2>Password</h2>
        <input type="password" name ="mot_de_passe">
        <br><br>
        <input type= "submit" name ="connexion">
    <a href= 'Inscription.php'> Inscrivez vous ici </a>
    </form>
    
</body>
</html>