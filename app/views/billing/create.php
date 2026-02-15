<h2>Add Billing Rate</h2>

<form method="post">

Project:

<select name="project_id">

<?php foreach($projects as $project): ?>

<option value="<?php echo $project['id']; ?>">
<?php echo $project['project_name']; ?>
</option>

<?php endforeach; ?>

</select>

<br><br>

Platform:

<input type="text" name="platform" required>

<br><br>

Billing Type:

<select name="billing_type">

<option value="Billed">Billed</option>
<option value="Overtime">Overtime</option>
<option value="Unbilled">Unbilled</option>

</select>

<br><br>

Rate per Hour:

<input type="number" step="0.01" name="rate_per_hour" required>

<br><br>

Currency:

<input type="text" name="currency" value="USD">

<br><br>

<button type="submit">
Save Rate
</button>

</form>
