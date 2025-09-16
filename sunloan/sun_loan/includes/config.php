<?php
session_start();

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sun_loan');

// Connexion à la base de données
try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<div style="color:red; font-weight:bold; padding:10px;">';
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage() . '<br>';
    echo 'Vérifiez que le serveur MySQL est démarré, que la base <b>' . DB_NAME . '</b> existe, et que les identifiants sont corrects.';
    echo '</div>';
    exit();
}

// Fonction pour sécuriser les entrées
function secure_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>