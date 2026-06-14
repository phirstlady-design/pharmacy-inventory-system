
<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$message = '';
$messageType = '';

// Get all suppliers with purchase stats
$suppliersStmt = $pdo->query("
    SELECT 
        s.*,
        COUNT(po.id) as total_purchases,
        COALESCE(SUM(po.total_amount), 0) as total_spent
    FROM suppliers s
    LEFT JOIN purchase_orders po ON s.id = po.supplier_id
    GROUP BY s.id
    ORDER BY s.supplier_name ASC
");
$suppliers = $suppliersStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle add/edit supplier
if (isset($_POST['save_supplier'])) {
    $supplier_name = trim($_POST['supplier_name'] ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($supplier_name) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO suppliers (supplier_name, contact_person, phone, email, address)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$supplier_name, $contact_person, $phone, $email, $address]);
            $message = 'Supplier added successfully';
            $messageType = 'success';
            // Refresh suppliers list
            header('Location: add-supplier.php');
            exit;
        } catch (Exception $e) {
            $message = 'Error adding supplier: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

// Handle delete supplier
if (isset($_POST['delete_supplier'])) {
    $supplier_id = intval($_POST['supplier_id']);
    try {
        $stmt = $pdo->prepare("DELETE FROM suppliers WHERE id = ?");
        $stmt->execute([$supplier_id]);
        $message = 'Supplier deleted successfully';
        $messageType = 'success';
        header('Location: add-supplier.php');
        exit;
    } catch (Exception $e) {
        $message = 'Error deleting supplier: ' . $e->getMessage();
        $messageType = 'danger';
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
                <i class="fas fa-truck me-2 text-primary"></i>Supplier Management
            </h1>
            <p class="text-muted">Manage your pharmacy suppliers and contact information</p>
        </div>

        <!-- Alert Messages -->
        <?php if($message): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show">
                <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-2"></i>
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Total Suppliers</h5>
                        <h2 class="text-primary"><?= count($suppliers) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Active Suppliers</h5>
                        <h2 class="text-success"><?= count(array_filter($suppliers, fn($s) => $s['total_purchases'] > 0)) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Total Spent</h5>
                        <h2 class="text-info">₦<?= number_format(array_sum(array_column($suppliers, 'total_spent')), 0) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary h-100 w-100" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i class="fas fa-plus me-2"></i>Add Supplier
                </button>
            </div>
        </div>

        <!-- Suppliers Table -->
        <div class="card">
            <div class="card-body">
                <?php if (empty($suppliers)): ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>No suppliers found. Add your first supplier to get started.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Contact Person</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Purchases</th>
                                    <th>Total Spent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($supplier['supplier_name']) ?></strong>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($supplier['contact_person'] ?? '-') ?>
                                        </td>
                                        <td>
                                            <?php if ($supplier['phone']): ?>
                                                <a href="tel:<?= htmlspecialchars($supplier['phone']) ?>">
                                                    <?= htmlspecialchars($supplier['phone']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($supplier['email']): ?>
                                                <a href="mailto:<?= htmlspecialchars($supplier['email']) ?>">
                                                    <?= htmlspecialchars($supplier['email']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?= (int)$supplier['total_purchases'] ?></span>
                                        </td>
                                        <td>
                                            <strong>₦<?= number_format($supplier['total_spent'], 2) ?></strong>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewSupplierModal"
                                                    onclick="loadSupplierDetails(<?= $supplier['id'] ?>, '<?= htmlspecialchars($supplier['supplier_name']) ?>', '<?= htmlspecialchars($supplier['contact_person'] ?? '') ?>', '<?= htmlspecialchars($supplier['phone'] ?? '') ?>', '<?= htmlspecialchars($supplier['email'] ?? '') ?>', '<?= htmlspecialchars($supplier['address'] ?? '') ?>')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                                <input type="hidden" name="supplier_id" value="<?= $supplier['id'] ?>">
                                                <button type="submit" name="delete_supplier" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
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

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="supplier_name" class="form-label">
                            <strong>Supplier Name</strong>
                        </label>
                        <input type="text" id="supplier_name" name="supplier_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" id="phone" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="save_supplier" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Add Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Supplier Modal -->
<div class="modal fade" id="viewSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supplier Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Supplier Name:</strong>
                    <p id="detail_name"></p>
                </div>
                <div class="mb-3">
                    <strong>Contact Person:</strong>
                    <p id="detail_contact"></p>
                </div>
                <div class="mb-3">
                    <strong>Phone:</strong>
                    <p id="detail_phone"></p>
                </div>
                <div class="mb-3">
                    <strong>Email:</strong>
                    <p id="detail_email"></p>
                </div>
                <div class="mb-3">
                    <strong>Address:</strong>
                    <p id="detail_address"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function loadSupplierDetails(id, name, contact, phone, email, address) {
    document.getElementById('detail_name').textContent = name || '-';
    document.getElementById('detail_contact').textContent = contact || '-';
    document.getElementById('detail_phone').textContent = phone ? `<a href="tel:${phone}">${phone}</a>` : '-';
    document.getElementById('detail_email').textContent = email ? `<a href="mailto:${email}">${email}</a>` : '-';
    document.getElementById('detail_address').textContent = address || '-';
}
</script>

<?php require_once '../../includes/footer.php'; ?>