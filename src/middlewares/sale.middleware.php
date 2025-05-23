<?php 
require_once '../configs/db.config.php';


$stmt = $pdo->query(
      "SELECT a.*, u.username AS auteur 
     FROM article a
     JOIN user u ON a.auteur_id = u.id
     ORDER BY a.date_publication DESC"
);  

$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>