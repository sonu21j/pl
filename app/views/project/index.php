<h2>Project Management</h2>

<a href="index.php?url=project/create">
    <button>Create Project</button>
</a>

<br><br>

<table border="1" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Project Name</th>
    <th>Code</th>
    <th>Platform</th>
	<th>Allocated</th>
	<th>Used</th>
	<th>Remaining</th>
	<th>Usage %</th>
    <th>Action</th>
</tr>

<?php foreach ($projects as $project): ?>

<tr>

<td><?php echo $project['id']; ?></td>

<td><?php echo $project['project_name']; ?></td>

<td><?php echo $project['project_code']; ?></td>

<td><?php echo $project['platform']; ?></td>
<td>
<?php echo $project['client_allocated_hours']; ?>
</td>

<td>
<?php echo $project['used_hours']; ?>
</td>

<td>
<?php 
$remaining = $project['client_allocated_hours'] - $project['used_hours'];
echo $remaining;
?>
</td>

<td>
<?php

if ($project['client_allocated_hours'] > 0) {

    $percent = ($project['used_hours'] / $project['client_allocated_hours']) * 100;

    echo round($percent, 1) . "%";

} else {

    echo "0%";

}

?>
</td>

<td>

<a href="index.php?url=project/edit&id=<?php echo $project['id']; ?>">
Edit
</a>

|

<a href="index.php?url=project/delete&id=<?php echo $project['id']; ?>"
onclick="return confirm('Delete this project?');">
Delete
</a>

</td>

</tr>

<?php endforeach; ?>

</table>
