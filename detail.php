<?php
session_start();
// Get product ID from URL
$product_id = $_GET['id'] ?? 0;

// In a real application, you would fetch the product from the database
// This is just placeholder data
$product = [
    'id' => $product_id,
    'title' => 'Casque audio sans fil',
    'price' => 129.99,
    'description' => 'Casque audio sans fil avec réduction de bruit active et autonomie de 30 heures. Profitez d\'un son de qualité supérieure et d\'un confort optimal pour vos longues sessions d\'écoute. Compatible avec tous les appareils Bluetooth.',
    'images' => [
        '/assets/images/placeholder.jpg',
        '/assets/images/placeholder.jpg',
        '/assets/images/placeholder.jpg',
        '/assets/images/placeholder.jpg'
    ],
    'seller' => [
        'id' => 1,
        'username' => 'tech_master',
        'avatar' => '/assets/images/avatar1.jpg',
        'rating' => 4.8,
        'sales' => 156,
        'joined' => '2022-01-15'
    ],
    'stock' => 10,
    'category' => 'Électronique',
    'condition' => 'Neuf',
    'date' => '2023-05-15'
];

include 'includes/header.php';
?>

<div class="container">
    <div class="product-detail">
        <div class="product-detail-images">
            <img src="<?php echo $product['images'][0]; ?>" alt="<?php echo $product['title']; ?>" class="product-main-image" id="main-image">
            <div class="product-thumbnails">
                <?php foreach ($product['images'] as $index => $image): ?>
                <div class="product-thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" data-image="<?php echo $image; ?>">
                    <img src="<?php echo $image; ?>" alt="<?php echo $product['title']; ?> thumbnail">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="product-detail-info">
            <h1 class="product-detail-title"><?php echo $product['title']; ?></h1>
            <p class="product-detail-price"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</p>
            <div class="product-detail-meta">
                <div class="meta-item">
                    <i class="fas fa-tag meta-icon"></i>
                    <span><?php echo $product['category']; ?></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-box meta-icon"></i>
                    <span><?php echo $product['condition']; ?></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar-alt meta-icon"></i>
                    <span>Publié le <?php echo (new DateTime($product['date']))->format('d/m/Y'); ?></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-cubes meta-icon"></i>
                    <span><?php echo $product['stock']; ?> en stock</span>
                </div>
            </div>
            <p class="product-detail-description"><?php echo $product['description']; ?></p>
            
            <?php if (isset($_SESSION['user_id'])): ?>
            <form action="/cart.php" method="POST" class="product-actions">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <div class="quantity-selector">
                    <button type="button" class="quantity-btn" id="decrease-quantity">-</button>
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="quantity-input" id="quantity">
                    <button type="button" class="quantity-btn" id="increase-quantity">+</button>
                </div>
                <button type="submit" class="btn btn-primary add-to-cart-btn">
                    <i class="fas fa-shopping-cart"></i> Ajouter au panier
                </button>
            </form>
            <?php else: ?>
            <a href="/login.php" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Connectez-vous pour acheter
            </a>
            <?php endif; ?>
            
            <div class="product-seller-info">
                <div class="seller-header">
                    <img src="<?php echo $product['seller']['avatar']; ?>" alt="<?php echo $product['seller']['username']; ?>" class="seller-avatar-lg">
                    <div>
                        <h3 class="seller-name"><?php echo $product['seller']['username']; ?></h3>
                        <div class="seller-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= floor($product['seller']['rating'])): ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif ($i - 0.5 <= $product['seller']['rating']): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <span>(<?php echo $product['seller']['rating']; ?>)</span>
                        </div>
                    </div>
                </div>
                <div class="seller-stats">
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $product['seller']['sales']; ?></div>
                        <div class="stat-label">Ventes</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?php echo (new DateTime($product['seller']['joined']))->format('m/Y'); ?></div>
                        <div class="stat-label">Membre depuis</div>
                    </div>
                </div>
                <a href="/account.php?id=<?php echo $product['seller']['id']; ?>" class="btn btn-outline view-profile-btn">Voir le profil</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple JavaScript for the product detail page
    document.addEventListener('DOMContentLoaded', function() {
        // Thumbnail gallery
        const mainImage = document.getElementById('main-image');
        const thumbnails = document.querySelectorAll('.product-thumbnail');
        
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const imageUrl = this.getAttribute('data-image');
                mainImage.src = imageUrl;
                
                // Update active state
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Quantity selector
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decrease-quantity');
        const increaseBtn = document.getElementById('increase-quantity');
        
        if (decreaseBtn && increaseBtn && quantityInput) {
            decreaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });
            
            increaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                const maxValue = parseInt(quantityInput.getAttribute('max'));
                if (currentValue < maxValue) {
                    quantityInput.value = currentValue + 1;
                }
            });
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
