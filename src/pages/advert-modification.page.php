<?php
require_once '../middlewares/auth.middleware.php';
require_once '../configs/db.config.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.page.php');
    exit;
}

// Récupération du rôle de l'utilisateur
$stmt = $pdo->prepare("SELECT role FROM user WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
$user_role = $user['role'] ?? 'client';

$article_id = $_GET['id'] ?? null;
$message = '';
$error = '';

if (!$article_id) {
    header('Location: account.page.php');
    exit;
}

// Récupérer l'article selon le rôle
if ($user_role === 'admin') {
    $stmt = $pdo->prepare("SELECT * FROM article WHERE id = :id");
    $stmt->execute(['id' => $article_id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM article WHERE id = :id AND auteur_id = :user_id");
    $stmt->execute(['id' => $article_id, 'user_id' => $user_id]);
}
$article = $stmt->fetch();

if (!$article) {
    header('Location: account.page.php');
    exit;
}

if ($_POST) {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = floatval($_POST['prix'] ?? 0);
    $image = $article['image'];

    if (empty($nom)) {
        $error = "Le nom du livre est obligatoire.";
    } elseif (empty($description)) {
        $error = "La description est obligatoire.";
    } elseif ($prix <= 0) {
        $error = "Le prix doit être supérieur à 0.";
    } else {
        try {
            if (!empty($_FILES['newImageFile']['name'])) {
                $allowedExtensions = ['jpeg', 'jpg', 'gif', 'png', 'webp'];
                $fileName = $_FILES['newImageFile']['name'];
                $fileTmp = $_FILES['newImageFile']['tmp_name'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (in_array($fileExt, $allowedExtensions)) {
                    $uploadPath = '../uploads/' . uniqid() . '.' . $fileExt;
                    move_uploaded_file($fileTmp, $uploadPath);
                    $image = $uploadPath;
                }
            }

            // Mise à jour selon le rôle
            if ($user_role === 'admin') {
                $stmt = $pdo->prepare("
                    UPDATE article 
                    SET nom = :nom, description = :description, prix = :prix, image = :image
                    WHERE id = :id
                ");
                $params = [
                    'nom' => $nom,
                    'description' => $description,
                    'prix' => $prix,
                    'image' => $image,
                    'id' => $article_id
                ];
            } else {
                $stmt = $pdo->prepare("
                    UPDATE article 
                    SET nom = :nom, description = :description, prix = :prix, image = :image
                    WHERE id = :id AND auteur_id = :user_id
                ");
                $params = [
                    'nom' => $nom,
                    'description' => $description,
                    'prix' => $prix,
                    'image' => $image,
                    'id' => $article_id,
                    'user_id' => $user_id
                ];
            }

            $result = $stmt->execute($params);

            if ($result) {
                $message = "L'annonce a été modifiée avec succès !";
                if ($user_role === 'admin') {
                    $stmt = $pdo->prepare("SELECT * FROM article WHERE id = :id");
                    $stmt->execute(['id' => $article_id]);
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM article WHERE id = :id AND auteur_id = :user_id");
                    $stmt->execute(['id' => $article_id, 'user_id' => $user_id]);
                }
                $article = $stmt->fetch();
            } else {
                $error = "Erreur lors de la modification de l'annonce.";
            }
        } catch (PDOException $e) {
            $error = "Erreur de base de données : " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'annonce | Bibliothèque</title>
    <link rel="stylesheet" href="../assets/css/account.css">
    <style>
        .edit-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 12px;
            margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .image-options {
            margin-top: 0.5rem;
        }

        .option-buttons {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #3498db;
            color: #3498db;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-outline:hover,
        .btn-outline.active {
            background: #3498db;
            color: white;
        }

        .image-input-option {
            margin-bottom: 1rem;
        }

        .file-input {
            padding: 8px;
            border: 2px dashed #3498db;
            background: #f8f9fa;
        }

        .file-info {
            font-size: 12px;
            color: #666;
            margin-top: 0.5rem;
            margin-bottom: 0;
        }

        .image-preview-container {
            position: relative;
            display: inline-block;
            margin-top: 1rem;
        }

        .btn-remove-image {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-remove-image:hover {
            background: #c0392b;
        }
    </style>
</head>

<body>
    <div class="account-page">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>

        <div class="site-header">
            <div class="container">
                <nav class="site-nav">
                    <div class="nav-greeting">Bonjour, <?php echo htmlspecialchars($user_name); ?></div>
                    <div class="nav-links">
                        <a href="home.page.php" class="nav-link">Accueil</a>
                        <a href="sale.page.php" class="nav-link">Vendre</a>
                        <a href="cart.page.php" class="nav-link">Panier</a>
                        <a href="account.page.php" class="nav-link">Mon Compte</a>
                        <a href="login.page.php" class="nav-link">Déconnexion</a>
                    </div>
                </nav>
            </div>
        </div>

        <div class="main-content">
            <div class="container">
                <h2 class="section-title">Modifier l'annonce</h2>

                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="edit-form">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nom" class="form-label">Nom du livre *</label>
                            <input type="text" id="nom" name="nom" class="form-input"
                                value="<?php echo htmlspecialchars($article['nom']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Description *</label>
                            <textarea id="description" name="description" class="form-input form-textarea"
                                required><?php echo htmlspecialchars($article['description']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="prix" class="form-label">Prix (€) *</label>
                            <input type="number" id="prix" name="prix" class="form-input"
                                step="0.01" min="0.01"
                                value="<?php echo htmlspecialchars($article['prix']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="newImageFile" class="form-label">Nouvelle image (optionnel)</label>
                            <input type="file" id="newImageFile" name="newImageFile" class="form-input file-input" accept="image/*">
                            <p class="file-info">Extensions autorisées: jpeg, jpg, gif, png, webp.</p>
                        </div>

                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                💾 Enregistrer les modifications
                            </button>
                            <a href="account.page.php" class="btn btn-secondary">
                                ↩️ Retour au compte
                            </a>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                🗑️ Supprimer l'annonce
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('image').addEventListener('input', function() {
            const imageUrl = this.value;
            const preview = document.getElementById('preview');

            if (imageUrl && imageUrl.match(/\.(jpeg|jpg|gif|png|webp)$/i)) {
                if (!preview) {
                    const img = document.createElement('img');
                    img.id = 'preview';
                    img.className = 'preview-image';
                    img.alt = 'Aperçu';
                    this.parentNode.appendChild(img);
                }
                document.getElementById('preview').src = imageUrl;
            } else if (preview) {
                preview.remove();
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.edit-form');
            form.style.opacity = '0';
            form.style.transform = 'translateY(20px)';

            setTimeout(() => {
                form.style.opacity = '1';
                form.style.transform = 'translateY(0)';
            }, 100);
        });


        function confirmDelete() {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette annonce ? Cette action est irréversible.')) {
                window.location.href = 'delete-article.php?id=<?php echo $article_id; ?>';
            }
        }
    </script>
</body>

</html>