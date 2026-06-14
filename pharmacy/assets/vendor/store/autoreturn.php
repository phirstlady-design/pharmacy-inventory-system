<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Return Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #007bff;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
        .table-container {
            overflow-x: auto;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeaa7;
        }
        .loading {
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Auto Return Dashboard</h1>
            <p>Monitor and manage automatic item returns</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" id="totalAutoReturns">-</div>
                <div>Total Auto Returns (30 days)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="totalQuantityReturned">-</div>
                <div>Total Quantity Returned</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="itemsAboutToExpire">-</div>
                <div>Items About to Expire</div>
            </div>
        </div>

        <div class="alert alert-warning" id="expiringItemsAlert" style="display: none;">
            <strong>Warning!</strong> Some items are about to expire (within 2 hours).
        </div>

        <button class="btn" onclick="runAutoReturn()">Run Auto Return Now</button>
        <button class="btn btn-warning" onclick="loadData()">Refresh Data</button>

        <h3>Items About to Expire (Next 2 Hours)</h3>
        <div class="table-container">
            <table id="expiringItemsTable">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Item Name</th>
                        <th>Quantity Released</th>
                        <th>Release Date</th>
                        <th>Hours Elapsed</th>
                        <th>Time Remaining</th>
                    </tr>
                </thead>
                <tbody id="expiringItemsBody">
                    <tr><td colspan="6" class="loading">Loading...</td></tr>
                </tbody>
            </table>
        </div>

        <h3>Auto Return Statistics (Last 30 Days)</h3>
        <div class="table-container">
            <table id="statsTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Auto Returns</th>
                        <th>Quantity Returned</th>
                    </tr>
                </thead>
                <tbody id="statsBody">
                    <tr><td colspan="3" class="loading">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="dashboard.js"></script>
</body>
</html>
