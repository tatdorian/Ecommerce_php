<?php
session_start();
require_once 'includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-image"></div>
    <div class="auth-form-container">
        <div class="auth-form">
            <h1 class="auth-title">Créer un compte</h1>
            <p class="auth-subtitle">Rejoignez Nexus et commencez à acheter et vendre</p>
            
            <?php if (isset($_SESSION['register_error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="actions/register_process.php">
                <div class="form-group">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
                </div>
            </form>
            
            <div class="auth-divider">
                <span class="auth-divider-text">ou</span>
            </div>
            
            <div class="social-auth">
                <button class="social-auth-btn">
                    <i class="fab fa-google social-auth-icon"></i>
                    Google
                </button>
                <button class="social-auth-btn">
                    <i class="fab fa-facebook-f social-auth-icon"></i>
                    Facebook
                </button>
            </div>
            
            <div class="auth-footer">
                Vous avez déjà un compte ? <a href="login.php" class="auth-link">Se connecter</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
