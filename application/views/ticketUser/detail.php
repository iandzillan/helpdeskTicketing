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
			<div style="text-align: center">
				<?php if (pathinfo($detail['filefoto'], PATHINFO_EXTENSION) == 'pdf'){?>
					<a href="<?php echo base_url('uploads/'.$detail['filefoto']) ?>" class="btn btn-light btn-icon-split">
						<span class="icon text-gray-600">
							<i class="fas fa-file-pdf"></i>
						</span>
						<span class="text"><?php echo $detail['filefoto'] ?></span>
					</a>
				<?php } else {?>
					<a data-fancybox="gallery"  href="<?php echo base_url('uploads/'.$detail['filefoto']) ?>">
						<img src="<?php echo base_url('uploads/'.$detail['filefoto']) ?>" style="width:100%;max-width:300px">
					</a><br>
					Click to zoom
				<?php }?>	
			</div><hr>
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
			<h6 class="m-0 font-weight-bold text-primary">Priority</h6>
			<div class="font-weight-bold">
				<?php if ($detail['id_kondisi'] == 0) {?>
						Will be determined
				<?php } else { ?>
					<div style="color: <?php echo $detail['warna']?>">
						<i class="fas fa-exclamation-triangle"></i>
						<?php echo $detail['nama_kondisi'] ?> - <?php echo $detail['waktu_respon'] ?> Day
					</div>
				<?php } ?>
			</div><hr>
			<h6 class="m-0 font-weight-bold text-primary">Subect</h6>
			<div class="font-weight-bold">
				<?php echo $detail['problem_summary'] ?><br>
			</div><hr>
			<h6 class="m-0 font-weight-bold text-primary">Description</h6>
			<div class="font-weight-bold">
				<?php echo nl2br($detail['problem_detail']) ?><br>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6 mb-4">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">System Tracking</h6>
				</div>
				<div class="card-body" style="overflow-x: scroll; height: 320px;">
					<?php $no = 1; foreach ($tracking as $row){?>
						<div class="tracking-item">
							<div class="tracking-icon status-intransit text-primary" data-icon="circle">
								<?php echo $no?>
							</div>
							<div class="tracking-date">
								<div class="font-weight-bold"><?php echo $row->tanggal?></div>
							</div>
							<div class="tracking-content">
								<div class="font-weight-bold text-primary"><?php echo $row->status?></div>
								<h4 class="small font-weight-bold">By: <?php echo $row->nama?></h4>
								<?php if($row->filefoto!="")
						        	{?>
						        		<?php if (pathinfo($row->filefoto, PATHINFO_EXTENSION) == 'pdf'){?>
						        			<p><?php echo nl2br($row->deskripsi)?></p>
						        			<a href="<?php echo base_url('teknisi/'.$row->filefoto) ?>" class="btn btn-light btn-icon-split">
						        				<span class="icon text-gray-600">
						        					<i class="fas fa-file-pdf"></i>
						        				</span>
						        				<span class="text"><?php echo $row->filefoto ?></span>
						        			</a><br>
						        			Click to download
						        		<?php } else {?>
						        			<p><?php echo nl2br($row->deskripsi)?></p>
						        			<a data-fancybox="gallery"  href="<?php echo base_url('teknisi/'.$row->filefoto) ?>">
						        				<img src="<?php echo base_url('teknisi/'.$row->filefoto) ?>" style="width:100%;max-width:300px">
						        			</a><br>
						        			Click to zoom
						        		<?php }?>
						        	<?php } else {
						        		echo nl2br($row->deskripsi);
						        	}?>
							</div>
						</div>
					<?php $no++;}?>
				</div>
			</div>
		</div>
		<div class="col-lg-6 mb-4">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">
						<?php echo " Processed By ".$detail['nama_teknisi'] ?></h6>
				</div>
				<div class="card-body">
					<h6 class="font-weight-bold text-primary">Progress <span class="float-right text-primary"><?php echo $detail['progress'] ?>%</span></h6>
					<div class="progress mb-4">
						<div class="progress-bar" role="progressbar" style="width: <?php echo $detail['progress'] ?>%" aria-valuenow="<?php echo $detail['progress'] ?>" aria-valuemin="0" aria-valuemax="100">
						</div>
					</div><hr>
					<h6 class="m-0 font-weight-bold text-primary">Deadline Date</h6>
					<div class="font-weight-bold">
						<?php if ($detail['deadline'] == "0000-00-00 00:00:00") 
						{ 
							echo "Not set yet";
						} else { ?>
							<span class="label label-primary"><?php echo $detail['deadline']; ?> </span>
						<?php } ?><br>
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>