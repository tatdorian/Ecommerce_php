<?php
session_start();
require_once 'includes/config.php';

// Récupérer les produits depuis la base de données
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$query = "SELECT a.*, u.username as seller, u.photo_profil as seller_avatar 
          FROM article a 
          JOIN user u ON a.auteur_id = u.id 
          WHERE 1=1";

$params = [];

if (!empty($search)) {
    $query .= " AND (a.nom LIKE ? OR a.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $query .= " AND a.categorie = ?";
    $params[] = $category;
}

$query .= " ORDER BY a.date_publication DESC LIMIT 12";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message et continuer avec un tableau vide
    echo "<!-- Erreur SQL: " . $e->getMessage() . " -->";
    $products = [];
}

include 'includes/header.php';
?>

<section class="hero">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-subtitle">Collection exclusive</div>
            <h1 class="hero-title">L'art du parfum de luxe</h1>
            <p class="hero-description">Découvrez notre collection de parfums d'exception, soigneusement sélectionnés pour les connaisseurs et les passionnés.</p>
            <div class="hero-buttons">
                <a href="#products" class="btn btn-primary">Découvrir</a>
                <a href="about.php" class="btn btn-outline">Notre histoire</a>
            </div>
        </div>
    </div>
    <div class="featured-product">
        <div class="featured-product-anim"></div>
        <img src="assets/images/test.jpg" alt="Parfum exclusif">
    </div>
    <div class="scroll-indicator">
        <span>Découvrir</span>
        <div class="mouse"></div>
    </div>
</section>

<section class="categories">
    <div class="container">
        <div class="section-title">
            <h2>Nos collections</h2>
            <p>Explorez nos collections soigneusement sélectionnées pour tous les goûts et toutes les occasions.</p>
        </div>
        <div class="categories-grid">
            <a href="index.php?category=homme" class="category-card">
                <img src="assets/images/category-men.jpg" alt="Parfums Homme">
                <div class="category-content">
                    <h3 class="category-name">Homme</h3>
                    <span class="category-link">Découvrir</span>
                </div>
            </a>
            <a href="index.php?category=femme" class="category-card">
                <img src="assets/images/category-women.jpg" alt="Parfums Femme">
                <div class="category-content">
                    <h3 class="category-name">Femme</h3>
                    <span class="category-link">Découvrir</span>
                </div>
            </a>
            <a href="index.php?category=unisexe" class="category-card">
                <img src="assets/images/category-unisex.jpg" alt="Parfums Unisexe">
                <div class="category-content">
                    <h3 class="category-name">Unisexe</h3>
                    <span class="category-link">Découvrir</span>
                </div>
            </a>
            <a href="index.php?category=collection" class="category-card">
                <img src="assets/images/category-collection.jpg" alt="Collections">
                <div class="category-content">
                    <h3 class="category-name">Éditions limitées</h3>
                    <span class="category-link">Découvrir</span>
                </div>
            </a>
        </div>
    </div>
</section>

<section class="products" id="products">
    <div class="container">
        <div class="section-title">
            <h2>Parfums en vedette</h2>
            <p>Découvrez notre sélection de parfums de luxe, des créations uniques pour des personnalités d'exception.</p>
        </div>
        
        <?php if (!empty($search)): ?>
            <p class="search-results">Résultats pour: "<?php echo htmlspecialchars($search); ?>"</p>
        <?php endif; ?>
        
        <div class="products-grid">
            <?php if (empty($products)): ?>
                <div class="no-products">
                    <p>Aucun produit trouvé.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): 
                    $date = new DateTime($product['date_publication']);
                    $formattedDate = $date->format('d/m/Y');
                ?>
                <div class="product-card animate">
                    <div class="product-image-container">
                        <img src="<?php echo !empty($product['image']) ? $product['image'] : 'assets/images/placeholder.php'; ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" class="product-image">
                        <div class="product-actions">
                            <a href="detail.php?id=<?php echo $product['id']; ?>" class="product-action">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="product-action">
                                <i class="fas fa-shopping-bag"></i>
                            </a>
                            <a href="#" class="product-action">
                                <i class="fas fa-heart"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="product-content">
                        <div class="product-category"><?php echo !empty($product['categorie']) ? htmlspecialchars($product['categorie']) : 'Parfum'; ?></div>
                        <h3 class="product-title"><?php echo htmlspecialchars($product['nom']); ?></h3>
                        <div class="product-price"><?php echo number_format($product['prix'], 2, ',', ' '); ?> €</div>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php if (count($products) >= 12): ?>
        <div class="load-more">
            <a href="index.php?page=2<?php echo !empty($search) ? '&search='.urlencode($search) : ''; ?><?php echo !empty($category) ? '&category='.urlencode($category) : ''; ?>" class="btn btn-outline">Voir plus</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="featured-section">
    <div class="featured-bg"></div>
    <div class="container">
        <div class="featured-content">
            <div class="featured-text animate">
                <div class="featured-subtitle">Édition limitée</div>
                <h2 class="featured-title">L'Essence du Luxe</h2>
                <p class="featured-description">Un parfum d'exception, créé par les plus grands maîtres parfumeurs. Notes de tête d'agrumes, cœur de jasmin et fond de bois précieux pour une expérience olfactive inoubliable.</p>
                <a href="detail.php?id=special" class="btn btn-primary">Découvrir</a>
            </div>
            <div class="featured-image animate">
                <img src="assets/images/featured-perfume.png" alt="L'Essence du Luxe">
                <div class="featured-badge">Édition<br>Limitée</div>
            </div>
        </div>
    </div>
</section>

<section class="testimonials">
    <div class="container">
        <div class="section-title">
            <h2>Ce que disent nos clients</h2>
            <p>Découvrez les témoignages de nos clients satisfaits et leur expérience avec nos parfums de luxe.</p>
        </div>
        <div class="testimonials-slider">
            <div class="testimonial" style="display: block;">
                <div class="testimonial-content">
                    J'ai découvert ma signature olfactive grâce à ESSENCE. La qualité est incomparable et le service client exceptionnel. Je ne me fournis plus qu'ici pour mes parfums.
                </div>
                <div class="testimonial-author">
                    <img src="assets/images/testimonial-1.jpg" alt="Sophie Marceau" class="testimonial-avatar">
                    <div class="testimonial-name">Sophie Marceau</div>
                    <div class="testimonial-role">Cliente fidèle</div>
                </div>
            </div>
            <div class="testimonial" style="display: none;">
                <div class="testimonial-content">
                    En tant que collectionneur de parfums rares, je suis impressionné par la sélection proposée par ESSENCE. Des fragrances introuvables ailleurs et une authenticité garantie.
                </div>
                <div class="testimonial-author">
                    <img src="assets/images/testimonial-2.jpg" alt="Jean Dujardin" class="testimonial-avatar">
                    <div class="testimonial-name">Jean Dujardin</div>
                    <div class="testimonial-role">Collectionneur</div>
                </div>
            </div>
            <div class="testimonial" style="display: none;">
                <div class="testimonial-content">
                    L'emballage luxueux, la livraison rapide et le parfum... tout était parfait ! Un cadeau qui a fait sensation. Je recommande vivement.
                </div>
                <div class="testimonial-author">
                    <img src="assets/images/testimonial-3.jpg" alt="Marie Laurent" class="testimonial-avatar">
                    <div class="testimonial-name">Marie Laurent</div>
                    <div class="testimonial-role">Nouvelle cliente</div>
                </div>
            </div>
            <div class="testimonial-dots">
                <div class="testimonial-dot active"></div>
                <div class="testimonial-dot"></div>
                <div class="testimonial-dot"></div>
            </div>
        </div>
    </div>
</section>

<section class="brands">
    <div class="container">
        <div class="brands-grid">
            <div class="brand-item">
                <img src="assets/images/brand-1.png" alt="Marque de parfum">
            </div>
            <div class="brand-item">
                <img src="assets/images/brand-2.png" alt="Marque de parfum">
            </div>
            <div class="brand-item">
                <img src="assets/images/brand-3.png" alt="Marque de parfum">
            </div>
            <div class="brand-item">
                <img src="assets/images/brand-4.png" alt="Marque de parfum">
            </div>
            <div class="brand-item">
                <img src="assets/images/brand-5.png" alt="Marque de parfum">
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
