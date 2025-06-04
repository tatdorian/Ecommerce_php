<?php 
require_once '../middlewares/auth.middleware.php';
require_once '../middlewares/edit-profile.middleware.php'; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil</title>
    <link rel="stylesheet" href="../assets/css/edit-profile.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="account-page">
        <!-- Floating shapes -->
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        
        <div class="site-header">
            <div class="container">
                <nav class="site-nav">
                    <div class="nav-greeting">Bonjour, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Utilisateur'); ?></div>
                    <div class="nav-links">
                        <a href="account.page.php" class="nav-link">Mon compte</a>
                        <a href="home.page.php" class="nav-link">Accueil</a>
                        <a href="cart.page.php" class="nav-link">Panier</a>
                        <a href="login.page.php" class="nav-link">Déconnexion</a>
                    </div>
                </nav>
            </div>
        </div>
        
        <div class="main-content">
            <div class="container">
                <h2 class="section-title">Modifier mon profil</h2>
                
                <?php if ($success): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        Profil mis à jour avec succès !
                    </div>
                <?php elseif ($error): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" class="edit-profile-form" enctype="multipart/form-data">
                    <!-- Section Informations personnelles -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-user form-section-icon"></i>
                            Informations personnelles
                        </h3>
                        
                        <div class="form-group">
                            <label for="username">
                                <i class="fas fa-user"></i>
                                Nom d'utilisateur
                            </label>
                            <input type="text" id="username" name="username" 
                                   value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" 
                                   required placeholder="Votre nom d'utilisateur">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                Adresse email
                            </label>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" 
                                   required placeholder="votre@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="photo_profil">
                                <i class="fas fa-camera"></i>
                                Photo de profil
                            </label>
                            <input type="file" id="photo_profil" name="photo_profil" accept="image/*">
                            
                            <div class="photo-preview">
                                <?php if (!empty($user['photo_profil'])): ?>
                                    <img src="<?php echo $user['photo_profil']; ?>" alt="Photo de profil">
                                <?php else: ?>
                                    <div class="photo-preview-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="form-divider">
                    
                    <!-- Section Sécurité -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-lock form-section-icon"></i>
                            Sécurité du compte
                        </h3>
                        
                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-key"></i>
                                Mot de passe actuel
                            </label>
                            <input type="password" id="password" name="password" 
                                   placeholder="Requis pour changer le mot de passe">
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">
                                <i class="fas fa-lock"></i>
                                Nouveau mot de passe
                            </label>
                            <input type="password" id="new_password" name="new_password" 
                                   placeholder="Laisser vide pour ne pas changer">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">
                                <i class="fas fa-lock"></i>
                                Confirmer le nouveau mot de passe
                            </label>
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   placeholder="Confirmer le nouveau mot de passe">
                        </div>
                    </div>
                    
                    <button type="submit" class="edit-profile-btn">
                        <i class="fas fa-save"></i>
                        Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>