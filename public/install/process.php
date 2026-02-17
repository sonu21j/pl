<?php
session_start();

// Security: CSRF Check
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF Token validation failed.");
    }
} else {
    header("Location: index.php");
    exit;
}

$step = (int)$_POST['step'];

try {
    switch ($step) {
        case 1: // Store Company Info
            if(empty($_POST['company_name']) || empty($_POST['company_email'])) {
                throw new Exception("Company Name and Email are required.");
            }
            $_SESSION['install']['company'] = [
                'name' => htmlspecialchars($_POST['company_name']),
                'email' => htmlspecialchars($_POST['company_email']),
                'timezone' => htmlspecialchars($_POST['timezone']),
            ];
            header("Location: index.php?step=2");
            break;

        case 2: // DB Connection & Schema Creation
            $host = $_POST['db_host'];
            $user = $_POST['db_user'];
            $pass = $_POST['db_pass'];
            $name = $_POST['db_name'];
            $create = isset($_POST['create_db']);

            // 1. Test Connection (No DB selected yet)
            try {
                $pdo = new PDO("mysql:host=$host", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new Exception("Connection Failed: " . $e->getMessage());
            }

            // 2. Create Database
            if($create) {
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }

            // 3. Connect to specific DB
            $pdo->exec("USE `$name`");

            // 4. Create Tables (Schema)
            $sql = getSchema();
            $pdo->exec($sql);

            // 5. Insert Company Settings
            $stmt = $pdo->prepare("INSERT INTO company_settings (company_name, company_email, timezone, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([
                $_SESSION['install']['company']['name'],
                $_SESSION['install']['company']['email'],
                $_SESSION['install']['company']['timezone']
            ]);

            // Save DB credentials to session for final step
            $_SESSION['install']['db'] = [
                'host' => $host,
                'user' => $user,
                'pass' => $pass,
                'name' => $name
            ];

            header("Location: index.php?step=3");
            break;

        case 3: // Create Admin & Generate Files
            $dbConfig = $_SESSION['install']['db'];
            
            // Connect
            $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset=utf8mb4";
            $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insert Admin
            $passHash = password_hash($_POST['admin_pass'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, role, status, created_at) VALUES (?, ?, ?, ?, 'admin', 'active', NOW())");
            $stmt->execute([
                $_POST['admin_fname'],
                $_POST['admin_lname'],
                $_POST['admin_email'],
                $passHash
            ]);

            // Generate Config File
            $configFileContent = "<?php\n\n";
            $configFileContent .= "define('DB_HOST', '{$dbConfig['host']}');\n";
            $configFileContent .= "define('DB_USER', '{$dbConfig['user']}');\n";
            $configFileContent .= "define('DB_PASS', '{$dbConfig['pass']}');\n";
            $configFileContent .= "define('DB_NAME', '{$dbConfig['name']}');\n";
            $configFileContent .= "define('APP_ENV', 'production');\n";
            
            file_put_contents('../../config/database.php', $configFileContent);

            // Create Lock File
            file_put_contents('../../config/installed.lock', 'INSTALLED on ' . date('Y-m-d H:i:s'));

            // Destroy Install Session
            session_destroy();

            // Redirect to Login
            echo "<!DOCTYPE html><html><head><link rel='stylesheet' href='css/style.css'></head><body><div class='installer-container'><h1>Installation Complete!</h1><p class='success-msg'>The system has been installed successfully.</p><a href='/login' class='btn'>Go to Login</a></div></body></html>";
            break;
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: index.php?step=$step");
}

// Helper: Returns SQL Schema
function getSchema() {
    return "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(150) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('tester','manager','admin') NOT NULL,
        department VARCHAR(100),
        designation VARCHAR(100),
        status ENUM('active','disabled') DEFAULT 'active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        project_name VARCHAR(150) NOT NULL,
        project_code VARCHAR(50) NOT NULL,
        platform VARCHAR(50),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS allocations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        project_id INT NOT NULL,
        tester_id INT NOT NULL,
        scope ENUM('FQA','QA','Anya') NOT NULL,
        platform VARCHAR(50),
        shift_time VARCHAR(50),
        allocation_date DATE NOT NULL,
        hours DECIMAL(4,2) NOT NULL,
        allocation_type ENUM('Billed','Unbilled','Overtime') NOT NULL,
        created_by INT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
        FOREIGN KEY (tester_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS correction_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        allocation_id INT NOT NULL,
        tester_id INT NOT NULL,
        comment TEXT,
        status ENUM('pending','approved','rejected') DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (allocation_id) REFERENCES allocations(id) ON DELETE CASCADE,
        FOREIGN KEY (tester_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS company_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        company_name VARCHAR(150),
        company_email VARCHAR(150),
        timezone VARCHAR(50),
        logo_path VARCHAR(255),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS activity_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        action VARCHAR(100),
        description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    ";
}
?>