<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pharma POS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #eef6f5;
            font-family: "Segoe UI", Arial, sans-serif;
            color: #1f2937;
        }

        .sidebar {
    width: 265px;
    height: 100vh;
    max-height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: #ffffff;
    border-right: 1px solid #dbeafe;
    box-shadow: 5px 0 25px rgba(15, 23, 42, 0.08);
    padding: 16px 14px;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 1000;
    transition: all 0.3s ease;
}
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #14b8a6;
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #0f766e;
}
.brand {
    background: linear-gradient(135deg, #0f766e, #14b8a6, #0ea5e9);
    color: white;
    border-radius: 18px;
    padding: 14px 10px;
    text-align: center;
    margin-bottom: 16px;
    box-shadow: 0 10px 22px rgba(20, 184, 166, 0.28);
}
        .brand h4 {
            margin: 0;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .brand small {
            opacity: 0.9;
        }

       .menu-label {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 800;
    margin: 12px 10px 6px;
    text-transform: uppercase;
}

        .sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #334155;
    text-decoration: none;
    padding: 10px 12px;
    border-radius: 14px;
    margin-bottom: 5px;
    font-weight: 700;
    transition: all .2s ease;
    white-space: nowrap;
}

        .sidebar a i {
            width: 22px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0f766e;
        }

        .sidebar a:hover {
            background: #ccfbf1;
            color: #0f766e;
        }

        .sidebar a.logout {
            background: #fee2e2;
            color: #b91c1c;
            margin-top: 12px;
        }

        .sidebar a.logout i {
            color: #b91c1c;
        }

        .content {
            margin-left: 250px;
            padding: 26px;
        }

        .topbar {
            background: #ffffff;
            border-radius: 20px;
            padding: 18px 22px;
            margin-bottom: 24px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h5 {
            margin: 0;
            font-weight: 800;
            color: #0f172a;
        }

        .topbar .subtitle {
            font-size: 13px;
            color: #64748b;
        }

        .user-box {
            background: #ecfeff;
            color: #0e7490;
            border: 1px solid #a5f3fc;
            padding: 9px 15px;
            border-radius: 50px;
            font-weight: 700;
        }

        h3 {
            font-weight: 800;
            color: #0f172a;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
            overflow: hidden;
        }

        .card-header {
            border: none;
            font-weight: 800;
            padding: 15px 18px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #0f766e !important;
            color: white;
            border: none;
            padding: 13px;
        }

        .table tbody td {
            padding: 12px;
        }

        .btn {
            border-radius: 12px;
            font-weight: 700;
            padding: 8px 16px;
        }

        .btn-primary,
        .btn-success {
            background: #14b8a6;
            border-color: #14b8a6;
        }

        .btn-primary:hover,
        .btn-success:hover {
            background: #0f766e;
            border-color: #0f766e;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 10px 13px;
            border: 1px solid #cbd5e1;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #14b8a6;
            box-shadow: 0 0 0 0.2rem rgba(20, 184, 166, 0.15);
        }

        .dashboard-card {
            border-radius: 22px;
            color: white;
        }

        .dashboard-card .card-body {
            padding: 22px;
        }

        .dashboard-card h6 {
            font-weight: 700;
            opacity: 0.9;
        }

        .dashboard-card h3 {
            color: white;
            font-size: 30px;
            margin: 0;
        }

        .bg-blue {
            background: linear-gradient(135deg, #0ea5e9, #22d3ee);
        }

        .bg-yellow {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .bg-red {
            background: linear-gradient(135deg, #ef4444, #fb7185);
        }

        .bg-green {
            background: linear-gradient(135deg, #10b981, #2dd4bf);
        }
    </style>
</head>

<body>

<div class="sidebar">
    <div class="brand">
        <h4><i class="bi bi-capsule-pill"></i> Pharma POS</h4>
        <small>Smart Pharmacy System</small>
    </div>

    <div class="menu-label">Main</div>

    <a href="/pharma-pos/views/dashboard.php">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>

    <?php if ($_SESSION['user_role'] == 'admin'): ?>

        <div class="menu-label">Management</div>

        <a href="/pharma-pos/views/medicines/list.php">
            <i class="bi bi-capsule"></i> Medicines
        </a>

        <a href="/pharma-pos/views/suppliers/list.php">
            <i class="bi bi-truck"></i> Suppliers
        </a>

        <a href="/pharma-pos/views/purchases/list.php">
            <i class="bi bi-bag-plus"></i> Purchases
        </a>

        <a href="/pharma-pos/views/users/list.php">
            <i class="bi bi-people"></i> Users
        </a>

        <div class="menu-label">Reports</div>

        <a href="/pharma-pos/views/reports/profit.php">
            <i class="bi bi-graph-up-arrow"></i> Profit Report
        </a>

        <a href="/pharma-pos/views/reports/stock.php">
            <i class="bi bi-box-seam"></i> Stock Report
        </a>

        <a href="/pharma-pos/views/backup.php">
            <i class="bi bi-database-down"></i> Backup
        </a>

    <?php endif; ?>

    <div class="menu-label">Sales</div>

    <a href="/pharma-pos/views/sales/pos.php">
        <i class="bi bi-cart-plus"></i> POS Sale
    </a>

    <a href="/pharma-pos/views/sales/cart.php">
        <i class="bi bi-cart3"></i>
        Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)
    </a>

    <a href="/pharma-pos/views/sales/list.php">
        <i class="bi bi-receipt"></i> Sales Report
    </a>

    <a href="/pharma-pos/views/logout.php" class="logout">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>

<div class="content">

    <div class="topbar">
        <div>
            <h5>Medicine Store</h5>
            <div class="subtitle">Pharmacy sales, stock and reporting dashboard</div>
        </div>

        <div class="user-box">
            <i class="bi bi-person-circle"></i>
            <?= $_SESSION['user_name'] ?? 'User'; ?>
        </div>
    </div>
    
