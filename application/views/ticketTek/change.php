<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		$('#id_kategori').attr('disabled', true);
		$('#id_sub_kategori').attr('disabled', true);
		$('#diagnos').on('keyup', function() {
			if($(this).val().length>0){
				$('#id_kategori').attr('disabled', false);
				$('#id_sub_kategori').attr('disabled', false);
			} else {
				$('#id_kategori').attr('disabled', true);
				$('#id_sub_kategori').attr('disabled', true);
			}
		});
	});
</script>
<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		$("#id_kategori").change(function() {	 		
			var data = {
				id_kategori: $("#id_kategori").val()
			};
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('Select/select_sub') ?>",
				data: data,
				success: function(msg) {
					$('#div-order').html(msg);
				}
			});
		});

	});
</script>

<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Change Category (<?php echo $detail['id_ticket']?>)</h1>
	</div>

	<div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('status')?>"></div>
	<div class="flash-data1" data-flashdata="<?php echo $this->session->flashdata('status1')?>"></div>
	<?php echo form_error('id_sub_kategori'); ?>
	<?php echo form_error('id_kategori'); ?>
	<?php echo form_error('diagnos'); ?>

	<form method="post" action="<?php echo site_url('List_ticket_tek/change/'.$detail['id_ticket']) ?>" enctype="multipart/form-data">
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">
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
					<?php echo $detail['nama_dept']." (".$detail['nama_bagian_dept'].")" ?><p></p>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Position</h6>
				<div class="font-weight-bold">
					<?php echo $detail['nama_jabatan'] ?><br>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Email</h6>
				<div class="font-weight-bold">
					<?php echo $detail['email'] ?><p></p>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Location</h6>
				<div class="font-weight-bold">
					<?php echo $detail['lokasi'] ?><p></p>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Priority</h6>
				<div class="font-weight-bold" style="color: <?php echo $detail['warna']?>">
					<i class="fas fa-exclamation-triangle"></i>
					<?php echo $detail['nama_kondisi'] ?><p></p>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Subect</h6>
				<div class="font-weight-bold">
					<?php echo $detail['problem_summary'] ?><p></p>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Description</h6>
				<div class="font-weight-bold">
					<?php echo nl2br($detail['problem_detail']) ?><p></p>
				</div><hr>
				<div class="form-group">
					<h6 class="m-0 font-weight-bold text-primary">Describe Your Diagnosis</h6><br>
					<textarea name="diagnos" class="form-control" rows="10" id="diagnos"></textarea>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Category</h6>
				<div class="form-group">
					<?php echo form_dropdown('id_kategori', $dd_kategori, $id_kategori, 'id="id_kategori" class="form-control"'); ?>
					<code>*Describe your diagnosis first before choose the category</code>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Sub Category</h6>
				<div class="form-group">
					<div id="div-order">
						<?php echo form_dropdown('id_sub_kategori', $dd_sub_kategori, $id_sub_kategori, 'id="id_sub_kategori" class="form-control"');?>
					</div>
					<code>*Describe your diagnosis first before choose the sub category</code>
				</div><hr>
				<div class="form-group">
					<h6 class="m-0 font-weight-bold text-primary">Attachment</h6>
					<span class="label label-success">Max Size 2 MB. Format file: gif, jpg, png, or pdf.</span><br>
					<input type="file" name="filediagnosa" size="20" required>
				</div>
			</div>
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
		<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('List_ticket_tek/detail_update/'.$detail['id_ticket']) ?>'">Cancel</button><p></p>
	</form>
</div>

<script type="text/javascript">
	const flashData = $('.flash-data').data('flashdata');
	if (flashData){
		Swal.fire({
			icon: 'error',
			title: 'Error',
			text: flashData,
			footer: ''
		})
	}

	const flashData1 = $('.flash-data1').data('flashdata');
	if (flashData1){
		Swal.fire({
			icon: 'warning',
			title: 'Warning',
			text: flashData1,
			footer: ''
		})
	}
	
	$('textarea').keypress(function(event) {
      if (event.which == 13) {
        event.preventDefault();
          var s = $(this).val();
          $(this).val(s+"\n");
      }
    });
</script>