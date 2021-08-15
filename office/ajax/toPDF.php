<?php
  require_once('../config/database_functions.php');

//   if(tableToPdf($_POST['table'], $_POST['cols'])){
//       echo tableToPdf($_POST['table'], $_POST['cols']);
//       $output = array(
//           'success' =>  true
//       );
//       echo json_encode($output);
//   }else{
//       $output = array(
//           'error'   =>  true
//       );

//       echo json_encode($output);
//   }
// echo $_POST['table'].$_POST['cols'];
require('../vendor/toPDF/vendor/autoload.php');
$html = tableToPdf($_POST['table'], $_POST['cols']);
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$file='../media/'.time().'.pdf';

$mpdf->output($file,'F');

echo str_replace('../', '', $file);

// $output = array(
// 'success' =>  true
// );
// echo json_encode($output);
?>