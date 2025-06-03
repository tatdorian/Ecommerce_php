<?php
require_once '../middlewares/auth.middleware.php';
require_once '../middlewares/account.middleware.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';


$message = '';
$error = '';

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'article_deleted':
            $message = 'L\'annonce a été supprimée avec succès !';
            break;
    }
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'missing_id':
            $error = 'Identifiant de l\'annonce manquant.';
            break;
        case 'article_not_found':
            $error = 'Annonce non trouvée ou vous n\'avez pas les droits pour la modifier.';
            break;
        case 'delete_failed':
            $error = 'Erreur lors de la suppression de l\'annonce.';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte | Bibliothèque</title>
    <link rel="stylesheet" href="../assets/css/account.css">
</head>

<body>
    <div class="account-page">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>

        <div class="site-header">
            <div class="container">
                <nav class="site-nav">
                    <?php if ($is_logged_in): ?>
                        <div class="nav-greeting">Bonjour, <?php echo htmlspecialchars($user_name); ?></div>
                        <div class="nav-links">
                            <a href="home.page.php" class="nav-link">Accueil</a>
                            <a href="sale.page.php" class="nav-link">Vendre</a>
                            <a href="cart.page.php" class="nav-link">Panier</a>
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

                <h3 class="section-subtitle">Mes informations</h3>
                <?php if ($user): ?>
                    <div class="user-info">
                        <div class="profile-picture">
                            <img src="<?php echo !empty($user['photo_profil'])
                                            ? htmlspecialchars($user['photo_profil'])
                                            : 'https://cdn-icons-png.flaticon.com/512/10337/10337609.png'; ?>"
                                alt="Photo de profil" class="profile-image">
                        </div>

                        <div class="profile-details">
                            <p><strong>Nom :</strong> <?php echo htmlspecialchars($user_name); ?></p>
                            <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            <p><strong>Solde :</strong> <?php echo number_format($user['solde'], 2, ',', ' '); ?> €</p>
                            <p><strong>Rôle :</strong> <?php echo htmlspecialchars($user['role']); ?></p>
                        </div>

                        <a href="edit-profile.page.php" class="edit-profile-btn">
                            ✏️ Modifier mes informations
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="container">
                <h3 class="section-subtitle">Mes annonces</h3>
                <?php if ($message): ?>
                    <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem;">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if (empty($articles)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📚</div>
                        <p class="empty-state-text">Vous n'avez posté aucune annonce pour le moment.</p>
                    </div>
                <?php else: ?>
                    <div class="books-grid">
                        <?php foreach ($articles as $article): ?>
                            <div class="book-card">
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

                                    <a href="advert-modification.page.php?id=<?php echo $article['id']; ?>" class="edit-article-btn">
                                        Modifier l'annonce
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="container">
                <h3 class="section-subtitle">Mes anciennes annonces</h3>
                <?php if (empty($old_articles)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📚</div>
                        <p class="empty-state-text">Aucune de vos annonces n'a aboutie ou n'a été supprimée pour le moment.</p>
                    </div>
                <?php else: ?>
                    <div class="books-grid">
                        <?php foreach ($old_articles as $article): ?>
                            <div class="book-card">
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userInfo = document.querySelector('.user-info');
            const books = document.querySelectorAll('.book-card');

            if (userInfo) {
                userInfo.style.opacity = '0';
                userInfo.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    userInfo.style.opacity = '1';
                    userInfo.style.transform = 'translateY(0)';
                }, 100);
            }

            books.forEach((book, index) => {
                book.style.opacity = '0';
                book.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    book.style.opacity = '1';
                    book.style.transform = 'translateY(0)';
                }, 200 + (index * 100));
            });
        });
    </script>
</body>

</html>