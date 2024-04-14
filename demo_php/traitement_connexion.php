<?php

function verif($pseudo,$mot_de_passe)

{
    $bdd = new PDO('mysql:host=localhost;dbname=instameme;charset=utf8', 'root', '');
    $req = $bdd->prepare('SELECT * FROM utilisateurs WHERE pseudo = :pseudo AND mot_de_passe = :mot_de_passe');
    $req->execute(array(
        'pseudo' => $pseudo,
       'mot_de_passe' => $mot_de_passe
    ));
    $resultat = $req->fetchAll();
    if (count($resultat) > 0) {
        return true;
    } else {
        echo "mot de passe ou pseudo incorrect";
    }
    
  
}

?>