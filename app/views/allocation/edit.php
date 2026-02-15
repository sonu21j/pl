<h2>Edit Allocation</h2>

<form method="post">

Project:

<select name="project_id">

	<?php foreach($projects as $project): ?>

	<option value="<?php echo $project['id']; ?>"
	<?php if($project['id']==$batch['project_id']) echo "selected"; ?>>

	<?php echo $project['project_name']; ?>

	</option>

	<?php endforeach; ?>

</select>

<br><br>

Date:

<input type="date"
name="allocation_date"
value="<?php echo $batch['allocation_date']; ?>">

<br><br>

Shift:

<input type="text"
name="shift_time"
value="<?php echo $batch['shift_time']; ?>">

<br><br>


<h3>Testers</h3>

<h3>Testers</h3>

<div id="tester_container">

<?php foreach($allocations as $a): ?>

<div class="tester_row">

Tester:

<select name="tester_id[]">

<?php foreach($testers as $tester): ?>

<option value="<?php echo $tester['id']; ?>"
<?php if($tester['id']==$a['tester_id']) echo "selected"; ?>>

<?php echo $tester['first_name']." ".$tester['last_name']; ?>

</option>

<?php endforeach; ?>

</select>


Scope:

<select name="scope[]">

<option value="QA"
<?php if($a['scope']=='QA') echo "selected"; ?>>
QA
</option>

<option value="FQA"
<?php if($a['scope']=='FQA') echo "selected"; ?>>
FQA
</option>

<option value="Anya"
<?php if($a['scope']=='Anya') echo "selected"; ?>>
Anya
</option>

</select>


Platform:

<input type="text"
name="platform[]"
value="<?php echo $a['platform']; ?>">


Hours:

<input type="number"
name="hours[]"
value="<?php echo $a['hours']; ?>"
step="0.5">


Billing:

<select name="billing_type[]">

<option value="Billed"
<?php if($a['billing_type']=='Billed') echo "selected"; ?>>
Billed
</option>

<option value="Unbilled"
<?php if($a['billing_type']=='Unbilled') echo "selected"; ?>>
Unbilled
</option>

<option value="Overtime"
<?php if($a['billing_type']=='Overtime') echo "selected"; ?>>
Overtime
</option>

</select>


<button type="button" onclick="removeRow(this)">
Remove
</button>

<br><br>

</div>

<?php endforeach; ?>

</div>


<button type="button" onclick="addRow()">
+ Add Tester
</button>


<button type="submit">Update Allocation</button>

</form>
<script>

function addRow() {

    var container = document.getElementById("tester_container");

    var firstRow = container.children[0];

    var newRow = firstRow.cloneNode(true);

    // clear values
    newRow.querySelectorAll("input").forEach(function(input){
        if(input.type === "number") input.value = "8";
        else input.value = "";
    });

    newRow.querySelectorAll("select").forEach(function(select){
        select.selectedIndex = 0;
    });

    container.appendChild(newRow);
}


function removeRow(button) {

    var container = document.getElementById("tester_container");

    if(container.children.length > 1) {
        button.parentElement.remove();
    } else {
        alert("At least one tester required");
    }
}

</script>
