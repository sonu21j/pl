<?php
class AuthController extends Controller {
    public function login() {

    // If already logged in
    if (isset($_SESSION['user'])) {
        header("Location: index.php?url=dashboard/index");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email=? AND status='active'");
        $stmt->execute([$_POST['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($_POST['password'], $user['password'])) {

			session_regenerate_id(true); // 🔐 Prevent session fixation

			$_SESSION['user'] = $user;

			header("Location: index.php?url=dashboard/index");
			exit;
		}


        echo "Invalid Credentials";
    }

    // IMPORTANT: load view directly (not using $this->view())
    require BASE_PATH . '/app/views/auth/login.php';
}


    public function logout() {
        session_destroy();
        header("Location: index.php?url=auth/login");
    }
}
?>
