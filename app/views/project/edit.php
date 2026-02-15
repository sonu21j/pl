<h2>Edit Project</h2>

<form method="post">

Project Name:<br>
<input type="text"
name="project_name"
value="<?php echo $project['project_name']; ?>"
required>

<br><br>

Project Code:<br>
<input type="text"
name="project_code"
value="<?php echo $project['project_code']; ?>"
required>

<br><br>

Platform:<br>
<input type="text"
name="platform"
value="<?php echo $project['platform']; ?>"
required>

<br><br>
Client Allocated Hours:

<input type="number"
step="0.5"
name="client_allocated_hours"
value="<?php echo $project['client_allocated_hours']; ?>">

<br><br>

Warning Threshold (%):

<input type="number"
name="warning_threshold_percent"
value="<?php echo $project['warning_threshold_percent']; ?>">

<br><br>

<button type="submit">Update Project</button>

</form>
