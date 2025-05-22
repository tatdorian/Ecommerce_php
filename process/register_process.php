<?php
session_start();
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        $_SESSION['register_error'] = "Les mots de passe ne correspondent pas.";
        header("Location: register.php");
        exit();
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['register_error'] = "Cet email est déjà utilisé.";
            header("Location: register.php");
            exit();
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $password_hash])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_name'] = $name;
                header("Location: ../home.php");
                exit();
            } else {
                $_SESSION['register_error'] = "Erreur lors de l'inscription.";
                header("Location: register.php");
                exit();
            }
        }
    }
}
?>