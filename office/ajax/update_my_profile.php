<?php 
  require_once('../config/database_functions.php');
        $lga = isset($_POST['lga']) ? $_POST['lga'] : "";
        if($lga == ""){
          echo 'select lga';
        }else{

                    $table = 'users';
                    $data = ['fname','lname','onames','email','phone','gender','dob','state','lga','personal_account_no','address'];
                    $conditional_value = $_POST['unique_id'];
                    $update_my_profile = update_data_by_a_param($table,$data,'unique_id',$conditional_value);

                    // $signup = insert_into_db($table,$data,$param,$validate_value);
                    $update_my_profile_dec = json_decode($update_my_profile,true); 
                   
                    if($update_my_profile_dec['status'] != 111){
                      echo $update_my_profile_dec['msg'];
                    } else{
                      echo 200;
                    }


        }





?>