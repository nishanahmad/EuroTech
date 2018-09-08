<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	$id = $_GET['id'];
	$queryOrder = mysqli_query($con,"SELECT * FROM orders WHERE Id = '$id'");
	$order= mysqli_fetch_array($queryOrder,MYSQLI_ASSOC);
	
	$queryItems = mysqli_query($con,"SELECT * FROM order_items WHERE order_id = '$id'");
	$items = array();
	while($item= mysqli_fetch_array($queryItems,MYSQLI_ASSOC))
	{
		$itemCode = $item['itemCode'];
		$priceListQuery = mysqli_query($con,"SELECT number,description,price FROM pricelist WHERE itemCode = '$itemCode'");
		$priceList = mysqli_fetch_array($priceListQuery,MYSQLI_ASSOC);
		$item['number'] = $priceList['number'];
		$item['price'] = $priceList['price'];
		$item['description'] = $priceList['description'];
		$items[] = $item;
	}
	$url =  "http://" . $_SERVER['SERVER_NAME'].'/orders/exports/page.php?id='.$_GET['id'];
	$fileName = 'ord-'.$id.'.pdf';
	echo $url;
?>				
<html lang="en" class="no-js">
	<head>	
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title>View Order</title>
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/form.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">		
		<link rel="stylesheet" href="../css/table.css">
		<link rel="stylesheet" type="text/css" href="../css/button.css" />				
		<link rel="stylesheet" type="text/css" href="../css/demo.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabs.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabstyles.css" />		
		
		<script src="../js/jquery3.min.js"></script>
		<script src="../js/RefreshFunctions.js"></script>		
		<script src="../js/addRow.js"></script>		
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>		
	</head>
	
	<body>	
		<div class="container" style="width:100% !important;font-size:1.60em !important">
			<section>
				<div class="tabs tabs-style-linemove">
					<nav>
						<ul>
							<li><a href="../priceList/list.php" class="icon icon-date"><span>Price List</span></a></li>
							<li><a href="../quotations/list.php" class="icon icon-display"><span>Quotations</span></a></li>
							<li class="tab-current"><a href="list.php" class="icon icon-box"><span>Orders</span></a></li>
							<li><a href="../purchases/list.php" class="icon icon-tools"><span>Purchases</span></a></li>
							<li><a href="../returns/list.php" class="icon icon-upload"><span>Returns</span></a></li>														
						</ul>
					</nav>
				</div>
			</section>
		</div>		
			<div style="float:right;margin-right:50px;">
			    <form method="post" action="../libraries/pdfDownload.php">
			        <input type="hidden" name="fileName" value="<?php echo $fileName;?>"/>
			        <input type="hidden" name="url" value="<?php echo $url;?>"/>
			        <input type="image" src="../images/pdf.png" alt="Submit Form" />
			    </form>
			</div>		
			<div align="center" style="margin-left:170px;">
			<img src="../images/logo.png" width="250" height="100">			
			</div>
			<br>
			<div class="inner contact" align="center">
				<div class="contact-form">
					<form id="contact-us" method="post" action="">
						<input type="hidden" name="id" value="<?php echo $id;?>">
						<h3>ord-<?php echo $id;?></h3>
						<br>
						<table style="width:30%" class="" id="customerTable" >								
							<tr><td><input type="text" name="date" id="date" class="form" required readonly value="<?php echo date('d-m-Y',strtotime($order['date'])); ?>" /></td></tr>								
							<tr><td><input type="text" name="customerName" id="customerName" readonly class="form" value="<?php echo $order['customerName']; ?>" /></td></tr>
							<tr><td><input type="text" name="customerPhone" id="customerPhone" readonly class="form" value="<?php echo $order['customerPhone']; ?>" /></td></tr>
						</table>
					
						<div class="relative fullwidth col-xs-12" align="center">
							<table style="width:75%;" class="column-options" id="tbl">
								<tr>
									<th style="width:10%;text-align:center;">#</th>
									<th style="width:15%;text-align:center;">Item Code</th>
									<th style="text-align:center;">Description</th>
									<th style="width:10%;text-align:center;">Qty</th>
									<th style="width:15%;text-align:center;">Price</th>
									<th style="width:15%;text-align:center;">Total</th>
								</tr>
								
								<?php 
								$count = 1;
								$total = 0;
								foreach($items as $item)
								{
									if($count%2 != 0)
									{
?>										<tr class="odd">				<?php									
									}
									else
									{
?>										<tr>							<?php	
									}
?>									<td style="text-align:center;"><input type="text" readonly name="number<?php echo $count;?>" id="number<?php echo $count;?>" value="<?php echo $item['number'];?>" onChange="numberRefresh();updateTotal();discountRefresh();" /></td>
									<td style="text-align:center;"><input type="text" readonly name="itemCode<?php echo $count;?>" id="itemCode<?php echo $count;?>" value="<?php echo $item['itemCode'];?>" onChange="itemRefresh();updateTotal();discountRefresh();" /></td>
									<td style="text-align:center;"><input type="text" readonly name="description<?php echo $count;?>" id="description<?php echo $count;?>" value="<?php echo $item['description'];?>" onChange="descriptionRefresh();updateTotal();discountRefresh();" /></td>
									<td style="text-align:center;"><input type="text" readonly name="qty<?php echo $count;?>" id="qty<?php echo $count;?>" value="<?php echo $item['qty'];?>" onChange="qtyRefresh();updateTotal();discountRefresh();"/></td>
									<td style="text-align:center;"><input type="text" readonly id="price<?php echo $count;?>" value="<?php echo $item['price'];?>"></td>
									<td style="text-align:center;"><input type="text" readonly id="total<?php echo $count;?>" value="<?php echo $item['qty'] * $item['price'];?>" /></td>
									</tr>
									
<?php								$total = $total + $item['qty'] * $item['price'];
									$count++;							
								}
						?>
						</table>
						<table style="width:75%;" class="column-options" id="tbl2">						
						<tr>
						<td style="text-align:right;"></td>
						<td style="width:15%;text-align:left;"><input type="text" readonly id="total" value="<?php echo $total;?>"></td>
						</tr>						
						<tr>
						<td style="text-align:right;">Discount %</td>
						<td style="width:15%;text-align:left;"><input type="text" readonly name="discount" id="discount" value="<?php echo $order['discount'];?>" onChange="discountRefresh();" /></td>
						</tr>												
						<tr>
						<td style="text-align:right;">Discount Amount</td>
						<td style="width:15%;text-align:left;"><input type="text" readonly name="discountAmount" id="discountAmount" value="<?php echo round($total * $order['discount']/100,2);?>" /></td>
						</tr>												
						<tr>
						<td style="text-align:right;"><b>Total</b></td>
						<td style="width:15%;text-align:left;"><input type="text" readonly name="discountedTotal" id="discountedTotal" value="<?php echo round($total - ($total * $order['discount']/100),2);?>"/></td>
						</tr>																		
						</table>
						<br><br>
						<table style="width:30%" style="border:1px;" name="plumberTable" id="plumberTable" class="plumberTable">	
							<tr><td><input type="text" name="masonName" readonly id="masonName"  placeholder="Plumber Name..." value="<?php echo $order['masonName']; ?>"/></td></tr>
							<tr><td><input type="text" name="masonPhone" readonly id="masonPhone" placeholder="Plumber Phone..." value="<?php echo $order['masonPhone']; ?>"/></td></tr>
						</table>						
						<br><br><br>
						<button type="button" class="btn" style="width : 100px;" onclick="location.href='edit.php?id=<?php echo $order['id'] ?>'">Edit</button>&nbsp&nbsp&nbsp
						<a href="cancel.php?id=<?php echo $order["id"]; ?>" class="btn btn-red" onclick="return confirm('Are you sure you want to cancel this order?')">Reject</a>												
						<br><br><br><br>
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
header("Location:../loginPage.php");
?>