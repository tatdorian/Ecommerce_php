<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.page.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Bienvenue</title></head>
<body>
<h1>Bienvenue <?php echo htmlspecialchars($_SESSION['user_name']); ?> !</h1>
<p>Vous êtes connecté.</p>
<p><a href="login.page.php">Se déconnecter</a></p>
</body>
</html>
