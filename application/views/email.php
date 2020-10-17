<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Detail Ticket <?php echo $detail['id_ticket']?></h1>
	</div>
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				<?php echo " Your Ticket (" .$detail['tanggal'].")" ?>
			</h6>
		</div>
		<div class="card-body">
			<div align="center">
				<a targer="_blank" href="<?php echo base_url('uploads/'.$detail['filefoto']) ?>">
					<img id="myImg" style="width:100%;max-width:300px" src="<?php echo base_url('uploads/'.$detail['filefoto']) ?>"><hr>
				</a>
			</div>
			<h6 class="m-0 font-weight-bold text-primary">Department</h6>
			<div class="font-weight-bold">
				<?php echo $detail['nama_dept']." (".$detail['nama_bagian_dept'].")" ?><br>
			</div><hr>
			<h6 class="m-0 font-weight-bold text-primary">Categgory</h6>
			<div class="font-weight-bold">
				<?php echo $detail['nama_kategori']." (".$detail['nama_sub_kategori'].")" ?><br>
			</div><hr>
			<h6 class="m-0 font-weight-bold text-primary">Email</h6>
			<div class="font-weight-bold">
				<?php echo $detail['email'] ?><br>
			</div><hr>
			<h6 class="m-0 font-weight-bold text-primary">Location</h6>
			<div class="font-weight-bold">
				<?php echo $detail['lokasi'] ?><br>
			</div><hr>
			<h6 class="m-0 font-weight-bold text-primary">Subect</h6>
			<div class="font-weight-bold">
				<?php echo $detail['problem_summary'] ?><br>
			</div><hr>
			<h6 class="m-0 font-weight-bold text-primary">Description</h6>
			<div class="font-weight-bold">
				<?php echo $detail['problem_detail'] ?><br>
			</div>
		</div>
	</div>
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				<?php echo " Process By ".$detail['nama_teknisi'] ?></h6>
		</div>
		<div class="card-body">
			<div class="progress mb-4">
				<div class="progress-bar" role="progressbar" style="width: <?php echo $detail['progress'] ?>%" aria-valuenow="<?php echo $detail['progress'] ?>" aria-valuemin="0" aria-valuemax="100">
					<span><?php echo $detail['progress'] ?> % Complete (Progress)</span>
				</div>
			</div><hr>
			<h6 class="m-0 font-weight-bold text-primary">Process Date</h6>
			<div class="font-weight-bold">
				<?php if ($detail['tanggal_proses'] == "0000-00-00 00:00:00") 
				{ 
					echo "Not yet started";
				} else { ?>
					<span class="label label-primary"><?php echo $detail['tanggal_proses']; ?> </span>
				<?php } ?><br>
			</div><hr>
			<h6 class="m-0 font-weight-bold text-primary">Solved Date</h6>
			<div class="font-weight-bold">
				<?php if ($detail['tanggal_solved'] == "0000-00-00 00:00:00") 
				{ 
					echo "Not yet solved";
				} else { ?>
					<span class="label label-primary"><?php echo $detail['tanggal_solved']; ?> </span>
				<?php } ?><br>
			</div><hr><br>
			<h3 class="m-0 font-weight-bold text-primary">System Tracking Ticket</h3>
			<table class="table" width="100%">
				<thead>
					<tr>
						<th>#</th>
						<th>Date</th>
						<th>Status</th>
						<th>Descriptions</th>
						<th>By</th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 1; foreach ($tracking as $row){?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $row->tanggal?></td>
							<td><?php echo $row->status?></td>
							<td><?php echo $row->deskripsi?></td>
							<td><?php echo $row->nama?></td>
						</tr>
					<?php $no++;}?>
				</tbody>
			</table>
		</div>
	</div>
</div>