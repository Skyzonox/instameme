<?php
session_start();
require_once 'affichage.php';
require_once 'db.php';

requireLogin();

$error = '';
$success = '';

if (isset($_POST['creer'])) {
    // Vérifier le token CSRF
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Token de sécurité invalide';
    } else {
        $description = trim($_POST['description'] ?? '');
        
        if (empty($description)) {
            $error = 'La description est obligatoire';
        } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Veuillez sélectionner une image valide';
        } else {
            $file = $_FILES['image'];
            
            // Vérifier la taille
            if ($file['size'] > MAX_FILE_SIZE) {
                $error = 'Le fichier est trop volumineux (max 5MB)';
            } else {
                // Vérifier l'extension
                $filename = $file['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (!in_array($ext, ALLOWED_EXTENSIONS)) {
                    $error = 'Format de fichier non autorisé. Utilisez : ' . implode(', ', ALLOWED_EXTENSIONS);
                } else {
                    // Vérifier que c'est vraiment une image
                    $image_info = getimagesize($file['tmp_name']);
                    if ($image_info === false) {
                        $error = 'Le fichier n\'est pas une image valide';
                    } else {
                        // Créer le dossier d'upload s'il n'existe pas
                        if (!is_dir(UPLOAD_DIR)) {
                            mkdir(UPLOAD_DIR, 0755, true);
                        }
                        
                        // Générer un nom de fichier unique
                        $new_filename = uniqid() . '_' . time() . '.' . $ext;
                        $destination = UPLOAD_DIR . $new_filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $destination)) {
                            // Sauvegarder en base de données
                            $insert = db()->prepare('
                                INSERT INTO contenus (description, chemin_image, id_utilisateur, date_creation) 
                                VALUES (?, ?, ?, NOW())
                            ');
                            
                            if ($insert->execute([$description, $new_filename, $_SESSION['id']])) {
                                $success = 'Votre meme a été publié avec succès !';
                                // Redirection après 2 secondes
                                header('refresh:2;url=index.php');
                            } else {
                                $error = 'Erreur lors de la sauvegarde en base de données';
                                // Supprimer le fichier uploadé en cas d'erreur
                                unlink($destination);
                            }
                        } else {
                            $error = 'Erreur lors de l\'upload du fichier';
                        }
                    }
                }
            }
        }
    }
}

pageHeader('Créer un meme');
?>

<div class="create-container">
    <h1>Créer un nouveau meme</h1>
    
    <?php if ($error): ?>
        <?php showAlert($error, 'error'); ?>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <?php showAlert($success, 'success'); ?>
        <p>Redirection vers l'accueil...</p>
    <?php else: ?>
    
    <form method="POST" enctype="multipart/form-data" class="create-form">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        
        <div class="form-group">
            <label for="image">Image *</label>
            <input type="file" name="image" id="image" required 
                   accept="<?php echo implode(',', array_map(function($ext) { return '.' . $ext; }, ALLOWED_EXTENSIONS)); ?>">
            <small>Formats acceptés : <?php echo implode(', ', ALLOWED_EXTENSIONS); ?> (max 5MB)</small>
        </div>
        
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea name="description" id="description" rows="3" required 
                      placeholder="Décrivez votre meme..."><?php echo sanitizeOutput($_POST['description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" name="creer" class="btn btn-primary">Publier</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
    
    <?php endif; ?>
</div>

<?php pageFooter(); ?>
