<?php
class UserController extends Controller {

    public function index() {

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        // Only admin allowed
        if ($_SESSION['user']['role'] !== 'admin') {
            echo "Access denied";
            exit;
        }

        $db = Database::connect();

        $stmt = $db->query("SELECT id, first_name, last_name, email, role, status FROM users ORDER BY id DESC");

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('user/index', ['users' => $users]);
    }
	public function create() {

		if (!isset($_SESSION['user'])) {
			header("Location: index.php?url=auth/login");
			exit;
		}

		if ($_SESSION['user']['role'] !== 'admin') {
			echo "Access denied";
			exit;
		}

		// If form submitted
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$db = Database::connect();

			$first = $_POST['first_name'];
			$last = $_POST['last_name'];
			$email = $_POST['email'];
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$role = $_POST['role'];

			$stmt = $db->prepare("
				INSERT INTO users 
				(first_name, last_name, email, password, role, status, created_at)
				VALUES (?, ?, ?, ?, ?, 'active', NOW())
			");

			$stmt->execute([$first, $last, $email, $password, $role]);

			header("Location: index.php?url=user/index");
			exit;
		}

		// Show form
		$this->view('user/create');
	}
	
	public function edit() {

		if (!isset($_SESSION['user'])) {
			header("Location: index.php?url=auth/login");
			exit;
		}

		if ($_SESSION['user']['role'] !== 'admin') {
			echo "Access denied";
			exit;
		}

		$db = Database::connect();

		$id = $_GET['id'];

		// If form submitted → update
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$first = $_POST['first_name'];
			$last = $_POST['last_name'];
			$email = $_POST['email'];
			$role = $_POST['role'];
			$status = $_POST['status'];

			$stmt = $db->prepare("
				UPDATE users
				SET first_name=?, last_name=?, email=?, role=?, status=?
				WHERE id=?
			");

			$stmt->execute([$first, $last, $email, $role, $status, $id]);

			header("Location: index.php?url=user/index");
			exit;
		}

		// Load existing user
		$stmt = $db->prepare("SELECT * FROM users WHERE id=?");
		$stmt->execute([$id]);

		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->view('user/edit', ['user' => $user]);
	} //edit function ends here
	
	public function delete() {

		if (!isset($_SESSION['user'])) {
			header("Location: index.php?url=auth/login");
			exit;
		}

		if ($_SESSION['user']['role'] !== 'admin') {
			echo "Access denied";
			exit;
		}

		$db = Database::connect();

		$id = $_GET['id'];

		$stmt = $db->prepare("DELETE FROM users WHERE id=?");

		$stmt->execute([$id]);

		header("Location: index.php?url=user/index");
		exit;
	}//delete function ends here



}//class ends here


?>
