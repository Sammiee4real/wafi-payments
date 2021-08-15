<?php
require('vendor/autoload.php');
$con=mysqli_connect('localhost','root','','exceldata');
$res=mysqli_query($con,"select * from tbl_excel");
if(mysqli_num_rows($res)>0){
	$html='<style></style><table class="table" border="1" cellpadding="10" cellspacing="0" width="100%">';
		$html.='<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th></tr>';
		while($row=mysqli_fetch_assoc($res)){
			$html.='<tr><td>'.$row['id'].'</td><td>'.$row['name'].'</td><td>'.$row['email'].'</td><td>'.$row['phone'].'</td></tr>';
		}
	$html.='</table>';
}else{
	$html="Data not found";
}
$mpdf=new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$file='media/'.time().'.pdf';
$mpdf->output($file,'D');
//D
//I
//F
//S
?>