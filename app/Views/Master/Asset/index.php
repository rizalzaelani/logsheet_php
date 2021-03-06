<?= $this->extend('Layout/main'); ?>
<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<style>
	.select2-container .select2-selection--multiple {
		max-height: 50px !important;
	}
</style>
<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="row" id="app">
	<div class="col-12">
		<div class="card card-main fixed-height">
			<div class="card-body">
				<div class="dt-search-input">
					<div class="input-container">
						<a href="javascript:void(0)" class="suffix text-decoration-none dt-search-hide"><i class="c-icon cil-x" style="font-size: 1.5rem;"></i></a>
						<input name="dt-search" class="material-input" type="text" data-target="#tableEq" placeholder="Search Data Asset" />
					</div>
				</div>
				<div class="d-flex justify-content-between mb-1">
					<h4><?= $title ?></h4>
					<h5 class="header-icon">
						<a href="#filterDT" onclick="return false;" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="filterDT" id="filter"><i class="fa fa-filter" data-toggle="tooltip" title="Filter"></i></a>
						<!-- <a href="javascript:;" onclick="table.ajax.reload();"><i class="fa fa-redo-alt" data-toggle="tooltip" title="Refresh"></i></a> -->
						<a href="javascript:;" class="dt-search" data-target="#tableEq"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
						<a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
						<div class="dropdown-menu">
							<?php
							if (!checkLimitAsset()) { ?>
								<a class="dropdown-item disabled" @click="add()" style="cursor: pointer !important;"><i class="fa fa-plus mr-2"></i> Add Asset
									<div class="ml-2 d-flex justify-content-end">
										<i class="cil-lock-locked"></i>
									</div>
								</a>
							<?php } else { ?>
								<a class="dropdown-item" @click="add()" style="cursor: pointer !important;"><i class="fa fa-plus mr-2"></i> Add Asset</a>
							<?php } ?>
							<?php
							if (!checkLimitAsset()) { ?>
								<a class="dropdown-item disabled" @click="importData()" style="cursor: pointer !important;"><i class="fa fa-upload mr-2"></i> Import data
									<div class="ml-2 d-flex justify-content-end">
										<i class="cil-lock-locked"></i>
									</div>
								</a>
								<!-- <a class="dropdown-item" href="<?= base_url('/Asset/import'); ?>"><i class="fa fa-upload mr-2"></i> Import Data</a> -->
							<?php } else { ?>
								<a class="dropdown-item" href="<?= base_url('/Asset/import'); ?>"><i class="fa fa-upload mr-2"></i> Import Data</a>
							<?php } ?>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?= base_url('/Asset/export'); ?>" target="_blank"><i class="fa fa-file-excel mr-2"></i> Export Data</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="javascript:;" @click="table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
						</div>
					</h5>
				</div>
				<div class="row mt-2 collapse" id="filterDT">
					<div class="col-6">
						<div class="form-group">
							<select class="form-control bg-transparent w-100" name="location" id="filtDTLoc" multiple="multiple"></select>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<select class="form-control bg-transparent w-100" name="tag" id="filtDTTag" multiple="multiple"></select>
						</div>
					</div>
				</div>
				<div class="table-responsive w-100">
					<table class="table table-hover w-100 display" id="tableEq" @current-items="getFiltered()">
						<thead class="bg-primary">
							<tr>
								<th style="width: 30%;">Asset</th>
								<th style="width: 35%">Tag</th>
								<th style="width: 35%">Location</th>
							</tr>
						</thead>
					</table>
				</div>

				<!-- Modal Tambah-->
				<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalScrollableTitle">Add Asset</h5>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<form action="">
										<div class="mb-3">
											<label for="assetTag">Tag <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="assetTag"></i></label>
											<div class="form-group">
												<select class="form-control bg-transparent select2-multiple w-100 tag" name="tag" id="tag" multiple="multiple">
													<option value="all">All</option>
													<option value="Test 1">Test 1</option>
													<option value="Test 2">Test 2</option>
												</select>
											</div>
										</div>
										<div class="mb-3">
											<label for="assetLocation">Location <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="assetLocation"></i></label>
											<div class="form-group">
												<select class="form-control bg-transparent select2-multiple w-100 location" name="location" id="location" multiple="multiple">
													<option value="all">All</option>
													<option value="Test 1">Test 1</option>
													<option value="Test 2">Test 2</option>
												</select>
											</div>
										</div>
										<div class="mb-3">
											<label for="assetStatus">Status <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="assetStatus"></i></label>
											<input id="assetStatus" type="text" class="form-control" required>
										</div>
										<div class="mb-3">
											<label for="assetName">Name <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="assetName"></i></label>
											<input id="assetName" type="text" class="form-control" required>
										</div>
										<div class="mb-3">
											<label for="assetNumber">Number <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="assetNumber"></i></label>
											<input id="assetNumber" type="text" class="form-control" required>
										</div>
										<div class="mb-3">
											<label for="description">Description <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="description"></i></label>
											<input id="description" type="text" class="form-control" required>
										</div>
										<div class="mb-3">
											<label for="schType">Frequency Type <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="schType"></i></label>
											<input id="schType" type="text" class="form-control" required>
										</div>
										<div class="mb-3">
											<label for="frequency">Frequency <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="frequency"></i></label>
											<input id="frequency" type="text" class="form-control" required>
										</div>
									</form>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary">Save changes</button>
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
<script>
	const {
		ref
	} = Vue;
	let v = Vue.createApp({
		el: '#app',
		setup() {
			var myModal = Vue.ref(null);
			var table = Vue.ref(null);

			const getData = () => {
				return new Promise(async (resolve, reject) => {
					try {
						table.value = await $('#tableEq').DataTable({
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
								url: "<?= base_url('/Asset/datatable') ?>",
								type: "POST",
								data: {},
								complete: () => {
									resolve();
								}
							},
							columns: [{
									data: "assetName",
									name: "assetName",
									render: function(data, type, row, meta) {
										return '<div>' + row.assetName + '<br><span class="sub-text">' + row.assetNumber + '<span class="text-lowercase"><i> ' + (row.schType == '' ? '' : '(' + row.schType + ')') + '</i></span>' + '</span></div>';
									}
								},
								{
									data: "tagName",
									name: "tagName",
								},
								{
									data: "tagLocationName",
									name: "tagLocationName",
								},
							],
							order: [0, 'asc'],
							columnDefs: [{
								targets: [1, 2],
								width: '27.5%',
								render: function(data) {
									if (data != '-') {
										// unique = Array.from(new Set(data));
										var dt = Array.from(new Set(data.split(',')));
										var list_dt = '';
										$.each(dt, function(key, value) {
											list_dt += '<span class="badge badge-dark mr-1 mb-1 badge-size">' + value + '</span>';
										})
										return '<div style="max-height: 56px !important; overflow-y: scroll;">' + list_dt + '</div>';
									} else {
										return data;
									}
								}
							}],
							'createdRow': function(row, data) {
								row.setAttribute("data-id", data.assetId);
								row.classList.add("cursor-pointer");
								row.setAttribute("data-toggle", "tooltip");
								row.setAttribute("data-html", "true");
								row.setAttribute("title", "<div>Click to go to asset detail</div>");
							},
						});
					} catch (er) {
						console.log(er)
						reject(er);
					}
				})
			};

			const handleAdd = () => {
				this.myModal = new coreui.Modal(document.getElementById('exampleModalScrollable'), {});
				this.myModal.show();
			};

			const add = () => {
				<?php
				if (!checkLimitAsset()) { ?>
					return swal.fire({
						icon: 'info',
						title: "Your assets has reached the limit"
					});
				<?php }; ?>
				window.location.href = "<?= base_url('Asset/add') ?>";
			};

			const importData = () => {
				<?php
				if (!checkLimitAsset()) { ?>
					return swal.fire({
						icon: 'info',
						title: "Your assets has reached the limit"
					});
				<?php }; ?>
				window.location.href = "<?= base_url('Asset/import') ?>";
			}
			Vue.onMounted(() => {
				getData()

				let search = $(".dt-search-input input[data-target='#tableEq']");
				search.unbind().bind("keypress", function(e) {
					if (e.which == 13 || e.keyCode == 13) {
						let searchData = search.val();
						table.value.search(searchData).draw();
					}
				});

				$(document).on('click', '#tableEq tbody tr', function() {
					if ($(this).attr("data-id")) window.location.href = "<?= site_url('Asset/detail') ?>/" + $(this).attr("data-id");
				});

				$('#filtDTTag,#filtDTLoc').on('change', function() {
					let valTag = $('#filtDTTag').val() ?? '';
					let valLoc = $('#filtDTLoc').val() ?? '';

					table.value.columns(1).search(valTag).columns(2).search(valLoc).draw();
				});
			});

			return {
				myModal,
				table,
				getData,
				handleAdd,
				add,
				importData
			}
		},
	}).mount('#app');
</script>
<?= $this->endSection(); ?>