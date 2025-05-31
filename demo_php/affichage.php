<?php
function pageHeader($title = 'InstaMeme') {
    $GLOBALS['page_title'] = $title;
    require 'composants/header.php';
}

function pageFooter() {
    require 'composants/footer.php';
}

function redirectTo($url) {
    header('Location: ' . $url);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['id']) && isset($_SESSION['pseudo']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirectTo('connexion.php');
    }
}

function sanitizeOutput($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function showAlert($message, $type = 'info') {
    echo '<div class="alert alert-' . $type . '">' . sanitizeOutput($message) . '</div>';
}
?>