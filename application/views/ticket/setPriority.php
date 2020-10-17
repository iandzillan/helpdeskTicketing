<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		$("#id_kondisi").change(function() {
		// Put an animated GIF image insight of content	 		
		var data = {
			id_kondisi: $("#id_kondisi").val()
		};
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('Select/select_kondisi') ?>",
			data: data,
			success: function(msg) {
				$('#div-order').html(msg);
			}
		});
	});

	});
</script>

<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		$("#id_teknisi").change(function() {
		// Put an animated GIF image insight of content	 		
		var data = {
			id_teknisi: $("#id_teknisi").val()
		};
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('Select/select_job') ?>",
			data: data,
			success: function(msg) {
				$('#div-order2').html(msg);
			}
		});
	});

	});
</script>

<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Set Priority and Technician (<?php echo $detail['id_ticket']?>)</h1>
	</div><hr>

	<?php echo form_error('id_kondisi'); ?>
	<?php echo form_error('id_teknisi'); ?>
	<form method="post" action="<?php echo site_url('List_ticket/approve/'.$detail['id_ticket'])?>" enctype="multipart/form-data">
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-gray-800">
					<?php echo " Ticket from ".$detail['nama']." (" .$detail['tanggal'].")" ?>
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
						</a><br>
						Click to download
					<?php } else {?>
						<a data-fancybox="gallery"  href="<?php echo base_url('uploads/'.$detail['filefoto']) ?>">
							<img src="<?php echo base_url('uploads/'.$detail['filefoto']) ?>" style="width:100%;max-width:300px">
						</a><br>
						Click to zoom
					<?php }?>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Department</h6>
				<div class="font-weight-bold">
					<?php echo $detail['nama_dept']." (".$detail['nama_bagian_dept'].")" ?><p></p>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Position</h6>
				<div class="font-weight-bold">
					<?php echo $detail['nama_jabatan'] ?><br>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Categgory</h6>
				<div class="font-weight-bold">
					<?php echo $detail['nama_kategori']." (".$detail['nama_sub_kategori'].")" ?><p></p>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Email</h6>
				<div class="font-weight-bold">
					<?php echo $detail['email'] ?><p></p>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Location</h6>
				<div class="font-weight-bold">
					<?php echo $detail['lokasi'] ?><p></p>
				</div><hr>

				<h6 class="m-0 font-weight-bold text-primary">Subect</h6>
				<div class="font-weight-bold">
					<?php echo $detail['problem_summary'] ?><p></p>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Description</h6>
				<div class="font-weight-bold">
					<?php echo nl2br($detail['problem_detail']) ?>
				</div><hr>

				<div class="form-group">
					<label class="m-0 font-weight-bold text-primary">Priority</label>
					<?php echo form_dropdown('id_kondisi',$dd_kondisi, $id_kondisi, ' id="id_kondisi" class="form-control"');?>
				</div>

				<div class="form-group">
					<div id="div-order"></div>
				</div><hr>

				<h6 class="m-0 font-weight-bold text-primary">Choose Technician</h6>
				<div class="form-group">
					<?php echo form_dropdown('id_teknisi', $dd_teknisi, $id_teknisi, 'id="id_teknisi" class="form-control"'); ?>
					<br>
					<div>
						<div class="col-lg-9">
							<div id="div-order2"></div>
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('List_ticket/list_approve') ?>'">Cancel</button><p>
			</div>
		</div>
	</form>
</div>