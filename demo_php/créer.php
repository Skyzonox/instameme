<?php
session_start();
require_once 'affichage.php';
require_once 'db.php';

// Check if user is logged in
if(!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit;
}

echo pageHeader("Créer un meme");

// Process form submission
if(isset($_POST['creer'])) {
    if(!empty($_POST['description']) && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $description = htmlspecialchars($_POST['description']);
        $id_utilisateur = $_SESSION['id'];
        
        // Handle file upload
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            // Generate unique filename
            $new_filename = uniqid() . '.' . $ext;
            $destination = 'images/' . $new_filename;
            
            if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                // Save to database
                $insert = db()->prepare('INSERT INTO contenus (description, chemin_image, id_utilisateur) VALUES (?, ?, ?)');
                $insert->execute([$description, $new_filename, $id_utilisateur]);
                
                echo '<div class="success">Votre meme a été créé avec succès!</div>';
                header('Location: index.php');
                exit;
            } else {
                echo '<div class="error">Erreur lors de l\'upload du fichier</div>';
            }
        } else {
            echo '<div class="error">Format de fichier non autorisé</div>';
        }
    } else {
        echo '<div class="error">Veuillez remplir tous les champs</div>';
    }
}
?>

<div class="container">
    <form method="POST" enctype="multipart/form-data">
        <h2>Créer un nouveau meme</h2>
        
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3" required></textarea>
        </div>
        
        <button type="submit" name="creer">Publier</button>
    </form>
</div>

<?php echo pageFooter(); ?>