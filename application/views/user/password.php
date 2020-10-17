<div class="container-fluid">
	<h1 class="h3 mb-0 text-gray-800">Change Password</h1><hr>

	<div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('status')?>"></div>
	<div class="flash-data1" data-flashdata="<?php echo $this->session->flashdata('status1')?>"></div>
	<?php echo form_error('password_lama'); ?>
	<?php echo form_error('password'); ?>
	<?php echo form_error('password2'); ?>

	<div class="card-body">
		<form action="<?php echo site_url('User/updatepass') ?>" method="post">
			<div class="form-group">
				<label>Old Password</label>
				<input type="password" name="password_lama" class="form-control">
			</div>

			<div class="form-group">
				<label>New Password</label>
				<input type="password" name="password" class="form-control">
			</div>

			<div class="form-group">
				<label>Confirm Password</label>
				<input type="password" name="password2" class="form-control">
			</div>

			<button type="submit" class="btn btn-primary">Submit</button>
			<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('Dashboard') ?>'">Cancel</button>
		</form>
	</div>
</div>

<script type="text/javascript">
	const flashData 	= $('.flash-data').data('flashdata');
	const flashData1 	= $('.flash-data1').data('flashdata');
	if (flashData){
		Swal.fire(
			'Success!',
			'Password ' + flashData + ' Successfully',
			'success'
			)
	}
	if (flashData1){
		Swal.fire({
			icon: 'error',
			title: 'Error',
			text: flashData1,
			footer: ''
		})
	}
</script>