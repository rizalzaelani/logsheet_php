<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row">
	<div class="col-12">
		<div class="card card-main card-border-top fixed-height">
			<div class="container-fluid">
				<div class="card-header">
					<h4 class="title"><?= (isset($subtitle) ? $subtitle : ''); ?></h4>
				</div>
				<div class="card-body">
					<div class="container">
						<div class="row">
							<div class="col-12 border-light" style="border-radius: 7px;">
								<div class="row">
									<div class="col-6">
										<div class="row mt-2">
											<div class="col-5">
												<label class="col-form-label">IPC Logsheet / Plant Patrol</label>
											</div>
											<div class="col-7">
												<input type="text" class="form-control" name="company" value="IPC LOGSHEET" readonly>
											</div>
										</div>
										<div class="row mt-2">
											<div class="col-5">
												<label class="col-form-label">Report Type</label>
											</div>
											<div class="col-7">
												<!-- <input type="text" name="" class="form-control"> -->
												<select class="form-select form-control" aria-label="Default select example">
													<option selected>Open this select menu</option>
													<option value="1">One</option>
													<option value="2">Two</option>
													<option value="3">Three</option>
												</select>
											</div>
										</div>
										<div class="row mt-2">
											<div class="col-5">
												<label class="col-form-label">Date Range</label>
											</div>
											<div class="col-7">
												<div class="" id="reportrange" style="cursor: pointer; padding: 5px 10px; width: 100%">
													<i class="fa fa-calendar"></i>&nbsp;
													<span></span> <i class="fa fa-caret-down"></i>
												</div>
											</div>
										</div>
									</div>
									<div class="col-6">
										<div class="row mt-2">
											<div class="col-4">
												<label class="col-form-label">Company</label>
											</div>
											<div class="col-8">
												<!-- <input type="text" name="" class="form-control"> -->
												<select class="form-select form-control select2-single" id="company">
													<option value="">All</option>
													<option value="1">IPC</option>
												</select>
											</div>
										</div>
										<div class="row mt-2">
											<div class="col-4">
												<label class="col-form-label">Area</label>
											</div>
											<div class="col-8">
												<!-- <input type="text" name="" class="form-control"> -->
												<select class="form-select form-control select2-single" id="area">
													<option value="">All</option>
													<option value="1">GEDUNG MESIN</option>
													<option value="1">GEDUNG MAINTENANCE</option>
													<option value="1">GEDUNG KAS</option>
													<option value="1">GEDUNG FINANCE</option>
													<option value="1">GEDUNG COB</option>
													<option value="1">GEDUNG PARKIR</option>
												</select>
											</div>
										</div>
										<div class="row mt-2">
											<div class="col-4">
												<label class="col-form-label">Unit</label>
											</div>
											<div class="col-8">
												<!-- <input type="text" name="" class="form-control"> -->
												<select class="form-select form-control select2-single" id="unit">
													<option value="">All</option>
													<option value="1">ROUTER</option>
													<option value="2">CCTV</option>
													<option value="3">IT</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="my-2 mb-1">
									<a href="" class="btn btn-sm btn-primary expand-left">Get Data</a>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<a href="" class="btn btn-sm btn-success my-2"><i class="fa fa-download"></i> Export</a>
								<table class="table table-hover table-striped" cellspacing="0" id="example">
									<thead class="bg-primary">
										<tr>
											<th>#</th>
											<th>nama</th>
											<th>nama</th>
											<th>nama</th>
											<th>nama</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td>halo</td>
											<td>halo</td>
											<td>halo</td>
											<td>halo</td>
										</tr>
										<tr>
											<td>2</td>
											<td>halo</td>
											<td>halo</td>
											<td>halo</td>
											<td>halo</td>
										</tr>
										<tr>
											<td>3</td>
											<td>halo</td>
											<td>halo</td>
											<td>halo</td>
											<td>halo</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<?= $this->endSection(); ?>