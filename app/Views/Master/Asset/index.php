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
							<a class="dropdown-item" href="<?= base_url('/Asset/add'); ?>"><i class="fa fa-plus mr-2"></i> Add Asset</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?= base_url('/Asset/import'); ?>"><i class="fa fa-upload mr-2"></i> Import Data</a>
							<a class="dropdown-item" href="<?= base_url('/Asset/export'); ?>"><i class="fa fa-file-excel mr-2"></i> Export Data</a>
							<div class="dropdown-divider"></div>
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
				<div class="table-responsive w-100">
					<table class="table table-hover w-100 display" id="tableEq" @current-items="getFiltered()">
						<thead class="bg-primary">
							<tr>
								<th>Asset</th>
								<th>Number</th>
								<th>Tag</th>
								<th>Location</th>
								<th>Description</th>
								<th>Frequency</th>
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
	let v = new Vue({
		el: '#app',
		data: () => ({
			myModal: null,
			table: null
		}),
		mounted() {
			this.GetData()

			let search = $(".dt-search-input input[data-target='#tableEq']");
			search.unbind().bind("keypress", function(e) {
				if (e.which == 13 || e.keyCode == 13) {
					let searchData = search.val();
					v.table.search(searchData).draw();
				}
			});

			$(document).on('click', '#tableEq tbody tr', function() {
				window.location.href = "<?= site_url('Asset/detail') ?>/" + $(this).attr("data-id");
			});
		},
		methods: {
			GetData() {
				return new Promise(async (resolve, reject) => {
					try {
						this.table = await $('#tableEq').DataTable({
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
								},
								{
									data: "assetNumber",
									name: "assetNumber",
								},
								{
									data: "tagName",
									name: "tagName",
								},
								{
									data: "tagLocationName",
									name: "tagLocationName",
								},
								{
									data: "description",
									name: "description"
								},
								{
									data: "schType",
									name: "schType",
								},
							],
							order: [0, 'asc'],
							columnDefs: [{
									targets: "_all",
									className: "dt-head-center",
								},
								{
									targets: [2, 3],
									render: function(data) {
										if (data != '-') {
											// unique = Array.from(new Set(data));
											var dt = Array.from(new Set(data.split(',')));
											var list_dt = '';
											$.each(dt, function(key, value) {
												list_dt += '<span class="badge badge-dark mr-1 mb-1" style="font-size: 13px; padding: 5px !important;">' + value + '</span>';
											})
											return list_dt;
										} else {
											return data;
										}
									}
								}
							],
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
			},
			handleAdd() {
				this.myModal = new coreui.Modal(document.getElementById('exampleModalScrollable'), {});
				this.myModal.show();
			},
			add() {
				if (this.company != null && this.area != null && this.unit != null && this.equipment != null) {
					axios.post("<?= base_url('Asset/add'); ?>", {
							adminequip_id: this.adminequip_id,
							company: this.company,
							area: this.area,
							unit: this.unit,
							equipment: this.equipment,
						})
						// .then(response => (this.info = response))
						.then(res => {
							if (res.data.status == 'success') {
								const swalWithBootstrapButtons = Swal.mixin({
									customClass: {
										confirmButton: 'btn btn-success',
									},
									buttonsStyling: false
								})
								swalWithBootstrapButtons.fire({
										title: 'Success!',
										text: "Success add data.",
										icon: 'success',
										allowOutsideClick: false
									})
									.then(okay => {
										if (okay) {
											// loading('show');
											swal.fire({
												title: 'Please Wait!',
												text: 'Reloading page..',
												onOpen: function() {
													swal.showLoading()
												}
											})
											location.reload();
										}
									})
							}
						})
				} else {
					const swalWithBootstrapButtons = swal.mixin({
						customClass: {
							confirmButton: 'btn btn-danger'
						},
						buttonsStyling: false
					})

					swalWithBootstrapButtons.fire({
						icon: 'error',
						title: 'Failed!',
						text: 'Field cannot be empty.',
						allowOutsideClick: false
					})
				}
			},
		}
	});

	$('#filter').click(function() {
		let filt = document.querySelector('#filter');
		let contain = (filt.classList.contains('collapsed'));
		if (!(contain)) {
			$(".dataTables_scrollBody").css("max-height", "calc(100vh - 349px)");
		} else if (contain) {
			$(".dataTables_scrollBody").css("max-height", "calc(100vh - 272px)");
		}
		// let select = document.querySelector('.select2-container ');
	})

	$('#tag').select2({
		theme: 'coreui',
		placeholder: "Select Tag",
		allowClear: true
	})
	$('#location').select2({
		theme: 'coreui',
		placeholder: "Select Location",
		allowClear: true
	})
</script>
<?= $this->endSection(); ?>