<?php
function subtract_from_stock($date,$itemCode,$qty)
{
	require '../connect.php';
	
	if(empty($date) || empty($itemCode) || empty($qty) )
	{
		die("Error...... Cannot update closing stock. Please contact administrator");
	}	
	
	$date = date('Y-m-d', strtotime($date));
	
	$latest_stock_query = mysqli_query($con,"SELECT date,closing_stock FROM closing_stock WHERE 
											itemCode = '$itemCode' 
											ORDER BY DATE DESC LIMIT 1  ") or die(mysqli_error($con));	
	$latest_stock_array = mysqli_fetch_array($latest_stock_query,MYSQLI_ASSOC);
	
	$latest_date = date('Y-m-d', strtotime($latest_stock_array['date']));
	$latest_stock = $latest_stock_array['closing_stock'];
	
	$previous_stock_query = mysqli_query($con,"SELECT * FROM closing_stock 
													WHERE itemCode='$itemCode'  AND date <= '$date'	
													ORDER BY date DESC LIMIT 1") 
													or die(mysqli_error($con));
	if(mysqli_num_rows($previous_stock_query) == 0)
	{
		echo $date;echo '<br>';echo $itemCode;echo '<br>';echo $qty;echo '<br>';
		exit('Check the date');
	}
	else
	{
		$previous_stock_array = mysqli_fetch_array($previous_stock_query,MYSQLI_ASSOC);
		$previous_date = date('Y-m-d', strtotime($previous_stock_array['date']));
		$previous_stock = $previous_stock_array['closing_stock'];		
	}	
	



	if($date == $latest_date)
	{
		$update_stock = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$date' AND itemCode = '$itemCode'") 
										or die(mysqli_error($con));	
	}	
	
	else if($date > $latest_date)
	{
		$insert_stock = mysqli_query($con,"INSERT INTO closing_stock(date,itemCode,closing_stock) 
										VALUES('$date','$itemCode','$latest_stock') ") 
										or die(mysqli_error($con));	
		$update_stock = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$date' AND itemCode = '$itemCode'") 
										or die(mysqli_error($con));				
	}	


	// Process the entries of previous dates 
	else if($date < $latest_date)
	{
		// Check if the old date is already present in DB
		$match_query = mysqli_query($con,"SELECT date FROM closing_stock  
										  WHERE date = '$date' AND itemCode = '$itemCode'") 
										  or die(mysqli_error($con));
		
		// If match found update all the values from that date to the latest date in DB
		if(mysqli_num_rows($match_query) > 0) 
		{
			$loop_date = $date;
			
			//	Updating the post date value		
			$update_stock = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$date' AND itemCode = '$itemCode'") 
										or die(mysqli_error($con));			

			//	Updating all values between post date and latest date (latest date included) pesent in DB in a while loop									
			while($loop_date <= $latest_date)
			{
				$loop_date = date('Y-m-d', strtotime($loop_date . ' +1 day'));
				
				$update_stock = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$loop_date' AND itemCode = '$itemCode'") 
										or die(mysqli_error($con));			
			}	
		}

		// If no match found, query for previuos date entry and insert date with the same closing stock. 
		// Then repeat the above while loop		
		else if(mysqli_num_rows($match_query) == 0)
		{
			$insert_stock = mysqli_query($con,"INSERT INTO closing_stock(date,itemCode,closing_stock) 
											VALUES('$date','$itemCode','$previous_stock') ") 
											or die(mysqli_error($con));				
			
			$loop_date = $date;
			
			//	Updating the date value		
			$update_stock = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$date' AND itemCode = '$itemCode'") 
										or die(mysqli_error($con));			

			//	Updating all values between date and latest date (latest date included) pesent in DB									
			while($loop_date <= $latest_date)
			{
				$loop_date = date('Y-m-d', strtotime($loop_date . ' +1 day'));
				
				$update_stock = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$loop_date' AND itemCode = '$itemCode'") 
										or die(mysqli_error($con));
			}							
		}	

	}	

}
//subtract_from_stock('2017-12-21','45ELB110',10);
?>