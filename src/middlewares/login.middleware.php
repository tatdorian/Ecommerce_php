<?php
session_start();
require '../config.middleware.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_username'] = $user['username'];
        header("Location: ../pages/home.page.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Email ou mot de passe incorrect.";
        header("Location: ../pages/login.page.php");
        exit();
    }
}
?>