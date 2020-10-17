<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title ?></title>
	<link href="<?php echo base_url() ?>assets/vendor/fontawesome-free/css/all.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<!-- Custom styles for this template-->
	<link href="<?php echo base_url() ?>assets/css/sb-admin-2.css" rel="stylesheet">
	<link href="<?php echo base_url() ?>assets/css/timeline.css" rel="stylesheet">
	<!-- Custom styles for this page -->
	<link href="<?php echo base_url() ?>assets/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/img/icon.png">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/js/chart.js'?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/js/zoom.js'?>"></script>
</head>
<body id="page-top">
	<!-- Page Wrapper -->
	<div id="wrapper">
		<?php
		$this->load->view($sidebar);
		?>
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">

				<?php
				$this->load->view($navbar);
				?>

				<?php
				$this->load->view($body);
				?>

			</div>
		</div>
	</div>

	<!-- Logout Modal-->
	<div class="modal fade" id="modal-stok" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<?php echo form_open('Login/logout'); ?>
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">
						Are you sure want to log out?
					</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					Choose "Log out" button if yes.
				</div>
				<div class="modal-footer" id="ModalFooter">
					<button class="btn btn-primary" type="submit">
						Log Out
					</button>
					<button class="btn btn-danger" type="button" data-dismiss="modal">
						Cancel
					</button>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

	<!-- Bootstrap core JavaScript-->
	<script src="<?php echo base_url() ?>assets/vendor/jquery/jquery.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script>
		$( function() {
			$( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
		} );
    </script>
    <script>
    	$( function() {
    		$( "#datepicker2" ).datepicker({ dateFormat: 'yy-mm-dd' });
    	} );
    </script>
	<script src="<?php echo base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="<?php echo base_url() ?>assets/vendor/jquery-easing/jquery.easing.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="<?php echo base_url() ?>assets/js/sb-admin-2.js"></script>

	<!-- Page level plugins -->
	<script src="<?php echo base_url() ?>assets/vendor/chart.js/Chart.js"></script>

	<!-- Page level plugins -->
	<script src="<?php echo base_url() ?>assets/vendor/datatables/jquery.dataTables.js"></script>
	<script src="<?php echo base_url() ?>assets/vendor/datatables/dataTables.bootstrap4.js"></script>

	<!-- Page level custom scripts -->
	<script src="<?php echo base_url() ?>assets/js/demo/datatables-demo.js"></script>

	<script>
		$(document).ready(function() {
			$("#oke").click(function() {
				var id = $("#mod").text();

				var data = 'id=' + id;

				$.ajax({
					url: "<?php echo base_url(); ?><?php echo $link; ?>",
					type: "POST",
					data: data,
					dataType: 'html',
					cache: false,
					success: function(data) {
						location.reload();
					}
				});

			});
		});
	</script>

	<script>
      <?php 
	      if (isset($modal_show)) {
	      	echo $modal_show;
	      } 
      ?>
    </script>
</body>
</html>