<?php
$table = "";
$app_name = 'WAFI PEER-TO-PEER';
require_once("db_connect.php");
require_once("config.php");
global $dbc;
global $project_base_path;
require_once('php_funcs.php');



///make deposit
function make_deposit($uid,$amount){
    global $dbc;
    $uid = secure_database($uid);
    $amount = secure_database($amount);


       if( $uid == "" || $amount == ""){
                  return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found" ));
            }
  
           else{
                $unique_id = unique_id_generator($uid.$amount);
               
                $sql = "INSERT INTO `deposits` SET
                `unique_id` = '$unique_id',
                `user_id` = '$uid',
                `amount` = '$amount',
                `date_added` = now()
                ";
                $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));


                $sql_transaction = "INSERT INTO `transactions` SET
                `unique_id` = '$unique_id',
                `user_id` = '$uid',
                `amount` = '$amount',
                `transaction_type` = 1,
                `date_added` = now()
                ";
                $query_transaction = mysqli_query($dbc, $sql_transaction) or die(mysqli_error($dbc));
               
                $curr_balance = getData('wallet_balance', 'users', 'unique_id', $uid);
                $new_balance = floatval($curr_balance) + floatval($amount);
                $update_balance =  update_by_one_param('users','wallet_balance',$new_balance,'unique_id',$uid);

              
              if($query == true && $query_transaction == true){
          
                            
                        $email_subject = 'Deposit Action';
                        $content = '<p>You just made a deposit of $'.number_format($amount).'</p>';                
                        $content .= '<p>$ '.number_format($amount).'</p>';
                        $content .= '<p>Thanks you for Choosing Wafi</p>';
    
                        
                    // if(email_function($email, $email_subject, $content)){
                    return json_encode(array( "status"=>111, "msg"=>"success"));                         
                    // }else{
                    // return json_encode(array( "status"=>104, "msg"=>"Deposit action was not successful"));
                    // } 

                }else{

                return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));

                }


                }

}


//update one record of a table
function update_by_one_param($table,$param,$value,$condition,$condition_value){
  global $dbc;
  $sql = "UPDATE `$table` SET `$param`='$value' WHERE `$condition`='$condition_value'";
  $qry = mysqli_query($dbc,$sql);
  if($qry){
     return true;
  }else{
      return false;
  }
}

///transfer to a recipient
function transfer_funds($sender,$amount,$receiver){
    global $dbc;
    $sender = secure_database($sender);
    $amount = secure_database($amount);
    $receiver = secure_database($receiver);

     $sender_balance = getData('wallet_balance', 'users', 'unique_id', $sender);

     
     if($sender_balance < floatval($amount) ){
                  return json_encode(array( "status"=>101, "msg"=>"You have insufficient balance...Make a deposit" ));                        
       }

    if( $sender == "" || $amount == "" || $receiver == ""){
                  return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found" ));
    }
  
           else{
                $unique_id = unique_id_generator($sender.$amount.$receiver);               
                $sql_transfer = "INSERT INTO `transactions` SET
                `unique_id` = '$unique_id',
                `user_id` = '$sender',
                `amount` = '$amount',
                `transaction_type` = 2,
                `receiver_id` = '$receiver',
                `date_added` = now()
                ";
                $query_transfer = mysqli_query($dbc, $sql_transfer) or die(mysqli_error($dbc));

                $receiver_balance = getData('wallet_balance', 'users', 'unique_id', $receiver);
                $new_sender_balance = floatval($sender_balance) - floatval($amount);
                $new_receiver_balance = floatval($receiver_balance) + floatval($amount);

                $update_receiver_balance =  update_by_one_param('users','wallet_balance',$new_receiver_balance,'unique_id',$receiver);
                $update_sender_balance =  update_by_one_param('users','wallet_balance',$new_sender_balance,'unique_id',$sender);

              
              if($query_transfer == true && $update_receiver_balance == true && $update_sender_balance == true){

                        $fname = getData('fname', 'users', 'unique_id', $receiver);
                        $lname = getData('lname', 'users', 'unique_id', $receiver);
                        $receiver_full_name = $fname.' '.$lname;
                        $email_subject = 'Transfer Action';
                        $content = '<p>You just made a transfer of $'.number_format($amount).' to '.$receiver_full_name.'</p>';                
                        $content .= '<p>Thanks you for Choosing Wafi</p>';
                        
                    // if(email_function($email, $email_subject, $content)){
                    return json_encode(array( "status"=>111, "msg"=>"success"));                         
                    // }else{
                    // return json_encode(array( "status"=>104, "msg"=>"Transfer action was not successful"));
                    // } 

                }else{

                return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));

                }


                }

}

///for the user
function user_signup($fname,$lname,$email,$phone,$password,$cpassword){
        global $dbc;
        global $project_base_path;

        $table = 'users';
        $fname = secure_database($fname);
        $lname = secure_database($lname);
        $email = secure_database($email);
        $phone = secure_database($phone);
        $password = secure_database($password);
        $cpassword = secure_database($cpassword);
        $hashpassword = md5($password);
       
        $check_exist = check_record_by_one_param($table,'email',$email);
        $check_exist_phone = check_record_by_one_param($table,'phone',$phone);

        if($check_exist){
                return json_encode(array( "status"=>109, "msg"=>"This Email address exists" ));
         }
        else if($check_exist_phone){
                return json_encode(array( "status"=>109, "msg"=>"This Phone number exists" ));
         }
         else if($password != $cpassword){
                return json_encode(array( "status"=>109, "msg"=>"Password mismatch found" ));
         }
         else{

            if( $fname == "" || $lname == "" || $email == "" || $phone == "" || $password == "" || $cpassword == ""){
                  return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found" ));
                }

                else{
                $unique_id = unique_id_generator($fname.$lname.$phone);
                $sql = "INSERT INTO `users` SET
                `unique_id` = '$unique_id',
                `fname` = '$fname',
                `lname` = '$lname',
                `phone` = '$phone',
                `email` = '$email',
                `password` = '$hashpassword',
                `date_created` = now()
                ";
                $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
              
              if($query){
          
                        $login_link = $project_base_path.'/index';          
                        $email_subject = 'Account Creation';
                        $content = '<p>Please check the details below to login:</p>';
                    
                        $content .= '<p>Username: '.$email.'</p>';
                        $content .= '<p>Password: '.$password.'</p>';

                        $content .= '<p>You can login using this link <a class="btn btn-lg btn-success" href='.$login_link.'>Click Login to Account</a></p>';
                        $content .= '<p>Thank you</p>';
                        
                   // if(email_function($email, $email_subject, $content)){
                    return json_encode(array( "status"=>111, "msg"=>"success"));                         
                    // }else{
                    // return json_encode(array( "status"=>104, "msg"=>"Creation was not successful"));
                    // } 

                }else{

                return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));

                }


                }

         }
}



///to send email
function email_function($email, $subject, $content){
      $headers = "From: Wafi\r\n";
      $headers .= "MIME-Version: 1.0" . "\r\n"; 
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
      @$mail = mail($email, $subject, $content, $headers);
      return $mail;
  }


///check record by one param
function check_record_by_one_param($table,$param,$value){
    global $dbc;
    $sql = "SELECT id FROM `$table` WHERE `$param`='$value'";
    $query = mysqli_query($dbc, $sql);
    $count = mysqli_num_rows($query);
    if($count > 0){
      return true; ///exists
    }else{
      return false; //does not exist
    }
    
} 

///generate a unique id
function unique_id_generator($data){
    $data = secure_database($data);
    $newid = md5(uniqid().time().rand(11111,99999).rand(11111,99999).$data);
    return $newid;
}


////////////////////MY NEW GENERIC FUNCTIONS 07-JULY-2021 STARTS
// function get_users_not_in_a_cooperative($param,$order_option){
//          global $dbc;
//         $table = secure_database($table);
       
//         $sql = "SELECT * FROM `$table` WHERE `$param` IS NULL ORDER BY `$order_option` DESC";
//         $query = mysqli_query($dbc, $sql);
//         $num = mysqli_num_rows($query);
//        if($num > 0){
//              while($row = mysqli_fetch_array($query)){
//                 $display[] = $row;
//              }              
//              return $display;
//           }
//           else{
//              return null;
//           }
// }

function add_document($post_values,$file_name, $size, $tmpName,$type){
    global $dbc;
    $table = 'documents';
    $files_upload = doc_upload($file_name, $size, $tmpName,$type);
            $files_upload_dec = json_decode($files_upload,true);
            if($files_upload_dec['status'] != '111' ){
                 return json_encode(["status"=>"104","msg"=>$files_upload_dec['msg'] ]);  
            }else{
                $unique_id = unique_id_generator($_POST['document_title']);
                if($_POST['document_type'] == 'users_type'){
                    
                    $file_path = $files_upload_dec['msg'];
                    $document_name = secure_database($_POST['document_title']);
                    $document_description = secure_database($_POST['document_description']);
                    $document_type = secure_database($_POST['document_type']);
                    $added_by = secure_database($_POST['added_by']);

                    $upload_doc_sql = "INSERT `$table` SET `unique_id`='$unique_id',`user_id`='all',`document_name`='$document_name',`document_description`='$document_description',`document_type`='$document_type',`document_path`='$file_path',`date_added`=now(),`added_by`='$added_by'";
                    $upload_doc_qry = mysqli_query($dbc,$upload_doc_sql); 
                    return  json_encode(["status"=>111, "msg"=>"Document was successfully created: ".$document_name]);


                }

                if($_POST['document_type'] == 'cooperative_type'){

                    $file_path = $files_upload_dec['msg'];
                    $coop_id22 = secure_database($_POST['cooperative']);
                    $coop_id = json_encode($coop_id22);
                    $document_name = secure_database($_POST['document_title']);
                    $document_description = secure_database($_POST['document_description']);
                    $document_type = secure_database($_POST['document_type']);
                    $added_by = secure_database($_POST['added_by']);

                    $upload_doc_sql = "INSERT `$table` SET `unique_id`='$unique_id',`coop_id`='$coop_id',`document_name`='$document_name',`document_description`='$document_description',`document_type`='$document_type',`document_path`='$file_path',`date_added`=now(),`added_by`='$added_by'";
                    $upload_doc_qry = mysqli_query($dbc,$upload_doc_sql); 
                    return  json_encode(["status"=>111, "msg"=>"Document was successfully created: ".$document_name]);

                }

                if($_POST['document_type'] == 'project_type'){

                    $file_path = $files_upload_dec['msg'];
                    $project_id22 = secure_database($_POST['project']);
                    $project_id = json_encode($project_id22);
                    $document_name = secure_database($_POST['document_title']);
                    $document_description = secure_database($_POST['document_description']);
                    $document_type = secure_database($_POST['document_type']);
                    $added_by = secure_database($_POST['added_by']);

                    $upload_doc_sql = "INSERT `$table` SET `unique_id`='$unique_id',`project_id`='$project_id',`document_name`='$document_name',`document_description`='$document_description',`document_type`='$document_type',`document_path`='$file_path',`date_added`=now(),`added_by`='$added_by'";
                    $upload_doc_qry = mysqli_query($dbc,$upload_doc_sql); 
                    return  json_encode(["status"=>111, "msg"=>"Document was successfully created: ".$document_name]);

           
                }

                if($_POST['document_type'] == 'user_type'){

                    $file_path = $files_upload_dec['msg'];
                    $user_id22 = secure_database($_POST['user']);
                    $user_id = json_encode($user_id22);
                    $document_name = secure_database($_POST['document_title']);
                    $document_description = secure_database($_POST['document_description']);
                    $document_type = secure_database($_POST['document_type']);
                    $added_by = secure_database($_POST['added_by']);

                    $upload_doc_sql = "INSERT `$table` SET `unique_id`='$unique_id',`user_id`='$user_id',`document_name`='$document_name',`document_description`='$document_description',`document_type`='$document_type',`document_path`='$file_path',`date_added`=now(),`added_by`='$added_by'";
                    $upload_doc_qry = mysqli_query($dbc,$upload_doc_sql); 
                    return  json_encode(["status"=>111, "msg"=>"Document was successfully created: ".$document_name]);


                }

               
            }

}

function assigned_to_cooperative_but_active_members(){
    global $dbc;
    global $user_unique_id;
    $sql = "SELECT * from users where `role` = '$user_unique_id' AND `access_status`=1 AND `cooperative_group_id` IS NOT NULL order by  `gender` desc, lname asc ";
    $query = mysqli_query($dbc, $sql);
    $count = mysqli_num_rows($query);
    $display = array();
    if($count <= 0){
        return null;
    }else{

        while($row = mysqli_fetch_array($query)){
        $display[] = $row;
        }
        return $display;

    }

}

function unassigned_to_cooperative_but_active_members(){
    global $dbc;
    global $user_unique_id;
    $sql = "SELECT * from users where `role` = '$user_unique_id' AND `access_status`=1 AND `cooperative_group_id` IS NULL order by  `gender` desc, lname asc ";
    $query = mysqli_query($dbc, $sql);
    $count = mysqli_num_rows($query);
    $display = array();
    if($count <= 0){
        return null;
    }else{

        while($row = mysqli_fetch_array($query)){
        $display[] = $row;
        }
        return $display;

    }

}

function edit_project($project_id,$file_name, $size, $tmpName,$type){
    global $dbc;
    $table = 'project_eop';
  
            $files_upload = files_upload($file_name, $size, $tmpName,$type);
            $files_upload_dec = json_decode($files_upload,true);
            if($files_upload_dec['status'] != '111' ){
                 return json_encode(["status"=>"104","msg"=>$files_upload_dec['msg'] ]);  
            }else{
                $project_name = getData('project_name', 'projects', 'unique_id', $project_id);
               
                $file_path = $files_upload_dec['msg'];
                $update_sql = "UPDATE `$table` SET `eop_file_path`='$file_path' where `project_id`='$project_id'
                ";
                $update_qry = mysqli_query($dbc,$update_sql); 
                return  json_encode(["status"=>111, "msg"=>"Project EOP was successfully update for the project: ".$project_name]);
            }
    

}




function delete_document($unique_id){
    global $dbc;
    $unique_id = secure_database($unique_id);
    $sql = "DELETE FROM `documents`where `unique_id`='$unique_id'";
    $qry = mysqli_query($dbc,$sql);    
    return  json_encode(["status"=>111, "msg"=>"success"]);

}

function delete_eop($unique_id){
    global $dbc;
    $unique_id = secure_database($unique_id);
    $sql = "DELETE FROM `project_eop`where `unique_id`='$unique_id'";
    $qry = mysqli_query($dbc,$sql);
    
    return  json_encode(["status"=>111, "msg"=>"success"]);

}


function add_project($added_by,$project_id,$file_name, $size, $tmpName,$type){
    global $dbc;
    $table = 'project_eop';
    $check_exist = check_record_by_one_param($table,'project_id',$project_id);
    if($check_exist){
       return  json_encode(["status"=>104, "msg"=>"This project has an EOP uploaded already"]);
    }else{
            $files_upload = files_upload($file_name, $size, $tmpName,$type);
            $files_upload_dec = json_decode($files_upload,true);
            if($files_upload_dec['status'] != '111' ){
                 return json_encode(["status"=>"104","msg"=>$files_upload_dec['msg'] ]);  
            }else{
                $project_name = getData('project_name', 'projects', 'unique_id', $project_id);
                $unique_id = unique_id_generator($project_id.time());
                $file_path = $files_upload_dec['msg'];
                $create_sql = "INSERT INTO `$table` SET
                `unique_id`='$unique_id',
                `project_id`='$project_id',
                `eop_file_path`='$file_path',
                `added_by`='$added_by',
                `date_added`=now()
                ";
                $create_qry = mysqli_query($dbc,$create_sql); 
                return  json_encode(["status"=>111, "msg"=>"Project EOP was successfully added for the project: ".$project_name]);
            }
    }

}


function files_upload($file_name, $size, $tmpName,$type){
    // global $dbc;
    $upload_path33 = "eop/".$file_name;
    $file_extensions = ['pdf','docx','doc'];//pdf,PDF
    $file_extension = substr($file_name,strpos($file_name, '.') + 1);
        //$file_extension = strtolower(end(explode('.', $file_name)));
    
    if(!in_array($file_extension, $file_extensions)){
      return json_encode(["status"=>"0","msg"=>"incorrect_format"]);
    }else{
        // $upload_path33 = 'ebooks/'.$file_name;
        //2Mb
        if($size > 5000000){
          return json_encode(["status"=>"130","msg"=>"too_big"]);
        }else{
          $upload = move_uploaded_file($tmpName, $upload_path33);
          if($upload == true){
              return json_encode(["status"=>"111","msg"=>$upload_path33]);
          }else{
              return json_encode(["status"=>"104","msg"=>"failurerr   ".$upload_path33]);  
          }
        }

    }


}


function doc_upload($file_name, $size, $tmpName,$type){
    // global $dbc;
    $upload_path33 = "documents/".$file_name;
    $file_extensions = ['pdf','docx','doc'];//pdf,PDF
    $file_extension = substr($file_name,strpos($file_name, '.') + 1);
        //$file_extension = strtolower(end(explode('.', $file_name)));
    
    if(!in_array($file_extension, $file_extensions)){
      return json_encode(["status"=>"0","msg"=>"incorrect_format"]);
    }else{
        // $upload_path33 = 'ebooks/'.$file_name;
        //2Mb
        if($size > 5000000){
          return json_encode(["status"=>"130","msg"=>"too_big"]);
        }else{
          $upload = move_uploaded_file($tmpName, $upload_path33);
          if($upload == true){
              return json_encode(["status"=>"111","msg"=>$upload_path33]);
          }else{
              return json_encode(["status"=>"104","msg"=>"failurerr   ".$upload_path33]);  
          }
        }

    }


}



function edit_farm_records($farm_id,$farm_name,$farm_address,$coordinate_array){
    global $dbc;
    $table = 'farms';

    $farm_name = secure_database($farm_name);
    $farm_id = secure_database($farm_id);
    $farm_address = secure_database($farm_address);
    $coordinate_array_enc = json_encode($coordinate_array);

         $update_sql = "UPDATE  `$table` SET        
                    `farm_name`='$farm_name',
                    `farm_address`='$farm_address',
                    `farm_coordinates`='$coordinate_array_enc'
                     where `unique_id`='$farm_id'
                    ";
         $update_qry = mysqli_query($dbc,$update_sql); 
        return  json_encode(["status"=>111, "msg"=>"success"]);
}


function add_farm_records($uid,$farm_name,$farm_address,$coordinate_array){
    global $dbc;
    $table = 'farms';
    $uid = secure_database($uid);
    $farm_name = secure_database($farm_name);
    $farm_address = secure_database($farm_address);
    $coordinate_array_enc = json_encode($coordinate_array);
    $check_exist = check_record_by_one_param($table,'farm_name',$farm_name);
    if($check_exist){
       return  json_encode(["status"=>104, "msg"=>"This farm record exists"]);
    }else{
         $unique_id = unique_id_generator($farm_name.time());
         $create_sql = "INSERT INTO `$table` SET
                    `unique_id`='$unique_id',
                    `farm_name`='$farm_name',
                    `farm_address`='$farm_address',
                    `farm_coordinates`='$coordinate_array_enc',
                    `added_by`='$uid',
                    `date_added`=now()
                    ";
         $create_qry = mysqli_query($dbc,$create_sql); 
        return  json_encode(["status"=>111, "msg"=>"success"]);
    }
}

function empty_cooperative_members($coopid){
    global $dbc;
    $coopid = secure_database($coopid);
    $sql = "UPDATE `users` SET `cooperative_group_id`= NULL where `cooperative_group_id`='$coopid'";
    $qry = mysqli_query($dbc,$sql);
    
    return  json_encode(["status"=>111, "msg"=>"success"]);

}

 function assign_farms_to_project($uid,$farms,$project){
    global $dbc;
    $uid = secure_database($uid);
    // $farms = secure_database($farms);
    $project = secure_database($project);
    $table = 'project_farms_assignment';
       

            for($i=0; $i < count($farms); $i++){
                    $each_farm = $farms[$i];
                    $check = check_record_by_two_params($table,'project_id',$project,'farm_id',$each_farm);
                    if($check){
                        ///do nothing     
                    }else{
                    $unique_id = unique_id_generator($each_farm.$project.time());
                    $create_sql = "INSERT INTO `$table` SET
                    `unique_id`='$unique_id',
                    `project_id`='$project',
                    `farm_id`='$each_farm',
                    `added_by`='$uid',
                    `date_added`=now()
                    ";
                    $create_qry = mysqli_query($dbc,$create_sql); 
                    }

            }

            return  json_encode(["status"=>111, "msg"=>"success"]);


 }



 function unassign_farms_from_project($project_id,$farms){
    global $dbc;
    $project_id = secure_database($project_id);
    $table = 'project_farms_assignment';    
    for($i=0; $i < count($farms); $i++){
            $each_farm = $farms[$i];
            $del_sql = "DELETE FROM `$table` WHERE `farm_id`='$each_farm' AND `project_id`='$project_id'";
            $del_qry = mysqli_query($dbc,$del_sql); 
    }

   return  json_encode(["status"=>111, "msg"=>"success"]);   
 }


///assign projectS to a cooperative
function assign_project_to_cooperative($uid,$project_id,$cooperative){
    global $dbc;
    global $project_base_path;
    $table = 'cooperative_projects_assignment';
    $project_id = secure_database($project_id);
    $cooperative = secure_database($cooperative);

   
   // for($i=0; $i < count($project_id_var); $i++){
        // $each_project = $project_id_var[$i];
        // $check = check_record_by_two_params($table,'cooperative_id',$cooperative,'project_id',$each_project);
        $check = check_record_by_one_param($table,'cooperative_id',$cooperative);
        if($check){
           ///do nothing  
         return  json_encode(["status"=>101, "msg"=>"This cooperative has a project assigned to it already"]);

        }else{
        $unique_id = unique_id_generator($project_id.$cooperative.time());
        $create_sql = "INSERT INTO `$table` SET
               `unique_id`='$unique_id',
               `project_id`='$project_id',
               `cooperative_id`='$cooperative',
               `added_by`='$uid',
               `date_added`=now()
                ";
        $create_qry = mysqli_query($dbc,$create_sql); 
        return  json_encode(["status"=>111, "msg"=>"success"]);   

       

        // }

   }


  
}







/////not used again
function check_exist_cooperative_project_farm_assignment($project_id,$farm_id,$cooperative){
    global $dbc;
    $check_sql = "SELECT id FROM `cooperative_groups` where `project_id` IS NOT NULL and `farm_id` IS NOT NULL and `unique_id`='$cooperative'";
    $check_qry = mysqli_query($dbc,$check_sql);
    $count_qry = mysqli_num_rows($check_qry);
    
    // $check_sql2 = "SELECT id FROM `cooperative_groups` where `project_id` IS NOT NULL and `farm_id` IS NOT NULL and  `unique_id` != '$cooperative'";
    // $check_qry2 = mysqli_query($dbc,$check_sql2);
    // $count_qry2 = mysqli_num_rows($check_qry2);
    

    ///means assigned already
    if($count_qry >= 1){
            return true;
            //means exists
    }

    // else if ($count_qry2 >= 1){
    //         return true;
    // }

    
    else{
           return false;
    }
}


function assign_users_to_cooperative($users,$cooperative){
    global $dbc;
    global $project_base_path;
   
    for($i=0; $i < count($users); $i++){
        $user_id =  $users[$i];
        $check_sql = "SELECT id from users where unique_id='$user_id' AND cooperative_group_id IS NULL";
        $check_qry = mysqli_query($dbc,$check_sql);
        $check_count = mysqli_num_rows($check_qry);
        if($check_count == 1){
               $update_sql = "UPDATE users SET `cooperative_group_id`='$cooperative' WHERE `unique_id`='$user_id'";
               $update_qry = mysqli_query($dbc,$update_sql); 
               //$correct[] = $user_id;
        }else{
           //nothing
               //$wrong[] = $user_id;

        }      
      }

     return  json_encode(["status"=>111, "msg"=>"success"]);
     //var_dump($users);
    
}


function unassign_users_to_cooperative($users){
    global $dbc;
    // global $project_base_path;
   
    for($i=0; $i < count($users); $i++){
        $user_id =  $users[$i];
        $check_sql = "SELECT id from users where unique_id='$user_id' AND cooperative_group_id IS NOT NULL";
        $check_qry = mysqli_query($dbc,$check_sql);
        $check_count = mysqli_num_rows($check_qry);
        if($check_count == 1){
               $update_sql = "UPDATE users SET `cooperative_group_id` = NULL WHERE `unique_id`='$user_id'";
               $update_qry = mysqli_query($dbc,$update_sql); 
               //$correct[] = $user_id;
        }else{
           //nothing
               //$wrong[] = $user_id;

        }      
      }

     return  json_encode(["status"=>111, "msg"=>"success"]);
     //var_dump($users);
    
}

// function unassign_project_from_cooperative($cooperative,$projects){
function unassign_project_from_cooperative($cooperative){
    global $dbc;
    // global $project_base_path;
    //for($i=0; $i < count($projects); $i++){
    //        $each_project = $projects[$i];
            $del_sql = "DELETE FROM `cooperative_projects_assignment` WHERE `cooperative_id`='$cooperative'";
           $del_qry = mysqli_query($dbc,$del_sql); 
   // }

   return  json_encode(["status"=>111, "msg"=>"success"]);   
}

function verify_password_link($passcode){
    global $dbc;
    global $project_base_path;
    $table = 'reset_password';
    $link_in_db = $project_base_path.'/change_password?passcode='.$passcode;
    $check_expiry = check_record_by_two_params($table,'link_code',$link_in_db,'expiration_status',1);
    $check_exist = check_record_by_one_param($table,'link_code',$link_in_db);


  if($passcode == ""){
        //means expiration has expired
       return  json_encode(["status"=>108, "msg"=>"Code not found"]);
    }

   else if($check_expiry){
        //means expiration has expired
       return  json_encode(["status"=>108, "msg"=>"This link has expired"]);
    }
    else if(!$check_exist){
       return  json_encode(["status"=>102, "msg"=>"This link does not exist"]);
    
    }
    else{
       return  json_encode(["status"=>111, "msg"=>"This link is good"]);

    }

}

function confirm_password_change($passcode,$password,$cpassword){
    global $dbc;
    global $project_base_path;
    $link_in_db = $project_base_path.'/change_password?passcode='.$passcode;
    $table = 'reset_password';
    $table2 = 'users';

    if($password != $cpassword){
       return  json_encode(["status"=>108, "msg"=>"Pasword mismatch found"]);
    }

    if($password  == "" || $cpassword == ""){
       return  json_encode(["status"=>108, "msg"=>"Empty record found"]);
    }


    $hashpassword = md5($password);
    $user_id = explode('_',$passcode)[0];

    $update_reset_pass_link  = "UPDATE `$table` SET `expiration_status`=1 WHERE `link_code`='$link_in_db'";
    $qry_update_reset_pass_link = mysqli_query($dbc,$update_reset_pass_link);

    $update_pass = "UPDATE `$table2` SET `password`='$hashpassword' WHERE `unique_id`='$user_id'";
    $qry_update_pass = mysqli_query($dbc,$update_pass);

    if($qry_update_pass == true && $qry_update_reset_pass_link == true){
        //means expiration has expired
       return  json_encode(["status"=>111, "msg"=>"success"]);
    }
    else{
       return  json_encode(["status"=>108, "msg"=>"Server error"]);
    }

}

function password_reset($email){
    global $dbc;
    global $project_base_path;
    $table = 'reset_password';
     $user_details = get_one_row_from_one_table_by_id('users','email',$email,'date_created');
     if($user_details == null){
       return  json_encode(["status"=>101, "msg"=>"This record does not Exists"]);
     }

    $uid = $user_details['unique_id'];
    $passgener = unique_id_generator(rand(99999999,11111111));
    //means there is a record sent to mail already
    $check = check_record_by_two_params($table,'email',$email,'expiration_status',0);
    if($check){
        $rand_val = rand(1111111,9999999);
        $actual_link = $project_base_path.'/change_password?passcode='.$uid.'_'.$passgener.'_'.$rand_val;
        //do no insertion/ but update
        $update = "UPDATE `reset_password` SET `link_code`='$actual_link' WHERE `email`='$email' AND `expiration_status`=0";
       $qryup = mysqli_query($dbc,$update);
    }else{
        //insertion
        $rand_val = rand(1111111,9999999);
        $actual_link = $project_base_path.'/change_password?passcode='.$uid.'_'.$passgener.'_'.$rand_val;
        $unique_id = unique_id_generator($email.$rand_val);
        $insert = "INSERT INTO `reset_password` SET `link_code`='$actual_link',`unique_id`='$unique_id',`email`='$email'";
        $qry_insert = mysqli_query($dbc,$insert);
    }

        $email_subject = 'Password Reset Link';
        $content = '<p>Please click the link below to reset your password</p>';
        // $content .= '<p>'.$actual_link.'</p>';
        // $content .= '<p>Alternatively, you can click the change password button <a class="btn btn-lg btn-success" href=".$actual_link.">Reset Password</a></p>';
        $content .= '<p><a class="btn btn-lg btn-success" href='.$actual_link.'>Reset Password</a></p>';
        $content .= 'Thank you';
        if(email_function($email, $email_subject, $content)){
          return json_encode(array("status"=>111,"msg"=>"Password reset link was sent to your inbox, check spam too."));
        }else{
          return json_encode(array("status"=>107, "msg"=>"Password reset link not sent"));
        }

}


function insert_into_db($table,$data,$param,$validate_value){
  global $dbc;
  $validate_value = secure_database($validate_value);
  $param = secure_database($param);
  $table = secure_database($table);
  $emptyfound = 0;
  $emptyfound_arr = [];
  $check = check_record_by_one_param($table,$param,$validate_value);
  
  if($check === true){
    return  json_encode(["status"=>"0", "msg"=>"This Record Exists"]);
  }
  else{
   if( is_array($data) && !empty($data) ){
      $unique_id = unique_id_generator($validate_value.md5(uniqid()));
     $sql = "INSERT INTO `$table` SET  `unique_id` = '$unique_id',";
     $sql .= "`date_added` = now(), ";
     //$sql .= "`privilege` = '1', ";
        for($i = 0; $i < count($data); $i++){
            $each_data = $data[$i];
            
            if($_POST[$each_data] == ""  ){
              $emptyfound++;
              $emptyfound_arr[] = $each_data;
            }


            if($i ==  (count($data) - 1)  ){
                 $sql .= " $data[$i] = '$_POST[$each_data]' ";
              }else{
                if($data[$i] === "password"){
                $enc_password = md5($_POST[$data[$i]]); 
                $sql .= " $data[$i] = '$enc_password' ,";
                }else{
                $sql .= " $data[$i] = '$_POST[$each_data]' ,";
                } 
            }

        }
    
      
      if($emptyfound > 0){
          return json_encode(["status"=>"100", "msg"=>"Empty Field(s)","details"=>$emptyfound_arr]);
      } 
       else{
        $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
        if($query){
          return json_encode(["status"=>"111", "msg"=>"success"]);
        }else{
          return json_encode(["status"=>"102", "msg"=>"db_error"]);
        }

      }  


    }
    else{
      return json_encode(["status"=>"100", "msg"=>"error"]);
    }

  } 

}


function update_data_by_a_param($table,$data,$conditional_param,$conditional_value){
  global $dbc;
  $emptyfound = 0;
  $table = secure_database($table);
  // $data = secure_database($data);
  $conditional_param = secure_database($conditional_param);
  $conditional_value = secure_database($conditional_value);

  $emptyfound_arr = [];

  if( is_array($data) && !empty($data) ){
   $sql = "UPDATE `$table` SET ";
      for($i = 0; $i < count($data); $i++){
          $each_data = $data[$i];

           if($_POST[$each_data] == ""  ){
              $emptyfound++;
              $emptyfound_arr[] = $each_data;
           }

          $each_data = secure_database($_POST[$each_data]);
          if($i ==  (count($data) - 1)  ){
            $sql .= " $data[$i] = '$each_data' ";
          }else{
            $sql .= " $data[$i] = '$each_data' ,";
          }

      }

    $sql .= "WHERE `$conditional_param` = '$conditional_value'";

       

    if($emptyfound > 0){
            return json_encode(["status"=>"103", "msg"=>"Empty field(s) were found<br>".json_encode($emptyfound_arr) ]);
    }else{
            $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
            if($query){
            return json_encode(["status"=>"111", "msg"=>"success"]);
            }else{
            return json_encode(["status"=>"102", "msg"=>"db_error"]);
            }
    }

  
  
  }
  else{
    return json_encode(["status"=>"106", "msg"=>"error"]);
  }

}


function update_data($table, $data,$conditional_param,$conditional_value_array){
  global $dbc;
   $table = secure_database($table);
  //$data = secure_database($data);
  $conditional_param = secure_database($conditional_param);
  $conditional_value_array = secure_database($conditional_value_array);


  if( count($conditional_value_array) === 0  ){
      return json_encode(["status"=>"102", "msg"=>"no condition found"]);
  }


  if( is_array($data) && !empty($data) ){
   $sql = "UPDATE `$table` SET ";
      for($i = 0; $i < count($data); $i++){
          $each_data = $data[$i];
          $each_data2 = secure_database($_POST[$each_data]);

          if($i ==  (count($data) - 1)  ){
            $sql .= " $data[$i] = 'each_data2' ";
          }else{
            $sql .= " $data[$i] = 'each_data2' ,";
          }

      }

    $sql .= "WHERE `$conditional_param` = '$conditional_value'";
  
    $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
    if($query){
       return json_encode(["status"=>"111", "msg"=>"success"]);
    }else{
      return json_encode(["status"=>"102", "msg"=>"db_error"]);
    }
  }
  else{
    return json_encode(["status"=>"106", "msg"=>"error"]);
  }
}

function delete_old_image($table,$path_name,$unique_id){
     global $dbc;
    $table = secure_database($table);
    $path_name = secure_database($path_name);
    $unique_id = secure_database($unique_id);

     $sql = "SELECT * FROM `$table` WHERE `id`='$unique_id'";
     $query = mysqli_query($dbc, $sql);
     $row = mysqli_fetch_array($query);
     $img_url = $row[$path_name];
     return unlink($img_url);
}

function  delete_record($table,$param,$value){
    global $dbc;
    $table = secure_database($table);
    $param = secure_database($param);
    $value = secure_database($value);

    $sql = "DELETE FROM `$table` WHERE `$param`='$value'";
    $query = mysqli_query($dbc,$sql);
    if($query){
      return true;
    }else{
      return false;
    }
}


// function view_a_record(){
//     global $dbc;
// }

// function view_many_records(){
//     global $dbc;
// }


///////////////////MY NEW GENERIC FUNCTIONS 07-JULY-2021 ENDS

function in_array_all($needles, $haystack) {
   return empty(array_diff($needles, $haystack));
}


// function add_users($uid,$first_name,$last_name,$email,$phone,$password,$role){
//   global $dbc;

//    $check_exist = check_record_by_one_param('admin_users','phone',$phone);

//    if($check_exist){
//                 return json_encode(array( "status"=>109, "msg"=>"This User exists" ));
//          }

//          else{

//             if( $first_name == "" || $last_name == ""  || $email == ""  || $phone == "" 
//             || $password == "" || $role == ""  ){
//                   return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found" ));
//                 }
//               else{
//                 $unique_id = unique_id_generator($first_name.$phone);
//                 $enc_password = md5($password);
//                 $sql = "INSERT INTO `admin_users` SET
//                 `unique_id` = '$unique_id',
//                 `fname` = '$first_name',
//                 `lname` = '$last_name',
//                 `email` = '$email',
//                 `phone` = '$phone',
//                 `password` = '$enc_password',
//                 `added_by` = '$uid',
//                 `role` = '$role',
//                 `date_created` = now()
//                 ";
//                 $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
        
//               if($query){
          
//                  return json_encode(array( "status"=>111, "msg"=>"success"));

//                 }else{

//                  return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));

//                   }
//                 }
//          }
// }


function add_bank_admin($uid,$fname,$lname,$email,$phone,$password,$cpassword){
  global $dbc;
  global $bank_unique_id;
  global $project_base_path;

    $role = $bank_unique_id;
    $uid = secure_database($uid);
    $fname = secure_database($fname);
    $lname = secure_database($lname);
    $email = secure_database($email);
    $phone = secure_database($phone);
    $password = secure_database($password);
    $cpassword = secure_database($cpassword);




   $check_exist = check_record_by_one_param('users','phone',$phone);
   $check_exist2 = check_record_by_one_param('users','email',$email);


   if($check_exist2){
                return json_encode(array( "status"=>109, "msg"=>"This User Email exists" ));
         }

    // else if($check_exist2){
    //             return json_encode(array( "status"=>109, "msg"=>"This User Email  exists" ));
    //      }

         else{

            if( $fname == "" || $lname == ""  || $email == ""  || $phone == "" 
            || $password == ""  || $cpassword == "" || $role == ""  ){
                  return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found" ));
                }
              else{
                $unique_id = unique_id_generator($fname.$phone);
                $enc_password = md5($password);
                $sql = "INSERT INTO `users` SET
                `unique_id` = '$unique_id',
                `fname` = '$fname',
                `lname` = '$lname',
                `email` = '$email',
                `phone` = '$phone',
                `password` = '$enc_password',
                `added_by` = '$uid',
                `role` = '$role',
                `date_created` = now()
                ";
                $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
        
              if($query){
                     
                        $login_link = $project_base_path.'/index';          
                        $email_subject = 'Account Creation';
                        $content = '<p>Please check the details below to login:</p>';
                    
                        $content .= '<p>Username: '.$email.'</p>';
                        $content .= '<p>Password: '.$password.'</p>';

                        $content .= '<p>You can login using this link <a class="btn btn-lg btn-success" href='.$login_link.'>Click Login to Account</a></p>';
                        $content .= '<p>Thank you</p>';


                    if(email_function($email, $email_subject, $content)){
                        return json_encode(array( "status"=>111, "msg"=>"success"));                         
                    }else{
                        return json_encode(array( "status"=>104, "msg"=>"Creation was not successful"));
                    }       

                }else{

                 return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));

                  }
                }
         }
}

function add_fk_admin($uid,$fname,$lname,$email,$phone,$password,$cpassword){
  global $dbc;
  global $admin_unique_id;
  global $project_base_path;

  $role = $admin_unique_id;

    $uid = secure_database($uid);
    $fname = secure_database($fname);
    $lname = secure_database($lname);
    $email = secure_database($email);
    $phone = secure_database($phone);
    $password = secure_database($password);
    $cpassword = secure_database($cpassword);

   $check_exist = check_record_by_one_param('users','phone',$phone);
   $check_exist2 = check_record_by_one_param('users','email',$email);

   if($check_exist2){
                return json_encode(array( "status"=>109, "msg"=>"This User Email exists" ));
         }

    // else if($check_exist2){
    //             return json_encode(array( "status"=>109, "msg"=>"This User Email  exists" ));
    //      }

         else{

            if( $fname == "" || $lname == ""  || $email == ""  || $phone == "" 
            || $password == ""  || $cpassword == "" || $role == ""  ){
                  return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found" ));
                }
              else{
                $unique_id = unique_id_generator($fname.$phone);
                $enc_password = md5($password);
                $sql = "INSERT INTO `users` SET
                `unique_id` = '$unique_id',
                `fname` = '$fname',
                `lname` = '$lname',
                `email` = '$email',
                `phone` = '$phone',
                `password` = '$enc_password',
                `added_by` = '$uid',
                `role` = '$role',
                `date_created` = now()
                ";
                $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
        
              if($query){
          
                        $login_link = $project_base_path.'/index';          
                        $email_subject = 'Account Creation';
                        $content = '<p>Please check the details below to login:</p>';
                    
                        $content .= '<p>Username: '.$email.'</p>';
                        $content .= '<p>Password: '.$password.'</p>';

                        $content .= '<p>You can login using this link <a class="btn btn-lg btn-success" href='.$login_link.'>Click Login to Account</a></p>';
                        $content .= '<p>Thank you</p>';


                        if(email_function($email, $email_subject, $content)){
                        return json_encode(array("status"=>111,"msg"=>"Password reset link was sent to your inbox, check spam too."));
                        }else{
                        return json_encode(array("status"=>107, "msg"=>"Password reset link not sent"));
                        }

                }else{
                    return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));
                  }
                }
         }
}





function edit_users($user_id,$first_name,$last_name,$email,$phone,$password,$role){
  global $dbc;

            $uid = secure_database($uid);
            $first_name = secure_database($first_name);
            $last_name = secure_database($last_name);
            $email = secure_database($email);
            $phone = secure_database($phone);
            $password = secure_database($password);
            $role = secure_database($role);

            if( $first_name == "" || $last_name == ""  || $email == ""  || $phone == "" 
             || $role == "" || $user_id == ""  ){
                  return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found" ));
                }
              else{

                if($password == ""){
                    $sql = "UPDATE `admin_users` SET
                    `fname` = '$first_name',
                    `lname` = '$last_name',
                    `email` = '$email',
                    `phone` = '$phone',
                    `role` = '$role' WHERE `unique_id`='$user_id'
                    ";
                }else {
                    $enc_password = md5($password);
                    $sql = "UPDATE `admin_users` SET
                    `fname` = '$first_name',
                    `lname` = '$last_name',
                    `email` = '$email',
                    `phone` = '$phone',
                    `password` = '$enc_password',
                    `role` = '$role' WHERE `unique_id`='$user_id'
                    ";
                }
              

                $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
        
              if($query){
          
                 return json_encode(array( "status"=>111, "msg"=>"success"));

                }else{

                 return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));

                  }
                }
         

}


function add_role_privileges($uid,$role_name,$read_write_access,$pages_access){
  global $dbc;
  $check_exist = check_record_by_one_param('role_privileges','role_name',$role_name);

   if($check_exist){
                return json_encode(array( "status"=>109, "msg"=>"This Role exists" ));
         }

    else{
            if( $role_name == "" || $read_write_access == ""  ){
                  return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found" ));
                }
            if(count($pages_access) <= 0){
                  return json_encode(array( "status"=>101, "msg"=>"Please select atleast a page to access" ));
              }
              else{
                $role_id = unique_id_generator($role_name.rand(1111,5555));
                $enc_pages_access = json_encode($pages_access);



                $sql = "INSERT INTO `role_privileges` SET
                `role_id` = '$role_id',
                `role_name` = '$role_name',
                `read_write_access` = '$read_write_access',
                `pages_access` = '$enc_pages_access',
                `added_by` = '$uid',
                `date_added` = now()
                ";
                $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
        
              if($query){
          
                 return json_encode(array( "status"=>111, "msg"=>"success"));

                }else{

                 return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));

                  }
                }
         }
}

function edit_role_privileges($uid,$role_id,$pages_access){
  global $dbc;

            if( $role_id == ""){
                  return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found"));
                }
            if(count($pages_access) <= 0){
                  return json_encode(array( "status"=>101, "msg"=>"Please select atleast a page to access" ));
              }
              else{

                $enc_pages_access = json_encode($pages_access);
                $sql = "UPDATE `role_privileges` SET
                `pages_access` = '$enc_pages_access',
                `last_update_by` = '$uid',
                `date_added` = now() WHERE `role_id`='$role_id'
                ";
                $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
        
              if($query){
          
                 return json_encode(array( "status"=>111, "msg"=>"success"));

                }else{

                 return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));

                  }
                }
         
}


function add_accounting_items($uid,$title,$type,$description){
  global $dbc;
  $uid = secure_database($uid);
  $title = secure_database($title);
  $type = secure_database($type);
  $description = secure_database($description);
  $unique_id = unique_id_generator($title.$type);
  
  $check_exist = check_record_by_one_param('accounting_items','item_name',$title);


        if($check_exist){
                return json_encode(array( "status"=>109, "msg"=>"This Title exists" ));
         }

         else{

            if( $title == "" || $type == ""  ){
                  return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found" ));
                }
              else{
                $sql = "INSERT INTO `accounting_items` SET
                `item_id` = '$unique_id',
                `item_name` = '$title',
                `item_type` = '$type',
                `item_description` = '$description',
                `added_by` = '$uid',
                `visibility`= 1,
                `date_added` = now()
                ";
                $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
        
              if($query){
          
                 return json_encode(array( "status"=>111, "msg"=>"success"));

                }else{

                return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));

                }


                }

         }
}



function add_audio($title,$description,$author,$visibility,$audio_link,$uid){
   global $dbc;
   $check_exist = check_record_by_one_param('youtube_videos','title',$title);
   if($check_exist){
        return json_encode(["status"=>"109","msg"=>"This record exists"]);
   }else if($title == "" ||  $description == "" ||  $author == "" ||  $visibility == "" ||  $audio_link== "" ||  $uid == "" ){
        return json_encode(["status"=>"105","msg"=>"Empty field(s) found"]);
   }else{
        $audio_id = unique_id_generator($title.$author);
        $sqladd = "INSERT INTO `audios` SET 
        `audio_id`='$audio_id',
        `title`='$title',
        `description`='$description',
        `author`='$author',
        `visibility`='$visibility',
        `audio_link`='$audio_link',
        `added_by`='$uid',
        `date_added`=now()
        ";
        $qryadd = mysqli_query($dbc,$sqladd);
        if($qryadd){
        return json_encode(["status"=>"111","msg"=>"Audio was successfully created."]);
        }else{
        return json_encode(["status"=>"103","msg"=>"Server Error"]);
        }


   }
        // return json_encode(["status"=>"103","msg"=>"This record exists already"]);

   


}





// // fk_admin
// function fk_admin_signup($fname,$lname,$email,$phone,$password,$cpassword){
//   global $dbc;
//   $table = 'users';
//   $fname = secure_database($fname);
//   $lname = secure_database($lname);
//   $email = secure_database($email);
//   $phone = secure_database($phone);
//   $password = secure_database($password);
//   $cpassword = secure_database($cpassword);
//   $hashpassword = md5($password);
//   $role = '599f15b86c766b68e35c2a653d94a22e';
//   $check_exist = check_record_by_one_param($table,'email',$email);
//   $check_exist_phone = check_record_by_one_param($table,'phone',$phone);

//   if($check_exist){
//           return json_encode(array( "status"=>109, "msg"=>"This Email address exists" ));
//    }
//   else if($check_exist_phone){
//           return json_encode(array( "status"=>109, "msg"=>"This Phone number exists" ));
//    }
//    else if($password != $cpassword){
//           return json_encode(array( "status"=>109, "msg"=>"Password mismatch found" ));
//    }
//    else{

//       if( $fname == "" || $lname == "" || $email == "" || $phone == "" || $password == "" || $cpassword == ""){
//             return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found" ));
//           }

//           else{
//           $unique_id = unique_id_generator($fname.$lname.$phone);
//           $sql = "INSERT INTO `users` SET
//           `unique_id` = '$unique_id',
//           `fname` = '$fname',
//           `lname` = '$lname',
//           `phone` = '$phone',
//           `email` = '$email',
//           `role` = '$role',
//           `password` = '$hashpassword',
//           `date_created` = now()
//           ";
//           $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
        
//         if($query){
    
//            return json_encode(array( "status"=>111, "msg"=>"success"));

//           }else{

//           return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));

//           }


//           }

//    }
// }


// // bank_admin_signup
// function bank_admin_signup($fname,$lname,$email,$phone,$password,$cpassword){
//   global $dbc;
//   $table = 'users';
//   $fname = secure_database($fname);
//   $lname = secure_database($lname);
//   $email = secure_database($email);
//   $phone = secure_database($phone);
//   $password = secure_database($password);
//   $cpassword = secure_database($cpassword);
//   $hashpassword = md5($password);
//   $role = '6253e1b3e7d39816a2be7de22e9e6d14';
//   $check_exist = check_record_by_one_param($table,'email',$email);
//   $check_exist_phone = check_record_by_one_param($table,'phone',$phone);

//   if($check_exist){
//           return json_encode(array( "status"=>109, "msg"=>"This Email address exists" ));
//    }
//   else if($check_exist_phone){
//           return json_encode(array( "status"=>109, "msg"=>"This Phone number exists" ));
//    }
//    else if($password != $cpassword){
//           return json_encode(array( "status"=>109, "msg"=>"Password mismatch found" ));
//    }
//    else{

//       if( $fname == "" || $lname == "" || $email == "" || $phone == "" || $password == "" || $cpassword == ""){
//             return json_encode(array( "status"=>101, "msg"=>"Empty field(s) found" ));
//           }

//           else{
//           $unique_id = unique_id_generator($fname.$lname.$phone);
//           $sql = "INSERT INTO `users` SET
//           `unique_id` = '$unique_id',
//           `fname` = '$fname',
//           `lname` = '$lname',
//           `phone` = '$phone',
//           `email` = '$email',
//           `role` = '$role',
//           `password` = '$hashpassword',
//           `date_created` = now()
//           ";
//           $query = mysqli_query($dbc, $sql) or die(mysqli_error($dbc));
        
//         if($query){
    
//            return json_encode(array( "status"=>111, "msg"=>"success"));

//           }else{

//           return json_encode(array( "status"=>100, "msg"=>"Something went wrong"));

//           }


//           }

//    }
// }




function update_basic_profile($first_name2,$last_name2,$phone2,$gender2,$uid){
     global $dbc;

     if ($first_name2 == "" || $last_name2 == "" || $phone2 == "" || $gender2 == "" ) {

          return json_encode(array( "status"=>103, "msg"=>"Empty field(s) found" ));
     
     }else{

        $sql = "UPDATE `users` SET `fname`='$first_name2',`lname`='$last_name2',`phone`='$phone2',`gender`='$gender2',`update_option`=1 WHERE `unique_id`='$uid'";
        $qry = mysqli_query($dbc,$sql);
        if($qry){
        return json_encode(array( "status"=>111, "msg"=>"success" ));

        }else{

        return json_encode(array( "status"=>102, "msg"=>"failure" ));

        }

     }
}



function update_bank_details($uid,$bank_name,$account_name,$account_no){
    global $dbc;

    if($bank_name == "Access Bank Plc"){
                 $bankcode = "044";         
    }

    if($bank_name == "Fidelity Bank Plc"){
                 $bankcode = "070";         
    }

    if($bank_name == "First City Monument Bank Limited"){
                 $bankcode = "214";         
    }

    if($bank_name == "First Bank of Nigeria Limited"){
                 $bankcode = "011";         
    }

    if($bank_name == "Guaranty Trust Bank Plc"){
                 $bankcode = "058";         
    }

    if($bank_name == "Union Bank of Nigeria Plc"){
                 $bankcode = "032";         
    }

    if($bank_name == "United Bank for Africa Plc"){
                 $bankcode = "033";         
    }

    if($bank_name == "Citibank Nigeria Limited"){
                 $bankcode = "023";         
    }

    if($bank_name == "Ecobank Nigeria Plc"){
                 $bankcode = "050";         
    }

    if($bank_name == "Heritage Banking Company Limited"){
                 $bankcode = "030";         
    }

     if($bank_name == "Keystone Bank Limited"){
                 $bankcode = "082";         
    }

     if($bank_name == "Standard Chartered Bank"){
                 $bankcode = "068";         
    }

     if($bank_name == "Stanbic IBTC Bank Plc"){
                 $bankcode = "221";         
    }

     if($bank_name == "Sterling Bank Plc"){
                 $bankcode = "232";         
    }

     if($bank_name == "Titan Trust Bank Limited"){
                 $bankcode = "022";         
    }
      if($bank_name == "Unity Bank Plc"){
                 $bankcode = "215";         
    }
      if($bank_name == "Wema Bank Plc"){
                 $bankcode = "035";         
    }

     

    
    $sql = "UPDATE `users` SET `bank_code`='$bankcode', `bank_name`='$bank_name',`account_name`='$account_name',`account_no`='$account_no',`update_option`=1 WHERE `unique_id`='$uid'";
    $qry = mysqli_query($dbc,$sql);
    if($qry){
    return json_encode(array( "status"=>111, "msg"=>"success" ));

    }else{

    return json_encode(array( "status"=>102, "msg"=>"failure" ));

    }
    
}




function check_profile_update($uid,$bank_name,$account_name,$account_no,$update_option){
   global $dbc;
   $bank_name = secure_database($bank_name);
   $account_name = secure_database($account_name);
   $account_no = secure_database($account_no);
   $update_option = secure_database($update_option);
  
   $sql = "SELECT * FROM users WHERE `unique_id`='$uid'";
   $qry = mysqli_query($dbc,$sql);
   $count = mysqli_num_rows($qry);
   if($count >= 1){
         
         if( ($bank_name == NULL || $account_name == NULL || $account_no == NULL) && $update_option == 0 ){
                return json_encode(array( "status"=>101, "msg"=>"To continue, kindly update your profile..." ));
         }else{
                return json_encode(array( "status"=>111, "msg"=>"Good Standing" ));

         }
   }  
}



function user_login($email,$password){
   global $dbc;
   $email = secure_database($email);
   $password = secure_database($password);
   $hashpassword = md5($password);


   $sql = "SELECT * FROM `users` WHERE `email`='$email' AND `password`='$hashpassword'";
   $query = mysqli_query($dbc,$sql);
   $count = mysqli_num_rows($query);
   if($count === 1){
      $row = mysqli_fetch_array($query);
      $fname = $row['fname'];
      $lname = $row['lname'];
      $phone = $row['phone'];
      $email = $row['email'];
      $unique_id = $row['unique_id'];
      $access_status = $row['access_status'];

      if($access_status != 1){
                return json_encode(array( "status"=>101, "msg"=>"Sorry, you currently do not have access. Contact Admin!" ));
      }else{
                return json_encode(array( 
                    "status"=>111, 
                    "user_id"=>$unique_id, 
                    "fname"=>$fname, 
                    "lname"=>$lname, 
                    "phone"=>$phone, 
                    "email"=>$email 
                  )
                 );

      }
    
   }else{
                return json_encode(array( "status"=>102, "msg"=>"Wrong username or password!" ));

   }
 

}




function admin_login($email,$password){
   global $dbc;
   $email = secure_database($email);
   $password = secure_database($password);
   $hashpassword = md5($password);

   $sql = "SELECT * FROM admin_users WHERE `email`='$email' AND `password`='$hashpassword' AND `role`=1";
   $query = mysqli_query($dbc,$sql);
   $count = mysqli_num_rows($query);
   if($count === 1){
      $row = mysqli_fetch_array($query);
      $fname = $row['fname'];
      $lname = $row['lname'];
      $phone = $row['phone'];
      $email = $row['email'];
      $unique_id = $row['unique_id'];
      $access_status = $row['access_status'];

      if($access_status != 1){
                return json_encode(array( "status"=>101, "msg"=>"Sorry, you currently do not have access. Contact Admin!" ));
      }else{
                return json_encode(array( 
                    "status"=>111, 
                    "user_id"=>$unique_id, 
                    "fname"=>$fname, 
                    "lname"=>$lname, 
                    "phone"=>$phone, 
                    "email"=>$email 
                  )
                 );

      }
    
   }else{
                return json_encode(array( "status"=>102, "msg"=>"Wrong username and password!" ));

   }
 

}

/////////most important functions ends


////////////GENERIC FUNCTIONS BELOW
        function format_date_two($style,$date){
            $date = secure_database($date);
            $new_date_format = date($style, strtotime($date));
            return $new_date_format;
        }

        function format_date($date){
            $date = secure_database($date);
            $new_date_format = date('F-d-Y', strtotime($date));

            return $new_date_format;
         }

        function getDateForSpecificDayBetweenDates($startDate,$endDate,$day_number){
                  $endDate = strtotime($endDate);
                  $days=array('1'=>'Monday','2' => 'Tuesday','3' => 'Wednesday','4'=>'Thursday','5' =>'Friday','6' => 'Saturday','7'=>'Sunday');
                  for($i = strtotime($days[$day_number], strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i))
                  // $date_array[]=date('F-d-Y',$i);
                  $date_array[]=date('Y-m-d',$i);

                  return $date_array;
         }

     


//sms functions starts here
   function send_sms($destination_no, $message, $developer_id, $cloud_sms_password)
    {

        // The cloudsms api only accepts numbers in the format 234xxxxxxxxxx (without the + sign.)
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://developers.cloudsms.com.ng/api.php?userid=" . $developer_id . "&password=" . $cloud_sms_password . "&type=0&destination=". $destination_no."&sender=CLOUDSMS&message=$message",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        return $response;
    }

/////////////sms functions ends here



function get_one_row_from_one_table_with_sql_param($sql_array,$table,$conditions,$order_option){
         global $dbc;
         $array_ppt = "";
         $conditions_param = "";
        if(count($sql_array) == 0){
            $array_ppt .= "*";
        }else{
            for($j=0; $j < count($sql_array);$j++){
            if($j == (count($sql_array) - 1) ){
                $array_ppt .= $sql_array[$j];
            }else{
                $array_ppt .= $sql_array[$j].',';
            }
           }
        }

        //conditions
        if(count($conditions) == 0){
            $conditions_param .= "";
        }else{
            $k =1;
            $conditions_param .= " WHERE ";
            foreach($conditions as $key=>$value){
            if($k == (count($conditions)) ){
                $conditions_param .= "`".$key."`='".$value."'";
            }else{
                $conditions_param .= "`".$key."`='".$value."' AND ";
            }
            $k++;
           }
        }
      
        $sql = "SELECT $array_ppt FROM `$table`  $conditions_param ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){  
             $row = mysqli_fetch_array($query);               
             return $row;
          }
          else{
             return null;
          }
}


function get_rows_from_one_table_with_sql_param($sql_array,$table,$conditions,$order_option){
         global $dbc;
         $array_ppt = "";
         $conditions_param = "";
        if(count($sql_array) == 0){
            $array_ppt .= "*";
        }else{
            for($j=0; $j < count($sql_array);$j++){
            if($j == (count($sql_array) - 1) ){
                $array_ppt .= $sql_array[$j];
            }else{
                $array_ppt .= $sql_array[$j].',';
            }
           }
        }

        //conditions
        if(count($conditions) == 0){
            $conditions_param .= "";
        }else{
            $k =1;
            $conditions_param .= " WHERE ";
            foreach($conditions as $key=>$value){
            if($k == (count($conditions)) ){
                $conditions_param .= "`".$key."`='".$value."'";
            }else{
                $conditions_param .= "`".$key."`='".$value."' AND ";
            }
            $k++;
           }
        }
      
        $sql = "SELECT $array_ppt FROM `$table`  $conditions_param ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
           while($row = mysqli_fetch_array($query)){
             $row_display[] = $row;
           }
                          
            return $row_display;
          }
          else{
             return null;
          }
}





function check_record_by_two_params($table,$param,$value,$param2,$value2){
    global $dbc;
    $sql = "SELECT id FROM `$table` WHERE `$param`='$value' AND `$param2`='$value2'";
    $query = mysqli_query($dbc, $sql);
    $count = mysqli_num_rows($query);
    if($count > 0){
      return true; ///exists
    }else{
      return false; //does not exist
    }
    
}   



function secure_database($value){
    global $dbc;
    $new_value = mysqli_real_escape_string($dbc,$value);
    return $new_value;
}

function get_row_count_no_param($table){
    global $dbc;
    $sql = "SELECT id FROM `$table`";
    $qry = mysqli_query($dbc,$sql);
    $count = mysqli_num_rows($qry);
    if($count > 0){
        return $count;
    }else{
        return 0;
    }
}

function get_row_count_one_param($table,$param,$value){
    global $dbc;
    $sql = "SELECT id FROM `$table` WHERE `$param`='$value'";
    $qry = mysqli_query($dbc,$sql);
    $count = mysqli_num_rows($qry);
    if($count > 0){
        return $count;
    }else{
        return 0;
    }
}




function get_rows_from_one_table($table,$order_option){
         global $dbc;
       
        $sql = "SELECT * FROM `$table` ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
           while($row = mysqli_fetch_array($query)){
             $row_display[] = $row;
           }
                          
            return $row_display;
          }
          else{
             return null;
          }
}

function get_rows_from_one_table_by_id($table,$param,$value,$order_option){
         global $dbc;
        $table = secure_database($table);
       
        $sql = "SELECT * FROM `$table` WHERE `$param`='$value' ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
             while($row = mysqli_fetch_array($query)){
                $display[] = $row;
             }              
             return $display;
          }
          else{
             return null;
          }
}

function get_rows_from_one_table_by_id_null_param($table,$param,$order_option){
         global $dbc;
        $table = secure_database($table);
       
        $sql = "SELECT * FROM `$table` WHERE `$param` IS NULL ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
             while($row = mysqli_fetch_array($query)){
                $display[] = $row;
             }              
             return $display;
          }
          else{
             return null;
          }
}


// function get_rows_from_one_table_by_id_null_param($table,$param,$order_option){
//          global $dbc;
//         $table = secure_database($table);
       
//         $sql = "SELECT * FROM `$table` WHERE `$param` IS NULL ORDER BY `$order_option` DESC";
//         $query = mysqli_query($dbc, $sql);
//         $num = mysqli_num_rows($query);
//        if($num > 0){
//              while($row = mysqli_fetch_array($query)){
//                 $display[] = $row;
//              }              
//              return $display;
//           }
//           else{
//              return null;
//           }
// }

function get_rows_from_one_table_by_id_null_two_params($table,$param,$param2,$value2,$order_option){
         global $dbc;
        $table = secure_database($table);
       
        $sql = "SELECT * FROM `$table` WHERE `$param` IS NULL and  `$param2`='$value2' IS NULL ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
             while($row = mysqli_fetch_array($query)){
                $display[] = $row;
             }              
             return $display;
          }
          else{
             return null;
          }
}

function get_rows_from_one_table_by_id_not_null_param($table,$param,$order_option){
         global $dbc;
        $table = secure_database($table);
       
        $sql = "SELECT * FROM `$table` WHERE `$param` IS NOT NULL ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
             while($row = mysqli_fetch_array($query)){
                $display[] = $row;
             }              
             return $display;
          }
          else{
             return null;
          }
}



function get_rows_from_one_table_by_two_params($table,$param,$value,$param2,$value2,$order_option){
        global $dbc;
        $table = secure_database($table);
        $sql = "SELECT * FROM `$table` WHERE `$param`='$value' AND `$param2`='$value2' ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
             while($row = mysqli_fetch_array($query)){
                $display[] = $row;
             }              
             return $display;
          }
          else{
             return null;
          }
}


function get_rows_from_one_table_by_three_params($table,$param,$value,$param2,$value2,$param3,$value3,$order_option){
         global $dbc;
        $table = secure_database($table);
        $sql = "SELECT * FROM `$table` WHERE `$param`='$value' AND `$param2`='$value2' AND `$param3`='$value3' ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
             while($row = mysqli_fetch_array($query)){
                $display[] = $row;
             }              
             return $display;
          }
          else{
             return null;
          }
}


function get_one_row_from_one_table_by_id($table,$param,$value,$order_option){
         global $dbc;
        $table = secure_database($table);
        $sql = "SELECT * FROM `$table` WHERE `$param`='$value' ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
             $row = mysqli_fetch_array($query);              
             return $row;
          }
          else{
             return null;
        }
    }

function get_one_row_from_one_table_by_two_params($table,$param,$value,$param2,$value2,$order_option){
         global $dbc;
        $table = secure_database($table);
        $sql = "SELECT * FROM `$table` WHERE `$param`='$value' AND `$param2`='$value2' ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
             $row = mysqli_fetch_array($query);              
             return $row;
          }
          else{
             return null;
        }
    }


    function get_one_row_from_one_table_by_three_params($table,$param,$value,$param2,$value2,$param3,$value3,$order_option){
         global $dbc;
        $table = secure_database($table);
        $sql = "SELECT * FROM `$table` WHERE `$param`='$value' AND `$param2`='$value2' AND `$param3`='$value3' ORDER BY `$order_option` DESC";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
             $row = mysqli_fetch_array($query);              
             return $row;
          }
          else{
             return null;
        }
    }





function get_visible_rows_from_events_with_pagination($table,$offset,$no_per_page){
         global $dbc;
        $table = secure_database($table);
        $offset = secure_database($offset);
        $no_per_page = secure_database($no_per_page);
        $sql = "SELECT * FROM `events_tbl` WHERE visibility = 1 ORDER BY date_added DESC LIMIT $offset,$no_per_page ";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
            while($row = mysqli_fetch_array($query)){
                $row_display[] = $row;
                }
            return $row_display;
          }
          else{
             return null;
          }
}

function get_visible_rows_from_events_with_limit($table,$limit){
         global $dbc;
        $table = secure_database($table);
       
        $sql = "SELECT * FROM `events_tbl` WHERE visibility = 1 ORDER BY date_added DESC LIMIT $limit";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
            while($row = mysqli_fetch_array($query)){
                $row_display[] = $row;
                }
            return $row_display;
          }
          else{
             return null;
          }
}


function get_total_pages($table,$no_per_page){
    global $dbc;
    $no_per_page = secure_database($no_per_page);
    $total_pages_sql = "SELECT COUNT(id) FROM  `$table` ";
    $result = mysqli_query($dbc,$total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_per_page);
    return $total_pages;
}



function get_rows_from_one_table_with_pagination($table,$offset,$no_per_page){
         global $dbc;
        $table = secure_database($table);
        $offset = secure_database($offset);
        $no_per_page = secure_database($no_per_page);
        $sql = "SELECT * FROM `$table` ORDER BY date_added DESC LIMIT $offset,$no_per_page ";
        $query = mysqli_query($dbc, $sql);
        $num = mysqli_num_rows($query);
       if($num > 0){
            while($row = mysqli_fetch_array($query)){
                $row_display[] = $row;
                }
            return $row_display;
          }
          else{
             return null;
          }
}





?>
