<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	$id = $_GET['id'];	
	
	$queryPO = mysqli_query($con,"SELECT * FROM purchase_returns WHERE Id = '$id'");
	$po = mysqli_fetch_array($queryPO,MYSQLI_ASSOC);
	
	$queryItems = mysqli_query($con,"SELECT * FROM purchase_return_items WHERE purchase_return_id = '$id'");
	$items = array();
	while($item= mysqli_fetch_array($queryItems,MYSQLI_ASSOC))
	{
		$itemCode = $item['itemCode'];
		$priceListQuery = mysqli_query($con,"SELECT number,description,price FROM pricelist WHERE itemCode = '$itemCode'");		
		$priceList = mysqli_fetch_array($priceListQuery,MYSQLI_ASSOC);
		$item['number'] = $priceList['number'];
		$item['description'] = $priceList['description'];
		$items[] = $item;
	}	
?>				
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title>Purchase Return <?php echo $id;?></title>
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/button.css">
		<link rel="stylesheet" type="text/css" href="../css/form.css">
		<link rel="stylesheet" type="text/css" href="../css/demo.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabs.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabstyles.css" />						
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">		
		<link rel="stylesheet" href="../css/table.css">
		<script src="../js/jquery3.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>		
	</head>
	<body>	
			<div class="container" style="width:100% !important;font-size:1.2em !important">
			<section>
				<div class="tabs tabs-style-linemove">
					<nav>
						<ul>
							<li><a href="../priceList/list.php" class="icon icon-date"><span>Price List</span></a></li>
							<li><a href="../quotations/list.php" class="icon icon-display"><span>Quotations</span></a></li>
							<li><a href="../orders/list.php" class="icon icon-box"><span>Orders</span></a></li>
							<li class="tab-current"><a href="../purchases/list.php" class="icon icon-tools"><span>Purchases</span></a></li>
							<li><a href="../returns/list.php" class="icon icon-upload"><span>Returns</span></a></li>														
						</ul>
					</nav>
				</div>
			</section>
		</div>			
			<div align="center">
			<img src="../images/logo.png" width="250" height="100">
			<br><br>			
			<div class="inner contact">
				<div class="contact-form">
					<h3>pr-<?php echo $id;?></h3>						
					<br/><br/>
					<div class="relative fullwidth col-xs-12" align="center">
						<input type="text" style="width:15%;height:20px;" name="date" id="date" class="form" readonly value="<?php echo date('d-m-Y',strtotime($po['date'])); ?>" />						
						<table style="width:60%" class="column-options" id="tbl" >
							<tr>
								<th style="width:10%;text-align:center;">#</th>
								<th style="width:15%;text-align:center;">Item Code</th>
								<th style="text-align:center;">Description</th>
								<th style="width:10%;text-align:center;">Qty</th>
							</tr>
							<?php 
							$count = 1;
							foreach($items as $item)
							{
								if($count%2 != 0)
								{
									?><tr class="odd"><?php								
								}	
								else
							{
								?><tr><?php								
							}
?>						
						<td style="text-align:center;"><input type="text" readonly value="<?php echo $item['number'];?>" /></td>	
						<td style="text-align:center;"><input type="text" readonly value="<?php echo $item['itemCode'];?>" /></td>	
						<td style="text-align:center;"><input type="text" readonly value="<?php echo $item['description'];?>" /></td>	
						<td style="text-align:center;"><input type="text" readonly value="<?php echo $item['qty'];?>" /></td>	
						</tr>
<?php							
						$count++;								
					}	
					?>
					</table>
					<br><br>
					<button type="button" class="btn" style="width : 100px;" onclick="location.href='edit.php?id=<?php echo $po['id'] ?>'">Edit</button>&nbsp&nbsp&nbsp
					<a href="delete.php?id=<?php echo $po["id"]; ?>" class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">DELETE</a>						
					<br><br><br>
				</div>						
			</div>
		</div>
		</div>
	</body>
</html>
<?php
}
else
header("Location:loginPage.php");
?>