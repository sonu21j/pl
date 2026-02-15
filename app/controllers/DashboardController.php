<?php
class DashboardController extends Controller {

    public function index() {

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?url=auth/login");
            exit;
        }

        $db = Database::connect();


        /*
        ==========================================
        GLOBAL TOTALS
        ==========================================
        */

        // Total Projects
        $total_projects = $db->query("
            SELECT COUNT(*) FROM projects
        ")->fetchColumn();


        // Total Active Testers
        $total_testers = $db->query("
            SELECT COUNT(*)
            FROM users
            WHERE role='tester' AND status='active'
        ")->fetchColumn();


        // Total Allocation Batches
        $total_allocations = $db->query("
            SELECT COUNT(*)
            FROM allocation_batches
        ")->fetchColumn();



        /*
        ==========================================
        TOP PROJECT METRICS
        ==========================================
        */

        // Most Time Allocated Project
        $most_time_project = $db->query("
            SELECT p.project_name, SUM(a.hours) AS total_hours
            FROM allocations a
            JOIN allocation_batches ab ON ab.id = a.batch_id
            JOIN projects p ON p.id = ab.project_id
            GROUP BY ab.project_id
            ORDER BY total_hours DESC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);


        // Most Overtime Project
        $most_overtime_project = $db->query("
            SELECT p.project_name, SUM(a.hours) AS overtime_hours
            FROM allocations a
            JOIN allocation_batches ab ON ab.id = a.batch_id
            JOIN projects p ON p.id = ab.project_id
            WHERE a.billing_type='Overtime'
            GROUP BY ab.project_id
            ORDER BY overtime_hours DESC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);



        /*
        ==========================================
        TOP TESTER METRICS
        ==========================================
        */

        // Most Billed Tester
        $most_billed_tester = $db->query("
            SELECT CONCAT(u.first_name,' ',u.last_name) AS tester_name,
                   SUM(a.hours) AS billed_hours
            FROM allocations a
            JOIN users u ON u.id = a.tester_id
            WHERE a.billing_type='Billed'
            GROUP BY a.tester_id
            ORDER BY billed_hours DESC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);


        // Most Overtime Tester
        $most_overtime_tester = $db->query("
            SELECT CONCAT(u.first_name,' ',u.last_name) AS tester_name,
                   SUM(a.hours) AS overtime_hours
            FROM allocations a
            JOIN users u ON u.id = a.tester_id
            WHERE a.billing_type='Overtime'
            GROUP BY a.tester_id
            ORDER BY overtime_hours DESC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);


        // Most Unbilled Tester
        $most_unbilled_tester = $db->query("
            SELECT CONCAT(u.first_name,' ',u.last_name) AS tester_name,
                   SUM(a.hours) AS unbilled_hours
            FROM allocations a
            JOIN users u ON u.id = a.tester_id
            WHERE a.billing_type='Unbilled'
            GROUP BY a.tester_id
            ORDER BY unbilled_hours DESC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);



        /*
        ==========================================
        TODAY METRICS
        ==========================================
        */

        $today_allocations = $db->query("
            SELECT COUNT(*)
            FROM allocation_batches
            WHERE allocation_date = CURDATE()
        ")->fetchColumn();


        $today_hours = $db->query("
            SELECT SUM(a.hours)
            FROM allocations a
            JOIN allocation_batches ab ON ab.id = a.batch_id
            WHERE ab.allocation_date = CURDATE()
        ")->fetchColumn();

        if (!$today_hours) $today_hours = 0;


        $today_testers = $db->query("
            SELECT COUNT(DISTINCT a.tester_id)
            FROM allocations a
            JOIN allocation_batches ab ON ab.id = a.batch_id
            WHERE ab.allocation_date = CURDATE()
        ")->fetchColumn();


        $today_overtime = $db->query("
            SELECT SUM(a.hours)
            FROM allocations a
            JOIN allocation_batches ab ON ab.id = a.batch_id
            WHERE ab.allocation_date = CURDATE()
            AND a.billing_type='Overtime'
        ")->fetchColumn();

        if (!$today_overtime) $today_overtime = 0;



        /*
        ==========================================
        WEEK METRICS
        ==========================================
        */

        $week_allocations = $db->query("
            SELECT COUNT(*)
            FROM allocation_batches
            WHERE YEARWEEK(allocation_date,1)=YEARWEEK(CURDATE(),1)
        ")->fetchColumn();


        $week_hours = $db->query("
            SELECT SUM(a.hours)
            FROM allocations a
            JOIN allocation_batches ab ON ab.id=a.batch_id
            WHERE YEARWEEK(ab.allocation_date,1)=YEARWEEK(CURDATE(),1)
        ")->fetchColumn();

        if (!$week_hours) $week_hours = 0;


        $week_testers = $db->query("
            SELECT COUNT(DISTINCT a.tester_id)
            FROM allocations a
            JOIN allocation_batches ab ON ab.id=a.batch_id
            WHERE YEARWEEK(ab.allocation_date,1)=YEARWEEK(CURDATE(),1)
        ")->fetchColumn();


        $week_overtime = $db->query("
            SELECT SUM(a.hours)
            FROM allocations a
            JOIN allocation_batches ab ON ab.id=a.batch_id
            WHERE YEARWEEK(ab.allocation_date,1)=YEARWEEK(CURDATE(),1)
            AND a.billing_type='Overtime'
        ")->fetchColumn();

        if (!$week_overtime) $week_overtime = 0;



        /*
        ==========================================
        MONTH METRICS
        ==========================================
        */

        $month_allocations = $db->query("
            SELECT COUNT(*)
            FROM allocation_batches
            WHERE YEAR(allocation_date)=YEAR(CURDATE())
            AND MONTH(allocation_date)=MONTH(CURDATE())
        ")->fetchColumn();


        $month_hours = $db->query("
            SELECT SUM(a.hours)
            FROM allocations a
            JOIN allocation_batches ab ON ab.id=a.batch_id
            WHERE YEAR(ab.allocation_date)=YEAR(CURDATE())
            AND MONTH(ab.allocation_date)=MONTH(CURDATE())
        ")->fetchColumn();

        if (!$month_hours) $month_hours = 0;


        $month_testers = $db->query("
            SELECT COUNT(DISTINCT a.tester_id)
            FROM allocations a
            JOIN allocation_batches ab ON ab.id=a.batch_id
            WHERE YEAR(ab.allocation_date)=YEAR(CURDATE())
            AND MONTH(ab.allocation_date)=MONTH(CURDATE())
        ")->fetchColumn();


        $month_overtime = $db->query("
            SELECT SUM(a.hours)
            FROM allocations a
            JOIN allocation_batches ab ON ab.id=a.batch_id
            WHERE YEAR(ab.allocation_date)=YEAR(CURDATE())
            AND MONTH(ab.allocation_date)=MONTH(CURDATE())
            AND a.billing_type='Overtime'
        ")->fetchColumn();

        if (!$month_overtime) $month_overtime = 0;



        /*
        ==========================================
        SEND TO VIEW
        ==========================================
        */

        $this->view('dashboard/index', [

            'total_projects' => $total_projects,
            'total_testers' => $total_testers,
            'total_allocations' => $total_allocations,

            'most_time_project' => $most_time_project,
            'most_overtime_project' => $most_overtime_project,

            'most_billed_tester' => $most_billed_tester,
            'most_overtime_tester' => $most_overtime_tester,
            'most_unbilled_tester' => $most_unbilled_tester,

            'today_allocations' => $today_allocations,
            'today_hours' => $today_hours,
            'today_testers' => $today_testers,
            'today_overtime' => $today_overtime,

            'week_allocations' => $week_allocations,
            'week_hours' => $week_hours,
            'week_testers' => $week_testers,
            'week_overtime' => $week_overtime,

            'month_allocations' => $month_allocations,
            'month_hours' => $month_hours,
            'month_testers' => $month_testers,
            'month_overtime' => $month_overtime

        ]);

    }

}
?>
