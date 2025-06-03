<?php
require_once '../middlewares/auth.middleware.php';
require_once '../configs/db.config.php';

$user_id = $_SESSION['user_id'] ?? null;
$article_id = $_GET['id'] ?? null;


if (!$user_id) {
    header('Location: login.page.php');
    exit;
}


if (!$article_id) {
    header('Location: account.page.php?error=missing_id');
    exit;
}

try {
   
    $stmt = $pdo->prepare("SELECT * FROM article WHERE id = :id AND auteur_id = :user_id");
    $stmt->execute(['id' => $article_id, 'user_id' => $user_id]);
    $article = $stmt->fetch();
    
    if (!$article) {
        header('Location: account.page.php?error=article_not_found');
        exit;
    }
    
   
    $pdo->beginTransaction();
    
    
    $stmt = $pdo->prepare("
        INSERT INTO old_article (nom, description, prix, date_publication, image, auteur_id)
        VALUES (:nom, :description, :prix, :date_publication, :image, :auteur_id)
    ");
    
    $stmt->execute([
        'nom' => $article['nom'],
        'description' => $article['description'],
        'prix' => $article['prix'],
        'date_publication' => $article['date_publication'],
        'image' => $article['image'],
        'auteur_id' => $article['auteur_id']
    ]);
    
    
    $stmt = $pdo->prepare("DELETE FROM article WHERE id = :id AND auteur_id = :user_id");
    $stmt->execute(['id' => $article_id, 'user_id' => $user_id]);
    
    
    $pdo->commit();
    
   
    header('Location: account.page.php?success=article_deleted');
    exit;
    
} catch (PDOException $e) {
    
    $pdo->rollback();
    
    
    header('Location: account.page.php?error=delete_failed');
    exit;
}
?>