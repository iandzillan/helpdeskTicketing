<div class="container-fluid">
	<h1 class="h3 mb-0 text-gray-800">Ticket Recieved</h1>
	<p class="mb-4">List of all ticket that must be responded to.</p><hr>

	<div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('status')?>"></div>

	<!-- Datatable -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">List Ticket</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>ID Ticket</th>
							<th>Date</th>
							<th>Name</th>
							<th>Category</th>
							<th>Location</th>
							<th>Subject</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach ($approve as $row){?>
							<tr>
								<td><?php echo $no ?></td>
								<td><?php echo $row->id_ticket?></td>
								<td><?php echo $row->tanggal?></td>
								<td><?php echo $row->nama?></td>
								<td><?php echo $row->nama_kategori?> (<?php echo $row->nama_sub_kategori?>)</td>
								<td><?php echo $row->lokasi?></td>
								<td><?php echo $row->problem_summary?></td>
								<?php if ($row->status == 0) {?>
									<td>
										<strong style="color: #F36F13;">Ticket Rejected</strong>
									</td>
								<?php } else if ($row->status == 1) {?>
									<td>
										<strong style="color: #946038;">Ticket Submited</strong>
									</td>
								<?php } else if ($row->status == 2) {?>
									<td>
										<strong style="color: #FFB701;">Category Changed</strong>
									</td>
								<?php } else if ($row->status == 3) {?>
									<td>
										<strong style="color: #A2B969;">Assigned to Technician</strong>
									</td>
								<?php } else if ($row->status == 4) {?>
									<td>
										<strong style="color: #0D95BC;">On Process</strong>
									</td>
								<?php } else if ($row->status == 5) {?>
									<td>
										<strong style="color: #023047;">Pending</strong>
									</td>
								<?php } else if ($row->status == 6) {?>
									<td>
										<strong style="color: #2E6095;">Solve</strong>
									</td>
								<?php } else if ($row->status == 7) {?>
									<td>
										<strong style="color: #C13018;">Late Finished</strong>
									</td>
								<?php } ?>
								<td>
									<?php if ($row->status == 0) { ?>
										No Action
									<?php } else if ($row->status == 1) { ?>
										<a href="<?php echo site_url('List_ticket/detail_approve/'.$row->id_ticket)?>" class="btn btn-primary btn-circle btn-sm" title="Detail">
											<i class="fas fa-search"></i>
										</a>
										<a href="<?php echo site_url('List_ticket/setPriority/'.$row->id_ticket)?>" class="btn btn-info btn-circle btn-sm received" title="Received">
											<i class="fas fa-check"></i>
										</a>
										<a href="<?php echo site_url('List_ticket/detail_reject/'.$row->id_ticket)?>" class="btn btn-danger btn-circle btn-sm reject" title="Reject">
											<i class="fas fa-times-circle"></i>
										</a>
									<?php } else if ($row->status == 2) {?>
										<a href="<?php echo site_url('List_ticket/detail_approve/'.$row->id_ticket)?>" class="btn btn-primary btn-circle btn-sm" title="Detail">
											<i class="fas fa-search"></i>
										</a>
										<a href="<?php echo site_url('List_ticket/detail_pilih_teknisi/'.$row->id_ticket)?>" class="btn btn-warning btn-circle btn-sm" title="Assign Technician">
											<i class="fas fa-wrench"></i>
										</a>
									<?php }?>
								</td>
							</tr>
						<?php $no++;}?>
					</tbody>
				</table>
			</div><hr>
			Please go to <code>Ticket -> Technician Selection</code> after approve the ticket to assign the technician.
		</div>
	</div>
</div>

<script type="text/javascript">
	const flashData = $('.flash-data').data('flashdata');
	if (flashData){
		Swal.fire(
			'Success!',
			'The Ticket Has Been ' + flashData,
			'success'
			)
	}

	$('.received').on('click', function(e) {
		e.preventDefault();
		const href = $(this).attr('href');

		Swal.fire({
			title: 'Are you sure?',
			text: "This ticket will be received",
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Received'
		}).then((result) => {
			if (result.value) {
				document.location.href = href;
			}
		})
	});

	$('.reject').on('click', function(e) {
		e.preventDefault();
		const href = $(this).attr('href');

		Swal.fire({
			title: 'Are you sure?',
			text: "This ticket will be rejected",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Continue'
		}).then((result) => {
			if (result.value) {
				document.location.href = href;
			}
		})
	});
</script>