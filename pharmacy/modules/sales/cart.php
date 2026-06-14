<?php
session_start();
require_once '../../includes/auth.php';
require_once '../../config/database.php';

if(isset($_POST['add'])) {

    $id = $_POST['product_id'];
    $qty = (int)$_POST['quantity'];

    if($qty <= 0) $qty = 1;

    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if(isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }

    header("Location: pos.php");
    exit();
}

// remove item
if(isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: pos.php");
    exit();
}

// clear cart
if(isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header("Location: pos.php");
    exit();
}
?>