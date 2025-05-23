<?php
session_start();
require_once 'includes/config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user ID from URL or use the logged-in user's ID
$user_id = $_GET['id'] ?? $_SESSION['user_id'];
$is_own_account = $user_id == $_SESSION['user_id'];

// Fetch user data from database
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: index.php');
    exit;
}

// Get the active section
$section = $_GET['section'] ?? 'profile';

// Handle form submissions
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        // Update profile information
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM user WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$username, $email, $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            $error = "Ce nom d'utilisateur ou cet email est déjà utilisé.";
        } else {
            // Handle profile picture upload
            $photo_profil = $user['photo_profil'];
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/profiles/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_name = time() . '_' . $_FILES['profile_pic']['name'];
                $upload_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_path)) {
                    $photo_profil = $upload_path;
                }
            }
            
            // Update user in database
            $stmt = $pdo->prepare("UPDATE user SET username = ?, email = ?, photo_profil = ? WHERE id = ?");
            if ($stmt->execute([$username, $email, $photo_profil, $_SESSION['user_id']])) {
                // Update session variables
                $_SESSION['username'] = $username;
                $_SESSION['user_photo_profil'] = $photo_profil;
                
                $success = "Votre profil a été mis à jour avec succès !";
                
                // Refresh user data
                $stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch();
            } else {
                $error = "Une erreur est survenue lors de la mise à jour de votre profil.";
            }
        }
    } elseif ($action === 'update_password') {
        // Update password
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (!password_verify($current_password, $user['password'])) {
            $error = "Le mot de passe actuel est incorrect.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Les nouveaux mots de passe ne correspondent pas.";
        } else {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE user SET password = ? WHERE id = ?");
            if ($stmt->execute([$password_hash, $_SESSION['user_id']])) {
                $success = "Votre mot de passe a été mis à jour avec succès !";
            } else {
                $error = "Une erreur est survenue lors de la mise à jour de votre mot de passe.";
            }
        }
    } elseif ($action === 'add_funds') {
        // Add funds to user account
        $amount = floatval($_POST['amount']);
        
        if ($amount <= 0) {
            $error = "Le montant doit être supérieur à 0.";
        } else {
            $new_solde = $user['solde'] + $amount;
            $stmt = $pdo->prepare("UPDATE user SET solde = ? WHERE id = ?");
            if ($stmt->execute([$new_solde, $_SESSION['user_id']])) {
                $_SESSION['user_solde'] = $new_solde;
                $success = "Votre solde a été augmenté de " . number_format($amount, 2, ',', ' ') . " € avec succès !";
                
                // Refresh user data
                $stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch();
            } else {
                $error = "Une erreur est survenue lors de l'ajout de fonds à votre compte.";
            }
        }
    }
}

// Fetch user's listings
$stmt = $pdo->prepare("SELECT * FROM article WHERE auteur_id = ? ORDER BY date_publication DESC");
$stmt->execute([$user_id]);
$listings = $stmt->fetchAll();

// Fetch user's orders (invoices)
if ($is_own_account) {
    $stmt = $pdo->prepare("SELECT * FROM invoice WHERE user_id = ? ORDER BY date_transaction DESC");
    $stmt->execute([$user_id]);
    $invoices = $stmt->fetchAll();
    
    // Get order details for each invoice
    $orders = [];
    foreach ($invoices as $invoice) {
        // In a real application, you would have an order_items table to link invoices to articles
        // For now, we'll just create a placeholder
        $orders[] = [
            'id' => $invoice['id'],
            'date' => $invoice['date_transaction'],
            'total' => $invoice['montant'],
            'status' => 'completed',
            'items' => [] // This would be populated from the database
        ];
    }
}

include 'includes/header.php';
?>

<div class="container account-container">
    <div class="account-grid">
        <div class="account-sidebar">
            <div class="account-user">
                <img src="<?php echo !empty($user['photo_profil']) ? $user['photo_profil'] : 'assets/images/default-avatar.png'; ?>" alt="<?php echo htmlspecialchars($user['username']); ?>" class="account-avatar">
                <h2 class="account-name"><?php echo htmlspecialchars($user['username']); ?></h2>
                <?php if ($is_own_account): ?>
                <p class="account-email"><?php echo htmlspecialchars($user['email']); ?></p>
                <div class="account-balance">Solde: <?php echo number_format($user['solde'], 2, ',', ' '); ?> €</div>
                <?php endif; ?>
            </div>
            
            <?php if ($is_own_account): ?>
            <nav class="account-nav">
                <a href="account.php?section=profile" class="account-nav-link <?php echo $section === 'profile' ? 'active' : ''; ?>">
                    <i class="fas fa-user account-nav-icon"></i>
                    <span>Profil</span>
                </a>
                <a href="account.php?section=listings" class="account-nav-link <?php echo $section === 'listings' ? 'active' : ''; ?>">
                    <i class="fas fa-box account-nav-icon"></i>
                    <span>Mes annonces</span>
                </a>
                <a href="account.php?section=orders" class="account-nav-link <?php echo $section === 'orders' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-bag account-nav-icon"></i>
                    <span>Mes commandes</span>
                </a>
                <a href="account.php?section=security" class="account-nav-link <?php echo $section === 'security' ? 'active' : ''; ?>">
                    <i class="fas fa-lock account-nav-icon"></i>
                    <span>Sécurité</span>
                </a>
                <a href="account.php?section=wallet" class="account-nav-link <?php echo $section === 'wallet' ? 'active' : ''; ?>">
                    <i class="fas fa-wallet account-nav-icon"></i>
                    <span>Portefeuille</span>
                </a>
            </nav>
            <?php endif; ?>
        </div>
        
        <div class="account-content">
            <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
            <?php endif; ?>
            
            <?php if ($is_own_account && $section === 'profile'): ?>
            <div class="account-section">
                <div class="account-section-header">
                    <h2 class="account-section-title">Informations personnelles</h2>
                </div>
                <form method="POST" action="account.php?section=profile" class="profile-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="profile-avatar-upload">
                        <img src="<?php echo !empty($user['photo_profil']) ? $user['photo_profil'] : 'assets/images/default-avatar.png'; ?>" alt="<?php echo htmlspecialchars($user['username']); ?>" class="avatar-preview" id="profile_pic-preview">
                        <div class="avatar-upload-btn">
                            <label for="profile_pic" class="btn btn-outline">Changer l'avatar</label>
                            <input type="file" id="profile_pic" name="profile_pic" accept="image/*" style="display: none;">
                            <span class="form-hint">JPG, PNG ou GIF. 1MB max.</span>
                        </div>
                    </div>
                    <div class="profile-form-row">
                        <div class="form-group">
                            <label for="username" class="form-label">Nom d'utilisateur</label>
                            <input type="text" id="username" name="username" class="form-input" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
            
            <?php elseif ($is_own_account && $section === 'security'): ?>
            <div class="account-section">
                <div class="account-section-header">
                    <h2 class="account-section-title">Changer le mot de passe</h2>
                </div>
                <form method="POST" action="account.php?section=security" class="profile-form">
                    <input type="hidden" name="action" value="update_password">
                    <div class="form-group">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" required>
                    </div>
                    <div class="profile-form-row">
                        <div class="form-group">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" id="new_password" name="new_password" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Mettre à jour le mot de passe</button>
                    </div>
                </form>
            </div>
            
            <?php elseif ($is_own_account && $section === 'wallet'): ?>
            <div class="account-section">
                <div class="account-section-header">
                    <h2 class="account-section-title">Mon portefeuille</h2>
                </div>
                <div class="wallet-info">
                    <div class="wallet-balance">
                        <h3>Solde actuel</h3>
                        <div class="balance-amount"><?php echo number_format($user['solde'], 2, ',', ' '); ?> €</div>
                    </div>
                    <div class="wallet-actions">
                        <h3>Ajouter des fonds</h3>
                        <form method="POST" action="account.php?section=wallet" class="add-funds-form">
                            <input type="hidden" name="action" value="add_funds">
                            <div class="form-group">
                                <label for="amount" class="form-label">Montant (€)</label>
                                <input type="number" id="amount" name="amount" min="1" step="0.01" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Ajouter des fonds</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php elseif ($section === 'listings' || !$is_own_account): ?>
            <div class="account-section">
                <div class="account-section-header">
                    <h2 class="account-section-title">
                        <?php echo $is_own_account ? 'Mes annonces' : 'Annonces de ' . htmlspecialchars($user['username']); ?>
                    </h2>
                    <?php if ($is_own_account): ?>
                    <a href="sell.php" class="account-section-action">Ajouter une annonce</a>
                    <?php endif; ?>
                </div>
                
                <?php if (empty($listings)): ?>
                <div class="no-listings">
                    <p>Aucune annonce trouvée.</p>
                </div>
                <?php else: ?>
                <div class="listings-list">
                    <?php foreach ($listings as $listing): 
                        $date = new DateTime($listing['date_publication']);
                        $formattedDate = $date->format('d/m/Y');
                    ?>
                    <div class="listing-card">
                        <div class="listing-header">
                            <div class="listing-id">Annonce #<?php echo $listing['id']; ?></div>
                            <div class="listing-date"><?php echo $formattedDate; ?></div>
                        </div>
                        <div class="listing-details">
                            <div class="listing-detail">
                                <img src="<?php echo !empty($listing['image']) ? $listing['image'] : 'assets/images/placeholder.png'; ?>" alt="<?php echo htmlspecialchars($listing['nom']); ?>" class="listing-image">
                                <div class="listing-info">
                                    <h3 class="listing-name"><?php echo htmlspecialchars($listing['nom']); ?></h3>
                                    <p class="listing-price"><?php echo number_format($listing['prix'], 2, ',', ' '); ?> €</p>
                                </div>
                            </div>
                        </div>
                        <div class="listing-actions">
                            <a href="detail.php?id=<?php echo $listing['id']; ?>" class="btn btn-outline listing-action-btn">Voir</a>
                            <?php if ($is_own_account): ?>
                            <a href="edit.php?id=<?php echo $listing['id']; ?>" class="btn btn-primary listing-action-btn">Modifier</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <?php elseif ($is_own_account && $section === 'orders'): ?>
            <div class="account-section">
                <div class="account-section-header">
                    <h2 class="account-section-title">Mes commandes</h2>
                </div>
                
                <?php if (empty($orders)): ?>
                <div class="no-orders">
                    <p>Aucune commande trouvée.</p>
                </div>
                <?php else: ?>
                <div class="orders-list">
                    <?php foreach ($orders as $order): 
                        $date = new DateTime($order['date']);
                        $formattedDate = $date->format('d/m/Y');
                    ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-id">Commande #<?php echo $order['id']; ?></div>
                            <div class="order-date"><?php echo $formattedDate; ?></div>
                            <div class="order-status status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></div>
                        </div>
                        <div class="order-items">
                            <?php if (empty($order['items'])): ?>
                            <p>Détails de la commande non disponibles.</p>
                            <?php else: ?>
                                <?php foreach ($order['items'] as $item): ?>
                                <div class="order-item">
                                    <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="order-item-image">
                                    <div class="order-item-info">
                                        <h3 class="order-item-name"><?php echo htmlspecialchars($item['title']); ?></h3>
                                        <p class="order-item-price"><?php echo number_format($item['price'], 2, ',', ' '); ?> €</p>
                                        <p class="order-item-quantity">Quantité: <?php echo $item['quantity']; ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="order-total">
                            <span class="order-total-label">Total</span>
                            <span class="order-total-value"><?php echo number_format($order['total'], 2, ',', ' '); ?> €</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Preview profile picture before upload
document.addEventListener('DOMContentLoaded', function() {
    const profilePicInput = document.getElementById('profile_pic');
    const profilePicPreview = document.getElementById('profile_pic-preview');
    
    if (profilePicInput && profilePicPreview) {
        profilePicInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePicPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
