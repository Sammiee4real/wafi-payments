<?php 
  session_start();
  require_once('config/database_functions.php');
  if(isset($_SESSION['uid'])){
        header('location: home');
      }

include('inc/header.php'); 

?>


<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                      <center>
                      <img src="../images/wafi_logo.png" style="width:100px; height: 120px;" class="u-logo-image u-logo-image-1">
                      </center>
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4"><span><strong>WAFI PEER-TO-PEER</strong></span><br> Welcomes You Back</h1>
                  </div>
                  <form class="user" method="POST" id="login_form">
                    <div class="form-group">
                      <input type="email" id="email" name="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                    </div>
                    <div class="form-group">
                      <input type="password" id="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                    </div>
                    <!-- <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div> -->
                    <a href="" class="btn btn-sm btn-primary btn-user btn-block" id="cmd_login">
                      Login
                    </a>
                    
                    <hr>
                     <a href="signup" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-user-f fa-fw"></i> Sign Up
                    </a>

                   
                  </form>
                  <hr>
                 <!--  <div class="text-center">
                    <a class="small" href="#">Forgot Password?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="#">Create an Account!</a>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

 
 <?php include('inc/scripts.php'); ?>
