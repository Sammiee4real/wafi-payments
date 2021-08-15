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
            <h1 class="h3 mb-0 text-gray-800">View Profile</h1>
          </div>

      



    

        <div class="row">

         
          
              <div class="col-md-11">
           
              <h6 class="m-0 font-weight-bold text-primary">View Your Profile</h6>
              <p class="mb-4">You can view your profile here</p>



              <div class="card shadow mb-4">
           

              <div class="row">
                  <div class="col-lg-1">
                  </div>
                  <div class="col-lg-8 p-3">
                    <p class="text-center">
                          <img width="200px" height="200px" src="profiles/default.jpg">
                        </p>
                    <div class="alert alert-success"> 
                    <!--    <table  class="table table-bordered table-striped display nowrap">
                        <thead>
                         <tr>
                           <th></th>
                           <th></th>
                         </tr>
                       </thead>
                       <tbody>
                         <tr>
                           <td>First Name:</td>
                           <td><strong><?php echo $first_name; ?></strong></td>
                         </tr>
                        </tbody>
                       </table> -->                   
                        First Name: <strong><?php echo $first_name; ?></strong><br>
                        Last Name: <strong><?php echo $last_name; ?></strong><br>
                        Other Names: <strong><?php echo $onames; ?></strong><br>
                        Email: <strong><?php echo $email; ?></strong><br>
                        Phone: <strong><?php echo $phone; ?></strong><br>
                        Date of Registration: <strong><?php echo $date_created; ?></strong><br>
                    </div>

                    <!-- <a href="#" data-target="#update_my_profile" data-toggle="modal" class="btn btn-sm btn-danger">Update Record</a> -->

                    <div class="modal fade" id="update_my_profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update My Record</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    </div>
                    <div class="modal-body">
               <form method="post" id="update_my_profile_form" enctype="multipart/form-data" class="user" action="">
              
              

                <div class="form-group">
                    <label>First Name </label>                                
                    <input type="text" required="" class="form-control form-control-sm" value="<?= $first_name; ?>"  id="fname" name="fname" >
                     <input type="hidden" required="" value="<?php echo $uid; ?>" class="form-control form-control-sm"  id="unique_id" name="unique_id">         
                </div>

                 <div class="form-group">
                    <label>Last Name</label>                                
                    <input type="text" required="" class="form-control form-control-sm" value="<?= $last_name; ?>"  id="lname" name="lname">      
                </div>

                  <div class="form-group">
                    <label>Other Names</label>                                
                    <input type="text" required="" class="form-control form-control-sm" value="<?= $onames; ?>"  id="onames" name="onames">      
                </div>

                <div class="form-group">
                    <label>Email</label>                                
                    <input type="email" required="" class="form-control form-control-sm" value="<?= $email; ?>"  id="email" name="email">      
                </div>

               <div class="form-group">
                    <label>Phone</label>                                
                    <input type="number" required="" class="form-control form-control-sm" value="<?= $phone; ?>" id="phone" name="phone">      
                </div>

              <div class="form-group">
                    <label>Gender</label>   
                    <select class="form-control form-control-sm" name="gender" value="<?= $gender; ?>" id="gender">
                         <!-- <option value="">select an option</option> -->
                         <option value="<?= strtolower($gender); ?>"><?= ucfirst($gender); ?></option>
                         <option value="male">Male</option>
                         <option value="female">Female</option>
                    </select>                                     
                </div>

                 <div class="form-group">
                    <label>Date of Birth</label>                                
                    <input type="date" required="" class="form-control form-control-sm" value="<?= $dob; ?>"  id="dob" name="dob">      
                </div>

                 <div class="form-group">
                      <label><strong>Select a State</strong></label>
                      <select class="form-control form-control-sm" name="state" id="state"  >
                          <option value="">Select a State</option>
                            <option value="<?php echo $state;?>"><?php echo getData('name', 'states', 'id', $state);?></option>

                             <?php foreach($states as $state): ?>
                            <option value="<?php echo $state['id'];?>"><?php echo $state['name'];?></option>
                              <?php endforeach ?>
                      </select>
                 </div>
                    <div class="display_lgas">
                      
                </div>


                <div class="form-group">
                    <label>Member Account Number</label>                                
                      <input type="text" required="" class="form-control form-control-sm" value="<?= $personal_account_no; ?>"  id="personal_account_no" name="personal_account_no">
                </div>


                <div class="form-group">
                    <label>Address</label>                                
                      <input type="text" required="" class="form-control form-control-sm" value="<?= $address; ?>"  id="address" name="address">
                </div>

               
                <input type="submit" value="Update My Profile" id="cmd_update_my_profile"  name="cmd_update_my_profile" class="btn btn-danger btn-sm btn-block"/>

                
              </form>



                    </div>
                    <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    
                    </div>
                    </div>
                    </div>
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
