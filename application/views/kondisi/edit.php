<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Edit Priority</h1>
	</div><hr>

	<?php echo form_error('nama_kondisi'); ?>
	<?php echo form_error('waktu_respon'); ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">
				Edit Data <code><?php echo $kondisi['nama_kondisi'] ?></code>
			</h6>
		</div>

		<div class="card-body">
			<form action="<?php echo site_url('Kondisi/update/'.$kondisi['id_kondisi']) ?>" method="post">
				<div class="form-group">
					<label>Name of Priority</label>
					<input class="form-control" name="nama_kondisi" rows="3" value="<?php echo  $kondisi['nama_kondisi'] ?>"></input>
				</div>

				<div class="form-group">
					<label>Time Resolution <code>*(Day)</code></label>
					<input class="form-control" name="waktu_respon" rows="3" value="<?php echo  $kondisi['waktu_respon'] ?>"></input>
				</div>

				<div class="form-group">
					<label>Color</label>
					<input type="color" class="form-control" name="warna" value="<?php echo  $kondisi['warna'] ?>" rows="3">
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('Kondisi/index') ?>'">Cancel</button>
				
			</form>
		</div>
	</div>
</div>