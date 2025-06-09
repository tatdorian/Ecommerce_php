<?php
session_start();
require_once '../configs/db.config.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = floatval($_POST['prix']);
    $titre_livre = $_POST['titre_livre'];
    $auteur_livre = $_POST['auteur_livre'];
    $date_livre = $_POST['date_livre'];
    $genre_livre = $_POST['genre_livre'];
    $auteur_id = $_SESSION['user_id'];

    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO article (nom, description, prix, date_publication, auteur_id, image, titre_livre, auteur_livre, date_livre, genre_livre) 
                           VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $description, $prix, $auteur_id, $image_path, $titre_livre, $auteur_livre, $date_livre, $genre_livre]);

    header("Location: home.page.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre un livre en vente | Bibliothèque</title>
    <link rel="stylesheet" href="../assets/css/sale.css">
</head>
<body>
    <div class="sale-page">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        
        <div class="sale-header">
            <div class="container">
                <nav class="sale-nav">
                    <?php if ($is_logged_in): ?>
                        <div class="nav-greeting">Bonjour, <?php echo htmlspecialchars($user_name); ?></div>
                        <div class="nav-links">
                            <a href="home.page.php" class="nav-link">Accueil</a>
                            <a href="cart.page.php" class="nav-link">Panier</a>
                            <a href="login.page.php" class="nav-link">Déconnexion</a>
                        </div>
                    <?php else: ?>
                        <div class="nav-greeting">Bienvenue</div>
                        <div class="nav-links">
                            <a href="login.page.php" class="nav-link">Connexion</a>
                            <a href="register.page.php" class="nav-link">Inscription</a>
                        </div>
                    <?php endif; ?>
                </nav>
                
                <h1 class="sale-title">Vendre un Livre</h1>
                <p class="sale-subtitle">Partagez vos livres avec notre communauté de lecteurs</p>
            </div>
        </div>

        <div class="sale-container">
            <?php if ($is_logged_in): ?>
                <form action="sale.page.php" method="POST" enctype="multipart/form-data" class="sale-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom">Titre du livre</label>
                            <input type="text" name="nom" id="nom" required>
                        </div>

                        <div class="form-group">
                            <label for="auteur_livre">Auteur du livre</label>
                            <input type="text" name="auteur_livre" id="auteur_livre" required>
                        </div>

                        <div class="form-group">
                            <label for="prix">Prix (€)</label>
                            <input type="number" step="0.01" name="prix" id="prix" required>
                        </div>

                        <div class="form-group">
                            <label for="date_livre">Date de parution</label>
                            <input type="date" name="date_livre" id="date_livre" required>
                        </div>

                        <div class="form-group">
                            <label for="genre_livre">Genre</label>
                            <input type="text" name="genre_livre" id="genre_livre" required>
                        </div>

                        <div class="form-group full-width">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" required placeholder="Décrivez l'état du livre, son contenu..."></textarea>
                        </div>

                        <div class="form-group full-width">
                            <label>Image du livre</label>
                            <div class="file-input-wrapper">
                                <input type="file" name="image" id="image" accept="image/*" required>
                                <label for="image" class="file-input-label">
                                    📷 Choisir une image
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-button">Mettre en vente</button>
                </form>
            <?php else: ?>
                <div class="login-prompt">
                    <h2>Connexion requise</h2>
                    <p>Veuillez vous connecter pour mettre un livre en vente.</p>
                    <a href="login.page.php">Se connecter</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('image');
            const fileLabel = document.querySelector('.file-input-label');
            
            if (fileInput && fileLabel) {
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files.length > 0) {
                        fileLabel.textContent = `✓ ${this.files[0].name}`;
                        fileLabel.classList.add('has-file');
                    } else {
                        fileLabel.textContent = '📷 Choisir une image';
                        fileLabel.classList.remove('has-file');
                    }
                });
            }
        });
    </script>
</body>
</html>
