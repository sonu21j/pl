<h2>Create Project</h2>

<form method="post">

Project Name:<br>
<input type="text" name="project_name" required>
<br><br>

Project Code:<br>
<input type="text" name="project_code" required>
<br><br>

Platform:<br>
<input type="text" name="platform" required>
<br><br>
Client Allocated Hours:
<br>
<input type="number" step="0.5" name="client_allocated_hours">

<br><br>

Warning Threshold (%):
<br>
<input type="number" name="warning_threshold_percent" value="80">

<br><br>

<button type="submit">Create Project</button>

</form>
