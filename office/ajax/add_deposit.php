<?php 
  require_once('../config/database_functions.php');
  
  $amount =  $_POST['amount'];
  $user_id =  $_POST['user_id']; 
  $make_deposit = make_deposit($user_id,$amount);
  $make_deposit_dec = json_decode($make_deposit,true); 
 
  if($make_deposit_dec['status'] != 111){
    echo $make_deposit_dec['msg'];
  } else{
    echo 200;
  }





?>