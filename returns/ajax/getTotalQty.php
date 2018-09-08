<?php
require '../../connect.php';
session_start();
if(isset($_SESSION["user_name"]))
{
	$str = $_REQUEST["str"];
	$type = $_REQUEST["type"];
	$customerPhone = $_REQUEST["phone"];
	
	if($customerPhone != '')
	{
		if($type == 'itemCode')
			$itemCode = $str;
		else if($type == 'number')
		{
			$query = mysqli_query($con,"SELECT * FROM pricelist WHERE number='$str' ") or die(mysqli_error($con));				 	
			$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
			$itemCode = $row['itemCode'];
		}
		else if($type == 'description')		
		{
			$query = mysqli_query($con,"SELECT * FROM pricelist WHERE description='$str' ") or die(mysqli_error($con));				 	
			$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
			$itemCode = $row['itemCode'];		
		}

		$orderArray = array();
		$queryOrders = mysqli_query($con,"SELECT * FROM orders WHERE customerPhone='$customerPhone' ") or die(mysqli_error($con));				 	
		while($order = mysqli_fetch_array($queryOrders,MYSQLI_ASSOC))
		{
			$orderArray[] = $order['id'];
		}
		
		$orderList = implode(',', $orderArray);

		$querySum = mysqli_query($con,"SELECT SUM(qty) FROM order_items WHERE itemCode='$itemCode' AND order_id IN (".$orderList.") ") or die(mysqli_error($con));				 	
		$row = mysqli_fetch_array($querySum);	

		echo $row[0];		
	}
	else
		echo '0';		

}
?>