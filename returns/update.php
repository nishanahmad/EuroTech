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
	$discount = $_POST['discount'];

	$updateReturn = mysqli_query($con,"UPDATE returns SET date='$rawDate',customerName = '$customerName', customerPhone = '$customerPhone', discount = '$discount' 	
									   WHERE id='$id' ") or die(mysqli_error($con));				 
				
	$query_return_Items = mysqli_query($con,"SELECT * FROM return_items WHERE return_id='$id'") or die(mysqli_error($con));				 														
	
	while($item = mysqli_fetch_array($query_return_Items,MYSQLI_ASSOC))
	{
		subtract_from_stock($rawDate,$item['itemCode'],$item['qty']);
	}
				
	$delete_Return_Items = mysqli_query($con,"DELETE FROM return_items WHERE return_id='$id'") or die(mysqli_error($con));				 							

	for($count=1;$count<100;$count++)
	{
		if(empty($_POST['itemCode'.$count]))
			break;
		
		$itemCode = $_POST['itemCode'.$count];
		$qty = $_POST['qty'.$count];
		
		$sql="INSERT INTO return_items (return_id, itemCode, qty)
			 VALUES
			 ('$id', '$itemCode', '$qty')";

		$reInsertItems = mysqli_query($con, $sql) or die(mysqli_error($con));	
		subtract_from_stock($rawDate,$itemCode,-$qty);		
	}	
		
	header( "Location: list.php" );
}
else
{
	header( "Location: ../index.php" );
}	
?> 
					
