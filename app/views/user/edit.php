<h2>Edit User</h2>

<form method="post">

First Name:<br>
<input type="text" name="first_name"
value="<?php echo $user['first_name']; ?>" required>
<br><br>

Last Name:<br>
<input type="text" name="last_name"
value="<?php echo $user['last_name']; ?>" required>
<br><br>

Email:<br>
<input type="email" name="email"
value="<?php echo $user['email']; ?>" required>
<br><br>

Role:<br>
<select name="role">

<option value="tester"
<?php if($user['role']=='tester') echo 'selected'; ?>>
Tester
</option>

<option value="manager"
<?php if($user['role']=='manager') echo 'selected'; ?>>
Manager
</option>

<option value="admin"
<?php if($user['role']=='admin') echo 'selected'; ?>>
Admin
</option>

</select>

<br><br>

Status:<br>
<select name="status">

<option value="active"
<?php if($user['status']=='active') echo 'selected'; ?>>
Active
</option>

<option value="disabled"
<?php if($user['status']=='disabled') echo 'selected'; ?>>
Disabled
</option>

</select>

<br><br>

<button type="submit">Update User</button>

</form>
