<?php
class ProjectController extends Controller {

    public function index() {

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        $db = Database::connect();

        $stmt = $db->query("
			SELECT 
				p.*,

				COALESCE(
					(
						SELECT SUM(a.hours)
						FROM allocations a
						JOIN allocation_batches ab 
							ON ab.id = a.batch_id
						WHERE ab.project_id = p.id
					), 0
				) AS used_hours

			FROM projects p
			ORDER BY p.id DESC
		");


        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('project/index', ['projects' => $projects]);
    }


    public function create() {

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $db = Database::connect();

            $name = $_POST['project_name'];
            $code = $_POST['project_code'];
            $platform = $_POST['platform'];

            $stmt = $db->prepare("
			INSERT INTO projects
			(project_name, project_code, platform, client_allocated_hours, warning_threshold_percent, created_at)
			VALUES (?, ?, ?, ?, ?, NOW())
			");

			$stmt->execute([
			$name,
			$code,
			$platform,
			$_POST['client_allocated_hours'],
			$_POST['warning_threshold_percent']
			]);


            header("Location: index.php?url=project/index");
            exit;
        }

        $this->view('project/create');
    }


    public function edit() {

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        $db = Database::connect();

        $id = $_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = $_POST['project_name'];
            $code = $_POST['project_code'];
            $platform = $_POST['platform'];

            $stmt = $db->prepare("
                UPDATE projects
                SET project_name=?, project_code=?, platform=?
                WHERE id=?
            ");

            $stmt->execute([$name, $code, $platform, $id]);

            header("Location: index.php?url=project/index");
            exit;
        }

        $stmt = $db->prepare("SELECT * FROM projects WHERE id=?");
        $stmt->execute([$id]);

        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->view('project/edit', ['project' => $project]);
    }


    public function delete() {

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        $db = Database::connect();

        $id = $_GET['id'];

        $stmt = $db->prepare("DELETE FROM projects WHERE id=?");

        $stmt->execute([$id]);

        header("Location: index.php?url=project/index");
        exit;
    }

}
?>
