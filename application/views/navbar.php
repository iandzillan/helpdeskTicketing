<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
	<div class="font-weight-bold text-gray-800">PT. Lestari Banten Energi</div>
	<ul class="navbar-nav ml-auto">
		<li class="nav-item dropdown no-arrow">
			<a class="nav-link dropdown-toggle" href="javascript:void(0)" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="mr-2 d-none d-lg-inline text-gray-800 small">
					<?php echo $this->session->userdata('nama'); ?>
				</span>
				<i class="fas fa-fw fa-user text-black-100"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

				<a class="dropdown-item" href="<?php echo site_url('User/profile') ?>">
					<i class="fas fa-id-card-alt fa-sm fa-fw mr-2 text-black-100"></i>
					Profile
				</a>
				
				<a class="dropdown-item" href="<?php echo site_url('User/password') ?>">
					<i class="fas fa-key fa-sm fa-fw mr-2 text-black-100"></i>
					Change Password
				</a>
				
				<a class="dropdown-item" href="#modal-stok" data-toggle="modal">
					<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-black-100"></i>
					Logout
				</a>

			</div>
		</li>
	</ul>
</nav>