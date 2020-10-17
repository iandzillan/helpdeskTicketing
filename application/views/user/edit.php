<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Edit Level User</h1>
	</div>

	<?php echo form_error('id_level'); ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Edit Data <code><?php echo $username ?></code>
			</h6>
		</div>

		<div class="card-body">
			<form action="<?php echo site_url('User/update/'.$id_user) ?>" method="post">
				<div class="form-group">
					<label>Level</label>
					<?php echo form_dropdown('id_level', $dd_level, $id_level, ' id="id_level" class="form-control"'); ?>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('User/index') ?>'">Cancel</button>
			</form>
		</div>
	</div>
</div>