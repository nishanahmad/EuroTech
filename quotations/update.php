<?php
require '../connect.php';
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

	$updateQuotation = mysqli_query($con,"UPDATE quotations SET date='$rawDate', masonName='$masonName', masonPhone='$masonPhone',
													  customerName = '$customerName', customerPhone = '$customerPhone', discount = '$discount' 	
								WHERE id='$id' 
								") or die(mysqli_error($con));				 
								
	$delete_Quotation_Items = mysqli_query($con,"DELETE FROM quotation_items WHERE quotation_id='$id'") or die(mysqli_error($con));				 							

	for($count=1;$count<100;$count++)
	{
		if(empty($_POST['itemCode'.$count]))
			break;
		
		$itemCode = $_POST['itemCode'.$count];
		$qty = $_POST['qty'.$count];
		
		$sql="INSERT INTO quotation_items (quotation_id, itemCode, qty)
			 VALUES
			 ('$id', '$itemCode', '$qty')";

		$reInsertItems = mysqli_query($con, $sql) or die(mysqli_error($con));	
	}	
		
	header( "Location: list.php" );
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 
					
