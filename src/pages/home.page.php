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
    <title>Parfums de Luxe - Accueil</title>
    <link rel="stylesheet" href="../assets/css/home.css">
</head>
<body>
<header>
    <h1>Parfums de Luxe</h1>
    <p>Découvrez notre sélection exclusive de parfums haut de gamme</p>
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
    </nav>
    <h2 style="text-align:center; margin-bottom:32px;">Tous nos parfums</h2>
    <div class="articles">
        <?php if (empty($articles)): ?>
            <p>Aucun parfum en vente pour le moment.</p>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <div class="article-card">
                    <?php if (!empty($article['image'])): ?>
                        <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Parfum" class="article-image">
                    <?php else: ?>
                        <div class="article-image"></div>
                    <?php endif; ?>
                    <div class="article-title"><?php echo htmlspecialchars($article['nom']); ?></div>
                    <div class="article-desc"><?php echo nl2br(htmlspecialchars($article['description'])); ?></div>
                    <div class="article-price"><?php echo number_format($article['prix'], 2, ',', ' '); ?> €</div>
                    <div class="article-date">Publié le <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>