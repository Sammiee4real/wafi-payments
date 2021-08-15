<?php 
  require_once('../config/database_functions.php');

  if(!isset($_POST['lga'])  || $_POST['lga'] == "" ){
     echo "Please ensure lga is selected";
  }else{
    
  $unique_id =  $_POST['unique_id'];
  $table = 'users';
  $data = ['unique_id','onames','address','gender','dob','state','lga','personal_account_no'];
  //update_data_by_a_param($table,$post,$unique_id);

    $update_profile = update_data_by_a_param($table,$data,'unique_id',$unique_id);
    $update_profile_dec = json_decode($update_profile,true); 
       if($update_profile_dec['status'] != 111){
          echo $update_profile_dec['msg'];
        } else{
          echo 200;
        }

  
  }




?>