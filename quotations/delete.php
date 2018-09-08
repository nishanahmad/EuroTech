<?php
require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_GET['id'];

	$deleteQuotation = mysqli_query($con,"DELETE FROM quotations WHERE id='$id' ") or die(mysqli_error($con));				 
								
	$delete_Quotation_Items = mysqli_query($con,"DELETE FROM quotation_items WHERE quotation_id='$id'") or die(mysqli_error($con));				 							

	header( "Location: list.php" );
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 
					
