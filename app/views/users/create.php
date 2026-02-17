<?php 
$title = "Create New User";
require_once ROOT_PATH . '/app/Views/layouts/header.php'; 
?>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <h2>Add New User</h2>
        
        <?php if(isset($error)): ?>
            <div style="background: #ffebe6; color: #de350b; padding: 10px; border-radius: 3px; margin-bottom: 15px;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="/users/store" method="POST">
            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" required style="width: 100%; padding: 10px; border: 2px solid #dfe1e6; border-radius: 3px;">
                    <option value="tester">Tester</option>
                    <option value="manager">Manager</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Department</label>
                    <input type="text" name="department" placeholder="e.g. QA">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Designation</label>
                    <input type="text" name="designation" placeholder="e.g. Jr. Tester">
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn">Create User</button>
                <a href="/users" style="margin-left: 10px; color: #5e6c84; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>