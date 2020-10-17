<?php if ($this->session->userdata('level') == "Admin") { ?>
	<div class="container-fluid">
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
		</div>

		<div class="row">
			<!--Semua Tiket-->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card bg-danger text-white shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-uppercase mb-1">All Ticket</div>
								<div class="h5 mb-0 font-weight-bold"><?php echo $jml_ticket ?></div>
							</div>
							<div class="col-auto">
								<i class="fas fa-ticket-alt fa-2x"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--Need Approve-->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card bg-warning text-white shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-uppercase mb-1">New Ticket</div>
								<div class="h5 mb-0 font-weight-bold"><?php echo $jlm_new ?></div>
								<h4 class="small font-weight-bold">Rejected:  <span><?php echo $jml_reject ?></span></h4>
							</div>
							<div class="col-auto">
								<i class="fas fa-clipboard-list fa-2x"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--Process-->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card bg-info text-white shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-uppercase mb-1">On Process</div>
								<div class="h5 mb-0 font-weight-bold"><?php echo $jml_process ?></div>
								<h4 class="small font-weight-bold">On Hold:  <span><?php echo $jml_pending ?></span></h4>
							</div>
							<div class="col-auto">
								<i class="fas fa-circle-notch fa-2x"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--Done-->
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card bg-primary text-white shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-uppercase mb-1">Done</div>
								<div class="h5 mb-0 font-weight-bold"><?php echo $jml_done ?></div>
							</div>
							<div class="col-auto">
								<i class="fas fa-check-circle fa-2x"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-8 col-lg-7">
				<!-- Bar Chart -->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-gray-800">Ticket (<?php echo $jlmticket ?>)</h6>
						<hr>All ticket can be found in the <code>Ticket -> List Ticket</code>.
					</div>
					<div class="card-body" style="overflow-x: scroll; height: 390px;">
						<div class="table-responsive-sm">
							<table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>No</th>
										<th>ID Ticket</th>
										<th>Date</th>
										<th>Name</th>
										<th>Sub Category</th>
										<th>Priority</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php $no = 1; foreach ($ticket as $row){?>
										<tr>
											<td><?php echo $no ?></td>
											<td><?php echo $row->id_ticket ?></td>
											<td><?php echo $row->tanggal ?></td>
											<td><?php echo $row->nama ?></td>
											<td><?php echo $row->nama_sub_kategori ?></td>
											<?php if($row->id_kondisi == 0){?>
												<td>Not set yet</td>
											<?php }else{?>
												<td style="color: <?php echo $row->warna?>"><?php echo $row->nama_kondisi ?></td>
											<?php }?> 
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
										</tr>
									<?php $no++;}?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- Pie Chart -->
			<div class="col-xl-4 col-lg-5">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-gray-800">Technician</h6>
					</div>
					<!-- Card Body -->
					<div class="card-body" style="overflow: scroll; height: 450px;">
						<ul>
							<?php $no = 0;
							foreach ($teknisi as $row) : $no++; ?>
									<i class="fas fa-fw fa-user text-black-100"></i>
									<B class="chat-img pull-left">
										<?php echo $row->nama; ?>
									</B>
									<div class="chat-body clearfix">
										<?php if ($row->total == null) {
											echo "Assigned Ticket: 0";
										} else {
											echo "Assigned Ticket: $row->total";
										}?>
										<p></p>
									</div><hr>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6 mb-4">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-gray-800">Ticket 
							(<script type="text/javascript">var year = new Date();document.write(year.getFullYear());</script>)
						</h6>
					</div>
					<!-- Card Body -->
					<div class="card-body">
						<div class="chart-area">
							<canvas id="myAreaChart"></canvas>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6 mb-4">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-gray-800">Ticket By Priority
							(<script type="text/javascript">var year = new Date();document.write(year.getFullYear());</script>)
						</h6>
					</div>
					<!-- Card Body -->
					<div class="card-body">
						<div class="chart-area">
							<canvas id="myPieChart"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6 mb-4">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-gray-800">Ticket By Sub Category
							(<script type="text/javascript">var year = new Date();document.write(year.getFullYear());</script>)
						</h6>
					</div>
					<!-- Card Body -->
					<div class="card-body">
						<div class="chart-area">
							<canvas id="myBarChart"></canvas>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6 mb-4">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-gray-800">Ticket By Status
							(<script type="text/javascript">var year = new Date();document.write(year.getFullYear());</script>)
						</h6>
					</div>
					<!-- Card Body -->
					<div class="card-body">
						<div class="chart-area">
							<canvas id="myPieChart2"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

		<?php
	}else if ($this->session->userdata('level') == "Technician") { ?>
		<div class="container-fluid">
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
			</div>
			<div class="row">
				<!--Need Approve-->
				<div class="col-xl-3 col-md-6 mb-4">
					<div class="card bg-danger text-white shadow h-100 py-2">
						<div class="card-body">
							<div class="row no-gutters align-items-center">
								<div class="col mr-2">
									<div class="text-xs font-weight-bold text-uppercase mb-1">Assigned Ticket</div>
									<div class="h5 mb-0 font-weight-bold"><?php  echo $tekassign ?></div>
								</div>
								<div class="col-auto">
									<i class="fas fa-ticket-alt fa-2x"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!--Pending-->
				<div class="col-xl-3 col-md-6 mb-4">
					<div class="card bg-warning text-white shadow h-100 py-2">
						<div class="card-body">
							<div class="row no-gutters align-items-center">
								<div class="col mr-2">
									<div class="text-xs font-weight-bold text-uppercase mb-1">New Ticket</div>
									<div class="h5 mb-0 font-weight-bold"><?php  echo $tekapprove ?></div>
								</div>
								<div class="col-auto">
									<i class="fas fa-clipboard-list fa-2x"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!--Proses-->
				<div class="col-xl-3 col-md-6 mb-4">
					<div class="card bg-info text-white shadow h-100 py-2">
						<div class="card-body">
							<div class="row no-gutters align-items-center">
								<div class="col mr-2">
									<div class="text-xs font-weight-bold text-uppercase mb-1">On Process</div>
									<div class="h5 mb-0 font-weight-bold"><?php echo $tekkerja ?></div>
									<h4 class="small font-weight-bold">On Hold: <span><?php echo $tekpending ?></span></h4>
								</div>
								<div class="col-auto">
									<i class="fas fa-circle-notch fa-2x"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!--Done-->
				<div class="col-xl-3 col-md-6 mb-4">
					<div class="card bg-primary text-white shadow h-100 py-2">
						<div class="card-body">
							<div class="row no-gutters align-items-center">
								<div class="col mr-2">
									<div class="text-xs font-weight-bold text-uppercase mb-1">Done</div>
									<div class="h5 mb-0 font-weight-bold"><?php echo $tekselesai ?></div>
								</div>
								<div class="col-auto">
									<i class="fas fa-check-circle fa-2x"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
			<div class="col-xl-8 col-lg-7">
				<!-- Bar Chart -->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-gray-800">My Assignment (<?php echo $jlmtugas ?>)</h6>
					</div>
					<div class="card-body" style="overflow-x: scroll; height: 390px;">
						<div class="table-responsive-sm">
							<table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>No</th>
										<th>ID Ticket</th>
										<th>Priority</th>
										<th>Date</th>
										<th>Deadline</th>
										<th>Name</th>
										<th>Sub Category</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php $no = 1; foreach ($datatickettek as $row){?>
										<tr>
											<td><?php echo $no ?></td>
											<td><?php echo $row->id_ticket ?></td>
											<?php if($row->id_kondisi == 0){?>
												<td>Not set yet</td>
											<?php }else{?>
												<td style="color: <?php echo $row->warna?>"><?php echo $row->nama_kondisi ?></td>
											<?php }?> 
											<td><?php echo $row->tanggal ?></td>
											<td><?php echo $row->deadline ?></td>
											<td><?php echo $row->nama ?></td>
											<td><?php echo $row->nama_sub_kategori ?></td>
											<?php if ($row->status == 3) {?>
												<td>
													<strong style="color: #A2B969;">Assigned to You</strong>
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
											<?php } ?>
										</tr>
									<?php $no++;}?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- Pie Chart -->
			<div class="col-xl-4 col-lg-5">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-gray-800">Information</h6>
					</div>
					<!-- Card Body -->
					<div class="card-body" style="overflow: scroll; height: 450px;">
						<ul>
							<?php $no = 0;
							foreach ($datainformasi as $row) : $no++; ?>
								<li class="left clearfix">
									<span class="chat-img pull-left">
										<?php echo $row->nama; ?> (<small><?php echo $row->tanggal; ?></small>)
									</span>
									<div class="chat-body clearfix">
										<b><?php echo $row->subject; ?></b>
										<h4 class="small font-weight-bold"><?php echo $row->pesan; ?>.</h4>
										<p></p>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		</div>

		

		<!--Menu Untuk User-->
		<?php
	}else if ($this->session->userdata('level') == "User") { ?>
		<div class="container-fluid">
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
			</div>
			<div class="row">
				<!--All ticket-->
				<div class="col-xl-3 col-md-6 mb-4">
					<div class="card bg-danger text-white shadow h-100 py-2">
						<div class="card-body">
							<div class="row no-gutters align-items-center">
								<div class="col mr-2">
									<div class="text-xs font-weight-bold text-uppercase mb-1">Your Ticket</div>
									<div class="h5 mb-0 font-weight-bold"><?php  echo $userticket ?></div>
								</div>
								<div class="col-auto">
									<i class="fas fa-ticket-alt fa-2x"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!--Approved-->
				<div class="col-xl-3 col-md-6 mb-4">
					<div class="card bg-warning text-white shadow h-100 py-2">
						<div class="card-body">
							<div class="row no-gutters align-items-center">
								<div class="col mr-2">
									<div class="text-xs font-weight-bold text-uppercase mb-1">New Ticket</div>
									<div class="h5 mb-0 font-weight-bold"><?php  echo $userapprove ?></div>
									<h4 class="small font-weight-bold">Reject: <span><?php echo $userreject ?></span></h4>
								</div>
								<div class="col-auto">
									<i class="fas fa-clipboard-list fa-2x"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!--On Process-->
				<div class="col-xl-3 col-md-6 mb-4">
					<div class="card bg-info text-white shadow h-100 py-2">
						<div class="card-body">
							<div class="row no-gutters align-items-center">
								<div class="col mr-2">
									<div class="text-xs font-weight-bold text-uppercase mb-1">On Process</div>
									<div class="h5 mb-0 font-weight-bold"><?php echo $userprocess ?></div>
									<h4 class="small font-weight-bold">Pending: <span><?php echo $userpending ?></span></h4>
								</div>
								<div class="col-auto">
									<i class="fas fa-circle-notch fa-2x"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!--Done-->
				<div class="col-xl-3 col-md-6 mb-4">
					<div class="card bg-primary text-white shadow h-100 py-2">
						<div class="card-body">
							<div class="row no-gutters align-items-center">
								<div class="col mr-2">
									<div class="text-xs font-weight-bold text-uppercase mb-1">Done</div>
									<div class="h5 mb-0 font-weight-bold"><?php echo $userdone ?></div>
								</div>
								<div class="col-auto">
									<i class="fas fa-check-circle fa-2x"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
			<div class="col-xl-8 col-lg-7">
				<!-- Bar Chart -->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-gray-800">My Ticket Summary</h6>
						<hr>Detail for the ticket can be found in the <code>My Ticket</code>.
					</div>
					<div class="card-body" style="overflow-x: scroll; height: 390px;">
						<div class="table-responsive-sm">
							<table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>No</th>
										<th>ID Ticket</th>
										<th>Date</th>
										<th>Name</th>
										<th>Sub Category</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php $no = 1; foreach ($dataticketuser as $row){?>
										<tr>
											<td><?php echo $no ?></td>
											<td><?php echo $row->id_ticket ?></td>
											<td><?php echo $row->tanggal ?></td>
											<td><?php echo $row->nama ?></td>
											<td><?php echo $row->nama_sub_kategori ?></td>
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
										</tr>
									<?php $no++;}?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- Pie Chart -->
			<div class="col-xl-4 col-lg-5">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-gray-800">Information</h6>
					</div>
					<!-- Card Body -->
					<div class="card-body" style="overflow: scroll; height: 450px;">
						<ul>
							<?php $no = 0;
							foreach ($datainformasi as $row) : $no++; ?>
								<li class="left clearfix">
									<span class="chat-img pull-left">
										<?php echo $row->nama; ?> (<small><?php echo $row->tanggal; ?></small>)
									</span>
									<div class="chat-body clearfix">
										<b><?php echo $row->subject; ?></b>
										<h4 class="small font-weight-bold"><?php echo $row->pesan; ?>.</h4>
										<p></p>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<?php
    //Inisialisasi nilai variabel awal
    $subkat 	= "";
    $jumlah		=null;

    $kondisi 	= "";
    $BGkondisi 	= "";
    $jkondisi 	=null;

    $bulan 		= "";
    $Jbulan		=null;

    $Tstat     = "";
    $BGstat   = "";
    $Jstat    = null;

    foreach ($lbl_subkat as $data)
    {
        $sub=$data->nama_sub_kategori;
        $subkat .= "'$sub'". ", ";
        $jum=$data->total;
        $jumlah .= "$jum". ", ";
    }

    foreach ($lbl_kondisi as $data)
    {
    	$id=$data->id_kondisi;
    	if($id == 0){
    		$knds= "Not set yet";
    	}else{
    		$knds=$data->nama_kondisi;
    	}
        $kondisi .= "'$knds'". ", ";
        $bg=$data->warna;
        $BGkondisi .= "'$bg'". ", ";
        $jumk=$data->jumkondisi;
        $jkondisi .= "$jumk". ", ";
    }

    foreach ($lbl_perbulan as $data)
    {
        $bul=$data->bulan;
        $bulan .= "'$bul'". ", ";
        $Jumb=$data->jumbulan;
        $Jbulan .= "$Jumb". ", ";
    }

    foreach ($lbl_status as $data)
    {
        if ($data->status == 0) {
            $stat = "Ticket Rejected";
            $bg = "#F36F13";
        } else if ($data->status == 1) {
            $stat = "Ticket Submited";
            $bg = "#946038";
        } else if ($data->status == 2) {
            $stat = "Category Changed";
            $bg = "#FFB701";
        } else if ($data->status == 3) {
            $stat = "Assigned to Technician";
            $bg = "#A2B969";
        } else if ($data->status == 4) {
            $stat = "On Process";
            $bg = "#0D95BC";
        } else if ($data->status == 5) {
            $stat = "Pending";
            $bg = "#023047";
        } else if ($data->status == 6) {
            $stat = "Solve";
            $bg = "#2E6095";
        } else if ($data->status == 7) {
            $stat = "Late Finished";
            $bg = "#C13018";
        }
        $Tstat  .= "'$stat'". ", ";
        $BGstat .= "'$bg'". ", ";
        $jstat   =$data->jumstat;
        $Jstat  .= "$jstat". ", ";
    }
?>

<script type="text/javascript">
	window.onload = function() {
		var Bar = document.getElementById("myBarChart");
		var chart = new Chart(Bar, {
			type: 'horizontalBar',
			data: {
				labels: [<?php echo $subkat; ?>],
				datasets : [{
					label: 'Total Ticket',
					backgroundColor: "#FC8500",
					hoverBackgroundColor: "#FC8500",
					borderColor: "#4e73df",
					data: [<?php echo $jumlah; ?>]
				}]
			},
			options: {
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
						ticks: {
							beginAtZero:true,
						}
					}],
					yAxes: [{
						gridLines: {
							display: false,
							drawBorder: false
						},
						maxBarThickness: 25,
					}]
				},
				legend: {
					display: false
				}
			}
		});

		var Line = document.getElementById("myAreaChart");
		var myLineChart = new Chart(Line, {
			type: 'line',
			data: {
				labels: [<?php echo $bulan; ?>],
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
					data: [<?php echo $Jbulan; ?>]
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

		var Pie = document.getElementById("myPieChart");
		var myPieChart = new Chart(Pie, {
			type :'doughnut',
			data: {
				labels: [<?php echo $kondisi; ?>],
				datasets: [{
					data: [<?php echo $jkondisi; ?>],
					backgroundColor: [<?php echo $BGkondisi; ?>],
					hoverBackgroundColor: [<?php echo $BGkondisi; ?>],
					hoverBorderColor: "rgba(234, 236, 244, 1)",
				}],
			},
			options: {
				maintainAspectRatio: false,
				legend: {
					position: 'right'
				},
				tooltips: {
					borderWidth: 1,
					xPadding: 15,
					yPadding: 15,
					caretPadding: 10,
				},
			},
		});

		var Pie = document.getElementById("myPieChart2");
		var myPieChart = new Chart(Pie, {
			type :'doughnut',
			data: {
				labels: [<?php echo $Tstat?>],
				datasets: [{
					data: [<?php echo $Jstat; ?>],
					backgroundColor: [<?php echo $BGstat; ?>],
					hoverBackgroundColor: [<?php echo $BGstat; ?>],
					hoverBorderColor: "rgba(234, 236, 244, 1)",
				}],
			},
			options: {
				maintainAspectRatio: false,
				legend: {
					position: 'right'
				},
				tooltips: {
					borderWidth: 1,
					xPadding: 15,
					yPadding: 15,
					caretPadding: 10,
				},
			},
		});
	}
</script>