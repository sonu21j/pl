<?php
session_start();

define('BASE_PATH', dirname(__DIR__));
define('ENVIRONMENT', 'development');

if (file_exists(BASE_PATH . '/install/installation.lock') === false) {
    header("Location: /install/index.php");
    exit;
}

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/CSRF.php';

$router = new Router();
require_once BASE_PATH . '/routes.php';
$router->dispatch();
?>
