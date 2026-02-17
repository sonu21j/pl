<?php
session_start();

// Define Root Path
define('ROOT_PATH', dirname(__DIR__));

// Check if installed
if (!file_exists(ROOT_PATH . '/config/database.php') || !file_exists(ROOT_PATH . '/config/installed.lock')) {
    header("Location: /install/");
    exit;
}

// Load Core System
require_once ROOT_PATH . '/core/App.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';

// Initialize App
$app = new Core\App();