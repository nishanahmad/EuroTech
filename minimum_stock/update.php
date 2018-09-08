<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	$minQuery = mysqli_query($con, "SELECT * FROM minimum_stock") or die(mysqli_error($con));		
	foreach($minQuery as $minStock)
	{
		$minStockMap[$minStock['itemCode']] = $minStock['qty'];
	}
	
	$sql = "SELECT * FROM pricelist";
	$result = mysqli_query($con, $sql) or die(mysqli_error($con));		
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/responstable.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="../js/jquery3.min.js"></script>
<title>Minimum Balance</title>
</head>
<body>
	<div style="width:100%;">
	<div align="center" style="padding-bottom:5px;">
	<br><br>
	</div>
	<br><br>
	<form name="bulkUpdate" method="post" action="updateServer.php">
	<table align="center" class="responstable" style="width:60%;">
		<tr>
			<th style="width:10%">Number</th>
			<th style="width:30%">Item Code</th>
			<th>Description</th>
			<th style="width:10%;text-align:center;">Minimum Qty</th>
		</tr>					<?php
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{
		$itemCode = $row['itemCode'];
		$number = $row['number'];
		$description = $row['description'];																			?>				

		<tr>
			<td><label align="center"><?php echo $number; ?></td>			
			<td><label align="center"><?php echo $itemCode ?></td>	
			<td><label align="center"><?php echo $description; ?></td>	
			<td style="text-align:center;"><input type="text" style="text-align:center;width:100px;border:0px;background-color: transparent;" name="<?php echo $itemCode;?>" value="<?php echo $minStockMap[$itemCode]; ?>"></td>	
		</tr>																												<?php
	}																														?>						
	</table>
	<div align="center"><input type="submit" name="submit" value="Submit"></div>																											?>
	<br><br> 
	</div> 
</body>
</html>																								<?php
}
else
	header("Location:../index.php");
