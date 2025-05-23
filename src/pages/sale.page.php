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
    <title>Mettre un livre en vente</title>
    <link rel="stylesheet" href="../assets/css/sale.css">
</head>
<body>
    <nav>
        <?php if ($is_logged_in): ?>
            Bonjour, <?php echo htmlspecialchars($user_name); ?> |
            <a href="logout.page.php">Déconnexion</a>
        <?php else: ?>
            <a href="login.page.php">Connexion</a>
            <a href="register.page.php">Inscription</a>
        <?php endif; ?>
    </nav>

    <?php if ($is_logged_in): ?>
        <h2>Vous pouvez désormais mettre en vente vos livres</h2>
        <form action="sale.page.php" method="POST" enctype="multipart/form-data">
            <label for="nom">Nom du livre :</label>
            <input type="text" name="nom" id="nom" required>

            <label for="description">Description :</label>
            <textarea name="description" id="description" required></textarea>

            <label for="prix">Prix :</label>
            <input type="text" name="prix" id="prix" required>

            <label for="titre_livre">Titre du livre :</label>
            <input type="text" name="titre_livre" id="titre_livre" required>

            <label for="auteur_livre">Auteur du livre :</label>
            <input type="text" name="auteur_livre" id="auteur_livre" required>

            <label for="date_livre">Date de parution :</label>
            <input type="date" name="date_livre" id="date_livre" required>

            <label for="genre_livre">Genre :</label>
            <input type="text" name="genre_livre" id="genre_livre" required>

            <label for="image">Image :</label>
            <input type="file" name="image" id="image" accept="image/*" required>

            <input type="submit" value="Mettre en vente">
        </form>
    <?php else: ?>
        <p>Veuillez vous connecter pour mettre un livre en vente.</p>
    <?php endif; ?>
</body>
</html>
