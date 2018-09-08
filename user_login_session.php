<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require 'connect.php';
if(count($_POST)>0) 
{
	$result = mysqli_query($con,"SELECT * FROM users WHERE username='" . $_POST["user_name"] . "' and password = '". $_POST["password"]."'") or die(mysqli_error($con));

	$row  = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if(is_array($row)) 
	{
		$_SESSION["user_id"] = $row['user_id'];
		$_SESSION["user_name"] = $row['username'];
		header("Location:index.php");
	}
	else
	{
		echo "WRONG PASSWORD";
	}
}
else
{
	header("Location:loginPage.php");
}
?>