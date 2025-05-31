<?php
session_start();
require_once 'affichage.php';
require_once 'db.php';

requireLogin();

// Vérifier les paramètres
if (!isset($_GET['t'], $_GET['id'], $_GET['csrf']) || empty($_GET['id'])) {
    redirectTo('index.php');
}

// Vérifier le token CSRF
if (!verifyCSRFToken($_GET['csrf'])) {
    redirectTo('index.php');
}

$type = (int) $_GET['t'];
$id_contenu = (int) $_GET['id'];
$id_utilisateur = $_SESSION['id'];

// Vérifier que le contenu existe
$check_content = db()->prepare('SELECT id FROM contenus WHERE id = ?');
$check_content->execute([$id_contenu]);

if ($check_content->rowCount() === 0) {
    redirectTo('index.php');
}

switch ($type) {
    case 1: // Like/Unlike
        try {
            db()->beginTransaction();
            
            // Vérifier si déjà liké
            $check_like = db()->prepare('SELECT id FROM likes WHERE id_utilisateur = ? AND id_contenu = ?');
            $check_like->execute([$id_utilisateur, $id_contenu]);
            
            if ($check_like->rowCount() === 0) {
                // Ajouter le like
                $add_like = db()->prepare('INSERT INTO likes (id_utilisateur, id_contenu, date_creation) VALUES (?, ?, NOW())');
                $add_like->execute([$id_utilisateur, $id_contenu]);
            } else {
                // Supprimer le like
                $remove_like = db()->prepare('DELETE FROM likes WHERE id_utilisateur = ? AND id_contenu = ?');
                $remove_like->execute([$id_utilisateur, $id_contenu]);
            }
            
            db()->commit();
        } catch (Exception $e) {
            db()->rollBack();
            error_log('Erreur lors du like/unlike: ' . $e->getMessage());
        }
        break;
        
    case 2: // Share (à implémenter selon vos besoins)
        // Pour le moment, on redirige simplement
        // Vous pouvez ajouter ici la logique de partage
        break;
        
    default:
        // Type d'action non reconnu
        break;
}

// Redirection vers la page précédente ou index
$redirect_url = $_SERVER['HTTP_REFERER'] ?? 'index.php';
redirectTo($redirect_url);
?>