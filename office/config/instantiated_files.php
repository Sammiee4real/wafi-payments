<?php session_start();
     include('database_functions.php');
     if(!isset($_SESSION['uid'])){
        header('location: index');
      }
     $uid = $_SESSION['uid'];
     $user_details = get_one_row_from_one_table_by_id('users','unique_id',$uid,'date_created');
     $first_name = $user_details['fname'];
     $last_name = $user_details['lname'];
     $onames = $user_details['onames'];
     $fullname = $first_name.' '.$last_name;
     $phone = $user_details['phone'];
     $dob = $user_details['dob'];
     $address = $user_details['address'];
     $gender = $user_details['gender'];
     $email = $user_details['email'];
     $date_created = $user_details['date_created'];
     $role = $user_details['role'];
     $state = $user_details['state'];
     $lga = $user_details['lga'];
     $personal_account_no = $user_details['personal_account_no'];
     $wallet_balance = $user_details['wallet_balance'];
    

     $current_page = explode('.',basename($_SERVER['PHP_SELF']))[0];
 
?>