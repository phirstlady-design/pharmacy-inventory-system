<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $manufacturer_name = trim($_POST['manufacturer_name']);

    if (empty($manufacturer_name)) {
        $error = 'manufacturer name is required';
    } else {

        $check = $pdo->prepare("SELECT id FROM manufacturers WHERE manufacturer_name = ?");
        $check->execute([$manufacturer_name]);

        if ($check->fetch()) {
            $error = 'manufacturer already exists';
        } else {

            $stmt = $pdo->prepare("INSERT INTO manufacturers (manufacturer_name) VALUES (?)");

            if ($stmt->execute([$manufacturer_name])) {
                $message = 'manufacturer added successfully';
            } else {
                $error = 'Failed to add manufacturer';
            }
        }
    }
}

$manufacturers = $pdo->query("SELECT * FROM manufacturers ORDER BY manufacturer_name ASC")
             ->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>
                <i class="fas fa-ruler me-2"></i>
                Manage Manufacturer
            </h3>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="row">

            <div class="col-md-5">
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Add New Manufacturer
                        </h5>
                    </div>

                    <div class="card-body">

                        <form method="POST">

                            <div class="mb-3">
                                <label class="form-label">
                                    Manufacturer Name
                                </label>

                                <input
                                    type="text"
                                    name="manufacturer_name"
                                    class="form-control"
                                    placeholder="e.g. Carton"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Add Manufacturer
                            </button>

                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Existing Manufacturer
                        </h5>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered align-middle">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Manufacturer Name</th>
                                    <th>Date Added</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (count($manufacturers) > 0): ?>

                                    <?php foreach ($manufacturers as $index => $manufacturer): ?>

                                            <tr>
                                                <td><?= $index + 1 ?></td>

                                                <td>
                                                    <?= htmlspecialchars($manufacturer['manufacturer_name']) ?>
                                                </td>

                                                <td>
                                                    <?= date('d M Y', strtotime($manufacturer['created_at'])) ?>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            No manufacturer added yet
                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>