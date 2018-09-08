<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	$id = $_GET['id'];
	$fileName = 'qtn-'.$id.'.pdf';
	$queryQuote = mysqli_query($con,"SELECT * FROM quotations WHERE Id = '$id'");
	$quote= mysqli_fetch_array($queryQuote,MYSQLI_ASSOC);
	
	$queryItems = mysqli_query($con,"SELECT * FROM quotation_items WHERE quotation_id = '$id'");
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
	$url =  "http://" . $_SERVER['SERVER_NAME'].'/quotations/exports/page.php?id='.$_GET['id'];
	$fileName = 'qtn-'.$id.'.pdf';	
?>				
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title>View Quote</title>
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/form.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">		
		<link rel="stylesheet" href="../css/button.css">
		<link rel="stylesheet" type="text/css" href="../css/demo.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabs.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabstyles.css" />		
		<link rel="stylesheet" type="text/css" href="../css/qTable.css" />				
		<script src="../js/jquery3.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>	
		
	<style>
	.contactTable{
		width:20%;
		border:1px solid #000000;			
		border-collapse:collapse;
	}
	.contactTable td{
		padding: 5px;
		border-bottom:1px dashed #B8B8B8;			
		color : #000000;
	}			
	</style>		

	</head>
	<body>	
		<div class="container" style="width:100% !important;font-size:1.60em !important">
			<section>
				<div class="tabs tabs-style-linemove">
					<nav>
						<ul>
							<li><a href="../priceList/list.php" class="icon icon-date"><span>Price List</span></a></li>
							<li class="tab-current"><a href="list.php" class="icon icon-display"><span>Quotations</span></a></li>
							<li><a href="../orders/list.php" class="icon icon-box"><span>Orders</span></a></li>
							<li><a href="../purchases/list.php" class="icon icon-tools"><span>Purchases</span></a></li>
							<li><a href="../returns/list.php" class="icon icon-upload"><span>Returns</span></a></li>							
						</ul>
					</nav>
				</div>
			</section>
		</div>				
		<br><br>
			<div style="float:right;margin-right:50px;">
			    <form method="post" action="../libraries/pdfDownload.php">
			        <input type="hidden" name="fileName" value="<?php echo $fileName;?>"/>
			        <input type="hidden" name="url" value="<?php echo $url;?>"/>
			        <input type="image" src="../images/pdf.png" alt="PDF" />
			    </form>
			</div>		
			<div align="center" style="margin-left:170px;">
			<img src="../images/logo.png" width="250" height="100">
			</div>
			<br><br>
			
			<div class="inner contact" align="center">
<?php		if($quote['status'] == 'Pending')
			{
?>				<form id="convertQuotationToOrder" method="post" action="convertQuotationToOrder.php">
					<input type="hidden" name="id" value="<?php echo $id;?>">
					<button type="submit">Confirm Order</button>
				</form>
<?php		}
			else
			{
				echo '<font size="5">Status - '.$quote['status'].'</font>';
			}	
?>						
				<div class="contact-form">
						<h3>qtn-<?php echo $id;?></h3>
						<br>
						<table class="contactTable" id="customerTable" >	
							<tr><td><?php echo date('d-m-Y'); ?></td></tr>								
							<tr><td><?php echo $quote['customerName']; ?></td></tr>
							<tr><td><?php echo $quote['customerPhone']; ?></td></tr>
						</table>
						<br><br>
						<div class="relative fullwidth col-xs-12" align="center">
							<table class="qTable" id="tbl">
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
?>									<tr>
									<td style="text-align:center;"><?php echo $item['number'];?></td>
									<td style="text-align:center;"><?php echo $item['itemCode'];?></td>
									<td style="text-align:center;"><?php echo $item['description'];?></td>
									<td style="text-align:center;"><?php echo $item['qty'];?></td>
									<td style="text-align:center;"><?php echo $item['price'];?></td>
									<td style="text-align:center;"><?php echo $item['qty'] * $item['price'];?></td>
									</tr>
									
<?php								$total = $total + $item['qty'] * $item['price'];
									$count++;							
								}
						?>
						</table>
						<table class="qTable" id="tbl2">						
						<tr>
						<td style="text-align:right;"></td>
						<td style="width:15%;text-align:left;"><?php echo $total;?></td>
						</tr>						
						<tr>
						<td style="text-align:right;">Discount %</td>
						<td style="width:15%;text-align:left;"><?php echo $quote['discount'];?></td>
						</tr>												
						<tr>
						<td style="text-align:right;">Discount Amount</td>
						<td style="width:15%;text-align:left;"><?php echo round($total * $quote['discount']/100,2);?></td>
						</tr>												
						<tr>
						<td style="text-align:right;"><b>Total</b></td>
						<td style="width:15%;text-align:left;"><?php echo round($total - ($total * $quote['discount']/100),2);?></td>
						</tr>																		
						</table>
						<br><br>
						<br><br>
<?php		if($quote['status'] == 'Pending')
			{
?>				<button type="button" class="btn" style="width : 100px;" onclick="location.href='edit.php?id=<?php echo $quote['id'] ?>'">Edit</button>&nbsp&nbsp
				<a href="delete.php?id=<?php echo $quote["id"];?>" class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">DELETE</a>						
				<br><br><br><br>
<?php		}
?>						
					</div>						
				<br><br><br>						
			</div>
		</div>
		</div>
		<div align="center">
		<table class="contactTable"  name="plumberTable" id="plumberTable">	
			<tr><td><?php echo $quote['masonName']; ?></td></tr>
			<tr><td><?php echo $quote['masonPhone']; ?></td></tr>
		</table>		
		<br><br>
		<br><br>		
		</div>		
	</body>
</html>
<?php
}
else
header("Location:../loginPage.php");
?>