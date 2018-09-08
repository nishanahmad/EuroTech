<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	$id = $_GET['id'];	
	
	$queryPO = mysqli_query($con,"SELECT * FROM purchases WHERE Id = '$id'");
	$po = mysqli_fetch_array($queryPO,MYSQLI_ASSOC);
	
	$queryItems = mysqli_query($con,"SELECT * FROM purchase_items WHERE purchase_id = '$id'");
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
	
// Populate auto refresh arrays
	$query = mysqli_query($con,"SELECT number,itemCode,price,description FROM pricelist");
	while($row= mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
		$itemCode = strip_tags($row['itemCode']);
		$price = strip_tags($row['price']);
		$description = strip_tags($row['description']);
		$number = strip_tags($row['number']);

		$numberMap[$itemCode] = $number;
		$numberMap[addslashes($description)] = $number;		

		$itemCodeMap[$number] = $itemCode;
		$itemCodeMap[addslashes($description)] = $itemCode;
		
		$priceMap[$itemCode] = $price;
		$priceMap[$number] = $price;
		$priceMap[addslashes($description)] = $price;
		
		$descriptionMap[$itemCode] = addslashes($description);
		$descriptionMap[$number] = addslashes($description);
	}
	$numberArray = json_encode($numberMap);
	$numberArray = str_replace('\n',' ',$numberArray);
	$numberArray = str_replace('\r',' ',$numberArray);		
	
	$itemCodeArray = json_encode($itemCodeMap);
	$itemCodeArray = str_replace('\n',' ',$itemCodeArray);
	$itemCodeArray = str_replace('\r',' ',$itemCodeArray);			
	
	$priceArray = json_encode($priceMap);
	$priceArray = str_replace('\n',' ',$priceArray);
	$priceArray = str_replace('\r',' ',$priceArray);
	
	$descriptionArray = json_encode($descriptionMap);
	$descriptionArray = str_replace('\n',' ',$descriptionArray);
	$descriptionArray = str_replace('\r',' ',$descriptionArray);		
?>				
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title>Purchase Order <?php echo $id;?></title>
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/form.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">		
		<link rel="stylesheet" href="../css/table.css">
		<link rel="stylesheet" type="text/css" href="../css/demo.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabs.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabstyles.css" />						
		<link rel="stylesheet" type="text/css" href="../css/button.css">
		<script src="../js/addRowPurchase.js"></script>				
		<script src="../js/jquery3.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>	
		<script src="../js/RefreshFunctions.js"></script>		
		<script>
			var numberList = '<?php echo $numberArray;?>';
			var number_array = JSON.parse(numberList);
			var numberArray = number_array;									
			
			var itemCodeList = '<?php echo $itemCodeArray;?>';
			var itemCode_array = JSON.parse(itemCodeList);
			var itemCodeArray = itemCode_array;												
			
			var priceList = '<?php echo $priceArray;?>';
			var price_array = JSON.parse(priceList);
			var priceArray = price_array;
			
			var descriptionList = '<?php echo $descriptionArray;?>';
			var description_array = JSON.parse(descriptionList);
			var descriptionArray = description_array;
		</script>		
	</head>
	<body>	
			<div class="container" style="width:100% !important;font-size:1.60em !important">
			<section>
				<div class="tabs tabs-style-linemove">
					<nav>
						<ul>
							<li><a href="../priceList/list.php" class="icon icon-date"><span>Price List</span></a></li>
							<li><a href="../quotations/list.php" class="icon icon-display"><span>Quotations</span></a></li>
							<li><a href="../orders/list.php" class="icon icon-box"><span>Orders</span></a></li>
							<li class="tab-current"><a href="list.php" class="icon icon-tools"><span>Purchases</span></a></li>
							<li><a href="../returns/list.php" class="icon icon-upload"><span>Returns</span></a></li>														
						</ul>
					</nav>
				</div>
			</section>
		</div>			
			<br><br>
			<div align="center">
			<img src="../images/logo.png" width="250" height="100">
			<br><br>			
			<div class="inner contact">
				<div class="contact-form">
					<form id="contact-us" method="post" action="update.php">
						<input type="hidden" name="id" value="<?php echo $id;?>">
						<h3>po-<?php echo $id;?></h3>					
						<div class="relative fullwidth col-xs-12" align="center">
							<input type="text" style="width:15%;height:20px;" name="date" id="date" class="form" readonly value="<?php echo $po['date']; ?>" />						
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
							<td style="text-align:center;"><input type="text"  name="number<?php echo $count;?>" value="<?php echo $item['number'];?>" onChange="numberRefresh();" /></td>	
							<td style="text-align:center;"><input type="text"  name="itemCode<?php echo $count;?>" value="<?php echo $item['itemCode'];?>" onChange="itemRefresh();" /></td>	
							<td style="text-align:center;"><input type="text"  name="description<?php echo $count;?>" value="<?php echo $item['description'];?>" onChange="descriptionRefresh();"/></td>	
							<td style="text-align:center;"><input type="text"  name="qty<?php echo $count;?>" value="<?php echo $item['qty'];?>" /></td>	
							</tr>
<?php							
							$count++;								
						}	
						?>
						</table>
						<img src="../images/addRow.jpg" style="float:left;margin-left:12.5%;cursor:pointer" title="Add Row" height="35px" width="35px" onclick="addField('tbl',<?php echo $count;?>);" />						
						<br><br>
						<table>
						<tr>
						<td><button type="submit" class="btn">Update</button></td>
						<td style="vertical-align:bottom"><a href="view.php?id=<?php echo $po["id"]; ?>">Cancel</a></td>						
						</tr>
						</table>
						<br><br><br>
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