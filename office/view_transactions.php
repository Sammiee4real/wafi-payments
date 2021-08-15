<?php require_once('config/instantiated_files.php');
       include('inc/header.php'); 
 


?>
<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
  <?php include('inc/sidebar.php'); ?>
    <!-- Sidebar -->
    
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
       
          <?php include('inc/top_nav.php'); ?>

        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Transactions</h1>
  
          </div>

      



    

        <div class="row">

         
          
              <div class="col-md-11">
           
              <h6 class="m-0 font-weight-bold text-primary">View Transactions</h6>
              <p class="mb-4">You can view your all transactions here</p>



              <!-- <div class="card shadow mb-4"> -->
           
                    <div class="table-responsive">
               
						<table id="dataTable" class="table table-bordered table-striped display nowrap">
							<thead>
								<tr>
									<th>SN</th>
                  <th>TRANSACTION TYPE</th>
                  <th>AMOUNT</th>
                  <th>SENDER</th>
									<th>RECIPIENT</th>
									<th>DATE OF TRANSACTION</th>
						
									
								</tr>
							</thead>
                            <tbody>
                                <?php
                                  global $dbc;
                                  $output = '';
                                  $sql = "SELECT * from `transactions` order by date_added desc ";
                                  $query = mysqli_query($dbc, $sql);
                                  $i = 1;
                                  while($data = mysqli_fetch_assoc($query)) {
                                      $rfullname = "nil";
                                      $sfullname = "nil";
                                      $transaction_type =  $data['transaction_type'];
                                      $receiver_id = $data['receiver_id'] ?? "nil";
                                      $sender_id = $data['user_id'] ?? "nil";

                                      if($receiver_id != 'nil'){
                                        $receiver_fname = $receiver_id == $uid ? 'me': getData('fname', 'users', 'unique_id', $receiver_id);
                                        $receiver_lname = $receiver_id == $uid ? '': getData('fname', 'users', 'unique_id', $receiver_id);
                                        $rfullname = $receiver_fname.' '.$receiver_lname;
                                      }

                                        if($sender_id != 'nil' && $sender_id == $uid && $transaction_type == 2){
                                        $sender_fname = $sender_id == $uid ? 'me': getData('fname', 'users', 'unique_id', $sender_id);
                                        $sender_lname = $sender_id == $uid ? '': getData('fname', 'users', 'unique_id', $sender_id);
                                        $sfullname = $sender_fname.' '.$sender_lname;
                                      }

                                      $transaction = $transaction_type == 1 ? 'deposit':'transfer';

                                      $output .= '<tr>';
                                      $output.='<td>'.$i.'</td>';
                                      $output.='<td>'.$transaction.'</td>';
                                      $output.='<td>'.$data['amount'].'</td>';
                                      $output.='<td>'.$sfullname.'</td>';
                                      $output.='<td>'.$rfullname.'</td>';
                                      $output.='<td>'.$data['date_added'].'</td>';
                                      $output .='</tr>';
                                      $i++;
                                  }
                                  
                                  echo $output;
                                ?>
                            </tbody>
						</table>
					</div>


              <!-- </div> -->

              

              </div>
              


        </div>


      



          <!-- Content Row -->


          <!-- Content Row -->
         

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
    <?php include('inc/footer.php'); ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

 <?php include('inc/scripts.php'); ?>
    