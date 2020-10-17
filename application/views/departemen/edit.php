<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Edit Department</h1>
	</div><hr>

	<?php echo form_error('nama_dept'); ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">
				Edit Data <code><?php echo $departemen['nama_dept'] ?></code>
			</h6>
		</div>

		<div class="card-body">
			<form action="<?php echo site_url('Departemen/update/'.$departemen['id_dept']) ?>" method="post">
				<div class="form-group">
					<label>Name of Department</label>
					<input class="form-control" name="nama_dept" rows="3" value="<?php echo  $departemen['nama_dept'] ?>"></input>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('Departemen/index') ?>'">Cancel</button>
				
			</form>
		</div>
	</div>
</div>