<?php
  require_once('../config/database_functions.php');

  if(isset($_POST['page']))
  {
    $output = array(
        'error'   =>  true,
        'errMsg'  =>  'Unable to access requested page'
    );

    if($_POST['page'] == 'users')
    {
        if($_POST['action'] == 'change_access')
        {

            $status = $_POST['value'] == 1 ?'enabled':'disabled';

            $data = updateTableData('users', $_POST['ref'], $_POST['value'], 'unique_id', $_POST['id']);

            if($data == 'success')
            {
                $output = array(
                    'success' => true,
                    'succMsg' => 'User '.$status
                );
            }else{
                $output = array(
                    'error' =>  true,
                    'errMsg'    =>  $data
                );
            }
        }
    }



    ///cooperative update
    if($_POST['page'] == 'cooperatives')
    {
        if($_POST['action'] == 'change_access')
        {

            $status = $_POST['value'] == 1 ?'enabled':'deactivated';

            $data = updateTableData('cooperative_groups', $_POST['ref'], $_POST['value'], 'unique_id', $_POST['id']);

            if($data == 'success')
            {
                $output = array(
                    'success' => true,
                    'succMsg' => 'Cooperative '.$status
                );
            }else{
                $output = array(
                    'error' =>  true,
                    'errMsg'    =>  $data
                );
            }
        }
    }


    echo json_encode($output);
  }
?>