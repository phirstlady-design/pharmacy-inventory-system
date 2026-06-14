<?php
include("include/connect.php");

$id = $_GET['id'] ?? 0;
$message = '';
$error = '';

// Get medicine data
$stmt = $pdo->prepare("SELECT * FROM medicines WHERE id = ?");
$stmt->execute([$id]);
$medicine = $stmt->fetch();

if (!$medicine) {
    header('Location: inventory.php');
    exit;
}

if ($_POST) {
    $name = $_POST['name'];
    $generic_name = $_POST['generic_name'];
    $category = $_POST['category'];
    $manufacturer = $_POST['manufacturer'];
    $dosage = $_POST['dosage'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $reorder_level = $_POST['reorder_level'];
    $expiry_date = $_POST['expiry_date'];
    $supplier = $_POST['supplier'];
    $description = $_POST['description'];

    try {
        $sql = "UPDATE medicines SET name = ?, generic_name = ?, category = ?, manufacturer = ?, 
                dosage = ?, quantity = ?, price = ?, reorder_level = ?, expiry_date = ?, 
                supplier = ?, description = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $generic_name, $category, $manufacturer, $dosage, $quantity, $price, $reorder_level, $expiry_date, $supplier, $description, $id]);
        
        $message = "Medicine updated successfully!";
        
        // Refresh medicine data
        $stmt = $pdo->prepare("SELECT * FROM medicines WHERE id = ?");
        $stmt->execute([$id]);
        $medicine = $stmt->fetch();
    } catch (Exception $e) {
        $error = "Error updating medicine: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Medicine - Pharmacy System</title>
    <link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-pills"></i>
                <h2>PharmaCare</h2>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="index.php">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="inventory.php">
                        <i class="fas fa-boxes"></i>
                        Inventory
                    </a>
                </li>
                <li>
                    <a href="add-medicine.php">
                        <i class="fas fa-plus"></i>
                        Add Medicine
                    </a>
                </li>
                <li>
                    <a href="reports.php">
                        <i class="fas fa-chart-bar"></i>
                        Reports
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1>Edit Medicine</h1>
                <a href="inventory.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Inventory
                </a>
            </header>

            <?php if ($message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <form method="POST" class="medicine-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Medicine Name *</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($medicine['name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="generic_name">Generic Name</label>
                            <input type="text" id="generic_name" name="generic_name" value="<?php echo htmlspecialchars($medicine['generic_name']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Antibiotics" <?php echo $medicine['category'] === 'Antibiotics' ? 'selected' : ''; ?>>Antibiotics</option>
                                <option value="Pain Relief" <?php echo $medicine['category'] === 'Pain Relief' ? 'selected' : ''; ?>>Pain Relief</option>
                                <option value="Vitamins" <?php echo $medicine['category'] === 'Vitamins' ? 'selected' : ''; ?>>Vitamins</option>
                                <option value="Heart Medication" <?php echo $medicine['category'] === 'Heart Medication' ? 'selected' : ''; ?>>Heart Medication</option>
                                <option value="Diabetes" <?php echo $medicine['category'] === 'Diabetes' ? 'selected' : ''; ?>>Diabetes</option>
                                <option value="Blood Pressure" <?php echo $medicine['category'] === 'Blood Pressure' ? 'selected' : ''; ?>>Blood Pressure</option>
                                <option value="Respiratory" <?php echo $medicine['category'] === 'Respiratory' ? 'selected' : ''; ?>>Respiratory</option>
                                <option value="Digestive" <?php echo $medicine['category'] === 'Digestive' ? 'selected' : ''; ?>>Digestive</option>
                                <option value="Skin Care" <?php echo $medicine['category'] === 'Skin Care' ? 'selected' : ''; ?>>Skin Care</option>
                                <option value="Other" <?php echo $medicine['category'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="manufacturer">Manufacturer</label>
                            <input type="text" id="manufacturer" name="manufacturer" value="<?php echo htmlspecialchars($medicine['manufacturer']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="dosage">Dosage</label>
                            <input type="text" id="dosage" name="dosage" value="<?php echo htmlspecialchars($medicine['dosage']); ?>" placeholder="e.g., 500mg, 10ml">
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity *</label>
                            <input type="number" id="quantity" name="quantity" value="<?php echo $medicine['quantity']; ?>" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Price ($) *</label>
                            <input type="number" id="price" name="price" value="<?php echo $medicine['price']; ?>" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="reorder_level">Reorder Level</label>
                            <input type="number" id="reorder_level" name="reorder_level" value="<?php echo $medicine['reorder_level']; ?>" min="0">
                        </div>

                        <div class="form-group">
                            <label for="expiry_date">Expiry Date *</label>
                            <input type="date" id="expiry_date" name="expiry_date" value="<?php echo $medicine['expiry_date']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="supplier">Supplier</label>
                            <input type="text" id="supplier" name="supplier" value="<?php echo htmlspecialchars($medicine['supplier']); ?>">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" placeholder="Additional notes or description"><?php echo htmlspecialchars($medicine['description']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update Medicine
                        </button>
                        <a href="inventory.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>