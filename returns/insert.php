<?php
require '../connect.php';
require '../stock/helperFunctions.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$formattedDate = $_POST['date'];
	$rawDate = date("Y-m-d", strtotime($formattedDate));
	$customerName = $_POST['customerName'];
	$customerPhone = $_POST['customerPhone'];
	$discount = $_POST['discount'];
	
	$sql="INSERT INTO returns (date, customerName, customerPhone, discount)
		  VALUES
		  ('$rawDate', '$customerName', '$customerPhone', '$discount')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 
	$id = mysqli_insert_id($con);
		
	for($count=1;$count<100;$count++)
	{
		if(empty($_POST['itemCode'.$count]))
			break;
		
		$itemCode = $_POST['itemCode'.$count];
		$qty = $_POST['qty'.$count];
		
		$sql="INSERT INTO return_items (return_id, itemCode, qty)
			 VALUES
			 ('$id', '$itemCode', '$qty')";

		$result = mysqli_query($con, $sql) or die(mysqli_error($con));
		subtract_from_stock($rawDate,$itemCode,-$qty);	
	}	
	
	header( "Location: list.php" );
}
else
{
	header( "Location: ../index.php" );
}	
?> 
