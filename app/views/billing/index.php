<h2>Billing Rates</h2>

<a href="index.php?url=billing/create">
<button>Add Rate</button>
</a>

<br><br>

<table border="1" cellpadding="10">

<tr>
<th>Project</th>
<th>Platform</th>
<th>Billing Type</th>
<th>Rate</th>
<th>Currency</th>
</tr>

<?php foreach($rates as $rate): ?>

<tr>

<td><?php echo $rate['project_name']; ?></td>

<td><?php echo $rate['platform']; ?></td>

<td><?php echo $rate['billing_type']; ?></td>

<td><?php echo $rate['rate_per_hour']; ?></td>

<td><?php echo $rate['currency']; ?></td>

</tr>

<?php endforeach; ?>

</table>

