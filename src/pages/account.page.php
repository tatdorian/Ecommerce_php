<?php 
session_start(); 
$is_logged_in = isset($_SESSION['user_id']); 
$user_name = $is_logged_in ? $_SESSION['user_name'] : ''; 
require_once '../middlewares/account.middleware.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User account</title>
    <link rel="stylesheet" href="../assets/css/home.css">
</head>
<body>
    <div class="site-header">
        <div class="container">
            <nav class="site-nav">
                <?php if ($is_logged_in): ?>
                    <div class="nav-greeting">Bonjour, <?php echo htmlspecialchars($user_name); ?></div>
                    <div class="nav-links">
                        <a href="#" class="nav-link">Mon compte</a>
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
        </div>
    </div>

    <div class="main-content">
        <div class="container">
            <h2 class="section-title"><?php echo ucfirst(htmlspecialchars($user_name)); ?></h2>
            <h3> Mes informations </h3>
            <!-- user informations -->
            <?php if ($user): ?>
                <div class="user-info" >
                    <div class="profile-picture">
                        <img src="<?php echo !empty($user['photo_profil']) 
                            ? htmlspecialchars($user['photo_profil']) 
                            : 'https://cdn-icons-png.flaticon.com/512/10337/10337609.png'; ?>" 
                            alt="Photo de profil" class="profile-image" width="100" height="100">
                    </div>

                    <div class="profile-details">
                        <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['nom']); ?></p>
                        <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><strong>Solde :</strong> <?php echo number_format($user['solde'], 2, ',', ' '); ?> €</p>
                        <p><strong>Rôle :</strong> <?php echo htmlspecialchars($user['role']); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="container">
            <h3> Mes articles </h3>
            <!-- user articles -->
            <?php if (empty($articles)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📚</div>
                    <p class="empty-state-text">Aucun livre en vente pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="books-grid">
                    <?php foreach ($articles as $article): ?>
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
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>