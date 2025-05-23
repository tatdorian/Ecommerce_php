<?php
require_once '../configs/db.config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    // Informations du compte utilisateur
    $stmt = $pdo->prepare(
        "SELECT u.password, u.email, u.solde, u.photo_profil, u.role, u.nom, u.username AS utilisateur
         FROM user u
         WHERE u.id = :id"
    );
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();

    // Publications de l'utilisateur
    $stmt = $pdo->prepare(
        "SELECT a.id, a.nom, a.description, a.prix, a.date_publication, a.image
         FROM article a
         WHERE a.auteur_id = :id
         ORDER BY a.date_publication DESC"
    );
    $stmt->execute(['id' => $user_id]);
    $articles = $stmt->fetchAll();
} else {
    $user = null;
    $articles = [];
}
?>