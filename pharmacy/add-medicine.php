<?php
include("include/connect.php");

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and escape inputs
    $name = $conn->real_escape_string($_POST['name']);
    $generic_name = $conn->real_escape_string($_POST['generic_name']);
    $category = $conn->real_escape_string($_POST['category']);
    $manufacturer = $conn->real_escape_string($_POST['manufacturer']);
    $dosage = $conn->real_escape_string($_POST['dosage']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $reorder_level = (int)$_POST['reorder_level'];
    $expiry_date = $conn->real_escape_string($_POST['expiry_date']);
    $supplier = $conn->real_escape_string($_POST['supplier']);
    $description = $conn->real_escape_string($_POST['description']);

    // Prepare SQL using object-oriented mysqli
    $stmt = $conn->prepare("INSERT INTO medicines (name, generic_name, category, manufacturer, dosage, quantity, price, reorder_level, expiry_date, supplier, description)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sssssidisss", $name, $generic_name, $category, $manufacturer, $dosage, $quantity, $price, $reorder_level, $expiry_date, $supplier, $description);

        if ($stmt->execute()) {
            $message = "Medicine added successfully!";
        } else {
            $error = "Execute failed: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "Prepare failed: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medicine - Pharmacy System</title>
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
                <li class="active">
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
                <h1>Add New Medicine</h1>
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
                            <input type="text" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="generic_name">Generic Name</label>
                            <input type="text" id="generic_name" name="generic_name">
                        </div>

                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Antibiotics">Antibiotics</option>
                                <option value="Pain Relief">Pain Relief</option>
                                <option value="Vitamins">Vitamins</option>
                                <option value="Heart Medication">Heart Medication</option>
                                <option value="Diabetes">Diabetes</option>
                                <option value="Blood Pressure">Blood Pressure</option>
                                <option value="Respiratory">Respiratory</option>
                                <option value="Digestive">Digestive</option>
                                <option value="Skin Care">Skin Care</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="manufacturer">Manufacturer</label>
                            <input type="text" id="manufacturer" name="manufacturer">
                        </div>

                        <div class="form-group">
                            <label for="dosage">Dosage</label>
                            <input type="text" id="dosage" name="dosage" placeholder="e.g., 500mg, 10ml">
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity *</label>
                            <input type="number" id="quantity" name="quantity" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Price (#) *</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="reorder_level">Reorder Level</label>
                            <input type="number" id="reorder_level" name="reorder_level" min="0" value="10">
                        </div>

                        <div class="form-group">
                            <label for="expiry_date">Expiry Date *</label>
                            <input type="date" id="expiry_date" name="expiry_date" required>
                        </div>

                        <div class="form-group">
                            <label for="supplier">Supplier</label>
                            <input type="text" id="supplier" name="supplier">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" placeholder="Additional notes or description"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Add Medicine
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i>
                            Reset Form
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>