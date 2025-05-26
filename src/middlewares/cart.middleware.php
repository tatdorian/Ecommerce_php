<?php
require_once __DIR__ . '/../configs/db.config.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$cart_books = [];
$total = 0;

if ($user_id) {
    $stmt = $pdo->prepare(
        "SELECT a.id, a.titre_livre, a.nom, a.auteur_livre, a.prix, a.image, c.quantity
         FROM cart c
         JOIN article a ON c.article_id = a.id
         WHERE c.user_id = ?"
    );
    $stmt->execute([$user_id]);
    $cart_books = $stmt->fetchAll();

    foreach ($cart_books as $book) {
        $total += $book['prix'] * $book['quantity'];
    }
}
?>