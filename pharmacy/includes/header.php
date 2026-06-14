<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Inventory System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="/assets/css/styles.css"> -->
    <link rel="stylesheet" href="<?= '/pharmacy/assets/css/styles.css' ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script src="< ?= BASE_URL ?>/assets/js/script.js"></script> -->
</head>
<body>

<!-- TOP NAVBAR HEADER -->
<nav class="navbar">
    <div class="d-flex align-items-center justify-content-between w-100">
        <!-- Left: Logo and Brand -->
        <div class="d-flex align-items-center">
            <i class="fas fa-pills me-2" style="font-size: 1.75rem;"></i>
            <span class="navbar-brand m-0">Pharmacy Inventory</span>
        </div>

        <!-- Center: System Status -->
        <div class="navbar-text d-none d-lg-block">
            <small class="text-white-50">
                <i class="fas fa-circle me-1" style="color: #27ae60;"></i>
                System Online
            </small>
        </div>

        <!-- Right: User Info and Actions -->
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-light d-none d-md-inline-block" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <a href="../../modules/sales/pos.php" class="btn btn-sm btn-light" title="Open Point of Sale">
                <i class="fas fa-cash-register me-1"></i>
                <span class="d-none d-md-inline">POS</span>
            </a>
            <div class="vr" style="height: 30px; background-color: rgba(255,255,255,0.2);"></div>
            <div class="navbar-user">
                <div class="user-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="d-none d-md-block">
                    <div class="navbar-text m-0" style="font-size: 0.9rem;">
                        Welcome
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
