<?php 
$title = "Create Project";
require_once ROOT_PATH . '/app/Views/layouts/header.php'; 
?>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <h2>Create New Project</h2>
        
        <?php if(isset($error)): ?>
            <div style="background: #ffebe6; color: #de350b; padding: 10px; border-radius: 3px; margin-bottom: 15px;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="/projects/store" method="POST">
            <div class="form-group">
                <label>Project Name</label>
                <input type="text" name="project_name" required placeholder="e.g. Call of Duty Mobile">
            </div>

            <div class="form-group">
                <label>Project Code (Short ID)</label>
                <input type="text" name="project_code" required placeholder="e.g. CODM" style="text-transform: uppercase;">
                <small style="color: #666;">Used for quick identification on allocations.</small>
            </div>

            <div class="form-group">
                <label>Platform</label>
                <select name="platform" style="width: 100%; padding: 10px; border: 2px solid #dfe1e6; border-radius: 3px;">
                    <option value="PC">PC</option>
                    <option value="Mobile (iOS/Android)">Mobile (iOS/Android)</option>
                    <option value="PlayStation">PlayStation</option>
                    <option value="Xbox">Xbox</option>
                    <option value="Switch">Nintendo Switch</option>
                    <option value="VR">VR / AR</option>
                    <option value="Web">Web</option>
                </select>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn">Save Project</button>
                <a href="/projects" style="margin-left: 10px; color: #5e6c84; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>