<?php
require_once 'affichage.php';
require_once 'db.php';
session_start(); // Start session

// Check if user is logged in
if(!isset($_SESSION['pseudo'])){
    header('Location: connexion.php');
    exit; // Important to add exit after redirect
}

echo pageHeader("Profil");
echo "Bienvenue " . $_SESSION['pseudo'];
?>

<p><a href="deconnexion.php">Se déconnecter</a></p>

<?php echo pageFooter(); ?>