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
								<th>Scanned</th>
								<th>Asset Name</th>
								<th>Asset Number</th>
								<th width="27.5%">Tag</th>
								<th width="27.5%">Location</th>
								<th>Condition</th>
							</tr>
						</thead>
						<tbody></tbody>
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
	let v = Vue.createApp({
		el: '#app',
		setup() {
			const table = null;

			const getData = () => {
				return new Promise(async (resolve, reject) => {
					try {
						this.table = await $('#tableFinding').DataTable({
							drawCallback: function(settings) {
								$(document).ready(function() {
									$('[data-toggle="tooltip"]').tooltip();
								})
							},
							processing: true,
							serverSide: true,
							scrollY: "calc(100vh - 272px)",
							responsive: true,
							language: {
								processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
								lengthMenu: "Showing _MENU_ ",
								info: "of _MAX_ entries",
								infoEmpty: 'of 0 entries',
							},
							dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
							ajax: {
								url: "<?= base_url('/Finding/datatable') ?>",
								type: "POST",
								data: {},
								complete: () => {
									resolve();
								}
							},
							columns: [{
									data: "scheduleFrom",
								},
								{
									data: "assetName",
								},
								{
									data: "assetNumber",
								},
								{
									data: "tagName",
								},
								{
									data: "tagLocationName",
								},
								{
									data: "condition",
								},
							],
							order: [0, 'asc'],
							columnDefs: [
								{
									targets: [3, 4],
									render: function(data) {
										if (data != '-') {
											// unique = Array.from(new Set(data));
											var dt = Array.from(new Set(data.split(',')));
											var list_dt = '';
											$.each(dt, function(key, value) {
												list_dt += '<span class="badge badge-dark p-1 mr-1 mb-1 badge-size">' + value + '</span>';
											});
											return '<div style="max-height: 56px !important; overflow-y: scroll;">' + list_dt + '</div>';
										} else {
											return data;
										}
									}
								}
							],
							'createdRow': function(row, data) {
								<?php if (checkRoleList("FINDING.DETAIL.LIST.VIEW")) : ?>
									row.setAttribute("data-id", data.scheduleTrxId);
									row.classList.add("cursor-pointer");
									// row.setAttribute("data-toggle", "tooltip");
									// row.setAttribute("data-html", "true");
									// row.setAttribute("title", "<div>Click to go to asset detail</div>");
								<?php endif; ?>
							},
						});
					} catch (er) {
						console.log(er)
						reject(er);
					}
				})
			}

			Vue.onMounted(() => {
				getData();

				let search = $(".dt-search-input input[data-target='#tableFinding']");
				search.unbind().bind("keypress", function(e) {
					if (e.which == 13 || e.keyCode == 13) {
						let searchData = search.val();
						table.search(searchData).draw();
					}
				});

				<?php if (checkRoleList("FINDING.DETAIL.LIST.VIEW")) : ?>
					$(document).on('click', '#tableFinding tbody tr', function() {
						window.location.href = "<?= site_url('Finding/detailList') ?>?scheduleTrxId=" + $(this).attr("data-id");
					});
				<?php endif; ?>
			});

			return {
				table,
				getData
			}
		}
	}).mount("#app");
</script>
<?= $this->endSection(); ?>