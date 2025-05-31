<?php
session_start();
require_once 'affichage.php';
require_once 'db.php';

// Récupérer tous les contenus avec leurs données associées
$stmt = db()->prepare("
    SELECT 
        c.id,
        c.description,
        c.chemin_image,
        c.date_creation,
        u.pseudo,
        COALESCE(l.nb_likes, 0) AS nb_likes,
        COALESCE(com.nb_commentaires, 0) AS nb_commentaires,
        GROUP_CONCAT(DISTINCT CONCAT(u_com.pseudo, ': ', comments.message) SEPARATOR '|') AS commentaires
    FROM contenus c
    LEFT JOIN utilisateurs u ON u.id = c.id_utilisateur
    LEFT JOIN (
        SELECT id_contenu, COUNT(*) AS nb_likes
        FROM likes
        GROUP BY id_contenu
    ) l ON l.id_contenu = c.id
    LEFT JOIN (
        SELECT id_contenu, COUNT(*) AS nb_commentaires
        FROM commentaires
        GROUP BY id_contenu
    ) com ON com.id_contenu = c.id
    LEFT JOIN commentaires comments ON comments.id_contenu = c.id
    LEFT JOIN utilisateurs u_com ON u_com.id = comments.id_utilisateur
    GROUP BY c.id
    ORDER BY c.date_creation DESC
");

$stmt->execute();
$contenus = $stmt->fetchAll();

// Vérifier si l'utilisateur a liké chaque contenu
$user_likes = [];
if (isLoggedIn()) {
    $likes_stmt = db()->prepare("SELECT id_contenu FROM likes WHERE id_utilisateur = ?");
    $likes_stmt->execute([$_SESSION['id']]);
    $user_likes = array_column($likes_stmt->fetchAll(), 'id_contenu');
}

pageHeader('Accueil');
?>

<?php if (isLoggedIn()): ?>
    <div class="welcome-message">
        <h2>Bienvenue <?php echo sanitizeOutput($_SESSION['pseudo']); ?> !</h2>
        <a href="creer.php" class="btn btn-primary">Créer un nouveau meme</a>
    </div>
<?php else: ?>
    <div class="welcome-message">
        <h2>Bienvenue sur InstaMeme</h2>
        <p>Partagez vos memes préférés avec la communauté !</p>
        <a href="inscription.php" class="btn btn-primary">Rejoignez-nous</a>
    </div>
<?php endif; ?>

<div class="contenus-grid">
    <?php if (empty($contenus)): ?>
        <div class="no-content">
            <p>Aucun meme n'a été publié pour le moment.</p>
            <?php if (isLoggedIn()): ?>
                <a href="creer.php">Soyez le premier à publier !</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php foreach ($contenus as $contenu): ?>
            <div class="meme-card">
                <div class="meme-header">
                    <span class="pseudo"><?php echo sanitizeOutput($contenu['pseudo']); ?></span>
                    <span class="date"><?php echo date('d/m/Y H:i', strtotime($contenu['date_creation'])); ?></span>
                </div>
                
                <div class="meme-image">
                    <img src="<?php echo UPLOAD_DIR . sanitizeOutput($contenu['chemin_image']); ?>" 
                         alt="Meme de <?php echo sanitizeOutput($contenu['pseudo']); ?>">
                </div>
                
                <div class="meme-actions">
                    <?php if (isLoggedIn()): ?>
                        <a href="action.php?t=1&id=<?php echo $contenu['id']; ?>&csrf=<?php echo generateCSRFToken(); ?>" 
                           class="action-btn <?php echo in_array($contenu['id'], $user_likes) ? 'liked' : ''; ?>">
                            <i class="fas fa-heart"></i>
                        </a>
                        <a href="action.php?t=2&id=<?php echo $contenu['id']; ?>&csrf=<?php echo generateCSRFToken(); ?>" 
                           class="action-btn">
                            <i class="fas fa-share"></i>
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="meme-stats">
                    <span class="likes">
                        <i class="fas fa-heart"></i>
                        <?php echo $contenu['nb_likes']; ?> j'aime<?php echo $contenu['nb_likes'] > 1 ? 's' : ''; ?>
                    </span>
                </div>
                
                <div class="meme-description">
                    <strong><?php echo sanitizeOutput($contenu['pseudo']); ?></strong>
                    <?php echo sanitizeOutput($contenu['description']); ?>
                </div>
                
                <?php if ($contenu['nb_commentaires'] > 0): ?>
                    <div class="meme-comments">
                        <span class="comments-count">
                            Voir les <?php echo $contenu['nb_commentaires']; ?> commentaire<?php echo $contenu['nb_commentaires'] > 1 ? 's' : ''; ?>
                        </span>
                        <?php if ($contenu['commentaires']): ?>
                            <div class="comments-preview">
                                <?php 
                                $comments = explode('|', $contenu['commentaires']);
                                foreach (array_slice($comments, 0, 2) as $comment): 
                                    if (!empty($comment)):
                                ?>
                                    <div class="comment">
                                        <?php echo sanitizeOutput($comment); ?>
                                    </div>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php pageFooter(); ?>