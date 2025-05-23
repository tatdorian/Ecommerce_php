<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';
require_once '../middlewares/detail.middleware.php';

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($article['nom']); ?> - Détails</title>
    <link rel="stylesheet" href="../assets/css/detail.css">
</head>

<body>
    <div class="details-container">
        <div class="image-section">
            <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Parfum">
        </div>
        <div class="info-section">
            <h1><?php echo htmlspecialchars($article['nom']); ?></h1>
            <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($article['description'])); ?></p>
            <p><strong>Auteur:</strong> <?php echo htmlspecialchars($article['auteur']); ?></p>
            <p><strong>Prix:</strong> <?php echo number_format($article['prix'], 2, ',', ' '); ?> €</p>
            <p><strong>Date de publication:</strong> <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?></p>
            <div class="actions">
                <form method="post" action="ajouter_panier.php">
                    <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                    <button type="submit">Ajouter au panier</button>
                </form>
                <form method="post" action="acheter.php">
                    <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                    <button type="submit">Acheter cet article</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>