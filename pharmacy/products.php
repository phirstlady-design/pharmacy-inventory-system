<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

// $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$stmt = $pdo->query("
    SELECT 
        products.*,
        unit.unit_name
    FROM products
    LEFT JOIN unit 
        ON products.unit_id = unit.id
    ORDER BY products.id DESC
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require_once '../../includes/header.php'; ?>
 <?php require_once '../../includes/sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="../../styles.css"> -->
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
       
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--neutral-900);
        }
        
        .btn-add {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
            color: white;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            padding-left: 200px
        }
        
        .table {
            margin-bottom: 0;
            
        }
        
        .table thead {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        }
        
        .table thead th {
            border: none;
            font-weight: 700;
            color: var(--neutral-800);
            padding: 1.2rem;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            
        }
        
        .table tbody td {
            padding: 1.2rem;
            border-bottom: 1px solid var(--neutral-100);
            vertical-align: middle;
            
        }
        
        .table tbody tr:hover {
            background-color: var(--neutral-50);
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.5rem 0.9rem;
            font-size: 0.8rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            border: none;
            font-weight: 600;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
        }
        
        .btn-edit:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
        }
        
        .btn-delete:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--neutral-600);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>



<div class="container-lg mt-5" style="padding-left: 200px;">

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Products</h1>
        <a href="add-product.php" class="btn btn-add">
            <i class="fas fa-plus me-2"></i>Add Product
        </a>
    </div>

    <!-- Products Table -->
    <div class="table-container" style="padding-left: 2px;">
        <?php if(count($products) > 0): ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Barcode</th>
                        <th>Quantity</th>
                        <th>Units</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $product): ?>
                    <tr>
                        <td><strong>#<?= $product['id'] ?></strong></td>
                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                        <td><code><?= htmlspecialchars($product['barcode']) ?></code></td>
                        <td>
                            <span class="badge" style="background-color: #dbeafe; color: #0c4a6e;">
                                <?= $product['quantity'] ?> 
                            </span>
                        </td>
                        <td><?= htmlspecialchars($product['unit_name'] ?? 'N/A') ?></td>
                        <td><?= number_format($product['cost_price'], 2) ?></td>
                        <td><?= number_format($product['selling_price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['expiry_date'] ?? 'N/A') ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit-product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete-product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-box"></i>
                <h5>No Products Found</h5>
                <p>There are no products in the system yet. <a href="add-product.php">Add your first product</a></p>
            </div>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>