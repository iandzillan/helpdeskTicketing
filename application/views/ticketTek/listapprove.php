<div class="container-fluid">
	<h1 class="h3 mb-0 text-gray-800">Ticket Assigned</h1>
	<p class="mb-4">List of all ticket that Assigned to You.</p>

	<div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('status')?>"></div>
	<div class="flash-data1" data-flashdata="<?php echo $this->session->flashdata('status1')?>"></div>

	<!-- Datatable -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">List Ticket</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
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
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach ($approve as $row){?>
							<tr>
								<td><?php echo $no ?></td>
								<td><?php echo $row->id_ticket?></td>
								<td class="font-weight-bold" style="color: <?php echo $row->warna?>; text-align: center"><?php echo $row->nama_kondisi?></td>
								<td><?php echo $row->tanggal?></td>
								<td><?php echo $row->deadline?></td>
								<td><?php echo $row->nama?></td>
								<td><?php echo $row->nama_sub_kategori?></td>
								<td><?php echo $row->lokasi?></td>
								<td><?php echo $row->problem_summary?></td>
								<td>
									<?php if ($row->status == 3) { ?>
										<a href="<?php echo site_url('List_ticket_tek/detail_approve/'.$row->id_ticket)?>" class="btn btn-primary btn-circle btn-sm" title="Detail">
											<i class="fas fa-search"></i>
										</a>
										<a href="<?php echo site_url('List_ticket_tek/approve/'.$row->id_ticket)?>" class="btn btn-info btn-circle btn-sm process" title="Process Now">
											<i class="fas fa-check"></i>
										</a>
										<a href="<?php echo site_url('List_ticket_tek/pending/'.$row->id_ticket)?>" class="btn btn-warning btn-circle btn-sm pending" title="pending">
											<i class="fas fa-clock"></i>
										</a>
									<?php } else if ($row->status == 5) { ?>
										<a href="<?php echo site_url('List_ticket_tek/detail_approve/'.$row->id_ticket)?>" class="btn btn-primary btn-circle btn-sm" title="Detail">
											<i class="fas fa-search"></i>
										</a>
										<a href="<?php echo site_url('List_ticket_tek/approve/'.$row->id_ticket)?>" class="btn btn-info btn-circle btn-sm process" title="Process Now">
											<i class="fas fa-check"></i>
										</a>
									<?php } ?>
								</td>
							</tr>
						<?php $no++;}?>
					</tbody>
				</table>
			</div><hr>
			Please go to <code>Ticket -> List Assignment</code> after approve the ticket for update progress the ticket.
		</div>
	</div>
</div>

<script type="text/javascript">
	const flashData = $('.flash-data').data('flashdata');
	if (flashData){
		Swal.fire(
			'Success!',
			'The Ticket on ' + flashData + ' Now',
			'success'
			)
	}

	const flashData1 = $('.flash-data1').data('flashdata');
	if (flashData1){
		Swal.fire(
			'Success!',
			'The Ticket on ' + flashData1,
			'success'
			)
	}

	$('.process').on('click', function(e) {
		e.preventDefault();
		const href = $(this).attr('href');

		Swal.fire({
			title: 'Are you sure?',
			text: "This ticket will be processed",
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Process'
		}).then((result) => {
			if (result.value) {
				document.location.href = href;
			}
		})
	});

	$('.pending').on('click', function(e) {
		e.preventDefault();
		const href = $(this).attr('href');

		Swal.fire({
			title: 'Are you sure?',
			text: "This ticket will be holded",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Pending'
		}).then((result) => {
			if (result.value) {
				document.location.href = href;
			}
		})
	});
</script>