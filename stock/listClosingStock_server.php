<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require 'connect.php';


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 =>'date', 
	1 => 'itemCode',
	2=> 'closing_stock'

);

// getting total number records without any search

	$sql = "SELECT date,itemCode,closing_stock";
	$sql.=" FROM closing_stock";
	$query=mysqli_query($con, $sql) or die("employee-grid-data.php: get nas");
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


	$sql = "SELECT date,itemCode,closing_stock";
	$sql.=" FROM closing_stock WHERE 1 = 1";



// getting records as per search parameters
if( !empty($requestData['columns'][0]['search']['value']) )
{  
		$full_date = date('Y-m-d', strtotime($requestData['columns'][0]['search']['value']));
		$sql.=" AND date LIKE '".$full_date."' ";	
}

if( !empty($requestData['columns'][1]['search']['value']) )
{
	$sql.=" AND itemCode LIKE '".$requestData['columns'][1]['search']['value']."%' ";
}

if( !empty($requestData['columns'][2]['search']['value']) )
{ 
	$sql.=" AND closing_stock LIKE '".$requestData['columns'][2]['search']['value']."%' ";
}

$query=mysqli_query($con, $sql) or die("employee-grid-data.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length

$query=mysqli_query($con, $sql) or die("employee-grid-data.php: get employees");

	
$data = array();

while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = date('d-m-Y',strtotime($row['date']));
	$nestedData[] = $row["itemCode"];
	$nestedData[] = $row["closing_stock"];

	$data[] = $nestedData;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array

			);

echo json_encode($json_data);  // send data as json format

}
else
	header("Location:loginPage.php");
?>