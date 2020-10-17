<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		$('#progress').attr('disabled', true);
		$('#desk').on('keyup', function() {
			if($(this).val().length>0){
				$('#progress').attr('disabled', false);
			} else {
				$('#progress').attr('disabled', true);
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
		<h1 class="h3 mb-0 text-gray-800">Detail Ticket <?php echo $detail['id_ticket']?></h1>
		<a href="<?php echo site_url('List_ticket_tek/changeCategory/'.$detail['id_ticket'])?>" class="btn btn-warning btn-icon-split change">
			<span class="icon text-white-50">
				<i class="fas fa-edit"></i>
			</span>
			<span class="text">Change Category</span>
		</a>
	</div>

	<div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('status')?>"></div>
	<?php echo form_error('desk'); ?>

	<form method="post" action="<?php echo site_url('List_ticket_tek/update/'.$detail['id_ticket']) ?>" enctype="multipart/form-data">
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
				<h6 class="m-0 font-weight-bold text-primary">Categgory</h6>
				<div class="font-weight-bold">
					<?php echo $detail['nama_kategori'] ?><p></p>
				</div><hr>
				<h6 class="m-0 font-weight-bold text-primary">Sub Categgory</h6>
				<div class="font-weight-bold">
					<?php echo $detail['nama_sub_kategori'] ?><p></p>
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
				<h6 class="m-0 font-weight-bold text-primary">Progress Now</h6><br>
				<div class="progress mb-4">
					<div class="progress-bar" role="progressbar" style="width: <?php echo $detail['progress'] ?>%" aria-valuenow="<?php echo $detail['progress'] ?>" aria-valuemin="0" aria-valuemax="100">
						<span><?php echo $detail['progress'] ?> % Complete (Progress)</span>
					</div>
				</div>
			</div>
		</div>
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">
					Update Progress
				</h6>
			</div>
			<div class="card-body">

				<div class="form-group">
					<h6 class="m-0 font-weight-bold text-primary">Description</h6><br>
					<textarea name="desk" class="form-control" rows="10" id="desk"></textarea>
				</div><br>

				<div class="form-group">
					<select name="progress" class="form-control" id="progress">
						<?php for ($i = $detail['progress'] ; $i <= 100; $i += 10) { ?>
							<option value="<?php echo $i; ?>">Progress <?php echo $i; ?> %</option>
						<?php } ?>
					</select>
					<code>*Describe your progress first before choose the progress</code>
				</div><hr>

				<div class="form-group">
					<h6 class="m-0 font-weight-bold text-primary">Attachment</h6>
					<span class="label label-success">Max Size 2 MB. Format file: gif, jpg, png, or pdf.</span><br>
					<input type="file" name="fileupdate" size="20" required>
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="button" class="btn btn-danger" onclick="window.location='<?php echo site_url('List_ticket_tek/index_tugas') ?>'">Cancel</button>
			</div>
		</div>
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

	$('.change').on('click', function(e) {
		e.preventDefault();
		const href = $(this).attr('href');

		Swal.fire({
			title: 'Are you sure?',
			text: "This ticket will be returned to the admin",
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
	
	$('textarea').keypress(function(event) {
      if (event.which == 13) {
        event.preventDefault();
          var s = $(this).val();
          $(this).val(s+"\n");
      }
    });
</script>