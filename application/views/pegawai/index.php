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
	<h1 class="h3 mb-0 text-gray-800">Employees</h1><hr>

	<div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('status')?>"></div>

	<!-- Datatable -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">Data of Employee</h6><hr>
			<a href="#modal-fade" title="Add Employee" class="btn btn-primary" data-toggle="modal">
				<i class="fa fa-plus"></i> Add Employee
			</a>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>ID Number</th>
							<th>Name</th>
							<th>Email</th>
							<th>Position</th>
							<th>Department</th>
							<th>Sub Department</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach ($pegawai as $row){?>
							<tr>
								<td><?php echo $no ?></td>
								<td><?php echo $row->nik?></td>
								<td><?php echo $row->nama?></td>
								<td><?php echo $row->email?></td>
								<td><?php echo $row->nama_jabatan?></td>
								<td><?php echo $row->nama_dept?></td>
								<td><?php echo $row->nama_bagian_dept?></td>
								<td>
									<a href="<?php echo site_url('Pegawai/edit/'.$row->nik) ?>" data-toggle="tooltip" title="Edit Employee" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i>
									</a>
									<a href="<?php echo site_url('Pegawai/hapus/'.$row->nik) ?>" data-toggle="tooltip" title="Detele Employee" class="btn btn-danger btn-circle btn-sm hapus"><i class="fas fa-trash"></i>
									</a>
								</td>
							</tr>
						<?php $no++;}?>
					</tbody>
				</table>
			</div><hr>
			Go to <code>Configuration -> User Account</code> to create employee's account.
		</div>
	</div>
</div>

<div id="modal-fade" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="m-0 font-weight-bold text-gray-800">Add Employee</h6>
			</div>
			<div class="modal-body">
				<?php echo form_error('nik'); ?>
				<?php echo form_error('nama'); ?>
				<?php echo form_error('email'); ?>
				<?php echo form_error('id_jabatan'); ?>
				<?php echo form_error('id_departemen'); ?>
				<?php echo form_error('id_bagian_departemen'); ?>
				<form id="form-validation" action="<?php echo site_url('Pegawai/tambah') ?>" method="POST" enctype="multipart/form-data">
					
					<div class="form-group">
						<label>ID Number </label>
						<h4 class="small font-weight-bold">
							<code>(Can't be edited after submit)</code>
						</h4>
						<input class="form-control" name="nik" placeholder="Input here..." rows="3" maxlength="7">
					</div>

					<div class="form-group">
						<label>Name</label>
						<input class="form-control" name="nama" placeholder="Input here..." rows="3">
					</div>

					<div class="form-group">
						<label>Email</label>
						<input class="form-control" name="email" placeholder="Input here..." rows="3">
						<code>Just write "-" if employee do not have email</code>
					</div>

					<div class="form-group">
						<label>Position</label>
						<?php echo form_dropdown('id_jabatan', $dd_jabatan, $id_jabatan, ' class="form-control"'); ?>
					</div>

					<div class="form-group">
						<label>Department</label>
						<?php echo form_dropdown('id_departemen', $dd_departemen, $id_departemen, ' id="id_departemen"  class="form-control"'); ?>
					</div>

					<div class="form-group">
						<label>Sub Department</label>
						<div id="div-order">
							<?php echo form_dropdown('id_bagian_departemen', $dd_bagian_departemen, $id_bagian_departemen, ' class="form-control"'); ?>
						</div>
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
			'Employee Data ' + flashData + ' Successfully',
			'success'
			)
	}

	$('.hapus').on('click', function(e) {
		e.preventDefault();
		const href = $(this).attr('href');

		Swal.fire({
			title: 'Are you sure?',
			text: "This employee will be deleted",
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