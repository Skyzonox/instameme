<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if(!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit;
}

// Get action type and content ID
if(isset($_GET['t'], $_GET['id']) && !empty($_GET['id'])) {
    $type = (int) $_GET['t'];
    $id_contenu = (int) $_GET['id'];
    
    // Get user ID from session
    $id_utilisateur = $_SESSION['id'];
    
    // Action depends on type
    if($type == 1) { // Like
        // Check if already liked
        $check = db()->prepare('SELECT id FROM likes WHERE id_utilisateur = ? AND id_contenu = ?');
        $check->execute([$id_utilisateur, $id_contenu]);
        
        if($check->rowCount() == 0) {
            // Add like
            $like = db()->prepare('INSERT INTO likes (id_utilisateur, id_contenu) VALUES (?, ?)');
            $like->execute([$id_utilisateur, $id_contenu]);
        } else {
            // Remove like
            $unlike = db()->prepare('DELETE FROM likes WHERE id_utilisateur = ? AND id_contenu = ?');
            $unlike->execute([$id_utilisateur, $id_contenu]);
        }
    } elseif($type == 2) { // Share - you can implement sharing functionality here
        // Just redirect for now
    }
}

// Redirect back to index
header('Location: index.php');
exit;
?>