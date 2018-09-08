<?php
require '../connect.php';
require '../stock/helperFunctions.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_GET['id'];

	$query_PO = mysqli_query($con,"SELECT * FROM purchases WHERE id='$id'") or die(mysqli_error($con));				 															
	$po = mysqli_fetch_array($query_PO,MYSQLI_ASSOC);
	$date = $po['date'];
	
	if($po['status'] == 'confirmed')
	{
		$query_PO_Items = mysqli_query($con,"SELECT * FROM purchase_items WHERE purchase_id='$id'") or die(mysqli_error($con));				 														
		
		while($item = mysqli_fetch_array($query_PO_Items,MYSQLI_ASSOC))
		{
			subtract_from_stock($date,$item['itemCode'],$item['qty']);
		}		
	}	

	$delete_PO_Items = mysqli_query($con,"DELETE FROM purchase_items WHERE purchase_id='$id'") or die(mysqli_error($con));				 									

	$deletePO = mysqli_query($con,"DELETE FROM purchases WHERE id='$id'") or die(mysqli_error($con));				 									
		
	header( "Location: list.php" );
}
else
{
	header( "Location: ../index.php" );
}	
?> 
