<?php
$bdd = new PDO('mysql:host=localhost;dbname=instameme;charset=utf8', 'root', '');

if(isset($_GET['t'],$_GET['id']) AND !empty($_GET['t']) AND !empty($_GET['id'])){
    $getid =(INT)$_GET['id'];
    $gett = (INT)$_GET['t'];

    $sessionid = 4;


    $check = $bdd->prepare('SELECT id FROM contenus where id = ?');
    $check->execute(array($getid));

    if ($check->rowCount() ==1){
        if($gett ==1){
            $checklike = $bdd->prepare('SELECT * from likes where id_contenu = ? AND 
                id_utilisateur = ?');
            $checklike->execute(array($getid, $sessionid));

            if ($checklike->rowCount() == 1){
                $del = $bdd->prepare('DELETE FROM likes WHERE id_contenu = ? AND id_utilisateur = ?');
                $del->execute(array($getid, $sessionid));

            }else {
                $ins = $bdd->prepare('INSERT INTO likes (id_contenu, id_utilisateur) VALUES (?, ?)
                ');
                                      
                $ins->execute(array($getid, $sessionid));
            }
            

            header('Location: indextest.php?id=' .$getid);
        

            
        

       

    }
}
}


?>