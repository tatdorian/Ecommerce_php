<?php
require_once '../configs/db.config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../pages/login.page.php');
    exit;
}

$success = false;
$error = '';
$user = [
    'username' => '',
    'email' => '',
    'photo_profil' => ''
];

$stmt = $pdo->prepare("SELECT username, email, photo_profil FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();
if ($user_data) {
    $user = $user_data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

   
    $photo_profil_base64 = $user['photo_profil'];
    if (
        isset($_FILES['photo_profil']) &&
        $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK &&
        is_uploaded_file($_FILES['photo_profil']['tmp_name'])
    ) {
        $tmp_name = $_FILES['photo_profil']['tmp_name'];
        $name = basename($_FILES['photo_profil']['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($ext, $allowed)) {
           
            $file_size = filesize($tmp_name);
            if ($file_size > 5 * 1024 * 1024) {
                $error = "Le fichier est trop volumineux (max 5MB).";
            } else {
                
                $image_data = file_get_contents($tmp_name);
                $mime_type = mime_content_type($tmp_name);
                $photo_profil_base64 = 'data:' . $mime_type . ';base64,' . base64_encode($image_data);
            }
        } else {
            $error = "Format de fichier non autorisé (jpg, jpeg, png, gif, webp).";
        }
    }

   
    if (!$error && !empty($new_password)) {
        $stmt = $pdo->prepare("SELECT password FROM user WHERE id = ?");
        $stmt->execute([$user_id]);
        $user_pwd = $stmt->fetchColumn();
        if (!password_verify($password, $user_pwd)) {
            $error = "Mot de passe actuel incorrect.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Les nouveaux mots de passe ne correspondent pas.";
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE user SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $user_id]);
        }
    }

    if (!$error) {
        $stmt = $pdo->prepare("UPDATE user SET username = ?, email = ?, photo_profil = ? WHERE id = ?");
        $stmt->execute([$username, $email, $photo_profil_base64, $user_id]);
        $success = true;
        $_SESSION['user_name'] = $username;
    
        $stmt = $pdo->prepare("SELECT username, email, photo_profil FROM user WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    }
}
?>