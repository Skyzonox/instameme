<?php
// config.php - Configuration de l'application InstaMeme

// Configuration de l'upload
define('UPLOAD_DIR', 'images/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB en bytes
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Configuration de la base de données
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'instameme');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuration de sécurité
define('CSRF_TOKEN_NAME', 'csrf_token');

// Fonctions utilitaires pour CSRF
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Mise à jour de la fonction db() pour utiliser les constantes
function db() {
    try {
        $db = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    } catch (Throwable $e) {
        die('Erreur de connexion à la BDD: ' . $e->getMessage());
    }
    return $db;
}
?>