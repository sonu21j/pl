<?php

class BillingController extends Controller {

    private function authorize() {

        if (!in_array($_SESSION['user']['role'], ['admin','manager'])) {

            die("Access denied");

        }

    }


    public function index() {

        $this->authorize();

        $db = Database::connect();

        $rates = $db->query("
            SELECT br.*, p.project_name
            FROM billing_rates br
            JOIN projects p ON p.id = br.project_id
            ORDER BY p.project_name
        ")->fetchAll(PDO::FETCH_ASSOC);


        $this->view('billing/index', [
            'rates' => $rates
        ]);
    }


    public function create() {

        $this->authorize();

        $db = Database::connect();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $stmt = $db->prepare("
                INSERT INTO billing_rates
                (project_id, platform, billing_type, rate_per_hour, currency)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $_POST['project_id'],
                $_POST['platform'],
                $_POST['billing_type'],
                $_POST['rate_per_hour'],
                $_POST['currency']
            ]);

            header("Location: index.php?url=billing/index");
            exit;
        }


        $projects = $db->query("
            SELECT * FROM projects
        ")->fetchAll(PDO::FETCH_ASSOC);


        $this->view('billing/create', [
            'projects' => $projects
        ]);
    }

}
