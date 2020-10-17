<div class="container-fluid">
	<h1 class="h3 mb-0 text-gray-800">User Account</h1><hr>

	<div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('status')?>"></div>
	<div class="flash-data1" data-flashdata="<?php echo $this->session->flashdata('status1')?>"></div>

	<!-- Datatable -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">Data of User Account</h6><hr>
			<a href="#modal-fade" title="Add User" class="btn btn-primary" data-toggle="modal">
				<i class="fa fa-plus"></i> Add User
			</a>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>Username</th>
							<th>Name</th>
							<th>Department</th>
							<th>Sub Department</th>
							<th>Level</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach ($user as $row){?>
							<tr>
								<td><?php echo $no ?></td>
								<td><?php echo $row->nik?></td>
								<td><?php echo $row->nama?></td>
								<td><?php echo $row->nama_dept?></td>
								<td><?php echo $row->nama_bagian_dept?></td>
								<td><strong><?php echo $row->level?></strong></td>
								<td>
									<a href="<?php echo site_url('User/edit/'.$row->id_user) ?>" data-toggle="tooltip" title="Edit User" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i>
									</a>
									<a href="<?php echo site_url('User/hapus/'.$row->id_user) ?>" data-toggle="tooltip" title="Detele User" class="btn btn-danger btn-circle btn-sm hapus"><i class="fas fa-trash"></i>
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
			<h6 class="m-0 font-weight-bold text-primary">Add User</h6>
		</div>
			<div class="modal-body">
				<?php echo form_error('id_pegawai'); ?>
				<?php echo form_error('id_level'); ?>
				<form id="form-validation" action="<?php echo site_url('User/tambah') ?>" method="POST" enctype="multipart/form-data">

					<div class="form-group">
						<label>Employee</label>
						<?php echo form_dropdown('id_pegawai', $dd_pegawai, $id_pegawai, ' id="id_pegawai" class="form-control"'); ?>
					</div>

					<div class="form-group">
						<label>Level</label>
						<?php echo form_dropdown('id_level', $dd_level, $id_level, ' id="id_level" class="form-control"'); ?>
					</div>

					<div class="form-group">
						<div id="div-order"></div>
					</div>					

					<code class="small font-weight-bold">*Password will be same with Employee's ID Number.</code><br>
					<code class="small font-weight-bold">*Employee have to change the password after account is created.</code><hr>

					<button type="submit" class="btn btn-primary">Submit</button>
					<button type="button" class="btn btn-danger" class="close" data-dismiss="modal">Cancel</button>	
				</form>
			</div>
		</div>
	</div>
</div>

<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		$("#id_pegawai").change(function() {
		// Put an animated GIF image insight of content	 		
		var data = {
			id_pegawai: $("#id_pegawai").val()
		};
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('Select/select_email') ?>",
			data: data,
			success: function(msg) {
				$('#div-order').html(msg);
			}
		});
	});

	});
</script>

<script type="text/javascript">
	const flashData = $('.flash-data').data('flashdata');
	if (flashData){
		Swal.fire(
			'Success!',
			'User Account Data ' + flashData + ' Successfully',
			'success'
			)
	}

	const flashData1 = $('.flash-data1').data('flashdata');
	if (flashData1){
		Swal.fire(
			'Success!',
			flashData1,
			'success'
			)
	}

	$('.hapus').on('click', function(e) {
		e.preventDefault();
		const href = $(this).attr('href');

		Swal.fire({
			title: 'Are you sure?',
			text: "This account will be deleted",
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