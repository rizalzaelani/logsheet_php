<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
	<div class="col-12">
		<div class="card card-main fixed-height">
			<div class="card-body">
				<div class="dt-search-input">
					<div class="input-container">
						<a href="javascript:void(0)" class="suffix text-decoration-none dt-search-hide"><i class="c-icon cil-x" style="font-size: 1.5rem;"></i></a>
						<input name="dt-search" class="material-input" type="text" data-target="#tableTrx" placeholder="Search Data Transaction" />
					</div>
				</div>
				<div class="d-flex justify-content-between mb-1">
					<h4><?= $title ?></h4>
					<h5 class="header-icon">
						<a href="#filterDT" onclick="return false;" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="filterDT"><i class="fa fa-filter"></i></a>
						<a href="javascript:;" onclick="table.ajax.reload();"><i class="fa fa-redo-alt"></i></a>
						<a href="javascript:;" class="dt-search" data-target="#tableTrx"><i class="fa fa-search"></i></a>
					</h5>
				</div>
				<div class="row mt-2 collapse" id="filterDT">
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
					<table class="table table-hover w-100 nowrap" id="tableTrx">
						<thead class="bg-info">
							<tr>
								<th style="width: 20px;">#</th>
								<th>Asset</th>
								<th>Tag</th>
								<th>Location</th>
								<th>Condition</th>
							</tr>
						</thead>
						<tbody>
							<?php for ($i = 1; $i <= 15; $i++) { ?>
								<tr>
									<td class="text-center"><?= $i; ?></td>
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
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->

<script>
	let v = new Vue({
		el: '#app',
		data: () => ({
			data: null
		}),
		setup() {
			const table = ref(null)
		},
		mounted() {
			this.getData();

			let search = $(".dt-search-input input[data-target='#tableTrx']");
			search.unbind().bind("keypress", function(e) {
				if (e.which == 13 || e.keyCode == 13) {
					let searchData = search.val();
					table.search(searchData).draw();
				}
			});
		},
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
			},
			getData() {
				table = $('#tableTrx').DataTable({
					scrollY: "calc(100vh - 272px)",
					dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<""i><"d-flex justify-content-end align-items-center" <"mt-2 mr-2"l>pr>>>'
				});
			}
		}
	})
</script>
<?= $this->endSection(); ?>