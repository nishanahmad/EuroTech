<?php
require '../connect.php';
require '../stock/helperFunctions.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_POST['id'];
	$formattedDate = $_POST['date'];
	$rawDate = date("Y-m-d", strtotime($formattedDate));
	$customerName = $_POST['customerName'];
	$customerPhone = $_POST['customerPhone'];
	$masonName = $_POST['masonName'];
	$masonPhone = $_POST['masonPhone'];
	$discount = $_POST['discount'];

	$queryOrder = mysqli_query($con,"SELECT * FROM orders WHERE id='$id' ") or die(mysqli_error($con));				 	
	$order = mysqli_fetch_array($queryOrder,MYSQLI_ASSOC);
	$quotation_id = $order['quotation_id'];	
									 
	$updateOrder = mysqli_query($con,"UPDATE orders SET date='$rawDate', masonName='$masonName', masonPhone='$masonPhone',
													  customerName = '$customerName', customerPhone = '$customerPhone', discount = '$discount' 	
								WHERE id='$id' 
								") or die(mysqli_error($con));				 

	$updateQuote = mysqli_query($con,"UPDATE quotations SET masonName='$masonName', masonPhone='$masonPhone',
													  customerName = '$customerName', customerPhone = '$customerPhone', discount = '$discount' 	
								WHERE id='$quotation_id' 
								") or die(mysqli_error($con));				 								
								
	$query_order_Items = mysqli_query($con,"SELECT * FROM order_items WHERE order_id='$id'") or die(mysqli_error($con));				 														
	
	while($item = mysqli_fetch_array($query_order_Items,MYSQLI_ASSOC))
	{
		subtract_from_stock($rawDate,$item['itemCode'],-$item['qty']);
	}
								
	$delete_Order_Items = mysqli_query($con,"DELETE FROM order_items WHERE order_id='$id'") or die(mysqli_error($con));				 							
	$delete_Quotation_Items = mysqli_query($con,"DELETE FROM quotation_items WHERE quotation_id='$quotation_id'") or die(mysqli_error($con));				 							
	
	for($count=1;$count<100;$count++)
	{
		if(empty($_POST['itemCode'.$count]))
			break;
		
		$itemCode = $_POST['itemCode'.$count];
		$qty = $_POST['qty'.$count];
		
		$sql="INSERT INTO order_items (order_id, itemCode, qty)
			 VALUES
			 ('$id', '$itemCode', '$qty')";

		$reInsertItems = mysqli_query($con, $sql) or die(mysqli_error($con));	
		subtract_from_stock($rawDate,$itemCode,$qty);		
		
		$sql2 = "INSERT INTO quotation_items (quotation_id, itemCode, qty)
				VALUES
				('$quotation_id', '$itemCode', '$qty')";
		$reInsertItems2 = mysqli_query($con, $sql2) or die(mysqli_error($con));					
	}	
		
	header( "Location: list.php" );
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 
	
?>							
