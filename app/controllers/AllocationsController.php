<?php
use Core\Controller;
use Core\Database;

class AllocationsController extends Controller {

    public function __construct() {
        // Restrict to Admin/Manager
        if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
            header("Location: /dashboard");
            exit;
        }
    }

    // View All Allocations
    public function index() {
        $db = Database::getInstance();

        // Join Tables to get real names instead of IDs
        $sql = "SELECT a.*, 
                       u.first_name, u.last_name, 
                       p.project_name, p.project_code
                FROM allocations a
                JOIN users u ON a.tester_id = u.id
                JOIN projects p ON a.project_id = p.id
                ORDER BY a.allocation_date DESC, a.created_at DESC";

        $stmt = $db->query($sql);
        $allocations = $stmt->fetchAll();

        $this->view('allocations/index', ['allocations' => $allocations]);
    }

    // Show Allocation Form
    public function create() {
        $db = Database::getInstance();

        // 1. Get Active Testers for Dropdown
        $testers = $db->query("SELECT id, first_name, last_name FROM users WHERE role = 'tester' AND status = 'active' ORDER BY first_name ASC")->fetchAll();

        // 2. Get Projects for Dropdown
        $projects = $db->query("SELECT id, project_name FROM projects ORDER BY project_name ASC")->fetchAll();

        $this->view('allocations/create', [
            'testers' => $testers, 
            'projects' => $projects
        ]);
    }

    // Save Allocation
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getInstance();
            
            try {
                $stmt = $db->prepare("INSERT INTO allocations (
                    project_id, tester_id, scope, platform, 
                    shift_time, allocation_date, hours, allocation_type, 
                    created_by, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

                $stmt->execute([
                    $_POST['project_id'],
                    $_POST['tester_id'],
                    $_POST['scope'],
                    $_POST['platform'],
                    $_POST['shift_time'],
                    $_POST['allocation_date'],
                    $_POST['hours'],
                    $_POST['allocation_type'],
                    $_SESSION['user_id'] // Who created this
                ]);

                header("Location: /allocations");
            } catch (Exception $e) {
                // If error, reload form (simplification)
                die("Error creating allocation: " . $e->getMessage());
            }
        }
    }
	// ... inside AllocationsController class ...

    // SHOW THE ADVANCED BULK FORM
    public function create_bulk() {
        $db = Database::getInstance();
        
        // Data for dropdowns
        $testers = $db->query("SELECT id, first_name, last_name FROM users WHERE role = 'tester' AND status = 'active' ORDER BY first_name ASC")->fetchAll();
        $projects = $db->query("SELECT id, project_name FROM projects ORDER BY project_name ASC")->fetchAll();

        $this->view('allocations/create_bulk', [
            'testers' => $testers, 
            'projects' => $projects
        ]);
    }

    // PROCESS THE COMPLEX DATA
    public function store_bulk() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Debug: Stop if no dates or testers
            if (empty($_POST['dates']) || empty($_POST['testers'])) {
                die("Error: Please select at least one date and one tester.");
            }

            $db = Database::getInstance();
            
            $allocationName = $_POST['allocation_name'];
            $projectId = $_POST['project_id'];
            
            // Raw Arrays from Form
            // dates = ['2023-10-01', '2023-10-02']
            // testers = [ 
            //    0 => ['id'=>1, 'platform'=>'PC', ...], 
            //    1 => ['id'=>5, 'platform'=>'PS5', ...] 
            // ]
            $dates = explode(',', $_POST['selected_dates_string']); // We will perform hidden field logic in JS
            $testers = $_POST['testers'];

            try {
                $db->beginTransaction();

                $sql = "INSERT INTO allocations (
                    allocation_name, project_id, tester_id, scope, platform, 
                    shift_time, allocation_date, hours, allocation_type, 
                    created_by, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

                $stmt = $db->prepare($sql);

                foreach ($dates as $date) {
                    $date = trim($date);
                    if(empty($date)) continue;

                    foreach ($testers as $tester) {
                        // Skip if no tester selected in a specific row
                        if(empty($tester['id'])) continue;

                        $stmt->execute([
                            $allocationName,
                            $projectId,
                            $tester['id'],
                            'QA', // Default scope if not in row (or add scope to row)
                            $tester['platform'],
                            'Morning', // Default shift (or add shift to row)
                            $date,
                            $tester['hours'],
                            $tester['billing'],
                            $_SESSION['user_id']
                        ]);
                    }
                }

                $db->commit();
                header("Location: /allocations");

            } catch (Exception $e) {
                $db->rollBack();
                die("Database Error: " . $e->getMessage());
            }
        }
    }
}