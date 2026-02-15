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
	
	public function report() {

		if (!in_array($_SESSION['user']['role'], ['admin','manager'])) {
			die("Access denied");
		}

		$db = Database::connect();

		$stmt = $db->query("
			SELECT 

				p.project_name,

				a.platform,

				a.billing_type,

				SUM(a.hours) AS total_hours,

				br.rate_per_hour,

				br.currency,

				SUM(a.hours * br.rate_per_hour) AS total_amount

			FROM allocations a

			JOIN allocation_batches ab
				ON ab.id = a.batch_id

			JOIN projects p
				ON p.id = ab.project_id

			JOIN billing_rates br
				ON br.project_id = ab.project_id
				AND br.platform = a.platform
				AND br.billing_type = a.billing_type

			GROUP BY
				ab.project_id,
				a.platform,
				a.billing_type

			ORDER BY
				p.project_name,
				a.platform
		");

		$report = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$this->view('billing/report', [
			'report' => $report
		]);
	}


}
