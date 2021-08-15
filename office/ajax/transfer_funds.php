<?php 
  require_once('../config/database_functions.php');
  
  $amount =  $_POST['amount'];
  $sender =  $_POST['user_id']; 
  $receiver =  $_POST['receiver']; 
  
  $transfer_funds = transfer_funds($sender,$amount,$receiver);
  $transfer_funds_dec = json_decode($transfer_funds,true); 
 
  if($transfer_funds_dec['status'] != 111){
    echo $transfer_funds_dec['msg'];
  } else{
    echo 200;
  }





?>