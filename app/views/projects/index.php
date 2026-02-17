<?php 
$title = "Projects";
require_once ROOT_PATH . '/app/Views/layouts/header.php'; 
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Projects</h2>
    <a href="/projects/create" class="btn"> + Create Project</a>
</div>

<div class="card">
    <?php if(count($projects) > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Project Name</th>
                <th>Platform</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($projects as $project): ?>
            <tr>
                <td><span style="background: #dfe1e6; color: #42526e; padding: 2px 6px; border-radius: 3px; font-weight: bold; font-size: 11px;"><?= htmlspecialchars($project['project_code']) ?></span></td>
                <td><strong><?= htmlspecialchars($project['project_name']) ?></strong></td>
                <td><?= htmlspecialchars($project['platform']) ?></td>
                <td><?= date('M j, Y', strtotime($project['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p style="padding: 20px; color: #666; text-align: center;">No projects found. Create one to get started.</p>
    <?php endif; ?>
</div>

<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>