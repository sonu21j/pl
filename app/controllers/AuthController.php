<?php
use Core\Controller;
use Core\Database;

class AuthController extends Controller {
    
    // Default method: Shows Login Page
    public function index() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            header("Location: /dashboard"); 
            exit;
        }

        // Load the view (located at app/Views/auth/login.php)
        $this->view('auth/login');
    }

    // Handle Login Form Submission
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Get DB Connection
            $db = Database::getInstance();
            
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Prepare Query
            $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            // Verify Password
            if ($user && password_verify($password, $user['password'])) {
                
                if ($user['status'] === 'disabled') {
                    $this->view('auth/login', ['error' => 'Account is disabled. Contact Admin.']);
                    return;
                }

                // Set Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];

                // Log Activity
                $logStmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, created_at) VALUES (?, 'LOGIN', 'User logged in', NOW())");
                $logStmt->execute([$user['id']]);

                // Redirect based on role (or just to dashboard)
                header("Location: /dashboard");
                exit;

            } else {
                $this->view('auth/login', ['error' => 'Invalid email or password.']);
            }
        }
    }

    // Handle Logout
    public function logout() {
        session_destroy();
        header("Location: /");
        exit;
    }
}