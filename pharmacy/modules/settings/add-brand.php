<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $brand_name = trim($_POST['brand_name']);

    if (empty($brand_name)) {
        $error = 'brand name is required';
    } else {

        $check = $pdo->prepare("SELECT id FROM brands WHERE brand_name = ?");
        $check->execute([$brand_name]);

        if ($check->fetch()) {
            $error = 'brand already exists';
        } else {

            $stmt = $pdo->prepare("INSERT INTO brands (brand_name) VALUES (?)");

            if ($stmt->execute([$brand_name])) {
                $message = 'brand added successfully';
            } else {
                $error = 'Failed to add brand';
            }
        }
    }
}

$brands = $pdo->query("SELECT * FROM brands ORDER BY brand_name ASC")
             ->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>
                <i class="fas fa-ruler me-2"></i>
                Manage brand
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
                            Add New brand
                        </h5>
                    </div>

                    <div class="card-body">

                        <form method="POST">

                            <div class="mb-3">
                                <label class="form-label">
                                    brand Name
                                </label>

                                <input
                                    type="text"
                                    name="brand_name"
                                    class="form-control"
                                    placeholder="e.g. Carton"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Add brand
                            </button>

                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Existing brand
                        </h5>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered align-middle">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>brand Name</th>
                                    <th>Date Added</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (count($brands) > 0): ?>

                                    <?php foreach ($brands as $index => $brand): ?>

                                            <tr>
                                                <td><?= $index + 1 ?></td>

                                                <td>
                                                    <?= htmlspecialchars($brand['brand_name']) ?>
                                                </td>

                                                <td>
                                                    <?= date('d M Y', strtotime($brand['created_at'])) ?>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            No brand added yet
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