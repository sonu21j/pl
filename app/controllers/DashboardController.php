<?php
class DashboardController extends Controller {

    public function index() {

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        $data = [
            'name' => $_SESSION['user']['first_name']
        ];

        $this->view('dashboard/index', $data);
    }

}
?>
