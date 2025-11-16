<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
        .menu-item:hover{
            background-color:#E8E8E8;
            width:100%;
            padding:10px;
            border-radius:15px;
            color:#FD2B2E;
        }
        
    </style>
    
</head>
 <body style="background-color:#E8E8E8">
      
<?php 
include('includes/navigation.php');
?>
<div class="container-fluid">
<div class="main-content d-flex flex-column">
<?php
include('includes/topbar.php');
include ''.$page_name.'.php';
 ?>
</div>
</div>

</body>
<script type="text/javascript">

<?php if($this->session->flashdata('success_msg')){ ?>
    toastr.success("<?php echo $this->session->flashdata('success_msg'); ?>");
<?php }else if($this->session->flashdata('success_alert')){ ?>
    toastr.success("<?php echo $this->session->flashdata('success_alert'); ?>");
<?php }else if($this->session->flashdata('error_message')){  ?>
    toastr.error("<?php echo $this->session->flashdata('error_message'); ?>");
<?php }else if($this->session->flashdata('warning_alert')){  ?>
    toastr.warning("<?php echo $this->session->flashdata('warning_alert'); ?>");
<?php }else if($this->session->flashdata('info')){  ?>
    toastr.info("<?php echo $this->session->flashdata('info'); ?>");
<?php } ?>
</script>

   
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