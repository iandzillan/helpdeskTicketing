<div class="container-fluid">
	<h1 class="h3 mb-0 text-gray-800">List Assignment</h1><br>

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
							<th>Solve</th>
							<th>Name</th>
							<th>Sub Category</th>
							<th>Location</th>
							<th>Subject</th>
							<th>Progress</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach ($tugas as $row){?>
							<tr>
								<td><?php echo $no ?></td>
								<td><?php echo $row->id_ticket?></td>
								<td class="font-weight-bold" style="color: <?php echo $row->warna?>; text-align: center"><?php echo $row->nama_kondisi?></td>
								<td><?php echo $row->tanggal?></td>
								<td><?php echo $row->deadline?></td>
								<td>
								    <?php if($row->tanggal_solved == "0000-00-00 00:00:00"){
								        echo "Not solve yet";
								    }else{
								        echo "$row->tanggal_solved";
								    } ?>
								</td>
								<td><?php echo $row->nama?></td>
								<td><?php echo $row->nama_sub_kategori?></td>
								<td><?php echo $row->lokasi?></td>
								<td><?php echo $row->problem_summary?></td>
								<td><?php echo $row->progress?>%</td>
								<td>
									<?php if($row->status==4)
						        	{?>
						        		<a href="<?php echo site_url('List_ticket_tek/detail_update/'.$row->id_ticket)?>" class="btn btn-warning btn-circle btn-sm" title="Update Progress">
						        			<i class="fas fa-wrench"></i>
						        		</a>
						        	<?php } else {
						        		echo "Closed";
						        	}?>
								</td>
							</tr>
						<?php $no++;}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	const flashData = $('.flash-data').data('flashdata');
	if (flashData){
		Swal.fire(
			'Success!',
			'The Ticket Has Been ' + flashData + ' Successfully',
			'success'
			)
	}

	const flashData1 = $('.flash-data1').data('flashdata');
	if (flashData1){
		Swal.fire(
			'Success!',
			'The Ticket Has Been Updated Successfully. '+flashData1,
			'success'
			)
	}
</script>