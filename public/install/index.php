<?php
session_start();
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Installation - Step <?= $step ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="installer-container">
        <h1>Project Allocation System Setup</h1>
        
        <div class="step-indicator">
            <div class="step <?= $step >= 1 ? 'active' : '' ?>"></div>
            <div class="step <?= $step >= 2 ? 'active' : '' ?>"></div>
            <div class="step <?= $step >= 3 ? 'active' : '' ?>"></div>
            <div class="step <?= $step >= 4 ? 'active' : '' ?>"></div>
        </div>

        <?php if($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form action="process.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] = bin2hex(random_bytes(32)) ?>">
            <input type="hidden" name="step" value="<?= $step ?>">

            <!-- STEP 1: Company Config -->
            <?php if($step == 1): ?>
                <h2>Step 1: Company Configuration</h2>
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="company_name" required value="<?= $_SESSION['install']['company_name'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Company Email</label>
                    <input type="email" name="company_email" required value="<?= $_SESSION['install']['company_email'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Timezone</label>
                    <select name="timezone">
                        <option value="UTC">UTC</option>
                        <option value="America/New_York">America/New_York</option>
                        <option value="Europe/London">Europe/London</option>
                        <option value="Asia/Kolkata">Asia/Kolkata</option>
                    </select>
                </div>
                <button type="submit" class="btn">Next: Database &rarr;</button>

            <!-- STEP 2: Database Config -->
            <?php elseif($step == 2): ?>
                <h2>Step 2: Database Connection</h2>
                <div class="form-group">
                    <label>Database Host</label>
                    <input type="text" name="db_host" value="localhost" required>
                </div>
                <div class="form-group">
                    <label>Database Username</label>
                    <input type="text" name="db_user" value="root" required>
                </div>
                <div class="form-group">
                    <label>Database Password</label>
                    <input type="password" name="db_pass">
                </div>
                <div class="form-group">
                    <label>Database Name</label>
                    <input type="text" name="db_name" value="pams_db" required>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="create_db" value="1" checked style="width:auto;"> 
                        Create Database if not exists
                    </label>
                </div>
                <button type="submit" class="btn">Next: Build System &rarr;</button>

            <!-- STEP 3: Admin Setup -->
            <?php elseif($step == 3): ?>
                <h2>Step 3: Super Admin Account</h2>
                <div class="success-msg">Database connected and tables created successfully!</div>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="admin_fname" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="admin_lname" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="admin_email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="admin_pass" required>
                </div>
                <button type="submit" class="btn">Finish Installation &rarr;</button>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>