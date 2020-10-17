<div class="container-fluid">
	<h1 class="h3 mb-0 text-gray-800">Technicians</h1><hr>

	<div class="block-section">
		<?php 
		if($this->session->flashdata('status') != "") {
			echo '<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Success!</strong> Data Saved
			</div>';
		}
		?>

		<?php 
		if($this->session->flashdata('status_del') != "") {
			echo '<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Success!</strong> Data Deleted
			</div>';
		}
		?>

	</div>

	<!-- Datatable -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Data of Technician</h6><hr>
			<a href="#modal-fade" title="Add Technician" class="btn btn-primary" data-toggle="modal">
				<i class="fa fa-plus"></i> Add Technician
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
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach ($teknisi as $row){?>
							<tr>
								<td><?php echo $no ?></td>
								<td><?php echo $row->nik?></td>
								<td><?php echo $row->nama?></td>
								<td>
									<a href="<?php echo site_url('Teknisi/hapus/'.$row->id_teknisi) ?>" onclick="return confirm('You Sure Want to Delete This Technician?')" data-toggle="tooltip" title="Detele Technician" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i>
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
			<h6 class="m-0 font-weight-bold text-primary">Add Technician</h6>
		</div>
			<div class="modal-body">
				<form id="form-validation" action="<?php echo site_url('Teknisi/tambah') ?>" method="POST" enctype="multipart/form-data">

					<div class="form-group">
						<label>Employee</label>
						<?php echo form_dropdown('id_pegawai', $dd_pegawai, $id_pegawai, ' id="id_pegawai" required class="form-control"'); ?>
					</div>

					<div class="form-group">
						<label>Password</label>
						<input type="password" class="form-control" name="password" placeholder="Input here..." rows="3" required>
					</div>

					<button type="submit" class="btn btn-primary">Submit</button>
					<button type="button" class="btn btn-danger" class="close" data-dismiss="modal">Cancel</button>	
				</form>
			</div>
		</div>
	</div>
</div>