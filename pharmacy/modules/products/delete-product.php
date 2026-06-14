<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

header("Location: products.php");
exit();
?>