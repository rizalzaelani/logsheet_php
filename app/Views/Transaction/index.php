<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
	<div class="col-12">
		<div class="card">
			<div class="container-fluid">
				<div class="card-header">
					<h4 class="title"><?= (isset($subtitle) ? $subtitle : ''); ?></h4>
				</div>
				<div class="card-body">
					<div class="row">
						<!-- /.col-->
						<div class=" col-sm-6 col-lg-6">
							<div class="card text-white bg-gradient-warning">
								<div class="card-body card-body pb-0 d-flex justify-content-between align-items-start">
									<div>
										<div class="text-value-lg">9.823</div>
										<div>WAITING CHECKED</div>
									</div>
									<div class="btn-group">
										<button class="btn btn-transparent dropdown-toggle p-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<svg class="c-icon">
												<use xlink:href="/icons/coreui/svg/free.svg#cil-settings"></use>
											</svg>
										</button>
										<div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a></div>
									</div>
								</div>
								<div class="c-chart-wrapper mt-3" style="height:70px;">
									<canvas class="chart" id="card-chart3" height="70"></canvas>
								</div>
							</div>
						</div>
						<!-- /.col-->
						<div class="col-sm-6 col-lg-6">
							<div class="card text-white bg-gradient-success">
								<div class="card-body card-body pb-0 d-flex justify-content-between align-items-start">
									<div>
										<div class="text-value-lg">9.823</div>
										<div>CHECKED</div>
									</div>
									<div class="btn-group">
										<button class="btn btn-transparent dropdown-toggle p-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<svg class="c-icon">
												<use xlink:href="/icons/coreui/svg/free.svg#cil-settings"></use>
											</svg>
										</button>
										<div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a></div>
									</div>
								</div>
								<div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
									<canvas class="chart" id="card-chart4" height="70"></canvas>
								</div>
							</div>
						</div>
						<!-- /.col-->
					</div>

				</div>
			</div>
		</div>
		<div class="card">
			<div class="container-fluid">
				<div class="card-header d-flex flex-row justify-content-between">
					<h4 class="title"><?= (isset($title) ? $title : ''); ?></h4>
					<div>
						<div class="btn-group">
							<button class="btn btn-sm" id="btnFilter" @click="btnFilter()"><i class="fa fa-filter"></i> Filter</button>
							<button class="btn btn-sm" id="btnHideFilter" @click="btnHideFilter()" style="display: none;"><i class="fa fa-eye-slash"></i> Hide Filter</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<!-- filter -->
					<div class="row filter mt-2" id="filter" style="display: none;">
						<div class="col-4">
							<div class="form-group" id="filterCompany">
								<select class="form-control bg-transparent select2-multiple w-100 company" name="company" id="company" multiple="multiple">
									<option value="all">All</option>
									<option value="IPC">IPC</option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<fieldset class="form-group">
								<div class="" id="filterArea">
									<select class="form-control bg-transparent select2-multiple w-100 area" name="area" id="area" multiple="multiple">
										<option value="all">All</option>
										<option value="GEDUNG PARKIR">GEDUNG PARKIR</option>
										<option value="GEDUNG KAS">GEDUNG KAS</option>
										<option value="GEDUNG MAINTENANCE">GEDUNG MAINTENANCE</option>
										<option value="GEDUNG FINANCE">GEDUNG FINANCE</option>
									</select>
								</div>
							</fieldset>
						</div>
						<div class="col-4">
							<div class="form-group" id="filterUnit">
								<select class="form-control bg-transparent select2-multiple w-100 unit" name="unit" id="unit" multiple="multiple">
									<option value="all">All</option>
									<option value="CCTV">CCTV</option>
									<option value="ROUTER">ROUTER</option>
									<option value="IT">IT</option>
								</select>
							</div>
						</div>
					</div>
					<!-- datatable -->
					<div class="table-responsive">
						<table class="table table-hover w-100" id="example">
							<thead class="bg-primary">
								<tr>
									<th>#</th>
									<th>Asset</th>
									<th>Tag</th>
									<th>Location</th>
									<th>Condition</th>
								</tr>
							</thead>
							<tbody>
								<?php for ($i = 1; $i <= 15; $i++) { ?>
									<tr>
										<td><?= $i; ?></td>
										<td>Asset</td>
										<td>CCTV</td>
										<td>Gedung Mesin</td>
										<td>Normal</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->

<script>
	let v = new Vue({
		el: '#app',
		data: () => ({
			data: null
		}),
		methods: {
			btnFilter() {
				$('#btnHideFilter').show();
				$('#filter').show();
				$('#btnFilter').hide();
			},
			btnHideFilter() {
				$('#btnFilter').show();
				$('#filter').hide();
				$('#btnHideFilter').hide();
			}
		}
	})
</script>
<?= $this->endSection(); ?>