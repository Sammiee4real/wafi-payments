<?php 
  require_once('../config/database_functions.php');
  
  $password =  $_POST['password'];
  $cpassword =  $_POST['cpassword'];
  $email =  $_POST['email'];
  $fname =  $_POST['fname'];
  $lname =  $_POST['lname'];
  $phone =  $_POST['phone'];
  // $personal_account_no =  $_POST['personal_account_no'];
  // $state =  $_POST['state'];
  // $lga =  $_POST['lga'];
  // $dob =  $_POST['dob'];
  $password =  $_POST['password'];
  $cpassword =  $_POST['cpassword'];
 

  $signup = user_signup($fname,$lname,$email,$phone,$password,$cpassword);
    // $signup = insert_into_db($table,$data,$param,$validate_value);
    $signup_dec = json_decode($signup,true); 
 
  if($signup_dec['status'] != 111){
    echo $signup_dec['msg'];
  } else{
    echo 200;
  }





?>