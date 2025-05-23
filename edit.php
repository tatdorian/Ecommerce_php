<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// Get product ID from URL
$product_id = $_GET['id'] ?? 0;

// In a real application, you would fetch the product from the database and check if the user is the owner
// This is just placeholder data
$product = [
    'id' => $product_id,
    'title' => 'Casque audio sans fil',
    'price' => 129.99,
    'description' => 'Casque audio sans fil avec réduction de bruit active et autonomie de 30 heures.',
    'category' => 'electronics',
    'condition' => 'new',
    'stock' => 10,
    'images' => [
        '/assets/images/placeholder.jpg',
        '/assets/images/placeholder.jpg',
        '/assets/images/placeholder.jpg',
        '/assets/images/placeholder.jpg'
    ],
    'user_id' => 1 // This should match $_SESSION['user_id'] for the user to be able to edit
];

// Check if the user is the owner of the product or an admin
$is_owner = $product['user_id'] === $_SESSION['user_id'];
$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

if (!$is_owner && !$is_admin) {
    header('Location: /');
    exit;
}

// Handle form submission
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'delete') {
        // This is just a placeholder for the actual product deletion logic
        // In a real application, you would delete the product from the database
        $success = 'L\'article a été supprimé avec succès !';
    } else {
        // This is just a placeholder for the actual product update logic
        // In a real application, you would validate the input and update the product in the database
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $description = $_POST['description'] ?? '';
        $category = $_POST['category'] ?? '';
        $condition = $_POST['condition'] ?? '';
        $stock = $_POST['stock'] ?? '';
        
        if (empty($name) || empty($price) || empty($description) || empty($category) || empty($condition) || empty($stock)) {
            $error = 'Veuillez remplir tous les champs obligatoires.';
        } else {
            // Simulate successful product update
            $success = 'L\'article a été mis à jour avec succès !';
            
            // Update the product data for display
            $product['title'] = $name;
            $product['price'] = $price;
            $product['description'] = $description;
            $product['category'] = $category;
            $product['condition'] = $condition;
            $product['stock'] = $stock;
        }
    }
}

include 'includes/header.php';
?>

<div class="container sell-container">
    <div class="sell-header">
        <h1 class="sell-title">Modifier l'article</h1>
        <p class="sell-description">Modifiez les informations de votre article ou supprimez-le.</p>
    </div>
    
    <?php if (!empty($error)): ?>
    <div class="alert alert-error">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?php echo $success; ?>
        <div class="text-center" style="margin-top: 2rem;">
            <a href="/" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>
    <?php else: ?>
    <form method="POST" action="/edit.php?id=<?php echo $product_id; ?>" enctype="multipart/form-data" class="sell-form">
        <div class="sell-form-section">
            <h2 class="sell-form-title">Informations sur l'article</h2>
            <div class="form-group">
                <label for="name" class="form-label">Nom de l'article *</label>
                <input type="text" id="name" name="name" class="form-input" value="<?php echo $product['title']; ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="price" class="form-label">Prix (€) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0.01" class="form-input" value="<?php echo $product['price']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="stock" class="form-label">Quantité en stock *</label>
                    <input type="number" id="stock" name="stock" min="1" class="form-input" value="<?php echo $product['stock']; ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Description *</label>
                <textarea id="description" name="description" class="form-textarea" required><?php echo $product['description']; ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="category" class="form-label">Catégorie *</label>
                    <select id="category" name="category" class="form-select" required>
                        <option value="">Sélectionner une catégorie</option>
                        <option value="electronics" <?php echo $product['category'] === 'electronics' ? 'selected' : ''; ?>>Électronique</option>
                        <option value="fashion" <?php echo $product['category'] === 'fashion' ? 'selected' : ''; ?>>Mode</option>
                        <option value="home" <?php echo $product['category'] === 'home' ? 'selected' : ''; ?>>Maison</option>
                        <option value="sports" <?php echo $product['category'] === 'sports' ? 'selected' : ''; ?>>Sports</option>
                        <option value="beauty" <?php echo $product['category'] === 'beauty' ? 'selected' : ''; ?>>Beauté</option>
                        <option value="books" <?php echo $product['category'] === 'books' ? 'selected' : ''; ?>>Livres</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="condition" class="form-label">État *</label>
                    <select id="condition" name="condition" class="form-select" required>
                        <option value="">Sélectionner un état</option>
                        <option value="new" <?php echo $product['condition'] === 'new' ? 'selected' : ''; ?>>Neuf</option>
                        <option value="like_new" <?php echo $product['condition'] === 'like_new' ? 'selected' : ''; ?>>Comme neuf</option>
                        <option value="good" <?php echo $product['condition'] === 'good' ? 'selected' : ''; ?>>Bon état</option>
                        <option value="fair" <?php echo $product['condition'] === 'fair' ? 'selected' : ''; ?>>État correct</option>
                        <option value="poor" <?php echo $product['condition'] === 'poor' ? 'selected' : ''; ?>>État moyen</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="sell-form-section">
            <h2 class="sell-form-title">Images</h2>
            <p class="form-description">Modifiez les images de votre article.</p>
            <div class="image-upload-container">
                <?php foreach ($product['images'] as $index => $image): ?>
                <div class="image-preview">
                    <img src="<?php echo $image; ?>" alt="Image <?php echo $index + 1; ?>">
                    <div class="image-preview-remove" data-index="<?php echo $index; ?>">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php for ($i = count($product['images']); $i < 4; $i++): ?>
                <label for="image<?php echo $i + 1; ?>" class="image-upload-box">
                    <input type="file" id="image<?php echo $i + 1; ?>" name="image<?php echo $i + 1; ?>" accept="image/*" style="display: none;">
                    <i class="fas fa-plus image-upload-icon"></i>
                    <span class="image-upload-text">Ajouter une image</span>
                </label>
                <?php endfor; ?>
            </div>
        </div>
        
        <div class="form-group" style="display: flex; gap: 1rem;">
            <button type="submit" name="action" value="update" class="btn btn-primary" style="flex: 1;">Mettre à jour</button>
            <button type="submit" name="action" value="delete" class="btn btn-danger" style="flex: 1;" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<script>
    // Simple JavaScript for image preview
    document.addEventListener('DOMContentLoaded', function() {
        const imageInputs = document.querySelectorAll('input[type="file"]');
        const removeButtons = document.querySelectorAll('.image-preview-remove');
        
        imageInputs.forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    const uploadBox = this.parentElement;
                    
                    reader.onload = function(e) {
                        // Create preview
                        uploadBox.innerHTML = '';
                        uploadBox.style.padding = '0';
                        uploadBox.className = 'image-preview';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        
                        const removeBtn = document.createElement('div');
                        removeBtn.className = 'image-preview-remove';
                        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                        
                        uploadBox.appendChild(img);
                        uploadBox.appendChild(removeBtn);
                        uploadBox.appendChild(input);
                        
                        // Add remove functionality
                        removeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Reset the input
                            input.value = '';
                            
                            // Reset the upload box
                            uploadBox.innerHTML = '';
                            uploadBox.style.padding = '';
                            uploadBox.className = 'image-upload-box';
                            
                            const icon = document.createElement('i');
                            icon.className = 'fas fa-plus image-upload-icon';
                            
                            const text = document.createElement('span');
                            text.className = 'image-upload-text';
                            text.textContent = 'Ajouter une image';
                            
                            uploadBox.appendChild(input);
                            uploadBox.appendChild(icon);
                            uploadBox.appendChild(text);
                        });
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        });
        
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                // In a real application, you would send an AJAX request to delete the image
                // For now, we'll just hide the preview
                this.parentElement.style.display = 'none';
            });
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
