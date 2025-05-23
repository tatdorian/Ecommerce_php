<?php
require_once '../configs/db.config.php';

if (!isset($_GET['id'])) {
    header('Location: home.page.php');
    exit;
}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT a.*, u.username AS auteur FROM article a JOIN user u ON a.auteur_id = u.id WHERE a.id = ?");
$stmt->execute([$id]);

$article = $stmt->fetch();

if (!$article) {
    echo "Article non trouvé.";
    exit;
}
