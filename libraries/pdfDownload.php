<?php
$url = $_POST['url'];
$fileName = $_POST['fileName'];

generatePdf($url,$fileName);

function generatePdf($url,$fileName)
{
	require('pdf.class.php');
	$h2pdf = new html2pdf();

	//set the url to convert
	$h2pdf->setParam('document_url',$url);

	//start the conversion
	$h2pdf->convertHTML();

	//download the pdf file using supplied name
	$fileName = $fileName;
	$h2pdf->downloadCapture($fileName);	
}
?>
