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
						<div class="col-sm-6 col-lg-4">
							<div class="card text-white bg-gradient-danger">
								<div class="card-body card-body pb-0 d-flex justify-content-between align-items-start">
									<div>
										<div class="text-value-lg">9.823</div>
										<div>NOT FOLLOW UP</div>
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
									<canvas class="chart" id="card-chart1" height="70"></canvas>
								</div>
							</div>
						</div>
						<!-- /.col-->
						<div class="col-sm-6 col-lg-4">
							<div class="card text-white bg-gradient-warning">
								<div class="card-body card-body pb-0 d-flex justify-content-between align-items-start">
									<div>
										<div class="text-value-lg">9.823</div>
										<div>OPEN</div>
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
									<canvas class="chart" id="card-chart2" height="70"></canvas>
								</div>
							</div>
						</div>
						<!-- /.col-->
						<div class="col-sm-6 col-lg-4">
							<div class="card text-white bg-gradient-success">
								<div class="card-body card-body pb-0 d-flex justify-content-between align-items-start">
									<div>
										<div class="text-value-lg">9.823</div>
										<div>CLOSE</div>
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
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="container-fluid">
				<div class="card-header d-flex flex-row justify-content-between align-items-center">
					<h4 class="title">Finding</h4>
					<div>
						<div class="btn-group">
							<button class="btn btn-sm" id="btnFilter" @click="btnFilter()"><i class="fa fa-filter"></i> Filter</button>
							<button class="btn btn-sm hide" id="btnHideFilter" @click="btnHideFilter()"><i class="fa fa-eye-slash"></i> Hide Filter</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<!-- filter -->
					<div class="row filter" id="filter" style="display: none;">
						<div class="col-3">
							<div class="form-group">
								<div class="btn-group" id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%">
									<i class="fa fa-calendar"></i>&nbsp;
									<span></span> <i class="fa fa-caret-down"></i>
								</div>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group" id="filterCompany">
								<select class="form-control bg-transparent select2-multiple w-100 company" name="company" id="company" multiple="multiple">
									<option value="all">All</option>
									<option value="IPC">IPC</option>
								</select>
							</div>
						</div>
						<div class="col-3">
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
						<div class="col-3">
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
					<table class="table table-hover w-100" id="example">
						<thead class="bg-primary">
							<tr>
								<th>#</th>
								<th>Asset</th>
								<th>Tag</th>
								<th>Location</th>
								<th>Condition</th>
								<th>Finding Priority</th>
							</tr>
						</thead>
						<tbody>
							<?php for ($i = 1; $i <= 10; $i++) { ?>
								<tr>
									<td><?= $i; ?></td>
									<td>Asset Name</td>
									<td>CCTV</td>
									<td>Gedung Mesin</td>
									<td>Open</td>
									<td>High</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
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
				$('#btnFilter').hide();
				$('#btnHideFilter').show();
				$('#filter').show();
			},
			btnHideFilter() {
				$('#btnHideFilter').hide();
				$('#filter').hide();
				$('#btnFilter').show();
			}
		}
	})
</script>
<?= $this->endSection(); ?>