<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username']; 
        $_SESSION['user_role'] = $user['role']; 
        $_SESSION['user_photo_profil'] = $user['photo_profil'] ?? 'assets/images/default-avatar.png'; 
        $_SESSION['user_solde'] = $user['solde'];
        
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Email ou mot de passe incorrect.";
        header("Location: ../login.php");
        exit();
    }
}
?>
