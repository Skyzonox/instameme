<?php
require_once 'affichage.php';
require_once 'db.php';
echo pageHeader("Bonjour");
session_start();
session_destroy();
echo "Bienvenue " . $_SESSION['pseudo'];

if(!isset($_SESSION['pseudo'])){
    header('Location: connexion.php');
}

    

?>

<p><a href="indextest.php">Se déconnecter </a></p>
