<?php
// Assurez-vous que ces informations sont correctes pour Laragon
$host = 'localhost';
$dbname = 'football_db';    // Nom de votre base
$username = 'root';     // Par défaut dans Laragon
$password = 'root';         // Par défaut vide dans Laragon

// Version ultra-sécurisée avec vérifications
if (!file_exists(__FILE__)) {
    die('Fichier de configuration introuvable');
}

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("ERREUR CONNEXION DB: " . $e->getMessage());
}
?>