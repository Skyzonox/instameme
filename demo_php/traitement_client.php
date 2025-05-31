<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=instameme;charset=utf8','root', '');

if(isset($_POST['inscription'])){
    $pseudo = $_POST['pseudo'];
    // Utilise password_hash au lieu de md5
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); 
    
    $requete = $bdd->prepare("INSERT INTO utilisateurs (pseudo, mot_de_passe) VALUES (:pseudo, :mot_de_passe)"); 
    $requete->execute(
        array(
            'pseudo' => $pseudo,
            'mot_de_passe' => $mot_de_passe
        )
    );
    
    header('Location: index.php');
    exit;
}
?>