<div class="container-fluid">
	<h1 class="h3 mb-0 text-gray-800">Position</h1><hr>

	<div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('status')?>"></div>

	<!-- Datatable -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">Data of Position</h6><hr>
			<a href="#modal-fade" title="Add Position" class="btn btn-primary" data-toggle="modal">
				<i class="fa fa-plus"></i> Add Position
			</a>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>Position</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach ($jabatan as $row){?>
							<tr>
								<td><?php echo $no ?></td>
								<td><?php echo $row->nama_jabatan?></td>
								<td>
									<a href="<?php echo site_url('Jabatan/edit/'.$row->id_jabatan) ?>" data-toggle="tooltip" title="Edit Position" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i>
									</a>
									<a href="<?php echo site_url('Jabatan/hapus/'.$row->id_jabatan) ?>" data-toggle="tooltip" title="Detele Position" class="btn btn-danger btn-circle btn-sm hapus"><i class="fas fa-trash"></i>
									</a>
								</td>
							</tr>
						<?php $no++;}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="modal-fade" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			<h6 class="m-0 font-weight-bold text-gray-800">Add Position</h6>
		</div>
			<div class="modal-body">
				<?php echo form_error('nama_jabatan'); ?>
				<form id="form-validation" action="<?php echo site_url('Jabatan/tambah') ?>" method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label>Name of Position</label>
						<input class="form-control" name="nama_jabatan" placeholder="Input here..." rows="3">
					</div>

					<button type="submit" class="btn btn-primary">Submit</button>
					<button type="button" class="btn btn-danger" class="close" data-dismiss="modal">Cancel</button>	
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	const flashData = $('.flash-data').data('flashdata');
	if (flashData){
		Swal.fire(
			'Success!',
			'Position Data ' + flashData + ' Successfully',
			'success'
			)
	}

	$('.hapus').on('click', function(e) {
		e.preventDefault();
		const href = $(this).attr('href');

		Swal.fire({
			title: 'Are you sure?',
			text: "This Position will be deleted",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete'
		}).then((result) => {
			if (result.value) {
				document.location.href = href;
			}
		})
	});
</script>