<h2>User Management</h2>
<a href="index.php?url=user/create">
    <button>Create User</button>
</a>

<br><br>

<table border="1" cellpadding="10">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Email</th>
		<th>Role</th>
		<th>Status</th>
		<th>Action</th>

	</tr>

	<?php foreach ($users as $user): ?>

	<tr>
		<td><?php echo $user['id']; ?></td>

		<td>
			<?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
		</td>

		<td><?php echo $user['email']; ?></td>

		<td><?php echo $user['role']; ?></td>

		<td><?php echo $user['status']; ?></td>
		<td>

			<a href="index.php?url=user/edit&id=<?php echo $user['id']; ?>">
				Edit
			</a>

			|

			<a href="index.php?url=user/delete&id=<?php echo $user['id']; ?>"
			   onclick="return confirm('Are you sure you want to delete this user?');">
				Delete
			</a>

		</td>


	</tr>

	<?php endforeach; ?>

</table>
