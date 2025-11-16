<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="<?php echo adminAssets();?>img/icons/icon-48x48.png" />
	<title>Sign In | <?php echo get_settings('app_name');?></title>
    
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/sidebar-menu.css?ver=<?php echo time();?>">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/simplebar.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/prism.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/quill.snow.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/remixicon.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/swiper-bundle.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jsvectormap.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css?ver=<?php echo time();?>">
		
		<link rel="icon" type="image/png" href="<?php echo base_url();?>assets/logo.png">
		
    <style>
        .btn-primary{
            background-color:#FD2B2E;
        }
    </style>
   </head>
<body class="bg-body-bg">
     <div class="preloader" id="preloader">
            <div class="preloader">
                <div class="waviy position-relative">
                    <span class="d-inline-block">C</span>
                    <span class="d-inline-block">R</span>
                    <span class="d-inline-block">U</span>
                    <span class="d-inline-block">O</span>
                    <span class="d-inline-block">O</span>
                    <span class="d-inline-block">Z</span>
                    <span class="d-inline-block">E</span>
                </div>
            </div>
        </div>
        
        
    
    
    
      <div class="container-fluid">
            <div class="main-content d-flex flex-column p-0">
                <div class="m-lg-auto my-auto w-930 py-4">
                    <div class="card bg-white border rounded-10 border-white py-100 px-130">
                        <div class="p-md-5 p-4 p-lg-0">
                            <div class="text-center mb-4">
                                <h3 class="fs-26 fw-medium" style="margin-bottom: 6px;">Sign In</h3>
                                <p class="fs-16 text-secondary lh-1-8">Don’t have an account yet? <a href="sign-up.html" class="text-primary text-decoration-none">Sign Up</a></p>
                            </div>

                            <form action="<?php echo adminController();?>adminLogin" method="POST">
                                <div class="mb-20">
                                    <label class="label fs-16 mb-2">Email Address</label>
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="floatingInput1" name="txtemail" placeholder="Enter email address *">
                                        <label for="floatingInput1">Enter email address *</label>
                                    </div>
                                </div>
                                <div class="mb-20">
                                    <label class="label fs-16 mb-2">Your Password</label>
                                    <div class="form-group" id="password-show-hide">
                                        <div class="password-wrapper position-relative password-container">
                                            <input type="password" name="txtpasskode" class="form-control text-secondary password" placeholder="Enter password *">
                                            <i style="color: #A9A9C8; font-size: 22px; right: 15px;" class="ri-eye-off-line password-toggle-icon translate-middle-y top-50 position-absolute cursor text-secondary" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-20">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                            <label class="form-check-label fs-16" for="flexCheckDefault">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <button type="submit" class="btn btn-primary fw-normal text-white w-100" style="padding-top: 18px; padding-bottom: 18px;">Sign In</button>
                                </div>

                               
                              
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        
        
  <script src="<?php echo base_url();?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/sidebar-menu.js"></script>
        <script src="<?php echo base_url();?>assets/js/quill.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/data-table.js"></script>
        <script src="<?php echo base_url();?>assets/js/prism.js"></script>
        <script src="<?php echo base_url();?>assets/js/clipboard.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/simplebar.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/apexcharts.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/echarts.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/swiper-bundle.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/fullcalendar.main.js"></script>
        <script src="<?php echo base_url();?>assets/js/jsvectormap.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/world-merc.js"></script>
        <script src="<?php echo base_url();?>assets/js/custom/apexcharts.js"></script>
        <script src="<?php echo base_url();?>assets/js/custom/echarts.js"></script>
        <script src="<?php echo base_url();?>assets/js/custom/maps.js"></script>
        <script src="<?php echo base_url();?>assets/js/custom/custom.js"></script>
    </body>
</html>