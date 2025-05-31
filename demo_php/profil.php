<?php
session_start();
require_once 'affichage.php';
require_once 'db.php';

requireLogin();

// Récupérer les statistiques de l'utilisateur
$stats_query = db()->prepare("
    SELECT 
        COUNT(DISTINCT c.id) as nb_contenus,
        COUNT(DISTINCT l.id) as nb_likes_recus,
        COUNT(DISTINCT l2.id) as nb_likes_donnes
    FROM utilisateurs u
    LEFT JOIN contenus c ON c.id_utilisateur = u.id
    LEFT JOIN likes l ON l.id_contenu = c.id
    LEFT JOIN likes l2 ON l2.id_utilisateur = u.id
    WHERE u.id = ?
");
$stats_query->execute([$_SESSION['id']]);
$stats = $stats_query->fetch();

// Récupérer les contenus de l'utilisateur
$contenus_query = db()->prepare("
    SELECT 
        c.id,
        c.description,
        c.chemin_image,
        c.date_creation,
        COALESCE(l.nb_likes, 0) AS nb_likes
    FROM contenus c
    LEFT JOIN (
        SELECT id_contenu, COUNT(*) AS nb_likes
        FROM likes
        GROUP BY id_contenu
    ) l ON l.id_contenu = c.id
    WHERE c.id_utilisateur = ?
    ORDER BY c.date_creation DESC
");
$contenus_query->execute([$_SESSION['id']]);
$mes_contenus = $contenus_query->fetchAll();

pageHeader('Mon Profil');
?>

<div class="profile-container">
    <div class="profile-header">
        <h1>Profil de <?php echo sanitizeOutput($_SESSION['pseudo']); ?></h1>
        
        <div class="profile-stats">
            <div class="stat">
                <span class="stat-number"><?php echo $stats['nb_contenus']; ?></span>
                <span class="stat-label">meme<?php echo $stats['nb_contenus'] > 1 ? 's' : ''; ?></span>
            </div>
            <div class="stat">
                <span class="stat-number"><?php echo $stats['nb_likes_recus']; ?></span>
                <span class="stat-label">j'aime reçu<?php echo $stats['nb_likes_recus'] > 1 ? 's' : ''; ?></span>
            </div>
            <div class="stat">
                <span class="stat-number"><?php echo $stats['nb_likes_donnes']; ?></span>
                <span class="stat-label">j'aime donné<?php echo $stats['nb_likes_donnes'] > 1 ? 's' : ''; ?></span>
            </div>
        </div>
        
        <div class="profile-actions">
            <a href="creer.php" class="btn btn-primary">Créer un meme</a>
            <a href="deconnexion.php" class="btn btn-secondary">Se déconnecter</a>
        </div>
    </div>
    
    <div class="profile-content">
        <h2>Mes memes</h2>
        
        <?php if (empty($mes_contenus)): ?>
            <div class="no-content">
                <p>Vous n'avez pas encore publié de meme.</p>
                <a href="creer.php" class="btn btn-primary">Créer votre premier meme</a>
            </div>
        <?php else: ?>
            <div class="contenus-grid">
                <?php foreach ($mes_contenus as $contenu): ?>
                    <div class="meme-card profile-meme">
                        <div class="meme-image">
                            <img src="<?php echo UPLOAD_DIR . sanitizeOutput($contenu['chemin_image']); ?>" 
                                 alt="Meme du <?php echo date('d/m/Y', strtotime($contenu['date_creation'])); ?>">
                        </div>
                        
                        <div class="meme-stats">
                            <span class="likes">
                                <i class="fas fa-heart"></i>
                                <?php echo $contenu['nb_likes']; ?>
                            </span>
                            <span class="date">
                                <?php echo date('d/m/Y', strtotime($contenu['date_creation'])); ?>
                            </span>
                        </div>
                        
                        <div class="meme-description">
                            <?php echo sanitizeOutput($contenu['description']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php pageFooter(); ?>