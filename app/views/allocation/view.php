<h2>Allocation Details</h2>

<b>Project:</b>
<?php echo $batch['project_name']; ?>

<br><br>

<b>Date:</b>
<?php echo $batch['allocation_date']; ?>

<br><br>

<b>Shift:</b>
<?php echo $batch['shift_time']; ?>

<br><br>

<b>Created By:</b>
<?php echo $batch['first_name']." ".$batch['last_name']; ?>

<br><br><br>


<h3>Testers</h3>

<table border="1" cellpadding="10">

<tr>

<th>Name</th>
<th>Scope</th>
<th>Platform</th>
<th>Hours</th>
<th>Billing</th>

</tr>

<?php foreach($testers as $tester): ?>

<tr>

<td>
<?php echo $tester['first_name']." ".$tester['last_name']; ?>
</td>

<td>
<?php echo $tester['scope']; ?>
</td>

<td>
<?php echo $tester['platform']; ?>
</td>

<td>
<?php echo $tester['hours']; ?>
</td>

<td>
<?php echo $tester['billing_type']; ?>
</td>

</tr>

<?php endforeach; ?>

</table>
