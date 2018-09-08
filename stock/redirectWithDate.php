<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	$date = date("Y-m-d", strtotime($_POST['date']));
	header("Location:closingStock.php?date=$date");
}
else
{
	header("Location:../index.php");
}	