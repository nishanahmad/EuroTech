<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	if(isset($_GET['date']))
		$date = $_GET['date'];
	else
		$date = date('Y/m/d');

	$query_Items = mysqli_query($con,"SELECT * FROM pricelist") or die(mysqli_error($con));	
	$itemsArray = array();
	while($item = mysqli_fetch_array($query_Items,MYSQLI_ASSOC))
	{
		$temp = array();
		$temp['number'] = $item['number'];
		$temp['itemCode'] = $item['itemCode'];
		$temp['description'] = $item['description'];			
		
		$itemCode = $item['itemCode'];

		$query = mysqli_query($con,"SELECT closing_stock FROM closing_stock WHERE date <= '$date' AND itemCode = '$itemCode' ORDER BY date DESC LIMIT 1") or die(mysqli_error($con));					
		$closingStock = mysqli_fetch_array($query,MYSQLI_ASSOC);
		$temp['qty'] = $closingStock['closing_stock'];
		$itemsArray[] = $temp;				
	}	
?>	
<html>
	<title>Closing Stock</title>
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 	
		<style>
		.dataTables_wrapper .dt-buttons {
		  float:none;  
		  text-align:center;
		}
		.dataTables_length{
			display: none;
		}
		</style>	
		<link rel="stylesheet" type="text/css" href="../css/glow_box.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
		<link rel="stylesheet" type="text/css" href="../css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="../css/demo.css" />
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">		

  		<script src="../js/modernizr.custom.js"></script>		
		<script type="text/javascript" language="javascript" src="../js/jquery3.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>		

		<script type="text/javascript" language="javascript">
			$(document).ready(function() {
				$('#closingStock').DataTable({
					"paging":   false,
				});
			} );

			$(function() {

			var pickerOpts = { dateFormat:"dd-mm-yy"}; 
						
			$( "#datepicker" ).datepicker(pickerOpts);

			});
		</script>
	</head>	
	<body>
		<div align="center">
			<br>
			<img src='../images/stock.png' width="90" height="90">
			<h1> CLOSING STOCK</h1>						
			<form method="post" action="redirectWithDate.php">
			<input type="text" name="date" id="datepicker" required value="<?php echo date("d-m-Y", strtotime($date));?>"/>
			<button type="submit">Generate</button>
			</form>
			<br><br>			
			<table id="closingStock" class="display cell-border no-wrap" style="width:50%;">
			<thead>
				<tr>
					<th>#</th>
					<th>ItemCode</th>
					<th>description</th>
					<th>Closing Stock</th>
				</tr>
			</thead>
			<tbody>
<?php			foreach($itemsArray as $item)
				{
?>					<tr>
					<td><?php echo $item['number'];?></td>		
					<td><?php echo $item['itemCode'];?></td>		
					<td><?php echo $item['description'];?></td>		
					<td><?php echo $item['qty'];?></td>		
<?php			}
?>			</tbody>
			</table>
		</div>			
	</body>
</html>
<?php
}
else
	header("Location:../loginPage.php");

?>
	