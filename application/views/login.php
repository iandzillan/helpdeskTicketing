<!DOCTYPE html>
<html>
<head>
	<title>Helpdesk Ticketing LBE</title>

	<!-- Custom fonts for this template-->
	<link href="<?php echo base_url() ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="<?php echo base_url() ?>assets/css/sb-admin-2.css" rel="stylesheet">

	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/img/icon.png">

	<!-- Related styles of various icon packs and plugins -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/plugins.css">

	<!-- Modernizr (browser feature detection library) -->
	<script src="<?php echo base_url() ?>assets/js/vendor/modernizr-3.3.1.min.js"></script>

</head>
<body class="bg-gradient-darktheme">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<div class="card o-hidden border-0 shadow-lg" style="margin: auto; margin-top: 15%">
					<div class="card-body p-0">
						<div class="row">
							<div class="col-lg">
								<div class="p-5">
									<div class="text-center">
										<img src="<?php echo base_url() ?>assets/img/LBE12.png" style="width: 300px; height: 70px"><br>
										<h1 class="h2 text-center push-top-bottom animation-slideDown" style="color: black"><b>GENESYS</b></h1><br>

										<?php if($this->session->flashdata('status')) : ?>
											<div class="alert alert-danger alert-dismissable">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
												<strong>Failed!</strong> Username or Password are <?php echo $this->session->flashdata('status');?>
											</div>
										<?php endif;?>

										<?php if($this->session->flashdata('status1')) : ?>
											<div class="alert alert-info alert-dismissable">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
												Your session are <strong><?php echo $this->session->flashdata('status1');?></strong>! Please login first! 
											</div>
										<?php endif;?>

										<form action="<?php echo site_url('Login/loginProses') ?>" method="post">
											<div class="form-group">
												<input type="text" name="username" class="form-control" placeholder="Enter your ID number" maxlength="7" autofocus>
												<?php echo form_error('username'); ?>
											</div>

											<div class="form-group">
												<input type="password" name="password" class="form-control" placeholder="Enter your password">
												<?php echo form_error('password'); ?>
											</div>

											<button type="submit" class="btn btn-primary btn-user btn-block">LOGIN</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>