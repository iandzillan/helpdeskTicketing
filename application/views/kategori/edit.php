<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Edit Category</h1>
	</div><hr>

	<?php echo form_error('nama_kategori'); ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">
				Edit Data <code><?php echo $kategori['nama_kategori'] ?></code>
			</h6>
		</div>

		<div class="card-body">
			<form action="<?php echo site_url('Kategori/update/'.$kategori['id_kategori']) ?>" method="post">
				<div class="form-group">
					<label>Name of Department</label>
					<input class="form-control" name="nama_kategori" rows="3" value="<?php echo  $kategori['nama_kategori'] ?>"></input>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('Kategori/index') ?>'">Cancel</button>
				
			</form>
		</div>
	</div>
</div>