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
					<div class="d-flex justify-content-start align-items-center">
						<h4 class="mr-2 mb-0"><?= $title ?></h4>
						<span class="badge badge-pill badge-warning p-2 mr-1"></span>
						<p class="m-0 mr-2"> Waiting Approve</p>
						<span class="badge badge-pill badge-primary p-2 mr-1"></span>
						<p class="m-0 mr-2"> Approve</p>
					</div>
					<h5 class="header-icon">
						<a href="#filterDT" onclick="return false;" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="filterDT" id="btnFiltDT"><i class="fa fa-filter" data-toggle="tooltip" title="Filter"></i></a>
						<a href="javascript:;" class="dt-search" data-target="#tableTrx"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
						<a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="javascript:;" @click="table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
						</div>
					</h5>
				</div>
				<div class="row mt-2 collapse" id="filterDT">
					<div class="col-sm-5">
						<fieldset class="form-group">
							<div class="" id="filterArea">
								<select class="form-control bg-transparent w-100" name="filtDTTag" id="filtDTTag" multiple="multiple"></select>
							</div>
						</fieldset>
					</div>
					<div class="col-sm-5">
						<div class="form-group">
							<select class="form-control bg-transparent w-100" name="filtDTLoc" id="filtDTLoc" multiple="multiple"></select>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<select class="form-control bg-transparent w-100" name="filtDTStatus" id="filtDTStatus">
								<option value="2">All</option>
								<option value="1">Approved</option>
								<option value="0">Not Approved</option>
							</select>
						</div>
					</div>
				</div>
				<!-- datatable -->
				<div class="table-responsive">
					<table class="table table-hover w-100" id="tableTrx">
						<thead class="bg-primary">
							<tr>
								<th>Scanned</th>
								<th>Asset Name</th>
								<!-- <th>Asset Number</th> -->
								<th width="27.5%">Tag</th>
								<th width="27.5%">Location</th>
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
		setup() {
			var table = Vue.ref(null);

			const getData = () => {
				return new Promise(async (resolve, reject) => {
					try {
						table.value = await $('#tableTrx').DataTable({
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
								url: "<?= base_url('/Transaction/datatable') ?>",
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
									render: function(data, type, row, meta) {
										return '<div>' + row.assetName + '<br><span class="sub-text">' + row.assetNumber + '<span class="text-lowercase"><i> ' + (row.schType == '' ? '' : '(' + row.schType + ')') + '</i></span>' + '</span></div>';
									}
								},
								// {
								// 	data: "assetNumber",
								// },
								{
									data: "tagName",
								},
								{
									data: "tagLocationName",
								}
							],
							order: [0, 'asc'],
							columnDefs: [{
									targets: 0,
									render: function(data, type, row) {
										return `<div class="d-flex align-items-center"><span class="badge badge-pill badge-${(row.approvedAt ? "primary" : "warning")} p-2 mr-2" title="${row.approvedAt ? "Approved" : "Waiting Approve"}"></span>${moment(row.scannedAt ? row.scannedAt : data).format("DD MMM YYYY HH:mm")}</div>`;
									}
								},
								{
									targets: [2, 3],
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
								<?php if (checkRoleList("TRX.DETAIL.VIEW")) : ?>
									row.setAttribute("data-id", data.scheduleTrxId);
									row.classList.add("cursor-pointer");
								<?php endif; ?>
							},
						});
					} catch (er) {
						console.log(er)
						reject(er);
					}
				})
			}

			Vue.onMounted(async () => {
				await getData();

				let search = $(".dt-search-input input[data-target='#tableTrx']");
				search.unbind().bind("keypress", function(e) {
					if (e.which == 13 || e.keyCode == 13) {
						let searchData = search.val();
						table.value.search(searchData).draw();
					}
				});

				<?php if (checkRoleList("TRX.DETAIL.VIEW")) : ?>
					$(document).on('click', '#tableTrx tbody tr', function() {
						if ($(this).attr("data-id")) window.location.href = "<?= site_url('Transaction/detail') ?>?scheduleTrxId=" + $(this).attr("data-id");
					});
				<?php endif; ?>

				$('#filtDTTag,#filtDTLoc,#filtDTStatus').on('change', function() {
					let valTag = $('#filtDTTag').val() ?? '';
					let valLoc = $('#filtDTLoc').val() ?? '';
					let valStatus = $('#filtDTStatus').val() ?? '2';

					table.value.columns(3).search(valTag).columns(4).search(valLoc).columns(0).search(valStatus).draw();
				});
			});

			return {
				table,
				getData
			};
		}
	}).mount("#app")
</script>
<?= $this->endSection(); ?>