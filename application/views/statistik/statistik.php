<script type="text/javascript">
	$(document).ready(function() {
		$("#id_tahun").change(function() {
		// Put an animated GIF image insight of content	 		
			var data = {
				id_tahun: $("#id_tahun").val()
			};
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('Select/select_tahun') ?>",
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
		<h1 class="h3 mb-0 text-gray-800">Statistic of Ticket</h1>
		<a href="#modal-fade" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal">
			<i class="fas fa-download fa-sm text-white-50"></i> Generate Report
		</a>
	</div><hr>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-gray-800">
				Ticket
			</h6>
		</div>
		<div class="card-body">
			<div class="chart-area">
				<canvas id="myAreaChart"></canvas>
			</div>
		</div>
	</div>

	<h6 class="m-0 font-weight-bold text-gray-800">Report in a Year</h6><hr>
	<?php echo form_dropdown('id_tahun', $dd_tahun, $id_tahun, 'id="id_tahun" class="form-control"'); ?><br>
	<div id="div-order"></div>

</div>

<div id="modal-fade" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="m-0 font-weight-bold text-gray-800">Report</h6>
			</div>
			<div class="modal-body">
				<form id="form-validation" action="<?php echo site_url('Statistik/report') ?>" method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label>From</label>
						<input class="form-control" name="tgl1" id="datepicker" placeholder="Choose here..." rows="3" required>
					</div>

					<div class="form-group">
						<label>To</label>
						<input class="form-control" name="tgl2" id="datepicker2" placeholder="Choose here..." rows="3" required>
					</div>
					<button type="submit" class="btn btn-primary">Download</button>
					<button type="button" class="btn btn-danger" class="close" data-dismiss="modal">Cancel</button>	
				</form>
			</div>
		</div>
	</div>
</div>

<?php
    $tahun 		= "";
    $JTahun		=null;

    foreach ($stat_tahun as $data)
    {
        $thn=$data->tahun;
        $tahun .= "'$thn'". ", ";
        $jlmthn=$data->jumtahun;
        $JTahun .= "$jlmthn". ", ";
    }
?>

<script type="text/javascript">
	window.onload = function(){
		var Line = document.getElementById("myAreaChart");
		var myLineChart = new Chart(Line, {
			type: 'line',
			data: {
				labels: [<?php echo $tahun; ?>],
				datasets: [{
					label: 'Total Ticket',
					lineTension: 0.3,
					backgroundColor: "transparent",
					borderColor: "#209EEB",
					pointRadius: 3,
					pointBackgroundColor: "#209EEB",
					pointBorderColor: "#209EEB",
					pointHoverRadius: 3,
					pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
					pointHoverBorderColor: "rgba(78, 115, 223, 1)",
					pointHitRadius: 10,
					pointBorderWidth: 2,
					data: [<?php echo $JTahun; ?>]
				}],
			},
			options:{
				maintainAspectRatio: false,
				tooltips: {
					displayColors : false
				},
				layout: {
					padding: {
						left: 10,
						right: 25,
						top: 25,
						bottom: 0
					}
				},
				scales: {
					xAxes: [{
						gridLines: {
							display: false,
							drawBorder: false,
						},
						maxBarThickness: 25,
					}],
					yAxes: [{
						ticks: {
							beginAtZero:true,
						}
					}]
				},
				legend: {
					display: false
				}
			}
		});
	}
</script>