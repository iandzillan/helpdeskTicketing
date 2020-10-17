<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Edit Information</h1>
	</div><hr>

	<?php echo form_error('subject'); ?>
	<?php echo form_error('pesan'); ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">
				Edit Data <code><?php echo $informasi['subject'] ?></code>
			</h6>
		</div>

		<div class="card-body">
			<form action="<?php echo site_url('Informasi/update/'.$informasi['id_informasi']) ?>" method="post">
				<div class="form-group">
					<label>Subject</label>
					<input class="form-control" name="subject" rows="3" value="<?php echo  $informasi['subject'] ?>">
				</div>

				<div class="form-group">
					<label>Information</label>
					<textarea class="form-control" name="pesan" rows="3"><?php echo  $informasi['pesan'] ?></textarea>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('Informasi/index') ?>'">Cancel</button>
				
			</form>
		</div>
	</div>
</div>