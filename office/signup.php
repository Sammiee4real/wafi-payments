<?php 
  session_start();
  require_once('config/database_functions.php');
  if(isset($_SESSION['uid'])){
        header('location: home.php');
      }

include('inc/header.php'); 

?>


<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-8 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <!-- <div class="col-lg-4 d-none d-lg-block bg-signup-image"></div> -->
              <div class="col-lg-12">
                <div class="p-5">
                 
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4"><span><strong>WAFI PEER-TO-PEER</strong></span><br> Quick Sign Up</h1>
                  </div>
                  <form class="user" method="POST" id="signup_form">
                    
                    <div class="form-group">
                      <input type="text" id="fname" name="fname" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="First Name">
                    </div>

                    <div class="form-group">
                      <input type="text" id="lname" name="lname" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Last Name">
                    </div>

                    <div class="form-group">
                      <input type="email" id="email" name="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Email Address">
                    </div>

                    <div class="form-group">
                      <input type="number" id="phone" name="phone" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Phone Number">
                    </div>

                   
                    <div class="form-group">
                      <input type="password" id="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                    </div>

                    <div class="form-group">
                      <input type="password" id="cpassword" name="cpassword" class="form-control form-control-user" id="exampleInputPassword" placeholder="Confirm Password">
                    </div>

                    <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="tnc" id="tnc">
                    <label class="form-check-label" for="exampleCheck1"><a href="#">I agree to terms and conditions</a></label>
                    </div>

                    <a href="" class="btn btn-sm btn-primary btn-user btn-block" id="cmd_signup">
                      Signup
                    </a>
                   
                    <hr>
                     <a href="index" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-user-f fa-fw"></i> Login
                    </a>

                   
                  </form>
                  <hr>
               
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

 
 <?php include('inc/scripts.php'); ?>
