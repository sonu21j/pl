<?php
use Core\Controller;
use Core\Database;

class UsersController extends Controller {

    // List all users
    public function index() {
        // Only Admin can view
        if ($_SESSION['role'] !== 'admin') {
            header("Location: /dashboard");
            exit;
        }

        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll();

        $this->view('users/index', ['users' => $users]);
    }

    // Show Create User Form
    public function create() {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: /dashboard");
            exit;
        }
        $this->view('users/create');
    }

    // Handle Form Submission
    public function store() {
        if ($_SESSION['role'] !== 'admin') { return; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getInstance();
            
            // Basic Validation
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $this->view('users/create', ['error' => 'Email and Password are required']);
                return;
            }

            // Check if email exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$_POST['email']]);
            if ($stmt->fetch()) {
                $this->view('users/create', ['error' => 'Email already exists']);
                return;
            }

            // Insert User
            $sql = "INSERT INTO users (first_name, last_name, email, password, role, department, designation, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'active', NOW())";
            
            $stmt = $db->prepare($sql);
            try {
                $stmt->execute([
                    $_POST['first_name'],
                    $_POST['last_name'],
                    $_POST['email'],
                    password_hash($_POST['password'], PASSWORD_DEFAULT),
                    $_POST['role'],
                    $_POST['department'],
                    $_POST['designation']
                ]);
                header("Location: /users");
            } catch (Exception $e) {
                $this->view('users/create', ['error' => 'Database Error: ' . $e->getMessage()]);
            }
        }
    }
    
    // Toggle User Status (Enable/Disable)
    public function toggle($id) {
         if ($_SESSION['role'] !== 'admin') return;
         
         $db = Database::getInstance();
         // Get current status
         $stmt = $db->prepare("SELECT status FROM users WHERE id = ?");
         $stmt->execute([$id]);
         $user = $stmt->fetch();
         
         $newStatus = ($user['status'] == 'active') ? 'disabled' : 'active';
         
         $update = $db->prepare("UPDATE users SET status = ? WHERE id = ?");
         $update->execute([$newStatus, $id]);
         
         header("Location: /users");
    }
}