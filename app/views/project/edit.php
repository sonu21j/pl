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

<button type="submit">Update Project</button>

</form>
