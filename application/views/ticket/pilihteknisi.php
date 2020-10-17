<div class="container-fluid">
	<h1 class="h3 mb-0 text-gray-800">Assign Technician</h1><hr>

	<div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('status')?>"></div>
	<div class="flash-data1" data-flashdata="<?php echo $this->session->flashdata('status1')?>"></div>

	<!-- Datatable -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">List Ticket</h6>
		</div>
		<div class="card-body">
			Click <code>the ticket</code> to assign the technician.<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>ID Ticket</th>
							<th>Priority</th>
							<th>Date</th>
							<th>Deadline</th>
							<th>Name</th>
							<th>Sub Category</th>
							<th>Location</th>
							<th>Subject</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach ($pilihteknisi as $row){?>
							<tr>
								<td><?php echo $no ?></td>
								<td>
									<a href="<?php echo site_url('List_ticket/detail_pilih_teknisi/'.$row->id_ticket) ?>" title="Click to assign technician"><?php echo $row->id_ticket?></a>
								</td>
								<td class="font-weight-bold" style="color: <?php echo $row->warna?>; text-align: center"><?php echo $row->nama_kondisi?></td>
								<td><?php echo $row->tanggal?></td>
								<td><?php echo $row->deadline?></td>
								<td><?php echo $row->nama?></td>
								<td><?php echo $row->nama_sub_kategori?></td>
								<td><?php echo $row->lokasi?></td>
								<td><?php echo $row->problem_summary?></td>
							</tr>
						<?php $no++;}?>
					</tbody>
				</table>
			</div><hr>
			You can see the tracking of the ticket in <code>Ticket -> List Ticket</code>.
		</div>
	</div>
</div>

<script type="text/javascript">
	const flashData = $('.flash-data').data('flashdata');
	if (flashData){
		Swal.fire(
			'Success!',
			'The Ticket Has Been ' + flashData + ' to Technician',
			'success'
			)
	}

	const flashData1 = $('.flash-data1').data('flashdata');
	if (flashData1){
		Swal.fire(
			'Success!',
			'The Ticket Has Been Assign to Technician. '+flashData1,
			'success'
			)
	}
</script>