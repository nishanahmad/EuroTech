<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 =>'date', 
	2 =>'masonName', 
	3 => 'masonPhone',
	4=> 'customerName',
	5=> 'customerPhone'
);

// getting total number records without any search

	$sql = "SELECT id, date, masonName, masonPhone, customerName, customerPhone";
	$sql.=" FROM quotations WHERE status='Pending' ORDER BY date DESC";
	$query=mysqli_query($con, $sql) or die(mysqli_error($con));
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


	$sql = "SELECT id, date, masonName, masonPhone, customerName, customerPhone";
	$sql.=" FROM quotations WHERE status='Pending' ";



// getting records as per search parameters
if( !empty($requestData['columns'][0]['search']['value']) )
{
	$sql.=" AND id LIKE '".$requestData['columns'][0]['search']['value']."%' ";
}

if( !empty($requestData['columns'][1]['search']['value']) )
{
	$pattern_day1 = '/^[0-9]{2}$/';
	$pattern_day2 = '/^[0-9]{2}-$/';
	$pattern_day3 = '/^[0-9]{2}-[0-9]{1}$/';
	
	$pattern_day_month1 = '/^[0-9]{2}-[0-9]{2}$/';
	$pattern_day_month2 = '/^[0-9]{2}-[0-9]{2}-$/';
	$pattern_day_month3 = '/^[0-9]{2}-[0-9]{2}-[0-9]{1}$/';
	$pattern_day_month4 = '/^[0-9]{2}-[0-9]{2}-[0-9]{2}$/';
	$pattern_day_month5 = '/^[0-9]{2}-[0-9]{2}-[0-9]{3}$/';
	
	$pattern_month = '/^[a-z A-Z]/';

	$full_pattern = '/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/';
	if(preg_match($pattern_day1, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day2, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day3, $requestData['columns'][1]['search']['value']))
	{
		$day_array[0] = $requestData['columns'][1]['search']['value'][0];
		$day_array[1] = $requestData['columns'][1]['search']['value'][1];
		$day = implode ('', $day_array);
		$sql.=" AND date LIKE '%".$day."' ";	
	}

	if(preg_match($pattern_day_month1, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month2, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month3, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month4, $requestData['columns'][1]['search']['value']) || preg_match($pattern_day_month5, $requestData['columns'][1]['search']['value']))
	{
		$month_day_array[0] = $requestData['columns'][1]['search']['value'][3];
		$month_day_array[1] = $requestData['columns'][1]['search']['value'][4];
		$month_day_array[2] = $requestData['columns'][1]['search']['value'][2];
		$month_day_array[3] = $requestData['columns'][1]['search']['value'][0];
		$month_day_array[4] = $requestData['columns'][1]['search']['value'][1];
		
		$month_day = implode ('', $month_day_array);
		$sql.=" AND date LIKE '%".$month_day."' ";	

	}
	
	if( preg_match($pattern_month, $requestData['columns'][1]['search']['value']) )
	{
		$date = date_parse($requestData['columns'][1]['search']['value']);
		$month = $date['month'];
		$sql.=" AND month(date) = '".$month."' AND year(date)= year(CURDATE())";	
	}	

	if(	preg_match($full_pattern, $requestData['columns'][1]['search']['value'])	)
	{
		$full_date = date('Y-m-d', strtotime($requestData['columns'][1]['search']['value']));
		$sql.=" AND date LIKE '".$full_date."' ";	
	}	
	
}

if( !empty($requestData['columns'][2]['search']['value']) )
{
	$sql.=" AND masonName LIKE '".$requestData['columns'][2]['search']['value']."%' ";
}

if( !empty($requestData['columns'][3]['search']['value']) )
{
	$sql.=" AND masonPhone LIKE '%".$requestData['columns'][3]['search']['value']."%' ";
}

if( !empty($requestData['columns'][4]['search']['value']) )
{
	$sql.=" AND customerName LIKE '".$requestData['columns'][4]['search']['value']."%' ";
}

if( !empty($requestData['columns'][5]['search']['value']) )
{
	$sql.=" AND customerPhone LIKE '".$requestData['columns'][5]['search']['value']."%' ";
}


$query=mysqli_query($con, $sql) or die(mysqli_error($con));
$totalFiltered = mysqli_num_rows($query);
	
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length

$query=mysqli_query($con, $sql) or die(mysqli_error($con));

$data = array();

while( $row=mysqli_fetch_array($query) ) 
{
	$nestedData=array(); 

	$nestedData[] = '<a href="view.php?id='.$row["id"].'">qtn-'.$row["id"].'</a>';
	$nestedData[] = date('d-m-Y',strtotime($row['date']));
	$nestedData[] = $row['masonName'];
	$nestedData[] = $row["masonPhone"];
	$nestedData[] = $row["customerName"];
	$nestedData[] = $row["customerPhone"];

	$data[] = $nestedData;
}

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $data,
			);

echo json_encode($json_data);

}
else
	header("Location:loginPage.php");
?>