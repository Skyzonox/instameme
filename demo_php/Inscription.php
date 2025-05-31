<?php
session_start();
require_once 'affichage.php';
require_once 'db.php';

// Rediriger si déjà connecté
if (isLoggedIn()) {
    redirectTo('index.php');
}

$error = '';
$success = '';

if (isset($_POST['inscription'])) {
    // Vérifier le token CSRF
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Token de sécurité invalide';
    } else {
        $pseudo = trim($_POST['pseudo'] ?? '');
        $mot_de_passe = $_POST['mot_de_passe'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($pseudo) || empty($mot_de_passe) || empty($confirm_password)) {
            $error = 'Tous les champs sont obligatoires';
        } elseif (strlen($pseudo) < 3 || strlen($pseudo) > 20) {
            $error = 'Le pseudo doit contenir entre 3 et 20 caractères';
        } elseif (strlen($mot_de_passe) < 6) {
            $error = 'Le mot de passe doit contenir au moins 6 caractères';
        } elseif ($mot_de_passe !== $confirm_password) {
            $error = 'Les mots de passe ne correspondent pas';
        } else {
            // Vérifier si le pseudo existe déjà
            $check = db()->prepare('SELECT id FROM utilisateurs WHERE pseudo = ?');
            $check->execute([$pseudo]);
            
            if ($check->rowCount() > 0) {
                $error = 'Ce pseudo est déjà utilisé';
            } else {
                // Créer l'utilisateur
                $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
                
                $insert = db()->prepare('INSERT INTO utilisateurs (pseudo, mot_de_passe) VALUES (?, ?)');
                
                if ($insert->execute([$pseudo, $mot_de_passe_hash])) {
                    $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                } else {
                    $error = 'Erreur lors de l\'inscription';
                }
            }
        }
    }
}

pageHeader('Inscription');
?>

<div class="auth-container">
    <div class="auth-form">
        <h1>Inscription</h1>
        
        <?php if ($error): ?>
            <?php showAlert($error, 'error'); ?>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <?php showAlert($success, 'success'); ?>
            <p><a href="connexion.php">Se connecter maintenant</a></p>
        <?php else: ?>
            
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" required 
                       value="<?php echo sanitizeOutput($_POST['pseudo'] ?? ''); ?>"
                       minlength="3" maxlength="20">
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" required minlength="6">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" name="confirm_password" id="confirm_password" required minlength="6">
            </div>
            
            <button type="submit" name="inscription">S'inscrire</button>
        </form>
        
        <p>Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>
        
        <?php endif; ?>
    </div>
</div>

<?php pageFooter(); ?>