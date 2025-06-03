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
    <title>Paiement - Validation</title>
</head>
<body>
    <h1>Procéder au paiement</h1>

    <?php if (!empty($errors)): ?>
        <div style="color:red;">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (empty($cart_items)): ?>
        <p>Votre panier est vide.</p>
    <?php elseif ($payment_success): ?>
        <p style="color:green;"><strong>Paiement réussi ! Merci pour votre commande.</strong></p>
        <p><a href="account.page.php">Voir mes commandes</a></p>
    <?php else: ?>
        <h2>Récapitulatif :</h2>
        <ul>
            <?php foreach ($cart_items as $item): ?>
                <li><?php echo htmlspecialchars($item['nom']); ?> x<?php echo $item['quantity']; ?> - <?php echo number_format($item['prix'] * $item['quantity'], 2, ',', ' '); ?> €</li>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total : <?php echo number_format($total_price, 2, ',', ' '); ?> €</strong></p>

        <h2>Informations de paiement</h2>
        <form method="POST" action="">
            <label>Numéro de carte (16 chiffres) :
                <input type="text" name="card_number" maxlength="16" required>
            </label><br><br>

            <label>Date d'expiration (MM/AA) :
                <input type="text" name="card_expiry" maxlength="5" placeholder="MM/AA" required>
            </label><br><br>

            <label>CVV (3 chiffres) :
                <input type="text" name="card_cvv" maxlength="3" required>
            </label><br><br>

            <label>Adresse de livraison :
                <input type="text" name="postal_address" required>
            </label><br><br>

            <button type="submit" name="confirm_buy">Payer</button>
        </form>
        <p><a href="cart.page.php">⬅ Retour au panier</a></p>
    <?php endif; ?>
</body>
</html>