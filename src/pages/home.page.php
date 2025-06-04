<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';
require_once '../middlewares/home.middleware.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque | Découvrez notre collection</title>
    <link rel="stylesheet" href="../assets/css/home.css">
</head>
<body>
    <div class="site-header">
        <div class="container">
            <nav class="site-nav">
                <?php if ($is_logged_in): ?>
                    <div class="nav-greeting">Bonjour, <?php echo htmlspecialchars($user_name); ?></div>
                    <div class="nav-links">
                        <a href="account.page.php" class="nav-link">Mon compte</a>
                        <a href="sale.page.php" class="nav-link">Vendre</a>
                        <a href="cart.page.php" class="nav-link cart-link" title="Voir le panier">🛒 Panier</a>
                        <a href="login.page.php" class="nav-link">Déconnexion</a>
                    </div>
                <?php else: ?>
                    <div class="nav-greeting">Bienvenue sur notre bibliothèque</div>
                    <div class="nav-links">
                        <a href="login.page.php" class="nav-link">Connexion</a>
                        <a href="register.page.php" class="nav-link">Inscription</a>
                    </div>
                <?php endif; ?>
            </nav>
            
            <div class="header-content">
                <h1 class="header-title">Librairie en Ligne</h1>
                <p class="header-subtitle">Découvrez notre sélection de livres pour tous les passionnés de lecture</p>
            </div>
            
            <div class="header-blob blob-left"></div>
            <div class="header-blob blob-right"></div>
        </div>
    </div>
    
    <form method="GET" action="home.page.php" class="search">
        <div class="container">
            <div class="search-wrapper">
                <input type="text" name="search" placeholder="Recherchez un livre..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" />
                <button type="submit" class="search-button" title="Rechercher">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </div>
        </div>
    </form>


    <div class="main-content">
        <div class="container">
            <h2 class="section-title">Notre Collection</h2>
            
            <?php
            $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
            $filteredArticles = [];
            
            if (!empty($searchTerm)) {
                foreach ($articles as $article) {
                    $title = $article['titre_livre'] ?? $article['nom'];
                    if (stripos($title, $searchTerm) !== false) {
                        $filteredArticles[] = $article;
                    }
                }
            } else {
                $filteredArticles = $articles;
            }
            ?>
            
            <?php if (empty($filteredArticles)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📚</div>
                    <p class="empty-state-text">Aucun livre trouvé.</p>
                </div>
            <?php else: ?>
                <div class="books-grid">
                    <?php foreach ($filteredArticles as $article): ?>
                        <div class="book-card">
                            <a href="detail.page.php?id=<?php echo $article['id']; ?>" class="book-card">
                                <?php if (rand(0, 5) === 0): ?>
                                    <div class="book-badge">Nouveau</div>
                                <?php endif; ?>
                                
                                <div class="book-image-container">
                                    <?php if (!empty($article['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Couverture du livre" class="book-image">
                                    <?php else: ?>
                                        <div class="book-placeholder">📚</div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="book-content">
                                    <h3 class="book-title"><?php echo htmlspecialchars($article['titre_livre'] ?? $article['nom']); ?></h3>
                                    <p class="book-description"><?php echo nl2br(htmlspecialchars($article['description'])); ?></p>
                                    
                                    <div class="book-meta">
                                        <div class="book-price"><?php echo number_format($article['prix'], 2, ',', ' '); ?> €</div>
                                        <div class="book-date">Ajouté le <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?></div>
                                    </div>
                                </div>
                            </a>
                            <?php if (
                                (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $article['auteur_id']) ||
                                (isset($user) && $user['role'] == 'admin')
                            ) : ?>
                                <a href="advert-modification.page.php?id=<?php echo $article['id']; ?>" class="edit-article-btn">Modifier l'annonce</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('.header-content');
            const books = document.querySelectorAll('.book-card');
            const nav = document.querySelector('.site-nav');
            
            // Animation d'entrée
            header.style.opacity = '0';
            header.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                header.style.opacity = '1';
                header.style.transform = 'translateY(0)';
            }, 100);
            
            books.forEach((book, index) => {
                book.style.opacity = '0';
                book.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    book.style.opacity = '1';
                    book.style.transform = 'translateY(0)';
                }, 200 + (index * 100));
            });
            
            // Effet de scroll pour la navigation
            let lastScrollTop = 0;
            
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 50) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
                
                // Effet de parallaxe léger pour l'image de livre
                const bookBg = document.querySelector('.site-header::before');
                if (bookBg) {
                    const scrolled = window.pageYOffset;
                    const parallax = scrolled * 0.2;
                    document.querySelector('.site-header').style.transform = `translateY(${parallax}px)`;
                }
                
                lastScrollTop = scrollTop;
            });
            
            // Smooth scroll pour les liens internes
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>