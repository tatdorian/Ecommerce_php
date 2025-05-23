<?php
session_start();
require_once 'includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        // Vérifier les identifiants
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_photo_profil'] = $user['photo_profil'] ?? 'assets/images/default-avatar.png';
            $_SESSION['user_solde'] = $user['solde'];
            
            header('Location: index.php');
            exit;
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    }
}

include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-image"></div>
    <div class="auth-form-container">
        <div class="auth-form">
            <h1 class="auth-title">Connexion</h1>
            <p class="auth-subtitle">Bienvenue sur Nexus, votre marketplace de confiance</p>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
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
                Vous n'avez pas de compte ? <a href="register.php" class="auth-link">S'inscrire</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
