<?php
require ('../connect.php');
require ('../stock/helperFunctions.php');
session_start();
if(isset($_SESSION["user_name"]))
{
	$formattedDate = $_POST['date'];
	$rawDate = date("Y-m-d", strtotime($formattedDate));
	
	$sql="INSERT INTO purchase_returns (date)
		  VALUES
		  ('$rawDate')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));				 
	$id = mysqli_insert_id($con);
		
	for($count=1;$count<100;$count++)
	{
		if(empty($_POST['itemCode'.$count]))
			break;
		
		$itemCode = $_POST['itemCode'.$count];
		$qty = $_POST['qty'.$count];
		
		$sql="INSERT INTO purchase_return_items (purchase_return_id, itemCode, qty)
			 VALUES
			 ('$id', '$itemCode', '$qty')";

		$result = mysqli_query($con, $sql) or die(mysqli_error($con));
		
		subtract_from_stock($rawDate,$itemCode,$qty);			
	}	
	
	header( "Location: list.php" );
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 
