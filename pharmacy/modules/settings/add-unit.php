<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $unit_name = trim($_POST['unit_name']);

    if (empty($unit_name)) {
        $error = 'Unit name is required';
    } else {

        $check = $pdo->prepare("SELECT id FROM unit WHERE unit_name = ?");
        $check->execute([$unit_name]);

        if ($check->fetch()) {
            $error = 'Unit already exists';
        } else {

            $stmt = $pdo->prepare("INSERT INTO unit (unit_name) VALUES (?)");

            if ($stmt->execute([$unit_name])) {
                $message = 'Unit added successfully';
            } else {
                $error = 'Failed to add unit';
            }
        }
    }
}

$unit = $pdo->query("SELECT * FROM unit ORDER BY unit_name ASC")
             ->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>
                <i class="fas fa-ruler me-2"></i>
                Manage Unit
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
                            Add New Unit
                        </h5>
                    </div>

                    <div class="card-body">

                        <form method="POST">

                            <div class="mb-3">
                                <label class="form-label">
                                    Unit Name
                                </label>

                                <input
                                    type="text"
                                    name="unit_name"
                                    class="form-control"
                                    placeholder="e.g. Carton"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Add Unit
                            </button>

                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Existing Unit
                        </h5>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered align-middle">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Unit Name</th>
                                    <th>Date Added</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (count($unit) > 0): ?>

                                    <?php foreach ($unit as $index => $unit): ?>

                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <?= htmlspecialchars($unit['unit_name']) ?>
                                            </td>
                                            <td>
                                                <?= date('d M Y', strtotime($unit['created_at'])) ?>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            No unit added yet
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