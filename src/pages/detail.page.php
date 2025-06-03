<?php

require_once '../middlewares/auth.middleware.php';
require_once '../middlewares/detail.middleware.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['nom']); ?> - Détails | Bibliothèque</title>
    <link rel="stylesheet" href="../assets/css/detail.css">
</head>
<body>
    <div class="detail-page">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        
        <div class="detail-header">
            <div class="container">
                <a href="home.page.php" class="back-link">← Retour à la boutique</a>
            </div>
        </div>

        <div class="detail-container">
            <div class="detail-content">
                <div class="image-section">
                    <?php if (!empty($article['image'])): ?>
                        <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Couverture du livre" class="book-image">
                    <?php else: ?>
                        <div class="book-placeholder">📚</div>
                    <?php endif; ?>
                </div>
                
                <div class="info-section">
                    <h1 class="book-title"><?php echo htmlspecialchars($article['nom']); ?></h1>
                    
                    <?php if (!empty($article['auteur_livre'])): ?>
                        <div class="book-author">par <?php echo htmlspecialchars($article['auteur_livre']); ?></div>
                    <?php endif; ?>
                    
                    <div class="book-price"><?php echo number_format($article['prix'], 2, ',', ' '); ?> €</div>
                    
                    <div class="book-details">
                        <?php if (!empty($article['genre_livre'])): ?>
                            <div class="detail-item">
                                <div class="detail-label">Genre</div>
                                <div class="detail-value"><?php echo htmlspecialchars($article['genre_livre']); ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($article['date_livre'])): ?>
                            <div class="detail-item">
                                <div class="detail-label">Date de parution</div>
                                <div class="detail-value"><?php echo date('d/m/Y', strtotime($article['date_livre'])); ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="detail-item">
                            <div class="detail-label">Vendeur</div>
                            <div class="detail-value"><?php echo htmlspecialchars($article['auteur']); ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Ajouté le</div>
                            <div class="detail-value"><?php echo date('d/m/Y', strtotime($article['date_publication'])); ?></div>
                        </div>
                    </div>
                    
                    <?php if (!empty($article['description'])): ?>
                        <div class="book-description">
                            <div class="detail-label">Description</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($article['description'])); ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="actions">
                        <form method="post" action="cart.page.php">
                            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                            <button type="submit" class="action-button secondary">Ajouter au panier</button>
                        </form>
                        <form method="post" action="buy.page.php">
                            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                            <button type="submit" class="action-button primary">Acheter maintenant</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.querySelector('.detail-content');
            content.style.opacity = '0';
            content.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                content.style.opacity = '1';
                content.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
