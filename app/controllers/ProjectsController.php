<?php
use Core\Controller;
use Core\Database;

class ProjectsController extends Controller {

    public function __construct() {
        // Restrict access to Admins and Managers only
        if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
            header("Location: /dashboard");
            exit;
        }
    }

    // List all projects
    public function index() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM projects ORDER BY created_at DESC");
        $projects = $stmt->fetchAll();

        $this->view('projects/index', ['projects' => $projects]);
    }

    // Show Create Form
    public function create() {
        $this->view('projects/create');
    }

    // Store New Project
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getInstance();
            
            // Validation
            if (empty($_POST['project_name']) || empty($_POST['project_code'])) {
                $this->view('projects/create', ['error' => 'Project Name and Code are required']);
                return;
            }

            try {
                $stmt = $db->prepare("INSERT INTO projects (project_name, project_code, platform, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->execute([
                    $_POST['project_name'],
                    strtoupper($_POST['project_code']), // Force uppercase code
                    $_POST['platform']
                ]);
                header("Location: /projects");
            } catch (Exception $e) {
                $this->view('projects/create', ['error' => 'Error: ' . $e->getMessage()]);
            }
        }
    }
}