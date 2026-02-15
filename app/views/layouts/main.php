<!DOCTYPE html>
<html>
<head>
    <title>Project Allocation</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-left">
        <span class="logo">Project Allocation</span>
    </div>
    <div class="nav-right">
        <span><?php echo $_SESSION['user']['first_name'] ?? ''; ?></span>
        <a href="index.php?url=auth/logout">Logout</a>
    </div>
</div>

<div class="sidebar">
    <ul>
        <li><a href="index.php?url=dashboard/index">Dashboard</a></li>

        <?php if($_SESSION['user']['role'] === 'admin'): ?>
			<li>
				<a href="index.php?url=allocation/index">
					Allocations
				</a>
			</li>
            <li><a href="index.php?url=user/index">Manage Users</a></li>
            <li><a href="index.php?url=project/index">Projects</a></li>
			<li><a href="#">Company Settings</a></li>
			

        <?php endif; ?>

        <?php if($_SESSION['user']['role'] === 'manager'): ?>
            <li><a href="#">Allocations</a></li>
        <?php endif; ?>

        <?php if($_SESSION['user']['role'] === 'tester'): ?>
            <li><a href="#">My Allocations</a></li>
        <?php endif; ?>
    </ul>
</div>

<div class="content">
    <?php echo $content; ?>
</div>

</body>
</html>
