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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier | Bibliothèque</title>
    <link rel="stylesheet" href="../assets/css/cart.css">
</head>
<body>
    <div class="cart-page">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        
        <div class="cart-header">
            <div class="container">
                <div class="cart-header-content">
                    <h1 class="cart-title">Mon Panier</h1>
                    <a href="home.page.php" class="back-link">← Retour à la boutique</a>
                </div>
            </div>
        </div>

        <div class="cart-container">
            <div class="cart-content">
                <h2 class="section-title">Livres dans votre panier</h2>
                
                <div class="cart-list">
                    <?php if (empty($cart_books)): ?>
                        <div class="empty-cart">Votre panier est vide.</div>
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
                                        <input type="number" id="qty-<?php echo $book['id']; ?>" name="quantity" value="<?php echo $book['quantity']; ?>" min="1">
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
                            Total : <?php echo number_format($total, 2, ',', ' '); ?> €
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.querySelector('.cart-content');
            const items = document.querySelectorAll('.cart-item');
            
            content.style.opacity = '0';
            content.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                content.style.opacity = '1';
                content.style.transform = 'translateY(0)';
            }, 100);
            
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, 200 + (index * 100));
            });
        });
    </script>
</body>
</html>
