<?php

$bdd = new PDO('mysql:host=127.0.0.1;dbname=instameme;charset=utf8','root',
'');

if(isset($_POST['inscription'])){
    
    $pseudo = $_POST['pseudo'];
    $mot_de_passe = md5($_POST['mot_de_passe']); 
    
    


    $requete = $bdd->prepare("INSERT INTO utilisateurs (pseudo, mot_de_passe) VALUES (:pseudo, :mot_de_passe)"); 
    $requete->execute(
        array(
            
            'pseudo' => $pseudo,
            'mot_de_passe' => $mot_de_passe
            
            
        )
    );

    $reponse = $requete->fetchALL(PDO::FETCH_ASSOC);
    var_dump($reponse);
    header('Location: index.php');


    


}  




?>