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
						<input name="dt-search" class="material-input" type="text" data-target="#tableFinding" placeholder="Search Data Transaction" />
					</div>
				</div>
				<div class="d-flex justify-content-between mb-1">
					<h4><?= $title ?></h4>
					<h5 class="header-icon">
						<a href="#filterDT" onclick="return false;" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="filterDT"><i class="fa fa-filter" data-toggle="tooltip" title="Filter"></i></a>
						<a href="javascript:;" class="dt-search" data-target="#tableFinding"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
						<a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="javascript:;" onclick="v.table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
						</div>
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
					<table class="table table-hover w-100" id="tableFinding">
						<thead class="bg-primary">
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
			data: null,
			table: null
		}),
		mounted() {
			this.getData();

			let search = $(".dt-search-input input[data-target='#tableFinding']");
			search.unbind().bind("keypress", function(e) {
				if (e.which == 13 || e.keyCode == 13) {
					let searchData = search.val();
					table.search(searchData).draw();
				}
			});

			$(document).on('click', '#tableFinding tbody tr', function() {
				window.location.href = "<?= site_url('Finding/detailList') ?>?trxId=" + $(this).attr("data-id");
			});
		},
		methods: {
			getData() {
				table = $('#tableFinding').DataTable({
					scrollY: "calc(100vh - 272px)",
					language: {
						lengthMenu: "Showing _MENU_ ",
						info: "of _MAX_ entries",
						infoEmpty: 'of 0 entries',
					},
					dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
					'createdRow': function(row, data, dataIndex) {
						$(row).attr('data-id', "1");
						$(row).addClass('cursor-pointer');
					},
				});
			}
		}
	})
</script>
<?= $this->endSection(); ?>