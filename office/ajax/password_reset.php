<?php 
  require_once('../config/database_functions.php');
  $email = $_POST['email'];

  $password_reset = password_reset($email);
  $password_reset_dec = json_decode($password_reset,true); 
     if($password_reset_dec['status'] != 111){
        echo $password_reset_dec['msg'];
      } else{
        echo 200;
      }

  



?>