<?php 
$title = "Dashboard";
require_once ROOT_PATH . '/app/Views/layouts/header.php'; 
?>

<!-- ADMIN / MANAGER DASHBOARD -->
<?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager'): ?>
    
    <div class="stats-grid">
        <div class="card">
            <h3>Total Users</h3>
            <div class="number"><?= $total_users ?></div>
        </div>
        <div class="card">
            <h3>Active Projects</h3>
            <div class="number"><?= $total_projects ?></div>
        </div>
        <div class="card">
            <h3>Allocations (Today)</h3>
            <div class="number"><?= $allocations_today ?></div>
        </div>
        <div class="card">
            <h3>System Status</h3>
            <div class="number" style="color: green; font-size: 20px;">Operational</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="margin-bottom: 30px;">
        <a href="/users/create" class="btn">Add New User</a>
        <a href="/projects/create" class="btn">Create Project</a>
        <a href="/allocations/create" class="btn">New Allocation</a>
    </div>

    <?php if(isset($logs)): ?>
    <div class="card">
        <h3>Recent Activity Logs</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($logs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['first_name'] . ' ' . $log['last_name']) ?></td>
                    <td><?= htmlspecialchars($log['action']) ?></td>
                    <td><?= htmlspecialchars($log['description']) ?></td>
                    <td><?= $log['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

<?php endif; ?>


<!-- TESTER DASHBOARD -->
<?php if($_SESSION['role'] === 'tester'): ?>
    
    <div class="stats-grid">
        <div class="card">
            <h3>Hours Today</h3>
            <div class="number"><?= $my_hours ?> / 8.00</div>
        </div>
    </div>

    <div class="card">
        <h3>My Allocations for Today</h3>
        <?php if(count($my_tasks) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Scope</th>
                        <th>Shift</th>
                        <th>Hours</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($my_tasks as $task): ?>
                    <tr>
                        <td><?= htmlspecialchars($task['project_name']) ?></td>
                        <td><?= $task['scope'] ?></td>
                        <td><?= $task['shift_time'] ?></td>
                        <td><?= $task['hours'] ?></td>
                        <td><span style="padding: 3px 8px; background: #e3fcef; color: #006644; border-radius: 3px;">Assigned</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="padding: 20px; color: #666;">No allocations assigned for today yet.</p>
        <?php endif; ?>
    </div>

<?php endif; ?>

<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>