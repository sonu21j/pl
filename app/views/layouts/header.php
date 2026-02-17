<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Allocation</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <span>🔹 PAMS</span>
        </div>
        
        <nav>
            <a href="/dashboard" class="nav-link <?= $_SERVER['REQUEST_URI'] == '/dashboard' ? 'active' : '' ?>">Dashboard</a>
            
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="/users" class="nav-link">User Management</a>
                <a href="/settings" class="nav-link">Company Settings</a>
            <?php endif; ?>

            <?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager'): ?>
                <a href="/projects" class="nav-link">Projects</a>
                <a href="/allocations" class="nav-link">Allocations</a>
            <?php endif; ?>

            <?php if($_SESSION['role'] === 'tester'): ?>
                <a href="/my-allocations" class="nav-link">My Allocations</a>
            <?php endif; ?>
        </nav>

        <div class="user-profile">
            <strong><?= htmlspecialchars($_SESSION['name']) ?></strong><br>
            <span style="color: #666; text-transform: capitalize;"><?= $_SESSION['role'] ?></span>
            <br><br>
            <a href="/auth/logout" style="color: var(--danger); text-decoration: none;">Log Out</a>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h3><?= isset($title) ? $title : 'Dashboard' ?></h3>
            <div><?= date('D, M j, Y') ?></div>
        </div>

        <!-- Dynamic Content loads here -->
        <div class="page-content">