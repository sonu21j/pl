<?php
use Core\Controller;
use Core\Database;

class DashboardController extends Controller {

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }

        $db = Database::getInstance();
        $role = $_SESSION['role'];
        $userId = $_SESSION['user_id'];
        $data = [];

        // 1. ADMIN & MANAGER STATS
        if ($role === 'admin' || $role === 'manager') {
            // Count Total Users
            $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE status='active'");
            $data['total_users'] = $stmt->fetch()['count'];

            // Count Active Projects
            $stmt = $db->query("SELECT COUNT(*) as count FROM projects");
            $data['total_projects'] = $stmt->fetch()['count'];

            // Count Allocations Today
            $stmt = $db->query("SELECT COUNT(*) as count FROM allocations WHERE allocation_date = CURDATE()");
            $data['allocations_today'] = $stmt->fetch()['count'];

            // Recent Activity Logs (Admin only)
            if ($role === 'admin') {
                $stmt = $db->query("SELECT l.*, u.first_name, u.last_name FROM activity_logs l JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC LIMIT 5");
                $data['logs'] = $stmt->fetchAll();
            }
        }

        // 2. TESTER STATS
        if ($role === 'tester') {
            // My Allocations Today
            $stmt = $db->prepare("SELECT * FROM allocations a JOIN projects p ON a.project_id = p.id WHERE a.tester_id = ? AND a.allocation_date = CURDATE()");
            $stmt->execute([$userId]);
            $data['my_tasks'] = $stmt->fetchAll();

            // My Total Hours Today
            $stmt = $db->prepare("SELECT SUM(hours) as total FROM allocations WHERE tester_id = ? AND allocation_date = CURDATE()");
            $stmt->execute([$userId]);
            $data['my_hours'] = $stmt->fetch()['total'] ?? 0;
        }

        $this->view('dashboard', $data);
    }
}