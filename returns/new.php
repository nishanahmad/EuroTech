<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
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
		<title>New Return</title>
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/form.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">	
		<link rel="stylesheet" type="text/css" href="../css/demo.css" />		
		<link rel="stylesheet" type="text/css" href="../css/tabs.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabstyles.css" />				
		<link rel="stylesheet" href="../css/qTable.css">
		<script src="../js/jquery3.min.js"></script>
		<script src="../js/addRowReturn.js"></script>				
		<script src="../js/RefreshFunctions.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>		
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
			
			function getTotalQty(str,type,rowNumber) 
			{
				var customerPhone = document.getElementById("customerPhone").value;
				if(customerPhone == '')
				{					
					alert('Please select customer details first');
					window.location.reload();
				}
				if (str.length == 0) { 
					document.getElementById("totalQty".concat(rowNumber)).innerHTML = "";
					return;
				} else {
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
							document.getElementById("totalQty".concat(rowNumber)).innerHTML = this.responseText;
						}
					};
					xmlhttp.open("GET", "ajax/getTotalQty.php?str=" + str + "&type=" + type + "&phone="+ customerPhone, true);
					xmlhttp.send();
				}
			}			
		</script>
		<script>
		$(function() {

		var pickerOpts = { dateFormat:"dd-mm-yy"}; 
					
		$( "#date" ).datepicker(pickerOpts);

		});
		</script>	
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
			<br><br>
			<div align="center">
			<img src="../images/logo.png" width="250" height="100">
			<br><br>			
			<div class="inner contact">
				<div class="contact-form">
					<form id="contact-us" method="post" action="insert.php">
						
						
						<table style="width:30%" class="" id="customerTable" >	
							<tr><td><input type="text" name="date" id="date" class="form" required value="<?php echo date('d-m-Y'); ?>" /></td></tr>								
							<tr><td>
							<select name="customerName" id="customerName" required class="form">
								<option value = "">--- Customer Name ---</option>
								<?php
								$customerNamesQuery = mysqli_query($con,"SELECT DISTINCT customerName FROM orders ORDER BY customerName");
								while( $row=mysqli_fetch_assoc($customerNamesQuery)) 
								{
									echo "<option value='".$row['customerName']."'>".$row['customerName']."</option>";    
								}
								?>
							</select>
							</td></tr>							
							<tr><td>							
							<select name="customerPhone" id="customerPhone" class="form">
								<option value = "">--- Customer Phone ---</option>
								<?php
								$customerPhonesQuery = mysqli_query($con,"SELECT DISTINCT customerPhone FROM orders ORDER BY customerName");
								while( $row=mysqli_fetch_assoc($customerPhonesQuery)) 
								{
									echo "<option value='".$row['customerPhone']."'>".$row['customerPhone']."</option>";    
								}
								?>
							</select>
							</td></tr>
						</table>
					
						<div class="relative fullwidth col-xs-12" align="center">
							<table class="qTable" id="tbl" >
								<tr>
									<th style="width:10%;text-align:center;">#</th>
									<th style="width:15%;text-align:center;">Item Code</th>
									<th style="text-align:center;">Description</th>
									<th style="width:10%;text-align:center;">Taken Qty</th>
									<th style="width:10%;text-align:center;">Return Qty</th>
									<th style="width:10%;text-align:center;">Price</th>
									<th style="width:15%;text-align:center;">Total</th>									
								</tr>
								<?php 
								$count = 1;
								while($count<10)
								{
?>							<tr>
							<td style="text-align:center;"><input type="text" name="number<?php echo $count;?>" id="number<?php echo $count;?>" onChange="numberRefresh();getTotalQty(this.value,'number',<?php echo $count;?>);" /></td>	
							<td style="text-align:center;"><input type="text" name="itemCode<?php echo $count;?>" id="itemCode<?php echo $count;?>" onChange="itemRefresh();getTotalQty(this.value,'itemCode',<?php echo $count;?>);" /></td>
							<td style="text-align:center;"><input type="text" name="description<?php echo $count;?>" id="description<?php echo $count;?>" onChange="descriptionRefresh();getTotalQty(this.value,'description',<?php echo $count;?>);" /></td>
							<td style="text-align:center;"><div id="totalQty<?php echo $count;?>"></div></td>
							<td style="text-align:center;"><input type="text" name="qty<?php echo $count;?>" id="qty<?php echo $count;?>" onChange="qtyRefresh();updateTotal();discountRefresh();"/></td>
							<td style="text-align:center;"><input type="text" readonly id="price<?php echo $count;?>" value=""></td>
							<td style="text-align:center;"><input type="text" readonly id="total<?php echo $count;?>" value=""></td>							
							</tr>
<?php							
							$count++;								
						}	
						?>
						</table>
						<br/>
						<table class="qTable" id="tbl2">						
						<tr>
						<td style="text-align:right;"></td>
						<td style="width:15%;text-align:left;"><input type="text" readonly id="total"></td>
						</tr>						
						<tr>
						<td style="text-align:right;">Discount %</td>
						<td style="width:15%;text-align:left;"><input type="text" name="discount" id="discount" onChange="discountRefresh();" /></td>
						</tr>												
						<tr>
						<td style="text-align:right;">Discount Amount</td>
						<td style="width:15%;text-align:left;"><input type="text" readonly name="discountAmount" id="discountAmount" /></td>
						</tr>												
						<tr>
						<td style="text-align:right;"><b>Total</b></td>
						<td style="width:15%;text-align:left;"><input type="text" readonly name="discountedTotal" id="discountedTotal"/></td>
						</tr>																		
						</table>
						<img src="../images/addRow.jpg" style="float:left;margin-left:12.5%;cursor:pointer" title="Add Row" height="35px" width="35px" onclick="addField('tbl',<?php echo $count;?>);" />
						<br/><br/>						
						<button type="submit" class="btn">Save</button>
						<br/><br/><br/><br/><br/>
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