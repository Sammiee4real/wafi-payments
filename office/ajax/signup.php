<?php 
  require_once('../config/database_functions.php');
  $check = isset($_POST['tnc']) ? 'checked':'unchecked';

  $password =  $_POST['password'];
  $cpassword =  $_POST['cpassword'];
  $email =  $_POST['email'];
  $fname =  $_POST['fname'];
  $lname =  $_POST['lname'];
  $phone =  $_POST['phone'];
  $password =  $_POST['password'];
  $cpassword =  $_POST['cpassword'];
 
  
  if($check == "unchecked"){
      echo "Please agree to terms and conditions";
  }else{
    
        $signup = user_signup($fname,$lname,$email,$phone,$password,$cpassword);
          // $signup = insert_into_db($table,$data,$param,$validate_value);
          $signup_dec = json_decode($signup,true); 
       
        if($signup_dec['status'] != 111){
          echo $signup_dec['msg'];
        } else{
          echo 200;
        }

  }



?>