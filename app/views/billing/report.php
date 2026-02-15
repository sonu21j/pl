<h2>Billing Report</h2>

<table border="1" cellpadding="10">

<tr>

<th>Project</th>
<th>Platform</th>
<th>Billing Type</th>
<th>Total Hours</th>
<th>Rate</th>
<th>Total Amount</th>

</tr>

<?php

$grand_total = 0;

foreach($report as $row):

$amount = $row['total_amount'];

$grand_total += $amount;

?>

<tr>

<td>
<?php echo $row['project_name']; ?>
</td>

<td>
<?php echo $row['platform']; ?>
</td>

<td>
<?php echo $row['billing_type']; ?>
</td>

<td>
<?php echo number_format($row['total_hours'], 2); ?>
</td>

<td>
<?php echo $row['currency'] . " " . number_format($row['rate_per_hour'], 2); ?>
</td>

<td>
<?php echo $row['currency'] . " " . number_format($amount, 2); ?>
</td>

</tr>

<?php endforeach; ?>


<tr style="font-weight:bold; background:#eee;">

<td colspan="5">
Grand Total
</td>

<td>
<?php
echo $report[0]['currency'] ?? '' ;
echo " ";
echo number_format($grand_total, 2);
?>
</td>

</tr>

</table>
