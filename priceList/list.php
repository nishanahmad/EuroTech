<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	$query_Items = mysqli_query($con,"SELECT * FROM pricelist") or die(mysqli_error($con));	
	$itemsArray = array();
	while($item = mysqli_fetch_array($query_Items,MYSQLI_ASSOC))
	{
		$itemsArray[] = $item;		
	}	
?>	
<html>
	<title>Price List</title>
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
		<link rel="stylesheet" type="text/css" href="../css/fixedHeader.css">
		<link rel="stylesheet" type="text/css" href="../css/buttons.css">
		<link rel="stylesheet" type="text/css" href="../css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="../css/demo.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabs.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabstyles.css" />		

  		<script src="../js/modernizr.custom.js"></script>		
		<script type="text/javascript" language="javascript" src="../js/jquery3.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/fixedHeader.js"></script>
		<script type="text/javascript" language="javascript" src="../js/buttons.js"></script>
		<script type="text/javascript" language="javascript" src="../js/html5ExportButton.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jsZip.js"></script>		
		<script type="text/javascript" language="javascript">
			$(document).ready(function() {
				var dataTable = $('#pricelist').DataTable( {
					"paging":false,
				} );
			} );
		</script>

	</head>
	<body>
		<div class="container">
			<section>
				<div class="tabs tabs-style-linemove">
					<nav>
						<ul>
							<li class="tab-current"><a href="#" class="icon icon-date"><span>Price List</span></a></li>
							<li><a href="../quotations/list.php" class="icon icon-display"><span>Quotations</span></a></li>
							<li><a href="../orders/list.php" class="icon icon-box"><span>Orders</span></a></li>
							<li><a href="../purchases/list.php" class="icon icon-tools"><span>Purchases</span></a></li>
							<li><a href="../returns/list.php" class="icon icon-upload"><span>Returns</span></a></li>														
						</ul>
					</nav>
				</div>
			</section>
		</div>	
			<a href="../stock/closingStock.php" onclick="window.open('../stock/closingStock.php','newwindow', 'width=700,height=600'); return false;"><img src="../images/stock.png" width="40" height="40" style="margin-left:30px;"/></a>		
		    <a href="../stock/closingStock.php" onclick="window.open('../minimum_stock','newwindow', 'width=800,height=800%'); return false;"><img src="../images/godown.png" width="40" height="40" style="float:right;margin-right:50px;"/></a>							
			<div align="center">
			<br><br>

						<table id="pricelist" class="display cell-border no-wrap" style="width:50%;">
						<thead>
							<tr>
								<th>#</th>
								<th>ItemCode</th>
								<th>description</th>
								<th>Price</th>
							</tr>
						</thead>
						<tbody>
			<?php			foreach($itemsArray as $item)
							{
			?>					<tr>
								<td><?php echo $item['number'];?></td>		
								<td><?php echo $item['itemCode'];?></td>		
								<td><?php echo $item['description'];?></td>		
								<td><?php echo $item['price'];?></td>		
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
	
