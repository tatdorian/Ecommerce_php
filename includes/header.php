<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESSENCE - Parfums de Luxe</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
    /* Styles pour les alertes */
    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 0;
        font-weight: 500;
    }
    
    .alert-error {
        background-color: rgba(255, 59, 48, 0.1);
        color: #ff3b30;
        border-left: 3px solid #ff3b30;
    }
    
    .alert-success {
        background-color: rgba(52, 199, 89, 0.1);
        color: #34c759;
        border-left: 3px solid #34c759;
    }
    </style>
</head>
<body>
    <div class="cursor"></div>
    <div class="cursor-follower"></div>
    
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <span class="logo-text">ESSENCE</span>
                </a>
                <nav class="main-nav">
                    <ul class="nav-list">
                        <li><a href="index.php" class="nav-link">Accueil</a></li>
                        <li><a href="index.php?category=homme" class="nav-link">Homme</a></li>
                        <li><a href="index.php?category=femme" class="nav-link">Femme</a></li>
                        <li><a href="index.php?category=unisexe" class="nav-link">Unisexe</a></li>
                        <li><a href="index.php?category=collection" class="nav-link">Collections</a></li>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li><a href="sell.php" class="nav-link">Vendre</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="header-actions">
                    <div class="search-container">
                        <form action="index.php" method="GET" class="search-form">
                            <input type="text" name="search" placeholder="Rechercher..." class="search-input">
                            <button type="submit" class="search-button">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="cart.php" class="cart-icon">
                            <i class="fas fa-shopping-bag"></i>
                            <?php 
                            // Récupérer le nombre d'articles dans le panier
                            $cartCount = 0;
                            if (isset($pdo)) {
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
                                $stmt->execute([$_SESSION['user_id']]);
                                $cartCount = $stmt->fetchColumn();
                            }
                            if($cartCount > 0): 
                            ?>
                            <span class="cart-count"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="user-menu">
                            <button class="user-menu-button">
                                <img src="<?php echo isset($_SESSION['user_photo_profil']) ? $_SESSION['user_photo_profil'] : 'assets/images/default-avatar.php'; ?>" alt="Avatar" class="user-avatar">
                            </button>
                            <div class="user-dropdown">
                                <a href="account.php" class="dropdown-item">Mon profil</a>
                                <a href="account.php?section=orders" class="dropdown-item">Mes commandes</a>
                                <a href="account.php?section=listings" class="dropdown-item">Mes annonces</a>
                                <a href="actions/logout.php" class="dropdown-item logout">Déconnexion</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline">Connexion</a>
                        <a href="register.php" class="btn btn-primary">Inscription</a>
                    <?php endif; ?>
                </div>
                <button class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>
    <div class="mobile-menu">
        <ul class="mobile-nav-list">
            <li><a href="index.php" class="mobile-nav-link">Accueil</a></li>
            <li><a href="index.php?category=homme" class="mobile-nav-link">Homme</a></li>
            <li><a href="index.php?category=femme" class="mobile-nav-link">Femme</a></li>
            <li><a href="index.php?category=unisexe" class="mobile-nav-link">Unisexe</a></li>
            <li><a href="index.php?category=collection" class="mobile-nav-link">Collections</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="sell.php" class="mobile-nav-link">Vendre</a></li>
                <li><a href="cart.php" class="mobile-nav-link">Panier</a></li>
                <li><a href="account.php" class="mobile-nav-link">Mon compte</a></li>
                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <li><a href="admin/index.php" class="mobile-nav-link admin-link">Admin</a></li>
                <?php endif; ?>
                <li><a href="actions/logout.php" class="mobile-nav-link logout">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="login.php" class="mobile-nav-link">Connexion</a></li>
                <li><a href="register.php" class="mobile-nav-link">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <main>
