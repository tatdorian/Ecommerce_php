<?php
require_once '../configs/db.config.php';

$user_id = $_SESSION['user_id'];
$errors = [];
$payment_success = false;
$cart_items = [];
$total_price = 0;

$stmt = $pdo->prepare("
    SELECT c.id AS cart_id, a.id AS article_id, a.nom, a.prix, c.quantity
    FROM cart c
    JOIN article a ON c.article_id = a.id
    WHERE c.user_id = ?
");

$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

foreach ($cart_items as $item) {
    $total_price += $item['prix'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_buy'])) {
    $card_number = $_POST['card_number'] ?? '';
    $card_expiry = $_POST['card_expiry'] ?? '';
    $card_cvv = $_POST['card_cvv'] ?? '';

    $card_number = str_replace(' ', '', $card_number);
    if (!preg_match('/^\d{16}$/', $card_number)) {
        $errors[] = "Numéro de carte invalide (16 chiffres attendus).";
    }
    if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $card_expiry)) {
        $errors[] = "Date d'expiration invalide (format MM/AA).";
    }
    if (!preg_match('/^\d{3}$/', $card_cvv)) {
        $errors[] = "CVV invalide (3 chiffres).";
    }

    if (empty($errors)) {
        $pdo->beginTransaction();
        try {
            foreach ($cart_items as $item) {

                $stmt = $pdo->prepare("INSERT INTO `old_article` SELECT * FROM article WHERE id = ?");
                $stmt->execute([$item['article_id']]);
                
                $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ?");
                $stmt->execute([$item['cart_id']]);

                $stmt = $pdo->prepare("DELETE FROM article WHERE id = ?");
                $stmt->execute([$item['article_id']]);
            }

            $pdo->commit();
            $payment_success = true;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Erreur durant le paiement : " . $e->getMessage();
        }
    }
}