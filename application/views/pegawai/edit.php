<script language="javascript" type="text/javascript">
	$(document).ready(function() {

		$("#id_departemen").change(function() {
			// Put an animated GIF image insight of content

			var data = {
				id_departemen: $("#id_departemen").val()
			};
			$.ajax({
				type: "POST",
				url: "<?php echo base_url() . 'Select/select_subdept' ?>",
				data: data,
				success: function(msg) {
					$('#div-order').html(msg);
				}
			});
		});

	});
</script>

<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Edit Employee</h1>
	</div><hr>

	<?php echo form_error('nama'); ?>
	<?php echo form_error('email'); ?>
	<?php echo form_error('id_departemen'); ?>
	<?php echo form_error('id_bagian_departemen'); ?>
	<?php echo form_error('id_jabatan'); ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">
				Edit Data <code><?php echo $nik ?></code>
			</h6>
		</div>

		<div class="card-body">
			<form action="<?php echo site_url('Pegawai/update/'.$nik) ?>" method="post">
				
				<div class="form-group">
					<label>Name</label>
					<input class="form-control" name="nama" rows="3" value="<?php echo $nama?>"></input>
				</div>

				<div class="form-group">
					<label>Email</label>
					<input class="form-control" name="email" rows="3" value="<?php echo $email?>"></input>
				</div>

				<div class="form-group">
					<label>Department</label>
					<?php echo form_dropdown('id_departemen', $dd_departemen, $id_departemen, 'id="id_departemen" class="form-control"'); ?>
				</div>

				<div class="form-group">
					<label>Sub Department</label>
					<div id="div-order">
						<?php echo form_dropdown('id_bagian_departemen', $dd_bagian_departemen, $id_bagian_departemen, ' class="form-control"'); ?>
					</div>
				</div>

				<div class="form-group">
					<label>Position</label>
					<?php echo form_dropdown('id_jabatan', $dd_jabatan, $id_jabatan, 'class="form-control"'); ?>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('Pegawai/index') ?>'">Cancel</button>

			</form>
		</div>
	</div>
</div>