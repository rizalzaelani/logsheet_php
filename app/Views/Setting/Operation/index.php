<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
	<div class="col-12">
		<div class="card card-main card-border-top fixed-height">
			<div class="container-fluid">
				<div class="card-header d-flex flex-row justify-content-between">
					<h4 class="title"><?= (isset($subtitle) ? $subtitle : ''); ?></h4>
					<div>
						<div class="btn-group">
							<button class="btn btn-sm" id="btnFilter" @click="btnFilter()"><i class="fa fa-filter"></i> Filter</button>
							<button class="btn btn-sm hide" id="btnHideFilter" @click="btnHideFilter()"><i class="fa fa-eye-slash"></i> Hide Filter</button>
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
					<div class="row">
						<div class="col-12">
							<table class="table table-hover table-striped" id="tblop">
								<thead class="bg-primary">
									<tr>
										<th></th>
										<th>Company</th>
										<th>Area</th>
										<th>Equipment</th>
										<th>Qrcode</th>
										<th>Status</th>
										<th>Last Value</th>
									</tr>
								</thead>
							</table>
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