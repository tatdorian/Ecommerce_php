<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';

// Suppression d'un article du panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    require_once '../configs/db.config.php';
    $user_id = $_SESSION['user_id'];
    $article_id = intval($_POST['remove_id']);
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND article_id = ?");
    $stmt->execute([$user_id, $article_id]);
}

// Modification de la quantité
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'], $_POST['quantity'])) {
    require_once '../configs/db.config.php';
    $user_id = $_SESSION['user_id'];
    $article_id = intval($_POST['update_id']);
    $quantity = max(1, intval($_POST['quantity']));
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND article_id = ?");
    $stmt->execute([$quantity, $user_id, $article_id]);
}

require_once '../middlewares/cart.middleware.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier - Librairie en Ligne</title>
    <link rel="stylesheet" href="../assets/css/cart.css">
</head>
<body>
<header>
    <h1>Mon Panier</h1>
    <a href="home.page.php" class="back-link">← Retour à la boutique</a>
</header>
<div class="container">
    <nav>
        <?php if ($is_logged_in): ?>
        <?php endif; ?>
    </nav>
    <h2 style="text-align:center; margin-bottom:32px;">Livres dans votre panier</h2>
    <div class="cart-list">
        <?php if (empty($cart_books)): ?>
            <p class="empty-cart">Votre panier est vide.</p>
        <?php else: ?>
            <?php foreach ($cart_books as $book): ?>
                <div class="cart-item">
                    <?php if (!empty($book['image'])): ?>
                        <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="Couverture du livre" class="cart-image">
                    <?php else: ?>
                        <div class="cart-image book-placeholder">📚</div>
                    <?php endif; ?>
                    <div class="cart-info">
                        <div class="cart-title"><?php echo htmlspecialchars($book['titre_livre'] ?? $book['nom']); ?></div>
                        <div class="cart-author"><?php echo htmlspecialchars($book['auteur_livre'] ?? 'Auteur inconnu'); ?></div>
                        <div class="cart-price"><?php echo number_format($book['prix'], 2, ',', ' '); ?> €</div>
                        <form method="post" class="cart-qty-form">
                            <input type="hidden" name="update_id" value="<?php echo $book['id']; ?>">
                            <label for="qty-<?php echo $book['id']; ?>">Quantité :</label>
                            <input type="number" id="qty-<?php echo $book['id']; ?>" name="quantity" value="<?php echo $book['quantity']; ?>" min="1" style="width:60px;">
                            <button type="submit" class="qty-btn">Modifier</button>
                        </form>
                    </div>
                    <form method="post" class="cart-remove-form">
                        <input type="hidden" name="remove_id" value="<?php echo $book['id']; ?>">
                        <button type="submit" class="remove-btn" title="Retirer du panier">✕</button>
                    </form>
                </div>
            <?php endforeach; ?>
            <div class="cart-total">
                Total : <span><?php echo number_format($total, 2, ',', ' '); ?> €</span>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>