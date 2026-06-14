<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

// Get filter parameters
$search = $_GET['search'] ?? '';

// Build query
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (product_name LIKE ? OR barcode LIKE ? OR category LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " AND quantity > 0 ORDER BY product_name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$cart = $_SESSION['cart'] ?? [];

// Handle barcode scanning
if(isset($_GET['barcode'])) {
    $barcode = trim($_GET['barcode']);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE barcode = ? AND quantity > 0");
    $stmt->execute([$barcode]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if($product) {
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][$product['id']] = ($_SESSION['cart'][$product['id']] ?? 0) + 1;
    }
    header("Location: pos.php");
    exit();
}

// Calculate cart total
$total = 0;
foreach($cart as $productId => $qty) {
    $stmt = $pdo->prepare("SELECT selling_price FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        $total += $product['selling_price'] * $qty;
    }
}
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="h3 fw-bold text-dark">
                <i class="fas fa-cash-register me-2 text-primary"></i>Point of Sale
            </h1>
            <p class="text-muted">Quick checkout system</p>
        </div>

        <div class="row g-3">
            <!-- LEFT: PRODUCTS -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Products</h5>
                    </div>
                    <div class="card-body">
                        <!-- Search and Barcode Section -->
                        <form method="GET" class="mb-4">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" id="barcode" class="form-control form-control-lg" 
                                           placeholder="Scan barcode..." autofocus>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="search" name="search" class="form-control form-control-lg" 
                                           placeholder="Search product name..." value="<?= htmlspecialchars($search) ?>">
                                </div>
                            </div>
                        </form>

                        <!-- Products Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Barcode</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Qty</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="productTable">
                                    <?php if (empty($products)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                No products available
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($products as $p): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($p['product_name']) ?></strong></td>
                                                <td><small class="text-muted"><?= htmlspecialchars($p['barcode'] ?? '-') ?></small></td>
                                                <td>₦<?= number_format($p['selling_price'], 2) ?></td>
                                                <td>
                                                    <span class="badge bg-info"><?= (int)$p['quantity'] ?></span>
                                                </td>
                                                <td style="width: 120px;">
                                                    <form method="POST" action="cart.php" class="d-inline">
                                                        <input type="number" name="quantity" value="1" class="form-control form-control-sm" min="1" max="<?= $p['quantity'] ?>">
                                                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                                </td>
                                                <td>
                                                    <button type="submit" name="add" class="btn btn-sm btn-success">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: CART & CHECKOUT -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 80px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-bag me-2"></i>Cart (<?= count($cart) ?> items)
                        </h5>
                    </div>

                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <?php if (empty($cart)): ?>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Cart is empty
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($cart as $productId => $qty):
                                            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                                            $stmt->execute([$productId]);
                                            $p = $stmt->fetch(PDO::FETCH_ASSOC);
                                            
                                            if(!$p) continue;
                                            $subtotal = $p['selling_price'] * $qty;
                                        ?>
                                            <tr>
                                                <td>
                                                    <small><strong><?= htmlspecialchars(substr($p['product_name'], 0, 15)) ?></strong></small>
                                                </td>
                                                <td><?= (int)$qty ?></td>
                                                <td>₦<?= number_format($subtotal, 2) ?></td>
                                                <td>
                                                    <a href="cart.php?remove=<?= $productId ?>" class="btn btn-sm btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <strong>₦<?= number_format($total, 2) ?></strong>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-0">Total:</h5>
                                <h5 class="mb-0 text-primary">₦<?= number_format($total, 2) ?></h5>
                            </div>
                        </div>

                        <?php if (!empty($cart)): ?>
                            <div class="d-grid gap-2">
                                <a href="checkout.php" class="btn btn-primary">
                                    <i class="fas fa-check-circle me-2"></i>Proceed to Checkout
                                </a>
                                <a href="cart.php?clear=1" class="btn btn-outline-danger">
                                    <i class="fas fa-trash me-2"></i>Clear Cart
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<script>
// Handle barcode scanning with Enter key
document.getElementById("barcode").addEventListener("keypress", function(e) {
    if(e.key === "Enter") {
        e.preventDefault();
        let code = this.value.trim();
        if(code.length > 0) {
            window.location.href = "pos.php?barcode=" + encodeURIComponent(code);
        }
    }
});

// Handle search with real-time filtering
const searchInput = document.getElementById("search");
if (searchInput) {
    searchInput.addEventListener("keyup", function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#productTable tr");
        
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(value) ? "" : "none";
        });
    });
}
</script>

<?php require_once '../../includes/footer.php'; ?>
</body>
</html>
