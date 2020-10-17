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
	<h1 class="h3 mb-0 text-gray-800">Create Ticket</h1>
	<p class="mb-4">Please sumbit your problem.</p>

	<div class="flash-data" data-flashdata="<?php echo $this->session->flashdata('status')?>"></div>
	<?php echo form_error('id_kategori'); ?>
	<?php echo form_error('id_sub_kategori'); ?>
	<?php echo form_error('id_kondisi'); ?>
	<?php echo form_error('id_lokasi'); ?>
	<?php echo form_error('problem_summary'); ?>
	<?php echo form_error('problem_detail'); ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Create your ticket</h6>
		</div>
		<div class="card-body">
			<form method="post" action="<?php echo site_url('List_ticket_user/submit')?>" enctype="multipart/form-data">
				
				<input class="form-control" name="nama" value="<?php echo  $profile['nama'] ?>" hidden>
				<input class="form-control" name="email" value="<?php echo  $profile['email'] ?>" hidden>

				<div class="form-group">
					<label class="m-0 font-weight-bold text-primary">Category</label>
					<?php echo form_dropdown('id_kategori', $dd_kategori, $id_kategori, 'id="id_kategori" class="form-control"'); ?>
				</div>

				<div class="form-group">
					<label class="m-0 font-weight-bold text-primary">Sub Category</label>
					<div id="div-order">
						<?php echo form_dropdown('id_sub_kategori', $dd_sub_kategori, $id_sub_kategori, ' class="form-control"');?>
					</div>
				</div>

				<div class="form-group">
					<label class="m-0 font-weight-bold text-primary">Lokasi</label>
					<?php echo form_dropdown('id_lokasi',$dd_lokasi, $id_lokasi, ' class="form-control"');?>
				</div>

				<div class="form-group">
					<label class="m-0 font-weight-bold text-primary">Subject</label>
					<input class="form-control" name="problem_summary" placeholder="Subject">
				</div>

				<div class="form-group">
					<label class="m-0 font-weight-bold text-primary">Description</label>
					<textarea name="problem_detail" placeholder="Describe your problem" class="form-control" rows="10"></textarea>
				</div>

				<div class="form-group">
					<label class="m-0 font-weight-bold text-primary">Attachment</label> </br>
					<span class="label label-success">Max Size 2 MB. Format file: gif, jpg, png, or pdf.</span><br>
					<input type="file" name="filefoto" size="20" required>
				</div><br>

				<button type="submit" class="btn btn-primary">Submit</button>

			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	const flashData = $('.flash-data').data('flashdata');
	if (flashData){
		Swal.fire({
			icon: 'error',
			title: flashData,
			text: 'Something went wrong! Image file is more than 2MB or not supported format',
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