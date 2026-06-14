<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';


// Get search, category, sort params safely
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'product_name';

// Validate sort column
$allowedSorts = ['product_name', 'manufacturer', 'category', 'quantity', 'price'];
if (!in_array($sort, $allowedSorts)) {
    $sort = 'product_name';
}

// Build SQL query with dynamic filters
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (product_name LIKE ? OR manufacturer LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category !== '') {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$sql .= " ORDER BY $sort ASC";

// Prepare statement
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get distinct categories
$category_result = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category");
$categories = $category_result->fetchAll(PDO::FETCH_COLUMN);

// Calculate inventory stats
$totalProducts = count($products);
$lowStockCount = $pdo->query("SELECT COUNT(*) FROM products WHERE quantity <= reorder_level")->fetchColumn();
$totalInventoryValue = $pdo->query("SELECT COALESCE(SUM(quantity * price), 0) FROM products")->fetchColumn();
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="h3 fw-bold text-dark">
                <i class="fas fa-warehouse me-2 text-primary"></i>Inventory Management
            </h1>
            <p class="text-muted">Current stock levels and product information</p>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h5>Total Products</h5>
                    <h2><?= number_format($totalProducts) ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card low-stock">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5>Low Stock Items</h5>
                    <h2><?= number_format($lowStockCount) ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h5>Inventory Value</h5>
                    <h2>₦<?= number_format($totalInventoryValue, 0) ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <h5>Stock In</h5>
                    <a href="add-purchase.php" class="btn btn-sm btn-primary mt-2">
                        <i class="fas fa-plus"></i> Add Stock
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" action="" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name, manufacturer..." 
                               value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>" 
                                    <?= ($cat === $category) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="sort" class="form-select">
                            <?php foreach ($allowedSorts as $col): ?>
                                <option value="<?= $col ?>" <?= ($sort === $col) ? 'selected' : '' ?>>
                                    <?= ucfirst(str_replace('_', ' ', $col)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-body">
                <?php if (empty($products)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No products found matching your criteria.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Manufacturer</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Unit Price</th>
                                    <th>Stock Value</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): 
                                    $stockValue = $product['quantity'] * $product['price'];
                                    $stockStatus = $product['quantity'] <= $product['reorder_level'] ? 'Low' : 'OK';
                                    $statusClass = $stockStatus === 'Low' ? 'danger' : 'success';
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($product['product_name']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($product['category']) ?></td>
                                    <td><?= htmlspecialchars($product['manufacturer']) ?></td>
                                    <td>
                                        <span class="badge bg-info"><?= (int)$product['quantity'] ?> units</span>
                                    </td>
                                    <td><?= (int)$product['reorder_level'] ?></td>
                                    <td>₦<?= number_format($product['price'], 2) ?></td>
                                    <td>₦<?= number_format($stockValue, 2) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $statusClass ?>">
                                            <?= $stockStatus ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="stock-adjustment.php" class="btn btn-sm btn-warning" title="Adjust Stock">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>