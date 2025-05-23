<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = $_POST['product_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;
    
    // In a real application, you would update the cart in the database
    // This is just a placeholder
    if ($action === 'remove') {
        // Remove item from cart
    } elseif ($action === 'update') {
        // Update item quantity
    } else {
        // Add item to cart
    }
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

include 'includes/header.php';
?>

<div class="container cart-container">
    <div class="cart-header">
        <h1 class="cart-title">Mon panier</h1>
        <span class="cart-count"><?php echo count($cart_items); ?> article(s)</span>
    </div>
    
    <?php if (empty($cart_items)): ?>
    <div class="cart-empty">
        <i class="fas fa-shopping-cart cart-empty-icon"></i>
        <h2 class="cart-empty-title">Votre panier est vide</h2>
        <p class="cart-empty-text">Parcourez notre catalogue et ajoutez des articles à votre panier.</p>
        <a href="/" class="btn btn-primary">Continuer mes achats</a>
    </div>
    <?php else: ?>
    <div class="cart-grid">
        <div class="cart-items">
            <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" class="cart-item-image">
                <div class="cart-item-details">
                    <h3 class="cart-item-title"><?php echo $item['title']; ?></h3>
                    <p class="cart-item-price"><?php echo number_format($item['price'], 2, ',', ' '); ?> €</p>
                    <div class="cart-item-actions">
                        <form action="/cart.php" method="POST" class="cart-item-quantity">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" name="quantity" value="<?php echo $item['quantity'] - 1; ?>" class="cart-item-quantity-btn" <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>-</button>
                            <span class="cart-item-quantity-value"><?php echo $item['quantity']; ?></span>
                            <button type="submit" name="quantity" value="<?php echo $item['quantity'] + 1; ?>" class="cart-item-quantity-btn">+</button>
                        </form>
                        <form action="/cart.php" method="POST">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" class="cart-item-remove">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                <div class="cart-item-total">
                    <?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="cart-summary">
            <h2 class="cart-summary-title">Récapitulatif</h2>
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
            <a href="/cart/validate.php" class="btn btn-primary checkout-btn">Passer commande</a>
            <a href="/" class="btn btn-outline checkout-btn" style="margin-top: 0.5rem;">Continuer mes achats</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
