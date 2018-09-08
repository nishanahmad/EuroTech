<?php
require '../connect.php';
require '../stock/helperFunctions.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$quotation_id = $_POST['id'];
	
	$queryQuotation ="SELECT * FROM quotations WHERE id = '$quotation_id'"; 
	$resultQuotation = mysqli_query($con, $queryQuotation) or die(mysqli_error($con));			
	$quotation = mysqli_fetch_array($resultQuotation,MYSQLI_ASSOC);	
	
	$today = date("Y/m/d");
	$masonName = $quotation['masonName'];
	$masonPhone = $quotation['masonPhone'];
	$customerName = $quotation['customerName'];
	$customerPhone = $quotation['customerPhone'];
	$discount = $quotation['discount'];
	
	// insert order data from quotation data
	$insertOrder ="INSERT INTO orders (date, quotation_id, masonName, masonPhone, customerName, customerPhone, discount)
		 VALUES
		 ('$today', '$quotation_id', '$masonName', '$masonPhone', '$customerName', '$customerPhone', '$discount')";

	$resultInsertOrder = mysqli_query($con, $insertOrder) or die(mysqli_error($con));				 
	
	$order_id = mysqli_insert_id($con);	
	
	
	// insert order items from quotation items in a loop
	$queryQuotationItems ="SELECT * FROM quotation_items WHERE quotation_id = '$quotation_id'"; 
	$resultQuotationItems = mysqli_query($con, $queryQuotationItems) or die(mysqli_error($con));			
	while($quotationItem = mysqli_fetch_array($resultQuotationItems,MYSQLI_ASSOC))	
	{
		$itemCode = $quotationItem['itemCode'];
		$qty = $quotationItem['qty'];
		$insertOrderItem ="INSERT INTO order_items (order_id, itemCode, qty)
			 VALUES
			 ('$order_id', '$itemCode', '$qty')";

		$resultinsertOrderItem = mysqli_query($con, $insertOrderItem) or die(mysqli_error($con));
		
		subtract_from_stock($today,$itemCode,$qty);	
	}

	// update quotation status	
	$updateOrder = mysqli_query($con,"UPDATE quotations SET status='Order Confirmed'
									  WHERE id='$quotation_id' ") or die(mysqli_error($con));				 

	header( "Location: list.php" );
										
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 
