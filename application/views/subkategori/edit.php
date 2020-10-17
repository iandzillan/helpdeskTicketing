<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Edit Sub Categoty</h1>
	</div><hr>

	<?php echo form_error('nama_sub_kategori'); ?>
	<?php echo form_error('id_kategori'); ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">
				Edit Data <code><?php echo $nama_sub_kategori ?></code>
			</h6>
		</div>

		<div class="card-body">
			<form action="<?php echo site_url('Subkategori/update/'.$id_sub_kategori) ?>" method="post">

				<div class="form-group">
					<label>Name of Sub Categoty</label>
					<input class="form-control" name="nama_sub_kategori" rows="3" value="<?php echo $nama_sub_kategori?>"></input>
				</div>

				<div class="form-group">
					<label>Category</label>
					<?php echo form_dropdown('id_kategori', $dd_kategori, $id_kategori, 'class="form-control"'); ?>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('Subkategori/index') ?>'">Cancel</button>
				
			</form>
		</div>
	</div>
</div>