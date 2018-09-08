<?php
//ini_set('display_errors', 0);
error_reporting(E_ALL);

require '../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{	
	foreach($_POST as $key => $value)
	{
		$sql="UPDATE minimum_stock SET qty = '$value' WHERE itemCode = '$key'";
		$result = mysqli_query($con, $sql) or die(mysqli_error($con));				   
	}
	
	header( "Location: index.php" );

	mysqli_close($con); 
}
else
{
	header( "Location: ../index.php" );
}	
?> 