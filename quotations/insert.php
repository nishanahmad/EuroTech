<?php
require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$formattedDate = $_POST['date'];
	$rawDate = date("Y-m-d", strtotime($formattedDate));
	$customerName = $_POST['customerName'];
	$customerPhone = $_POST['customerPhone'];
	$masonName = $_POST['masonName'];
	$masonPhone = $_POST['masonPhone'];
	$discount = $_POST['discount'];
	
	$sql="INSERT INTO quotations (date, masonName, masonPhone, customerName, customerPhone, discount, status)
		 VALUES
		 ('$rawDate', '$masonName', '$masonPhone', '$customerName', '$customerPhone', '$discount' ,'Pending')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 
	$id = mysqli_insert_id($con);
		
	for($count=1;$count<100;$count++)
	{
		if(empty($_POST['itemCode'.$count]))
			break;
		
		$itemCode = $_POST['itemCode'.$count];
		$qty = $_POST['qty'.$count];
		
		$sql="INSERT INTO quotation_items (quotation_id, itemCode, qty)
			 VALUES
			 ('$id', '$itemCode', '$qty')";

		$result = mysqli_query($con, $sql) or die(mysqli_error($con));	
	}	
	
	header( "Location: list.php" );
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 
