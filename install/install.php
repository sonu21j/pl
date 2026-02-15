<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $host = $_POST['db_host'];
    $port = $_POST['db_port'];
    $user = $_POST['db_user'];
    $pass = $_POST['db_pass'];
    $dbname = $_POST['db_name'];

    try {
        $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
        $pdo->exec("USE `$dbname`");

        $schema = file_get_contents("schema.sql");
        $pdo->exec($schema);

        $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (first_name,last_name,email,password,role,status,created_at)
            VALUES (?,?,?,?, 'admin','active',NOW())");
        $stmt->execute([$_POST['first_name'], $_POST['last_name'], $_POST['email'], $hashed]);

        $config = str_replace(
            ["HOST","DBNAME","PORT","USER","PASS"],
            [$host,$dbname,$port,$user,$pass],
            file_get_contents("../config/database.template.php")
        );
        file_put_contents("../config/database.php", $config);

        file_put_contents("installation.lock", "installed");

        header("Location: ../public/index.php");
    } catch (PDOException $e) {
        echo "Installation Failed: " . $e->getMessage();
    }
}
?>
