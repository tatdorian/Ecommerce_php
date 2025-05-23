<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password']; 

    if ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Les mots de passe ne correspondent pas.";
        header("Location: ../register.php");
        exit();
    } else {
        $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $_SESSION['register_error'] = "Cet email ou ce nom d'utilisateur est déjà utilisé.";
            header("Location: ../register.php");
            exit();
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            // Ajout des valeurs par défaut pour les nouveaux champs
            $stmt = $pdo->prepare("INSERT INTO user (username, email, password, solde, role) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $password_hash, 0.00, 'client'])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['user_role'] = 'client';
                $_SESSION['user_photo_profil'] = 'assets/images/default-avatar.png';
                $_SESSION['user_solde'] = 0.00;
                
                header("Location: ../index.php");
                exit();
            } else {
                $_SESSION['register_error'] = "Erreur lors de l'inscription.";
                header("Location: ../register.php");
                exit();
            }
        }
    }
}
?>
