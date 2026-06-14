<?php
session_start();

if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
    header('Location: index.php');
    exit();
}

include("include/connect.php");

// // Define allowed store sections
// define('STORE_SECTIONS', ['labstore', 'medstore', 'stationerystore', 'electricalstore', 'hardwarestore', 'civilstore', 'healthstore']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD Reserved Item Activation System</title>
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: var(--card-shadow);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .main-container {
            padding: 2rem 0;
            min-height: calc(100vh - 76px);
        }

        .activation-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.2"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        }

        .card-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .card-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin: 2rem 0;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            background: #e5e7eb;
            color: #6b7280;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .step.active {
            background: var(--primary-color);
            color: white;
        }

        .step.completed {
            background: var(--success-color);
            color: white;
        }

        .store-selection {
            padding: 2rem;
        }

        .store-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .store-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .store-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .store-card:hover::before {
            left: 100%;
        }

        .store-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.2);
        }

        .store-card.selected {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        .store-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .store-card:hover .store-icon {
            transform: scale(1.1);
        }

        .store-name {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .store-description {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }

        .items-section {
            padding: 2rem;
            display: none;
        }

        .items-section.show {
            display: block;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .items-title {
            color: var(--secondary-color);
            font-weight: 600;
            margin: 0;
        }

        .search-filter {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .items-table {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: #f8fafc;
            border: none;
            font-weight: 600;
            color: var(--secondary-color);
            padding: 1rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f4;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .item-code {
            background: #e0e7ff;
            color: #3730a3;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .btn-activate {
            background: linear-gradient(135deg, var(--success-color) 0%, #047857 100%);
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(5, 150, 105, 0.3);
        }

        .btn-activate:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(5, 150, 105, 0.4);
            color: white;
        }

        .btn-activate.activated {
            background: #6b7280;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .loading-spinner {
            text-align: center;
            padding: 2rem;
        }

        .alert {
            margin: 1rem 0;
        }

        @media (max-width: 768px) {
            .store-grid {
                grid-template-columns: 1fr;
            }
            
            .items-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-filter {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-user-tie me-2"></i>
                HOD Management Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="fas fa-home me-1"></i>Dashboard</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-box-open me-1"></i>Item Activation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-chart-bar me-1"></i>Reports</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user me-1"></i><?php  echo $_SESSION['fullname'];?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container main-container">
        <div class="activation-card">
            <!-- Card Header -->
            <div class="card-header">
                <h1><i class="fas fa-unlock-alt me-2"></i>HOD Reserved Item Activation System</h1>
                <p>Manage and activate reserved items for your department (Items past 6 MONTHS)</p>
            </div>

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step active" id="step1">
                    <i class="fas fa-store"></i>
                    <span>Select Store</span>
                </div>
                <div class="step" id="step2">
                    <i class="fas fa-list"></i>
                    <span>Review Items</span>
                </div>
                <div class="step" id="step3">
                    <i class="fas fa-check"></i>
                    <span>Activate</span>
                </div>
            </div>

            <!-- Store Selection Section -->
            <div class="store-selection" id="storeSelection">
                <h3 class="text-center mb-4">
                    <i class="fas fa-building me-2"></i>
                    Select Store Department
                </h3>

                <div class="store-grid">
                    <div class="store-card" data-store="labstore">
                        <div class="store-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h4 class="store-name">Lab Store</h4>
                        <p class="store-description">Laboratory equipment and scientific instruments</p>
                    </div>

                    <div class="store-card" data-store="medstore">
                        <div class="store-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <h4 class="store-name">Medical Store</h4>
                        <p class="store-description">Medical supplies and pharmaceutical items</p>
                    </div>

                    <div class="store-card" data-store="stationerystore">
                        <div class="store-icon">
                            <i class="fas fa-pen"></i>
                        </div>
                        <h4 class="store-name">Stationery Store</h4>
                        <p class="store-description">Office supplies and stationery items</p>
                    </div>

                    <div class="store-card" data-store="electricalstore">
                        <div class="store-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h4 class="store-name">Electrical Store</h4>
                        <p class="store-description">Electrical components and equipment</p>
                    </div>

                    <div class="store-card" data-store="hardwarestore">
                        <div class="store-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h4 class="store-name">Hardware Store</h4>
                        <p class="store-description">Hardware tools and mechanical equipment</p>
                    </div>

                    <div class="store-card" data-store="civilstore">
                        <div class="store-icon">
                            <i class="fas fa-hard-hat"></i>
                        </div>
                        <h4 class="store-name">Civil Store</h4>
                        <p class="store-description">Construction materials and civil engineering supplies</p>
                    </div>

                    <div class="store-card" data-store="healthstore">
                        <div class="store-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h4 class="store-name">Health Store</h4>
                        <p class="store-description">Health and safety equipment</p>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="items-section" id="itemsSection">
                <div class="items-header">
                    <h3 class="items-title">
                        <i class="fas fa-list-ul me-2"></i>
                        Reserved Items - <span id="selectedStoreName">Store</span>
                        <small class="text-muted">(Past 6 months)</small>
                    </h3>
                    <div class="search-filter">
                        <div class="input-group" style="max-width: 300px;">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search items..." id="searchInput">
                        </div>
                    </div>
                </div>

                <!-- Loading indicator -->
                <div id="loadingIndicator" class="loading-spinner" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading reserved items...</p>
                </div>

                <!-- Table Container -->
                <div class="items-table">
                    <div id="tableContainer">
                        <!-- Table will be loaded here via AJAX -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <button class="btn btn-secondary me-2" onclick="goBackToStores()">
                        <i class="fas fa-arrow-left me-1"></i>Back to Stores
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let selectedStore = null;

        // Store selection functionality
        $(document).ready(function() {
            $('.store-card').on('click', function() {
                const store = $(this).data('store');
                const storeName = $(this).find('.store-name').text();
                
                if (!store) return;

                // Update UI
                $('.store-card').removeClass('selected');
                $(this).addClass('selected');
                selectedStore = store;

                // Update step indicator
                $('#step1').removeClass('active').addClass('completed');
                $('#step2').addClass('active');

                // Show items section
                $('#storeSelection').hide();
                $('#itemsSection').addClass('show');
                $('#selectedStoreName').text(storeName);

                // Load items for this store
                loadStoreItems(store);
            });

            // Search functionality
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('#tableContainer table tbody tr').each(function() {
                    const itemCode = $(this).find('td:first').text().toLowerCase();
                    const itemName = $(this).find('td:nth-child(2)').text().toLowerCase();
                    
                    if (itemCode.includes(searchTerm) || itemName.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });

        function loadStoreItems(store) {
            $('#loadingIndicator').show();
            $('#tableContainer').html('');

            $.post('activate_reserved.php', { store: store }, function(response) {
                $('#loadingIndicator').hide();
                $('#tableContainer').html(response);
            }).fail(function(xhr, status, error) {
                $('#loadingIndicator').hide();
                $('#tableContainer').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error loading items: ${error}. Please try again.
                    </div>
                `);
            });
        }

        function goBackToStores() {
            $('#step2').removeClass('active');
            $('#step1').removeClass('completed').addClass('active');
            
            $('#itemsSection').removeClass('show');
            $('#storeSelection').show();
            
            $('.store-card').removeClass('selected');
            selectedStore = null;
            $('#tableContainer').html('');
        }

        function activateItem(id, itemcode, qty, dept, storeTable, sourceTable) {
            if (!confirm('Are you sure you want to activate this reserved item?')) return;

            // Show loading indicator on button
            const button = $(`#row_${id} button`);
            button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

            $.post('activate_reserved.php', {
                action: 'activate_reserved',
                reserved_id: id,
                itemcode: itemcode,
                reservedquantity: qty,
                department: dept,
                storeTable: storeTable,
                sourceTable: sourceTable
            }, function(response) {
                console.log('Server response:', response);
                
                if (response.status === 'success') {
                    // Add success visual indicator
                    $(`#row_${id}`).addClass('table-success');
                    
                    // Show success message
                    $('#tableContainer').prepend(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    
                    // Remove row after delay
                    setTimeout(function() {
                        $(`#row_${id}`).fadeOut('slow', function() {
                            $(this).remove();
                            
                            // Check if no more rows
                            if ($('#tableContainer table tbody tr:visible').length === 0) {
                                $('#tableContainer').html(`
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        All reserved items have been activated.
                                    </div>
                                `);
                            }
                        });
                    }, 1500);
                } else {
                    // Re-enable button on error
                    button.prop('disabled', false).html('Activate');
                    
                    $('#tableContainer').prepend(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Activation failed: ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            }, 'json').fail(function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                
                // Re-enable button on AJAX failure
                button.prop('disabled', false).html('Activate');
                
                $('#tableContainer').prepend(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Network error occurred. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            });
        }
    </script>
</body>
</html>