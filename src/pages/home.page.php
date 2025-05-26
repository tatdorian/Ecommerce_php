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
    <title>Librairie en Ligne - Accueil</title>
    <link rel="stylesheet" href="../assets/css/home.css">
</head>
<body>
<header>
    <h1>Librairie en Ligne</h1>
    <p>Découvrez notre sélection de livres pour tous les passionnés de lecture</p>
</header>
<div class="container">
    <nav>
        <?php if ($is_logged_in): ?>
            Bonjour, <?php echo htmlspecialchars($user_name); ?> |
            <a href="login.page.php">Déconnexion</a>
        <?php else: ?>
            <a href="login.page.php">Connexion</a>
            <a href="register.page.php">Inscription</a>
        <?php endif; ?>
        <a href="cart.page.php" class="cart-link" title="Voir le panier">
            🛒
        </a>
    </nav>
    <h2 style="text-align:center; margin-bottom:32px;">Tous nos livres</h2>
    <div class="articles">
        <?php if (empty($articles)): ?>
            <p>Aucun livre en vente pour le moment.</p>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <a class="article-link" href="detail.page.php?id=<?php echo $article['id']; ?>">
                    <div class="article-card">
                        <?php if (!empty($article['image'])): ?>
                            <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Couverture du livre" class="article-image">
                        <?php else: ?>
                            <div class="article-image book-placeholder">📚</div>
                        <?php endif; ?>
                        <div class="article-title"><?php echo htmlspecialchars($article['titre_livre'] ?? $article['nom']); ?></div>
                        <div class="article-desc"><?php echo nl2br(htmlspecialchars($article['description'])); ?></div>
                        <div class="article-price"><?php echo number_format($article['prix'], 2, ',', ' '); ?> €</div>
                        <div class="article-date">Ajouté le <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>