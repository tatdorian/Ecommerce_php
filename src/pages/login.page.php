<?php session_start(); $error = ''; if (!empty($_SESSION['login_error'])) { $error = $_SESSION['login_error']; unset($_SESSION['login_error']); } ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Bibliothèque</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="auth-page">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        
        <div class="auth-container">
            <div class="auth-header">
                <h1 class="auth-title">Bienvenue</h1>
                <p class="auth-subtitle">Connectez-vous pour accéder à votre bibliothèque</p>
            </div>
            
            <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
            
            <form method="post" action="../middlewares/login.middleware.php" class="auth-form">
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder=" " required>
                    <label for="email">Email</label>
                </div>
                
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label for="password">Mot de passe</label>
                </div>
                
                <button type="submit" class="auth-button">Se connecter</button>
            </form>
            
            <div class="auth-link">
                Pas encore inscrit ? <a href="register.page.php">Inscrivez-vous ici</a>
            </div>
        </div>
    </div>
    
    <script>
        // Animation des éléments au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.auth-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
