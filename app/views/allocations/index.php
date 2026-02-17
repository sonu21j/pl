<?php 
$title = "Resource Allocations";
require_once ROOT_PATH . '/app/Views/layouts/header.php'; 
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Daily Allocations</h2>
    <a href="/allocations/create_bulk" class="btn"> + New Bulk Allocation</a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Tester</th>
                <th>Project</th>
                <th>Shift / Scope</th>
                <th>Hours</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($allocations as $row): ?>
            <tr>
                <!-- Date Formatting -->
                <td><?= date('d M Y', strtotime($row['allocation_date'])) ?></td>
                
                <!-- Tester Name -->
                <td>
                    <div style="font-weight: bold;"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></div>
                </td>
                
                <!-- Project Name & Code -->
                <td>
                    <?= htmlspecialchars($row['project_name']) ?> 
                    <span style="font-size: 11px; color: #666;">(<?= $row['project_code'] ?>)</span>
                    <br>
                    <small style="color: #999;"><?= $row['platform'] ?></small>
                </td>

                <!-- Shift Details -->
                <td>
                    <?= htmlspecialchars($row['shift_time']) ?>
                    <span style="display:inline-block; margin-left:5px; padding: 2px 5px; background: #eee; border-radius: 3px; font-size: 11px;">
                        <?= $row['scope'] ?>
                    </span>
                </td>

                <!-- Hours -->
                <td style="font-weight: bold;"><?= $row['hours'] ?></td>

                <!-- Billing Type -->
                <td>
                    <?php if($row['allocation_type'] == 'Billed'): ?>
                        <span style="color: #006644; background: #e3fcef; padding: 3px 8px; border-radius: 3px; font-size: 12px;">Billed</span>
                    <?php elseif($row['allocation_type'] == 'Overtime'): ?>
                        <span style="color: #bf2600; background: #ffebe6; padding: 3px 8px; border-radius: 3px; font-size: 12px;">Overtime</span>
                    <?php else: ?>
                        <span style="color: #42526e; background: #dfe1e6; padding: 3px 8px; border-radius: 3px; font-size: 12px;">Unbilled</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if(empty($allocations)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #666;">No allocations found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>