<?php require_once('config/instantiated_files.php');
       include('inc/header.php'); 
       $users = get_rows_from_one_table_by_id('users','activation_status',1,'date_created');
       

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
            <h1 class="h3 mb-0 text-gray-800">Transfer Funds</h1>
            <a href="view_transactions" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i>View Transactions</a>
          </div>

      



    

        <div class="row">

         
          
              <div class="col-md-10">
               
              <h6 class="m-0 font-weight-bold text-primary">Transfer Funds</h6>
              <p class="mb-4">You can transfer funds to a user</p>

            <div class="card shadow mb-4">


              <div class="row">
              <!-- <div class="col-lg-5 d-none d-lg-block bg-register-image"></div> -->
              <div class="col-lg-12">
              <div class="p-3">
            
              <form method="post" id="add_transfer_form" enctype="multipart/form-data" class="user" action="">
                  
                  <div class="form-group">
                    <label>Select Recipient </label>                              
               
                    <select required="" class="form-control form-control-sm js-example-basic-single" id="receiver" name="receiver">
                        <option value="">Select recipient</option>
                        <?php  foreach ($users as $user): 
                                if($user['unique_id'] != $_SESSION['uid']){
                          ?>

                               <option value="<?= $user['unique_id']; ?>"><?= $user['fname'].' '.$user['lname']; ?></option>
                        <?php 
                              }
                              endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Amount to Deposit </label>                                
                    <input type="number" required="" class="form-control form-control-sm"  id="amount" name="amount" >
                </div>

                  <div class="form-group">
                    <!-- <label>Added by</label>                                 -->
                    <input type="hidden" required="" class="form-control form-control-sm"  id="user_id" name="user_id" value="<?=$_SESSION['uid']?>">      
                </div>

                <input type="submit" value="Transfer Funds" id="cmd_transfer_funds"  name="cmd_transfer_funds" class="btn btn-primary btn-sm btn-block"/>
                </a>
                
              </form>
             
              
           
            </div>
            </div>
          </div>


            </div>

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
