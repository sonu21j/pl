<h2>Create User</h2>

<form method="post">

    First Name:<br>
    <input type="text" name="first_name" required>
    <br><br>

    Last Name:<br>
    <input type="text" name="last_name" required>
    <br><br>

    Email:<br>
    <input type="email" name="email" required>
    <br><br>

    Password:<br>
    <input type="password" name="password" required>
    <br><br>

    Role:<br>
    <select name="role">
        <option value="tester">Tester</option>
        <option value="manager">Manager</option>
        <option value="admin">Admin</option>
    </select>

    <br><br>

    <button type="submit">Create User</button>

</form>
