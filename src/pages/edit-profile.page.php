
<?php require_once '../middlewares/edit-profile.middleware.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil</title>
    <link rel="stylesheet" href="../assets/css/account.css">
</head>
<body>
    <div class="account-page">
        <div class="site-header">
            <div class="container">
                <nav class="site-nav">
                    <div class="nav-greeting">Bonjour, <?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
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
                    <div class="success-message">Profil mis à jour avec succès !</div>
                <?php elseif ($error): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="post" class="edit-profile-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">Nom :</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse email :</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="photo_profil">Photo de profil :</label>
                        <input type="file" id="photo_profil" name="photo_profil" accept="image/*">
                        <?php if (!empty($user['photo_profil'])): ?>
                            <div>
                                <img src="<?php echo $user['photo_profil']; ?>" alt="Photo de profil" style="max-width:80px;max-height:80px;border-radius:50%;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="password">Mot de passe actuel :</label>
                        <input type="password" id="password" name="password" placeholder="Obligatoire pour changer le mot de passe">
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nouveau mot de passe :</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Laisser vide pour ne pas changer">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmer le nouveau mot de passe :</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Laisser vide pour ne pas changer">
                    </div>
                    <button type="submit" class="edit-profile-btn">Enregistrer les modifications</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>