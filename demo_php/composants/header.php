<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'InstaMeme'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/f1daa21f42.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <h1 class="nav-logo"><a href="index.php">InstaMeme</a></h1>
            <div class="nav-menu">
                <?php if (isset($_SESSION['pseudo'])): ?>
                    <a href="index.php" class="nav-link">Accueil</a>
                    <a href="creer.php" class="nav-link">Créer</a>
                    <a href="profil.php" class="nav-link">Profil (<?php echo htmlspecialchars($_SESSION['pseudo']); ?>)</a>
                    <a href="deconnexion.php" class="nav-link">Déconnexion</a>
                <?php else: ?>
                    <a href="connexion.php" class="nav-link">Connexion</a>
                    <a href="inscription.php" class="nav-link">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="main-content">