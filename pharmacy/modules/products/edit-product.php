<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

if(!isset($_GET['id'])) {
    die("Product ID Missing");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$product) {
    die("Product Not Found");
}

$success = '';
$error = '';

if(isset($_POST['update'])) {

    $product_name = trim($_POST['product_name']);
    $barcode = trim($_POST['barcode']);
    $quantity = intval($_POST['quantity']);
    $unit_id = intval($_POST['unit_id']);
    $cost_price = floatval($_POST['cost_price']);
    $selling_price = floatval($_POST['selling_price']);
    $expiry_date = $_POST['expiry_date'];

    if(empty($product_name)) {
        $error = "Product name is required";
    } elseif($cost_price < 0 || $selling_price < 0) {
        $error = "Prices cannot be negative";
    } else {
        $update = $pdo->prepare("UPDATE products SET
            product_name=?,
            barcode=?,
            unit_id=?,
            quantity=?,
            cost_price=?,
            selling_price=?,
            expiry_date=?
            WHERE id=?");

            if($update->execute([
                $product_name,
                $barcode,
                $unit_id,
                $quantity,
                $cost_price,
                $selling_price,
                $expiry_date,
                $id
            ])) {
            $success = "Product Updated Successfully!";
            // Refresh product data
            $product['product_name'] = $product_name;
            $product['barcode'] = $barcode;
            $product['unit_id'] = $unit_id;
            $product['quantity'] = $quantity;
            $product['cost_price'] = $cost_price;
            $product['selling_price'] = $selling_price;
            $product['expiry_date'] = $expiry_date;
        } else {
            $error = "Error updating product. Please try again.";
        }
    }
}
?>
<?php require_once '../../includes/header.php'; ?>
 <?php require_once '../../includes/sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="../../styles.css"> -->
    <style>
        /* body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        } */
        
        /* .navbar {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
        } */
        
        .form-wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding-top: 50px;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .form-header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 2rem;
            border-radius: 12px 12px 0 0;
            color: white;
        }
        
        .form-header h3 {
            color: white;
            margin: 0;
            font-size: 1.5rem;
        }
        
        .product-id {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }
        
        .form-body {
            padding: 2.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 0.6rem;
            display: block;
        }
        
        .form-control,
        .form-select {
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            padding: 0.85rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background-color: white;
        }
        
        .input-group {
            margin-bottom: 1.5rem;
        }
        
        .input-group-text {
            background: var(--neutral-100);
            border: 2px solid var(--neutral-200);
            color: var(--neutral-700);
            font-weight: 600;
        }
        
        .input-group .form-control {
            border-left: none;
        }
        
        .row-2cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border: none;
            border-radius: 8px;
            padding: 0.85rem 2rem;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            flex: 1;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.3);
            color: white;
        }
        
        .btn-cancel {
            background: var(--neutral-200);
            border: none;
            border-radius: 8px;
            padding: 0.85rem 2rem;
            color: var(--neutral-800);
            font-weight: 600;
            font-size: 1rem;
            flex: 1;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-cancel:hover {
            background: var(--neutral-300);
            color: var(--neutral-800);
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .row-2cols {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<!-- <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-lg">
        <a class="navbar-brand fw-bold" href="products.php">
            <i class="fas fa-arrow-left me-2"></i>Back to Products
        </a>
    </div>
</nav> -->

<div class="container-lg">
    <div class="form-wrapper">
        <div class="form-card">
            <div class="form-header">
                <h3><i class="fas fa-edit me-2"></i>Edit Product</h3>
                <div class="product-id">Product ID: #<?= $product['id'] ?></div>
            </div>
            
            <div class="form-body">
                
                <?php if($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><strong>Success!</strong> <?= $success ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><strong>Error!</strong> <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="form-group">
                        <label for="product_name" class="form-label">Product Name *</label>
                        <input type="text" id="product_name" name="product_name" class="form-control" 
                               value="<?= htmlspecialchars($product['product_name']) ?>" required>
                    </div>
                    <div class="row-2cols">

                    <div class="form-group">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" id="barcode" name="barcode" class="form-control"
                               value="<?= htmlspecialchars($product['barcode']) ?>">
                    </div>

                      <?php
                        $units = $pdo->query("SELECT * FROM unit ORDER BY unit_name ASC")->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <div class="form-group">
                            <label for="unit" class="form-label">Unit</label>

                            <select name="unit_id" class="form-select" required>
                                <option value="">Select Unit</option>

                                <?php foreach($units as $unit): ?>
                                    <option value="<?= $unit['id'] ?>"
                                        <?= $product['unit_id'] == $unit['id'] ? 'selected' : '' ?>>

                                        <?= htmlspecialchars($unit['unit_name']) ?>

                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row-2cols">
                        <div class="form-group">
                            <label for="quantity" class="form-label">Quantity</label>
                            <div class="input-group">
                                <input type="number" id="quantity" name="quantity" class="form-control" 
                                       value="<?= $product['quantity'] ?>" min="0">
                                <span class="input-group-text"></span>
                            </div>

                            
                        </div>

                        <div class="form-group">
                            <label for="expiry_date" class="form-label">Expiry Date</label>
                            <input type="date" id="expiry_date" name="expiry_date" class="form-control"
                                   value="<?= htmlspecialchars($product['expiry_date']) ?>">
                        </div>
                    </div>

                    <div class="row-2cols">
                        <div class="form-group">
                            <label for="cost_price" class="form-label">Cost Price</label>
                            <div class="input-group">
                                <span class="input-group-text">#</span>
                                <input type="number" id="cost_price" name="cost_price" class="form-control" 
                                       value="<?= number_format($product['cost_price'], 2) ?>" min="0" step="0.01">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <div class="input-group">
                                <span class="input-group-text">#</span>
                                <input type="number" id="selling_price" name="selling_price" class="form-control" 
                                       value="<?= number_format($product['selling_price'], 2) ?>" min="0" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="update" class="btn btn-submit">
                            <i class="fas fa-save me-2"></i>Update Product
                        </button>
                        <a href="products.php" class="btn-cancel">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>