<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Edit Sub Department</h1>
	</div><hr>

	<?php echo form_error('nama_bagian_dept'); ?>
	<?php echo form_error('id_departemen'); ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">
				Edit Data <code><?php echo $nama_bagian_dept ?></code>
			</h6>
		</div>

		<div class="card-body">
			<form action="<?php echo site_url('Subdept/update/'.$id_bagian_dept) ?>" method="post">

				<div class="form-group">
					<label>Name of Sub Department</label>
					<input class="form-control" name="nama_bagian_dept" rows="3" value="<?php echo $nama_bagian_dept?>"></input>
				</div>

				<div class="form-group">
					<label>Department</label>
					<?php echo form_dropdown('id_departemen', $dd_departemen, $id_departemen, 'class="form-control" '); ?>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('Subdept/index') ?>'">Cancel</button>
				
			</form>
		</div>
	</div>
</div>