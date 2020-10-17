<div class="container-fluid">
	<h1 class="h3 mb-0 text-gray-800">Sub Department</h1><hr>

	<div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('status')?>"></div>

	<!-- Datatable -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">Data of Sub Department</h6><hr>
			<a href="#modal-fade" title="Add Sub Department" class="btn btn-primary" data-toggle="modal">
				<i class="fa fa-plus"></i> Add Sub Department
			</a>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>Sub Department</th>
							<th>Department</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach ($sub as $row){?>
							<tr>
								<td><?php echo $no ?></td>
								<td><?php echo $row->nama_bagian_dept?></td>
								<td><?php echo $row->nama_dept?></td>
								<td>
									<a href="<?php echo site_url('Subdept/edit/'.$row->id_bagian_dept) ?>" data-toggle="tooltip" title="Edit Sub Department" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i>
									</a>
									<a href="<?php echo site_url('Subdept/hapus/'.$row->id_bagian_dept) ?>" data-toggle="tooltip" title="Detele Sub Department" class="btn btn-danger btn-circle btn-sm hapus"><i class="fas fa-trash"></i>
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
			<h6 class="m-0 font-weight-bold text-gray-800">Add Sub Department</h6>
		</div>
			<div class="modal-body">
				<?php echo form_error('nama_bagian_dept'); ?>
				<?php echo form_error('id_departemen'); ?>
				<form id="form-validation" action="<?php echo site_url('Subdept/tambah') ?>" method="POST" enctype="multipart/form-data">

					<div class="form-group">
						<label>Name of Sub Department</label>
						<input class="form-control" name="nama_bagian_dept" placeholder="Input here..." rows="3">
					</div>

					<div class="form-group">
						<label>Department</label>
						<?php echo form_dropdown('id_departemen', $dd_departemen, $id_departemen, 'class="form-control"'); ?>
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
			'Sub Department Data ' + flashData + ' Successfully',
			'success'
			)
	}

	$('.hapus').on('click', function(e) {
		e.preventDefault();
		const href = $(this).attr('href');

		Swal.fire({
			title: 'Are you sure?',
			text: "This sub department will be deleted",
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