<?php
require_once '../configs/db.config.php';

$stmt = $pdo->query(
    "SELECT a.id, a.nom, a.description, a.prix, a.date_publication, a.image, u.id AS auteur_id, u.username AS auteur
     FROM article a
     JOIN user u ON a.auteur_id = u.id
     ORDER BY a.date_publication DESC"
);
$articles = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT u.role FROM user u WHERE u.id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();