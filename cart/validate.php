<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// In a real application, you would fetch the cart items from the database
// This is just placeholder data
$cart_items = [
    [
        'id' => 1,
        'product_id' => 1,
        'title' => 'Casque audio sans fil',
        'price' => 129.99,
        'quantity' => 1,
        'image' => '/assets/images/placeholder.jpg'
    ],
    [
        'id' => 2,
        'product_id' => 3,
        'title' => 'Enceinte Bluetooth portable',
        'price' => 79.99,
        'quantity' => 2,
        'image' => '/assets/images/placeholder.jpg'
    ]
];

// Calculate totals
$subtotal = 0;
$shipping = 4.99;
$total = 0;

foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$total = $subtotal + $shipping;

// Handle form submission
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // This is just a placeholder for the actual checkout logic
    // In a real application, you would validate the input, process the payment, and create an invoice
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $postal_code = $_POST['postal_code'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    
    if (empty($address) || empty($city) || empty($postal_code) || empty($payment_method)) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } else {
        // Simulate successful checkout
        $success = 'Votre commande a été validée avec succès ! Un email de confirmation vous a été envoyé.';
        // In a real application, you would:
        // 1. Create an invoice in the database
        // 2. Update the product stock
        // 3. Clear the user's cart
        // 4. Send a confirmation email
    }
}

include 'includes/header.php';
?>

<div class="container checkout-container">
    <h1 class="section-title">Finaliser la commande</h1>
    
    <?php if (!empty($error)): ?>
    <div class="alert alert-error">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?php echo $success; ?>
        <div class="text-center" style="margin-top: 2rem;">
            <a href="/" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>
    <?php else: ?>
    <div class="checkout-grid">
        <div class="checkout-form">
            <form method="POST" action="/cart/validate.php">
                <div class="checkout-section">
                    <h2 class="checkout-section-title">Adresse de livraison</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" id="address" name="address" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city" class="form-label">Ville</label>
                            <input type="text" id="city" name="city" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="postal_code" class="form-label">Code postal</label>
                            <input type="text" id="postal_code" name="postal_code" class="form-input" required>
                        </div>
                    </div>
                </div>
                
                <div class="checkout-section">
                    <h2 class="checkout-section-title">Méthode de paiement</h2>
                    <div class="payment-methods">
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="credit_card" class="payment-method-radio" checked>
                            <i class="fas fa-credit-card payment-method-icon"></i>
                            <div class="payment-method-info">
                                <div class="payment-method-name">Carte de crédit</div>
                                <div class="payment-method-description">Paiement sécurisé par carte bancaire</div>
                            </div>
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="paypal" class="payment-method-radio">
                            <i class="fab fa-paypal payment-method-icon"></i>
                            <div class="payment-method-info">
                                <div class="payment-method-name">PayPal</div>
                                <div class="payment-method-description">Paiement sécurisé via votre compte PayPal</div>
                            </div>
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="wallet" class="payment-method-radio">
                            <i class="fas fa-wallet payment-method-icon"></i>
                            <div class="payment-method-info">
                                <div class="payment-method-name">Solde du compte</div>
                                <div class="payment-method-description">Utiliser votre solde disponible: 500,00 €</div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="checkout-section">
                    <h2 class="checkout-section-title">Récapitulatif de la commande</h2>
                    <div class="cart-items">
                        <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" class="cart-item-image">
                            <div class="cart-item-details">
                                <h3 class="cart-item-title"><?php echo $item['title']; ?></h3>
                                <p class="cart-item-price"><?php echo number_format($item['price'], 2, ',', ' '); ?> € x <?php echo $item['quantity']; ?></p>
                            </div>
                            <div class="cart-item-total">
                                <?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="cart-summary" style="box-shadow: none; padding: 1rem 0;">
                        <div class="summary-row">
                            <span class="summary-label">Sous-total</span>
                            <span class="summary-value"><?php echo number_format($subtotal, 2, ',', ' '); ?> €</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Frais de livraison</span>
                            <span class="summary-value"><?php echo number_format($shipping, 2, ',', ' '); ?> €</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-row">
                            <span class="summary-label">Total</span>
                            <span class="summary-total"><?php echo number_format($total, 2, ',', ' '); ?> €</span>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Confirmer la commande</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
