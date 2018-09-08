<?php
require '../connect.php';
require '../stock/helperFunctions.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_POST['id'];
	$formattedDate = $_POST['date'];
	$rawDate = date("Y-m-d", strtotime($formattedDate));
	
	$updatePO = mysqli_query($con,"UPDATE purchases SET date='$rawDate' WHERE id='$id' ") or die(mysqli_error($con));				 
								
	$queryPO = mysqli_query($con,"SELECT * FROM purchases WHERE id='$id' ") or die(mysqli_error($con));				 								
	$po = mysqli_fetch_array($queryPO,MYSQLI_ASSOC))	
								
	if($po['status'] == 'confirmed')
	{
		$query_PO_Items = mysqli_query($con,"SELECT * FROM purchase_items WHERE purchase_id='$id'") or die(mysqli_error($con));				 														
		
		while($item = mysqli_fetch_array($query_PO_Items,MYSQLI_ASSOC))
		{
			subtract_from_stock($rawDate,$item['itemCode'],$item['qty']);
		}		
	}		

	$delete_PO_Items = mysqli_query($con,"DELETE FROM purchase_items WHERE purchase_id='$id'") or die(mysqli_error($con));				 									

	for($count=1;$count<100;$count++)
	{
		if(empty($_POST['itemCode'.$count]))
			break;
		
		$itemCode = $_POST['itemCode'.$count];
		$qty = $_POST['qty'.$count];
		
		$sql="INSERT INTO purchase_items (purchase_id, itemCode, qty)
			 VALUES
			 ('$id', '$itemCode', '$qty')";

		$reInsertItems = mysqli_query($con, $sql) or die(mysqli_error($con));	
		
		if($po['status'] == 'confirmed')		
			subtract_from_stock($rawDate,$itemCode,-$qty);
	}	
		
	header( "Location: list.php" );
}
else
{
	header( "Location: ../index.php" );
}	
?> 
	
?>							
