<?php
session_start();
require_once 'affichage.php';
require_once 'db.php';

// Rediriger si déjà connecté
if (isLoggedIn()) {
    redirectTo('index.php');
}

$error = '';

if (isset($_POST['connexion'])) {
    // Vérifier le token CSRF
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Token de sécurité invalide';
    } else {
        $pseudo = trim($_POST['pseudo'] ?? '');
        $mot_de_passe = $_POST['mot_de_passe'] ?? '';
        
        if (empty($pseudo) || empty($mot_de_passe)) {
            $error = 'Veuillez compléter tous les champs';
        } else {
            // Récupérer l'utilisateur
            $user_query = db()->prepare('SELECT id, pseudo, mot_de_passe FROM utilisateurs WHERE pseudo = ?');
            $user_query->execute([$pseudo]);
            
            if ($user_query->rowCount() > 0) {
                $user = $user_query->fetch();
                
                // Vérifier le mot de passe
                if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                    // Connexion réussie
                    session_regenerate_id(true); // Sécurité contre les attaques de session
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['pseudo'] = $user['pseudo'];
                    
                    redirectTo('index.php');
                } else {
                    $error = 'Pseudo ou mot de passe incorrect';
                }
            } else {
                $error = 'Pseudo ou mot de passe incorrect';
            }
        }
    }
}

pageHeader('Connexion');
?>

<div class="auth-container">
    <div class="auth-form">
        <h1>Connexion</h1>
        
        <?php if ($error): ?>
            <?php showAlert($error, 'error'); ?>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" required 
                       value="<?php echo sanitizeOutput($_POST['pseudo'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" required>
            </div>
            
            <button type="submit" name="connexion">Se connecter</button>
        </form>
        
        <p>Pas encore inscrit ? <a href="inscription.php">S'inscrire</a></p>
    </div>
</div>

<?php pageFooter(); ?>