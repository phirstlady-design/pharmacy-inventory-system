<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

if(isset($_POST['open_shift'])) {

    $opening_balance = $_POST['opening_balance'];

    $stmt = $pdo->prepare(
        "INSERT INTO cashier_shifts (
            user_id,
            opening_balance
        ) VALUES (?,?)"
    );

    $stmt->execute([
        $_SESSION['user_id'],
        $opening_balance
    ]);

    header('Location: ../sales/pos.php');
    exit;
}
?>