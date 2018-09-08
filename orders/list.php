<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>	
<!DOCTYPE html>
<html>
	<title>Orders</title>
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 	
		<style>
		.dataTables_wrapper .dt-buttons {
		  float:none;  
		  text-align:center;
		}
		.dataTables_length{
			display: none;
		}
		</style>	
		<link rel="stylesheet" type="text/css" href="../css/glow_box.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
		<link rel="stylesheet" type="text/css" href="../css/fixedHeader.css">
		<link rel="stylesheet" type="text/css" href="../css/buttons.css">
		<link rel="stylesheet" type="text/css" href="../css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="../css/demo.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabs.css" />
		<link rel="stylesheet" type="text/css" href="../css/tabstyles.css" />		

  		<script src="../js/modernizr.custom.js"></script>		
		<script type="text/javascript" language="javascript" src="../js/jquery3.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/fixedHeader.js"></script>
		<script type="text/javascript" language="javascript" src="../js/buttons.js"></script>
		<script type="text/javascript" language="javascript" src="../js/html5ExportButton.js"></script>
		<script type="text/javascript" language="javascript" src="../js/colVis.js"></script>	
		<script type="text/javascript" language="javascript" src="../js/jsZip.js"></script>		
		<script type="text/javascript" language="javascript">
			$(document).ready(function() {
				var dataTable = $('#employee-grid').DataTable( {
					"processing": true,
					"serverSide": true,
					"responsive": true,
					"bJQueryUI":true,
					"iDisplayLength": 2000,					
					"ajax":{
						url :"list_server.php", // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							$(".employee-grid-error").html("");
							$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#employee-grid_processing").css("display","none");
										}
						   }
				} );
				
				$("#employee-grid_filter").css("display","none");  // hiding global search box
				$('.search-input-text').on( 'keyup click', function () {   // for text boxes
					var i =$(this).attr('data-column');  // getting column index
					var v =$(this).val();  // getting search input value
					dataTable.columns(i).search(v).draw();
				} );
				$('.search-input-select').on( 'change', function () {   // for select box
					var i =$(this).attr('data-column');  
					var v =$(this).val();  
					dataTable.columns(i).search(v).draw();
				} );
				
			} );
		</script>

	</head>
	<body>
		<div class="container">
			<section>
				<div class="tabs tabs-style-linemove">
					<nav>
						<ul>
							<li><a href="../priceList/list.php" class="icon icon-date"><span>Price List</span></a></li>
							<li><a href="../quotations/list.php" class="icon icon-display"><span>Quotations</span></a></li>
							<li class="tab-current"><a href="../orders/list.php" class="icon icon-box"><span>Orders</span></a></li>
							<li><a href="../purchases/list.php" class="icon icon-tools"><span>Purchases</span></a></li>
							<li><a href="../returns/list.php" class="icon icon-upload"><span>Returns</span></a></li>														
						</ul>
					</nav>
				</div>
			</section>
		</div>		
		<a href="../stock/closingStock.php" onclick="window.open('../stock/closingStock.php','newwindow', 'width=700,height=600'); return false;"><img src="../images/stock.png" width="40" height="40" style="margin-left:30px;"/></a>		
		<a href="../stock/closingStock.php" onclick="window.open('../minimum_stock','newwindow', 'width=800,height=800%'); return false;"><img src="../images/godown.png" width="40" height="40" style="float:right;margin-right:50px;"/></a>						
		<div align="center">
		<br><br>
			<input type="text" data-column="0"  style="width:50px" class="search-input-text textarea" placeholder="Id">&nbsp&nbsp
			<input type="text" data-column="1"  class="search-input-text textarea" placeholder="Date">&nbsp&nbsp
			<input type="text" data-column="2"  class="search-input-text textarea" placeholder="Mason Name">&nbsp&nbsp
			<input type="text" data-column="3"  class="search-input-text textarea" placeholder="Mason Phone">&nbsp&nbsp
			<input type="text" data-column="4"  class="search-input-text textarea" placeholder="Customer Name">&nbsp&nbsp
			<input type="text" data-column="5"  class="search-input-text textarea" placeholder="Customer Phone">&nbsp&nbsp

		<br><br>
			<table id="employee-grid" class="display cell-border no-wrap" >
					<thead>
						<tr>
							<th>Id</th>
							<th>Date</th>
							<th>Plumber Name</th>
							<th>Plumber Phone</th>
							<th>Customer Name</th>
							<th>Customer Phone</th>
						</tr>
					</thead>
			</table>
		</div>
	</body>
</html>
<?php
}
else
	header("Location:loginPage.php");

?>
	