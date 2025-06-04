<?php
require_once '../middlewares/auth.middleware.php';
require_once '../configs/db.config.php';
require_once '../middlewares/cart.middleware.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.page.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$is_logged_in = true; // AJOUT : Variable manquante

// Gérer ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id'])) {
    $article_id = intval($_POST['article_id']);

    // Vérifier si le produit est déjà dans le panier
    $stmt = $pdo->prepare("SELECT quantity FROM cart WHERE user_id = ? AND article_id = ?");
    $stmt->execute([$user_id, $article_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Incrémenter la quantité
        $new_quantity = $existing['quantity'] + 1;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND article_id = ?");
        $stmt->execute([$new_quantity, $user_id, $article_id]);
    } else {
        // Ajouter le produit au panier avec quantité 1
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, article_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$user_id, $article_id]);
    }

    header('Location: cart.page.php');
    exit;
}

// AJOUT : Gérer la modification de quantité
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $update_id = intval($_POST['update_id']);
    $new_quantity = intval($_POST['quantity']);
    
    if ($new_quantity > 0) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND article_id = ?");
        $stmt->execute([$new_quantity, $user_id, $update_id]);
    }
    
    header('Location: cart.page.php');
    exit;
}

// AJOUT : Gérer la suppression du panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $remove_id = intval($_POST['remove_id']);
    
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND article_id = ?");
    $stmt->execute([$user_id, $remove_id]);
    
    header('Location: cart.page.php');
    exit;
}
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
                <nav class="user-nav">
                    <?php if ($is_logged_in): ?>
                        <p class="user-greeting">Bonjour, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Utilisateur'; ?></p>
                    <?php endif; ?>
                </nav>
                
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
                                    <div class="cart-subtotal">Sous-total: <?php echo number_format($book['prix'] * $book['quantity'], 2, ',', ' '); ?> €</div>
                                    
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
                            <a href="buy.page.php" class="back-link">Total : <?php echo number_format($total, 2, ',', ' '); ?> €</a>
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
