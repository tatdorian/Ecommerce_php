<?php
session_start();
require '../configs/db.config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        $_SESSION['register_error'] = "Les mots de passe ne correspondent pas.";
        header("Location: ../pages/register.page.php");
        exit();
    } else {
        $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $_SESSION['register_error'] = "Cet email ou ce nom d'utilisateur est déjà utilisé.";
            header("Location: ../pages/register.page.php");
            exit();
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $password_hash])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_name'] = $username;
                header("Location: ../pages/home.page.php");
                exit();
            } else {
                $_SESSION['register_error'] = "Erreur lors de l'inscription.";
                header("Location: ../pages/register.page.php");
                exit();
            }
        }
    }
}
?>