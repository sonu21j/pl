<?php
if (file_exists("installation.lock")) {
    die("System already installed.");
}
?>
<h2>Project Allocation Management - Installation</h2>
<form method="post" action="install.php">
    <h3>Database Configuration</h3>
    Host: <input name="db_host" required><br>
    Port: <input name="db_port" value="3306"><br>
    User: <input name="db_user" required><br>
    Password: <input name="db_pass"><br>
    DB Name: <input name="db_name" required><br><br>

    <h3>Admin Account</h3>
    First Name: <input name="first_name" required><br>
    Last Name: <input name="last_name" required><br>
    Email: <input name="email" required><br>
    Password: <input type="password" name="password" required><br><br>

    <button type="submit">Install System</button>
</form>
