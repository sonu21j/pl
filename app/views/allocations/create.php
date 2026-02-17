<?php 
$title = "Assign Tester";
require_once ROOT_PATH . '/app/Views/layouts/header.php'; 
?>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <h2>Assign Resource</h2>
        
        <form action="/allocations/store" method="POST">
            
            <!-- Row 1: Who and Where -->
            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Project</label>
                    <select name="project_id" required>
                        <option value="">Select Project...</option>
                        <?php foreach($projects as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['project_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label>Tester</label>
                    <select name="tester_id" required>
                        <option value="">Select Tester...</option>
                        <?php foreach($testers as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['first_name'] . ' ' . $t['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Row 2: Date & Scope -->
            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Allocation Date</label>
                    <input type="date" name="allocation_date" value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="form-group" style="flex: 1;">
                    <label>Scope (Department)</label>
                    <select name="scope">
                        <option value="FQA">FQA (Functional)</option>
                        <option value="QA">QA (General)</option>
                        <option value="Anya">Anya (Specific)</option>
                    </select>
                </div>
            </div>

            <!-- Row 3: Platform & Shift -->
            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Platform (Specific to task)</label>
                    <input type="text" name="platform" placeholder="e.g. iOS / PC" required>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label>Shift Time</label>
                    <select name="shift_time">
                        <option value="Morning">Morning (9AM - 6PM)</option>
                        <option value="Afternoon">Afternoon (2PM - 11PM)</option>
                        <option value="Night">Night (10PM - 7AM)</option>
                    </select>
                </div>
            </div>

            <!-- Row 4: Billing & Hours -->
            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Billing Status</label>
                    <select name="allocation_type">
                        <option value="Billed">Billed</option>
                        <option value="Unbilled">Unbilled</option>
                        <option value="Overtime">Overtime</option>
                    </select>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label>Hours Assigned</label>
                    <input type="number" name="hours" step="0.5" value="8.0" max="24">
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn">Confirm Allocation</button>
                <a href="/allocations" style="margin-left: 10px; color: #5e6c84; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>