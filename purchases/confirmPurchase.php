<?php
require '../connect.php';
require '../stock/helperFunctions.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_POST['id'];
	$today = date("Y/m/d");	
	
	$queryPurchaseItems ="SELECT * FROM purchase_items WHERE purchase_id = '$id'"; 
	$resultPurchaseItems = mysqli_query($con, $queryPurchaseItems) or die(mysqli_error($con));			
	while($item = mysqli_fetch_array($resultPurchaseItems,MYSQLI_ASSOC))	
	{
		$itemCode = $item['itemCode'];
		$qty = $item['qty'];
		subtract_from_stock($today,$itemCode,-$qty);	
	}

	$updatePurchase = mysqli_query($con,"UPDATE purchases SET status='confirmed' WHERE id='$id' ") or die(mysqli_error($con));				 

	header( "Location: view.php?id=$id" );
}
else
{
	header( "Location: ../index.php" );
}	
?> 
