<?php 
$title = "User Management";
require_once ROOT_PATH . '/app/Views/layouts/header.php'; 
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>All Users</h2>
    <a href="/users/create" class="btn"> + Add New User</a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Department</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <span style="text-transform: uppercase; font-size: 12px; font-weight: bold; 
                        <?php 
                            if($user['role']=='admin') echo 'color:#0052cc'; 
                            elseif($user['role']=='manager') echo 'color:#ff991f'; 
                            else echo 'color:#00875a'; 
                        ?>">
                        <?= $user['role'] ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($user['department'] ?? '-') ?></td>
                <td>
                    <?php if($user['status'] == 'active'): ?>
                        <span style="background:#e3fcef; color:#006644; padding:2px 6px; border-radius:3px; font-size:12px;">Active</span>
                    <?php else: ?>
                        <span style="background:#ffebe6; color:#de350b; padding:2px 6px; border-radius:3px; font-size:12px;">Disabled</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($user['role'] !== 'admin' || $user['id'] == $_SESSION['user_id']): ?>
                        <a href="/users/toggle/<?= $user['id'] ?>" class="btn-sm" style="background: #eee; color: #333; text-decoration: none;">
                            <?= $user['status'] == 'active' ? 'Disable' : 'Enable' ?>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>