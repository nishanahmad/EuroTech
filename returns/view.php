<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	$id = $_GET['id'];
	$queryReturn = mysqli_query($con,"SELECT * FROM returns WHERE id = '$id'");
	$return= mysqli_fetch_array($queryReturn,MYSQLI_ASSOC);
	
	$items = array();
	$queryReturnItems = mysqli_query($con,"SELECT * FROM return_items WHERE return_id = '$id'");
	while($item= mysqli_fetch_array($queryReturnItems,MYSQLI_ASSOC))
	{
		$itemCode = $item['itemCode'];
		$priceListQuery = mysqli_query($con,"SELECT number,description,price FROM pricelist WHERE itemCode = '$itemCode'");
		$priceList = mysqli_fetch_array($priceListQuery,MYSQLI_ASSOC);
		$item['number'] = $priceList['number'];
		$item['description'] = $priceList['description'];
		$item['price'] = $priceList['price'];
		$items[] = $item;		
	}	
	
?>				
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title>View Return</title>
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/form.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">	
		<link rel="stylesheet" type="text/css" href="../css/demo.css" />		
		<link rel="stylesheet" type="text/css" href="../css/tabs.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabstyles.css" />				
		<link rel="stylesheet" href="../css/qTable.css">
		<link rel="stylesheet" href="../css/button.css">		
		<script src="../js/jquery3.min.js"></script>
		<script src="../js/RefreshFunctions.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>		
	</head>
	<body>	
		<div class="container" style="width:100% !important;font-size:1.60em !important">
			<section>
				<div class="tabs tabs-style-linemove">
					<nav>
						<ul>
							<li><a href="../priceList/list.php" class="icon icon-date"><span>Price List</span></a></li>
							<li><a href="list.php" class="icon icon-display"><span>Quotations</span></a></li>
							<li><a href="../orders/list.php" class="icon icon-box"><span>Orders</span></a></li>
							<li><a href="../purchases/list.php" class="icon icon-tools"><span>Purchases</span></a></li>
							<li class="tab-current"><a href="../returns/list.php" class="icon icon-upload"><span>Returns</span></a></li>							
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
				<form id="contact-us" method="post" action="insert.php">
					
					
					<table style="width:30%" class="" id="customerTable" >	
						<tr><td><input type="text" readonly name="date" id="date" class="form" required value="<?php echo date('d-m-Y'); ?>" /></td></tr>								
						<tr><td><tr><td><input type="text" readonly name="customerName" id="customerName" class="form" required value="<?php echo $return['customerName'];?>" /></td></tr>
						<tr><td><tr><td><input type="text" readonly name="customerPhone" id="customerPhone" class="form" required value="<?php echo $return['customerPhone'];?>" /></td></tr>							
					</table>
				
					<div class="relative fullwidth col-xs-12" align="center">
						<table class="qTable" id="tbl" >
							<tr>
								<th style="width:10%;text-align:center;">#</th>
								<th style="width:15%;text-align:center;">Item Code</th>
								<th style="text-align:center;">Description</th>
								<th style="width:10%;text-align:center;">Return Qty</th>
								<th style="width:15%;text-align:center;">Price</th>
								<th style="width:15%;text-align:center;">Total</th>								
							</tr>
							<?php 
							$total = 0;
							foreach($items as $item)
							{
?>								<tr>
								<td style="text-align:center;"><?php echo $item['number'];?></td>	
								<td style="text-align:center;"><?php echo $item['itemCode'];?></td>
								<td style="text-align:center;"><?php echo $item['description'];?></td>
								<td style="text-align:center;"><?php echo $item['qty'];?></td>
								<td style="text-align:center;"><?php echo $item['price'];?></td>
								<td style="text-align:center;"><?php echo $item['qty'] * $item['price'];?></td>
								</tr>
<?php							$total = $total + $item['qty'] * $item['price'];
							}	
?>							</table>
						
							<br>
							<table style="width:75%;" class="qTable" id="tbl2">						
							<tr>
							<td style="text-align:right;"></td>
							<td style="width:15%;text-align:left;"><input type="text" readonly id="total" value="<?php echo $total;?>"></td>
							</tr>						
							<tr>
							<td style="text-align:right;">Discount %</td>
							<td style="width:15%;text-align:left;"><input type="text" readonly name="discount" id="discount" value="<?php echo $return['discount'];?>" onChange="discountRefresh();" /></td>
							</tr>												
							<tr>
							<td style="text-align:right;">Discount Amount</td>
							<td style="width:15%;text-align:left;"><input type="text" readonly name="discountAmount" id="discountAmount" value="<?php echo round($total * $return['discount']/100,2);?>" /></td>
							</tr>												
							<tr>
							<td style="text-align:right;"><b>Total</b></td>
							<td style="width:15%;text-align:left;"><input type="text" readonly name="discountedTotal" id="discountedTotal" value="<?php echo round($total - ($total * $return['discount']/100),2);?>"/></td>
							</tr>																		
							</table>							
							
							<br/><br/>
							<button type="button" class="btn" style="width : 100px;" onclick="location.href='edit.php?id=<?php echo $return['id'] ?>'">Edit</button>&nbsp&nbsp
							<a href="delete.php?id=<?php echo $return["id"];?>" class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">DELETE</a>													
							<br/><br/><br/><br/><br/><br/><br/><br/>
							
						</div>						
					</form>
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