<?php
	require '../../connect.php';
	
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
?>				
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title>qtn-<?php echo $id;?></title>
		<link rel="stylesheet" type="text/css" href="../../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../../css/form.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">		
		<link rel="stylesheet" href="../../css/button.css">
		<link rel="stylesheet" type="text/css" href="../../css/demo.css" />
		<link rel="stylesheet" type="text/css" href="../../css/qTable.css" />				
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
		<div align="center" id="fullPage" style="background-color:white;color:#000000">
			<img src="../../images/logo.png" width="300" height="100">
			<br>
			<font size="2">
			<b>Kokkur Post, Changaramkulam, Kerala – 679591<br>		
			Tel : 0494 – 2653 111, Fax : 0494 2654 777</b><br>		
			<u>mail@eurotechpipe.com | www.eurotechpipe.com</u>					
			<br>
			<div style="float:left;margin-left:23%;text-align:left;width:50%;">
				REF <?php echo $id;?><br>
				Name & Phone of Consignee:<br>  	
				<b><?php echo $quote['customerName'].', '.$quote['customerPhone']; ?></b>

			</div>			
			<div style="float:right;margin-right:23%;text-align:left;">
			Date :&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date("d-m-Y", strtotime($quote['date']));?>
			</div>							
			</font>
			
			<div class="inner contact">
				<div class="contact-form">
						<div class="relative col-xs-12" align="center">
							<br>
							<table class="qTablePdf" id="tbl" style="width:70%">
								<tr>
									<th style="width:5%;text-align:center;">No.</th>
									<th style="width:12%;text-align:center;">Item Code</th>
									<th style="text-align:center;">Description</th>
									<th style="width:5%;text-align:center;">Qty</th>
									<th style="width:13%;text-align:center;">Price</th>
									<th style="width:13%;text-align:center;">Total</th>
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
						<table class="qTablePdf" id="tbl2" style="width:70%">
						<tr>
						<td style="width:74%;padding:5px;border-bottom:0px;"></td>
						<td style="width:26%;text-align:right;padding:5px;" colspan="2"><?php echo $total;?></td>
						</tr>						
						<tr>
						<td style="font-family: Geneva, Tahoma, Verdana, sans-serif;font-size:7;font-weight: bold;padding:5px;border-bottom:0px;border">Incoterms&emsp;&emsp;&emsp;:&emsp;&emsp;DAP(delivered at place)</td>						
						<td style="width:13%;text-align:right;padding:5px;border-right:0px !important;">Discount</td>
						<td style="width:13%;text-align:right;padding:5px"><?php echo $quote['discount'];?></td>						
						</tr>												
						<tr>
						<td style="padding:5px;width:74%;font-family: Geneva, Tahoma, Verdana, sans-serif;font-size:7;font-weight: bold;border-bottom:0px;">Payment terms&emsp;:&emsp;&emsp;25% advance and balance 75% before delivery</td>						
						<td style="padding:5px;width:13%;text-align:right;border-right:0px !important;">Round Off</td>
						<td style="padding:5px;width:13%;text-align:right;"><?php echo round($total * $quote['discount']/100,2);?></td>
						</tr>												
						<tr style="border-bottom:1px solid black;">
						<td style="padding:5px;width:74%;font-family: Geneva, Tahoma, Verdana, sans-serif;font-size:7;font-weight: bold;">Delivery&emsp;&emsp;&emsp;&emsp;:&emsp;&emsp;7 days from the date of purchase order</td>
						<td style="padding:5px;width:13%;text-align:right;border-right:0px !important;"><b>Total</b></td>
						<td style="padding:5px;width:13%;text-align:right;"><b><?php echo round($total - ($total * $quote['discount']/100),2);?></b></td>						
						</tr>																		
						<tr>
						<td style="width:74%;"></td>
						<td style="width:26%;text-align:left;" colspan="2">Contact 9072833273<br>NABA<br>Sales coordinator,ppr-c pipe system</td>
						</tr>
						</table>
						<br><br>
						<?php echo substr($quote['masonPhone'], -4);?>											
						<br><br>
					</div>						
				<br><br><br>						
			</div>
		</div>
		</div>
		<br><br>
		<br><br>		
		</div>		
	</body>
</html>