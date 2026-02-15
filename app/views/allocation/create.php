<h2>Create Allocation</h2>

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

Date:
<input type="date" name="allocation_date">

<br><br>

Shift Time:
<input type="text" name="shift_time"
placeholder="9AM - 7PM">

<br><br>

<h3>Testers</h3>

<div id="tester_container">

<div class="tester_row">

Tester:
<select name="tester_id[]">

<?php foreach($testers as $tester): ?>

<option value="<?php echo $tester['id']; ?>">
<?php echo $tester['first_name']." ".$tester['last_name']; ?>
</option>

<?php endforeach; ?>

</select>

Scope:
<select name="scope[]">
<option value="QA">QA</option>
<option value="FQA">FQA</option>
<option value="Anya">Anya</option>
</select>

Platform:
<input type="text" name="platform[]" value="Xbox">

Hours:
<input type="number" name="hours[]" value="8" step="0.5">

Billing:
<select name="billing_type[]">
<option value="Billed">Billed</option>
<option value="Unbilled">Unbilled</option>
<option value="Overtime">Overtime</option>
</select>

<button type="button" onclick="removeRow(this)">Remove</button>

<br><br>

</div>

</div>

<button type="button" onclick="addRow()">+ Add Tester</button>


<button type="submit">Save Allocation</button>

</form>
<script>

function addRow() {

    var container = document.getElementById("tester_container");

    var row = container.children[0].cloneNode(true);

    container.appendChild(row);
}

function removeRow(button) {

    var container = document.getElementById("tester_container");

    if (container.children.length > 1) {
        button.parentElement.remove();
    }

}

</script>
