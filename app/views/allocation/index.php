<h2>Allocations</h2>

<a href="index.php?url=allocation/create">
	<button>Create Allocation</button>
</a>

<br><br>
<h3>Filters</h3>

<form method="get">

<input type="hidden" name="url" value="allocation/index">

Project:

<select name="project_id">

<option value="">All</option>

<?php foreach($projects as $project): ?>

<option value="<?php echo $project['id']; ?>">

<?php echo $project['project_name']; ?>

</option>

<?php endforeach; ?>

</select>


Date From:

<input type="date" name="date_from">


Date To:

<input type="date" name="date_to">


<button type="submit">
Filter
</button>

</form>

<br>

<table border="1" cellpadding="10">

	<tr>
		<th>ID</th>
		<th>Project</th>
		<th>Date</th>
		<th>Shift</th>
		<th>Action</th>
	</tr>

	<?php foreach($batches as $batch): ?>

	<tr>

		<td><?php echo $batch['id']; ?></td>

		<td><?php echo $batch['project_name']; ?></td>

		<td><?php echo $batch['allocation_date']; ?></td>

		<td><?php echo $batch['shift_time']; ?></td>
		<td>

			<a href="index.php?url=allocation/details&id=<?php echo $batch['id']; ?>">
				View
			</a>

		|

			<a href="index.php?url=allocation/edit&id=<?php echo $batch['id']; ?>">
				Edit
			</a>

		|

			<a href="index.php?url=allocation/delete&id=<?php echo $batch['id']; ?>"
			onclick="return confirm('Delete this allocation?');">
				Delete
			</a>

		</td>


	</tr>

	<?php endforeach; ?>

</table>
