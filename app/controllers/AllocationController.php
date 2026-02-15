<?php
class AllocationController extends Controller {



	public function create() {

		if (!isset($_SESSION['user'])) {
			header("Location: index.php?url=auth/login");
			exit;
		}

		$db = Database::connect();

		// SAVE LOGIC
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$project_id = $_POST['project_id'];
			$allocation_date = $_POST['allocation_date'];
			$shift_time = $_POST['shift_time'];
			$created_by = $_SESSION['user']['id'];

			// 1. Insert batch
			$stmt = $db->prepare("
				INSERT INTO allocation_batches
				(project_id, allocation_date, shift_time, created_by)
				VALUES (?, ?, ?, ?)
			");

			$stmt->execute([
				$project_id,
				$allocation_date,
				$shift_time,
				$created_by
			]);

			// 2. Get batch_id
			$batch_id = $db->lastInsertId();


		   // 3. Insert testers

			$tester_ids = $_POST['tester_id'];
			$platforms = $_POST['platform'];
			$hours = $_POST['hours'];
			$billing_types = $_POST['billing_type'];
			$scopes = $_POST['scope'] ?? [];

			$stmt = $db->prepare("
				INSERT INTO allocations
				(batch_id, tester_id, scope, platform, hours, billing_type)
				VALUES (?, ?, ?, ?, ?, ?)
			");

			// Duplicate prevention tracker
			$seen = [];

			for ($i = 0; $i < count($tester_ids); $i++) {

				if (empty($tester_ids[$i])) continue;

				$scope_value = $scopes[$i] ?? 'QA';

				// Create unique key
				$key = $tester_ids[$i] . '-' .
					   $platforms[$i] . '-' .
					   $billing_types[$i] . '-' .
					   $scope_value;

				// Check duplicate
				if (isset($seen[$key])) {

					die("Duplicate tester entry detected for same tester, platform, scope and billing type.");

				}

				// Mark as seen
				$seen[$key] = true;

				// Insert record
				$stmt->execute([
					$batch_id,
					$tester_ids[$i],
					$scope_value,
					$platforms[$i],
					$hours[$i],
					$billing_types[$i]
				]);
			}


			header("Location: index.php?url=allocation/index");
			exit;
		}


		// LOAD FORM DATA

		$projects = $db->query("
			SELECT * FROM projects
			ORDER BY project_name
		")->fetchAll(PDO::FETCH_ASSOC);


		$testers = $db->query("
			SELECT id, first_name, last_name
			FROM users
			WHERE role='tester' AND status='active'
			ORDER BY first_name
		")->fetchAll(PDO::FETCH_ASSOC);


		$this->view('allocation/create', [
			'projects'=>$projects,
			'testers'=>$testers
		]);
	}

	public function details() {

		if (!isset($_SESSION['user'])) {
			header("Location: index.php?url=auth/login");
			exit;
		}

		$db = Database::connect();

		$batch_id = $_GET['id'];

		$stmt = $db->prepare("
			SELECT ab.*,
				   p.project_name,
				   u.first_name,
				   u.last_name
			FROM allocation_batches ab
			JOIN projects p ON p.id = ab.project_id
			JOIN users u ON u.id = ab.created_by
			WHERE ab.id=?
		");

		$stmt->execute([$batch_id]);

		$batch = $stmt->fetch(PDO::FETCH_ASSOC);


		$stmt = $db->prepare("
			SELECT a.*,
				   u.first_name,
				   u.last_name
			FROM allocations a
			JOIN users u ON u.id = a.tester_id
			WHERE a.batch_id=?
		");

		$stmt->execute([$batch_id]);

		$testers = $stmt->fetchAll(PDO::FETCH_ASSOC);


		$this->view('allocation/view', [
			'batch'=>$batch,
			'testers'=>$testers
		]);
	}
	public function delete() {

		if (!isset($_SESSION['user'])) {
			header("Location: index.php?url=auth/login");
			exit;
		}

		$db = Database::connect();

		$batch_id = $_GET['id'];

		// delete child records first
		$stmt = $db->prepare("
			DELETE FROM allocations
			WHERE batch_id=?
		");

		$stmt->execute([$batch_id]);

		// delete batch
		$stmt = $db->prepare("
			DELETE FROM allocation_batches
			WHERE id=?
		");

		$stmt->execute([$batch_id]);

		header("Location: index.php?url=allocation/index");
		exit;
	}
	public function edit() {

		if (!isset($_SESSION['user'])) {
			header("Location: index.php?url=auth/login");
			exit;
		}

		$db = Database::connect();

		$batch_id = $_GET['id'];

		// SAVE UPDATED DATA
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$project_id = $_POST['project_id'];
			$allocation_date = $_POST['allocation_date'];
			$shift_time = $_POST['shift_time'];

			// update batch
			$stmt = $db->prepare("
				UPDATE allocation_batches
				SET project_id=?, allocation_date=?, shift_time=?
				WHERE id=?
			");

			$stmt->execute([
				$project_id,
				$allocation_date,
				$shift_time,
				$batch_id
			]);


			// delete old testers
			$stmt = $db->prepare("
				DELETE FROM allocations
				WHERE batch_id=?
			");

			$stmt->execute([$batch_id]);


			// insert new testers
			$tester_ids = $_POST['tester_id'];
			$platforms = $_POST['platform'];
			$hours = $_POST['hours'];
			$billing_types = $_POST['billing_type'];
			$scopes = $_POST['scope'];

			$stmt = $db->prepare("
				INSERT INTO allocations
				(batch_id, tester_id, scope, platform, hours, billing_type)
				VALUES (?, ?, ?, ?, ?, ?)
			");

			for ($i=0; $i<count($tester_ids); $i++) {

				if (empty($tester_ids[$i])) continue;

				$stmt->execute([
					$batch_id,
					$tester_ids[$i],
					$scopes[$i],
					$platforms[$i],
					$hours[$i],
					$billing_types[$i]
				]);
			}

			header("Location: index.php?url=allocation/details&id=".$batch_id);
			exit;
		}


		// LOAD DATA

		$projects = $db->query("
			SELECT * FROM projects
		")->fetchAll(PDO::FETCH_ASSOC);


		$testers = $db->query("
			SELECT id, first_name, last_name
			FROM users
			WHERE role='tester'
		")->fetchAll(PDO::FETCH_ASSOC);


		$stmt = $db->prepare("
			SELECT *
			FROM allocation_batches
			WHERE id=?
		");

		$stmt->execute([$batch_id]);

		$batch = $stmt->fetch(PDO::FETCH_ASSOC);


		$stmt = $db->prepare("
			SELECT *
			FROM allocations
			WHERE batch_id=?
		");

		$stmt->execute([$batch_id]);

		$allocations = $stmt->fetchAll(PDO::FETCH_ASSOC);


		$this->view('allocation/edit', [
			'batch'=>$batch,
			'allocations'=>$allocations,
			'projects'=>$projects,
			'testers'=>$testers
		]);
	}

	public function index() {

		if (!isset($_SESSION['user'])) {
			header("Location: index.php?url=auth/login");
			exit;
		}

		$db = Database::connect();

		// Load filter dropdown data
		$projects = $db->query("
			SELECT id, project_name FROM projects
			ORDER BY project_name
		")->fetchAll(PDO::FETCH_ASSOC);


		$testers = $db->query("
			SELECT id, first_name, last_name
			FROM users
			WHERE role='tester'
			ORDER BY first_name
		")->fetchAll(PDO::FETCH_ASSOC);


		// Build filter query
		$where = [];
		$params = [];

		if (!empty($_GET['project_id'])) {
			$where[] = "ab.project_id = ?";
			$params[] = $_GET['project_id'];
		}

		if (!empty($_GET['date_from'])) {
			$where[] = "ab.allocation_date >= ?";
			$params[] = $_GET['date_from'];
		}

		if (!empty($_GET['date_to'])) {
			$where[] = "ab.allocation_date <= ?";
			$params[] = $_GET['date_to'];
		}

		$sql = "
		SELECT 

			ab.id AS batch_id,
			ab.allocation_date,
			ab.shift_time,

			p.project_name,

			u.first_name,
			u.last_name,

			a.hours,
			a.platform,
			a.billing_type

		FROM allocation_batches ab

		JOIN projects p ON p.id = ab.project_id

		JOIN allocations a ON a.batch_id = ab.id

		JOIN users u ON u.id = a.tester_id
		";

		if ($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

		$sql .= " ORDER BY ab.allocation_date DESC, ab.id DESC";

		$stmt = $db->prepare($sql);
		$stmt->execute($params);

		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


		/*
		Group data Jira-style
		*/

		$allocations = [];

		foreach ($rows as $row) {

			$batch_id = $row['batch_id'];

			if (!isset($allocations[$batch_id])) {

				$allocations[$batch_id] = [

					'batch_id' => $batch_id,

					'project_name' => $row['project_name'],
					'date' => $row['allocation_date'],
					'shift' => $row['shift_time'],

					'total_hours' => 0,
					'total_ot' => 0,

					'platforms' => [],
					'testers' => []
				];

			}

			$allocations[$batch_id]['total_hours'] += $row['hours'];

			if ($row['billing_type'] == 'Overtime') {
				$allocations[$batch_id]['total_ot'] += $row['hours'];
			}

			$allocations[$batch_id]['platforms'][$row['platform']] = true;

			$allocations[$batch_id]['testers'][] = $row;
		}


		/* Final calculations */

		foreach ($allocations as &$batch) {

			$batch['total_testers'] = count($batch['testers']);
			$batch['total_platforms'] = count($batch['platforms']);

		}


		$this->view('allocation/index', [
			'allocations' => $allocations,
			'projects' => $projects   // keep for filters
		]);
	}


}// class end
?>
