<?php

require_once '../middlewares/auth.middleware.php';
require_once '../middlewares/buy.middleware.php';

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

    header('Location: buy.page.php');
    exit;
}

?>
<!DOCTYPE html> 
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Validation | Bibliothèque</title>
    <link rel="stylesheet" href="../assets/css/buy.css">
</head>
<body>
    <div class="payment-page">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        
        <div class="payment-container">
            <div class="payment-header">
                <h1 class="payment-title">Finaliser votre commande</h1>
                <p class="payment-subtitle">Vérifiez vos articles et procédez au paiement</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (empty($cart_items)): ?>
                <div class="empty-cart">
                    <h2>Votre panier est vide</h2>
                    <p>Ajoutez des articles à votre panier pour continuer vos achats.</p>
                    <a href="catalog.page.php" class="payment-button" style="display: inline-block; text-decoration: none; margin-top: 1rem;">
                        Voir le catalogue
                    </a>
                </div>
            <?php elseif ($payment_success): ?>
                <div class="success-message">
                    <h2>✅ Paiement réussi !</h2>
                    <p>Merci pour votre commande.</p>
                    <a href="account.page.php">Voir mes commandes</a>
                </div>
            <?php else: ?>
                <div class="cart-summary">
                    <h2>Récapitulatif de votre commande</h2>
                    <ul class="cart-items">
                        <?php foreach ($cart_items as $item): ?>
                            <li class="cart-item">
                                <div class="item-info">
                                    <div class="item-name"><?php echo htmlspecialchars($item['nom']); ?></div>
                                    <div class="item-quantity">Quantité : <?php echo $item['quantity']; ?></div>
                                </div>
                                <div class="item-price"><?php echo number_format($item['prix'] * $item['quantity'], 2, ',', ' '); ?> €</div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="total-price">
                        <span>Total à payer :</span>
                        <span><?php echo number_format($total_price, 2, ',', ' '); ?> €</span>
                    </div>
                </div>

                <form method="POST" action="" class="payment-form">
                    <div class="form-section">
                        <h3>💳 Informations de paiement</h3>
                        
                        <div class="form-group">
                            <input type="text" id="card_number" name="card_number" maxlength="19" placeholder=" " required>
                            <label for="card_number">Numéro de carte (16 chiffres)</label>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" id="card_expiry" name="card_expiry" maxlength="5" placeholder=" " required>
                                <label for="card_expiry">MM/AA</label>
                            </div>
                            
                            <div class="form-group">
                                <input type="text" id="card_cvv" name="card_cvv" maxlength="3" placeholder=" " required>
                                <label for="card_cvv">CVV</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>📦 Adresse de livraison</h3>
                        
                        <div class="form-group">
                            <input type="text" id="postal_address" name="postal_address" placeholder=" " required>
                            <label for="postal_address">Adresse complète</label>
                        </div>
                    </div>

                    <button type="submit" name="confirm_buy" class="payment-button">
                        Confirmer le paiement - <?php echo number_format($total_price, 2, ',', ' '); ?> €
                    </button>
                </form>
                
                <div class="back-link">
                    <a href="cart.page.php">⬅ Retour au panier</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.payment-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);

            // Formatage automatique du numéro de carte
            const cardInput = document.getElementById('card_number');
            if (cardInput) {
                cardInput.addEventListener('input', function(e) {
                    // Supprimer tous les caractères non numériques
                    let value = e.target.value.replace(/\D/g, '');
                    // Limiter à 16 chiffres
                    value = value.substring(0, 19);
                    // Ajouter des espaces tous les 4 chiffres
                    value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
                    e.target.value = value;
                });
            }

            // Formatage automatique de la date d'expiration
            const expiryInput = document.getElementById('card_expiry');
            if (expiryInput) {
                expiryInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                });
            }

            // Validation du CVV (seulement des chiffres)
            const cvvInput = document.getElementById('card_cvv');
            if (cvvInput) {
                cvvInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/\D/g, '');
                });
            }
        });
    </script>
</body>
</html>