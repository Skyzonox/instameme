<?php

function db() {
    try {
        $db = new PDO(
            'mysql:host=127.0.0.1;dbname=instameme;charset=utf8',
            'root',
            ''
            
        );
    } catch (Throwable $e) {
        die('Erreur de connexion à la BDD');
    }
    return $db;
}

