<?= $this->extend('Layout/main'); ?>
<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="row" id="app">
	<div class="col-12">
		<div class="card card-main fixed-height card-border-top">
			<div class="container-fluid">
				<div class="card-header">
					<div class="d-flex flex-row justify-content-between align-items-center">
						<h4 class="title"><?= (isset($subtitle) ? $subtitle : '')  ?></h4>
						<div>
							<div class="btn-group">
								<button data-toggle="tooltip" data-placement="top" title="Add Data" class="btn btndark btn-sm" @click="handleAdd()"><i class="fa fa-plus"></i> Add</button>
							</div>
							<div class="btn-group">
								<button data-toggle="tooltip" data-placement="top" title="Filter" class="btn btndark btn-sm" id="btnFilter" @click="btnFilter()"><i class="fa fa-filter"></i> Filter</button>
								<button data-toggle="tooltip" data-placement="top" title="Hide Filter" class="btn btndark btn-sm" id="btnHideFilter" @click="btnHideFilter()" style="display: none;"><i class="fa fa-eye-slash"></i> Hide Filter</button>
							</div>
							<div class="btn-group">
								<button data-toggle="tooltip" data-placement="top" title="Import / Export" class="btn btndark btn-sm" type="button"><i class="fa fa-upload"></i> Import</button>
								<button class="btn btndark btn-sm dropdown-toggle dropdown-toggle-split" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span></button>
								<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-start" style="position: absolute; transform: translate3d(71px, 34px, 0px); top: 0px; left: 0px; will-change: transform; font-size: 12px;">
									<a class="dropdown-item" href="<?= base_url('/Asset/import'); ?>"><i class="fa fa-upload"></i> Import Data</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="<?= base_url('/Asset/export'); ?>"><i class="fa fa-file-excel"></i> Export Excel</a>
									<a class="dropdown-item" href="<?= base_url('/Asset/exportCsv'); ?>"><i class="fa fa-file-csv"></i> Export CSV</a>
									<a class="dropdown-item" href="<?= base_url('/Asset/exportOds'); ?>"><i class="fa fa-file-alt"></i> Export ODS</a>
								</div>
							</div>
							<div class="btn-group">
								<a style="text-decoration: none; " href="<?= base_url('Asset/domPdf'); ?>" data-toggle="tooltip" data-placement="top" title="Print Pdf" id="print" class="btn btndark btn-sm" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i> Print</a>
							</div>
							<div class="btn-group">
								<div class="dt-search-input">
									<div class="input-container">
										<input type="text" id="myInputTextField" class="form-control form-control-sm" style="display: none; text-decoration: none;">
									</div>
								</div>
							</div>
							<div class="btn-group">
								<button data-toggle="tooltip" data-placement="top" title="Search" class="btn btndark btn-sm" id="btnSearch" type="button" @click="btnSearch()"><i class="fa fa-search"></i> Search</button>
								<button data-toggle="tooltip" data-placement="top" title="Hide Search" class="btn btndark btn-sm" id="btnHide" type="button" @click="btnHide()" style="display: none;"><i class="fa fa-eye-slash"></i> Hide</button>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<!-- filter -->
					<div class="row filter mt-2" id="filter" style="display: none;">
						<div class="col-4">
							<div class="form-group" id="filterCompany">
								<select class="form-control bg-transparent select2-multiple w-100 company" name="company" id="company" multiple="multiple">
									<option value="">All</option>
									<?php foreach ($getCompany as $list) : ?>
										<option value="<?= $list; ?>"><?= $list; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-4">
							<fieldset class="form-group">
								<div class="" id="filterArea">
									<select class="form-control bg-transparent select2-multiple w-100 area" name="area" id="area" multiple="multiple">
										<option value="">All</option>
										<?php foreach ($getArea as $list) : ?>
											<option value="<?= $list; ?>"><?= $list; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</fieldset>
						</div>
						<div class="col-4">
							<div class="form-group" id="filterUnit">
								<select class="form-control bg-transparent select2-multiple w-100 unit" name="unit" id="unit" multiple="multiple">
									<option value="">All</option>
									<?php foreach ($getUnit as $list) : ?>
										<option value="<?= $list; ?>"><?= $list; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<div class="table-responsive w-100">
						<table class="table table-hover w-100 nowrap" id="tableEq" @current-items="getFiltered()">
							<thead class="bg-primary">
								<tr>
									<th>#</th>
									<th>Asset </th>
									<th>Tag </th>
									<th>Location </th>
									<th>Number </th>
									<th>Frequency </th>
								</tr>
							</thead>
							<tbody>

								<?php
								for ($i = 1; $i <= 11; $i++) { ?>
									<tr>
										<td><?= $i; ?></td>
										<td>Asset Name</td>
										<td>CCTV</td>
										<td>Gedung Mesin</td>
										<td>0<?= $i; ?></td>
										<td>Daily</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<!-- Modal Tambah-->
					<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
						<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalScrollableTitle">Add Asset</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
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
												<label for="frequencyType">Frequency Type <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="frequencyType"></i></label>
												<input id="frequencyType" type="text" class="form-control" required>
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
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<script>
	let v = new Vue({
		el: '#app',
		data: () => ({
			myModal: null,
		}),
		mounted() {
			this.getData()
		},
		methods: {
			GetData() {
				return new Promise(async (resolve, reject) => {
					try {
						this.table = await $('#tableEq').DataTable({
							searchDelay: 20000,
							processing: true,
							serverSide: true,
							destroy: true,
							scrollY: 'calc(100vh - 272px)',
							scrollX: true,
							scrollCollapse: true,
							fixedColumns: true,
							responsive: true,
							language: {
								"processing": `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`
							},
							dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-flex justify-content-between"<""i><"d-flex justify-content-end align-items-center" <"mt-2 mr-2"l>pr>>>',
							// order: [
							// 	[3, 'desc']
							// ],
							ajax: {
								url: "<?= base_url() . '/Asset/datatable' ?>",
								type: "POST",
								data: {},
								complete: () => {
									resolve();
								}
							},
							columns: [{
									data: "company",
									name: "company",
								},
								{
									data: "area",
									name: "area",
								},
								{
									data: "unit",
									name: "unit"
								},
								{
									data: "equipment",
									name: "equipment",
								},
							],
							columnDefs: [{
								targets: "_all",
								className: "dt-head-center"
							}],
							'createdRow': function(row, data) {
								row.setAttribute("data-id", data.adminequip_id);
								row.classList.add("cursor-pointer")
							},
						});
					} catch (er) {
						console.log(er)
						reject(er);
					}
				})
			},
			getData: () => {
				$('#tableEq').DataTable({
					'scrollY': "calc(100vh - 300px)",
					'paging': true,
					'dom': 'tip'
				});
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
			//cancel on modal
			handleCancel() {
				const swalWithBootstrapButtons = swal.mixin({
					customClass: {
						confirmButton: 'btn btn-danger'
					},
					buttonsStyling: false
				})
				swalWithBootstrapButtons.fire({
						icon: 'error',
						title: 'Cancelled!',
						text: 'You cancel adding data.',
						allowOutsideClick: false
					})
					.then(res => {
						v.adminequip_id = '',
							v.company = '',
							v.area = '',
							v.unit = '',
							v.equipment = ''
					})
			},
			//show hide search
			btnSearch() {
				$('#myInputTextField').show();
				$('#btnSearch').hide();
				$('#btnHide').show();
			},
			btnHide() {
				$('#myInputTextField').hide();
				$('#btnSearch').show();
				$('#btnHide').hide();
			},
			//show hide filter
			btnFilter() {
				$('#btnFilter').hide();
				$('#filter').show();
				$('#btnHideFilter').show();
				$(".dataTables_scrollBody").css("max-height", "calc(100vh - 359px)");
			},
			btnHideFilter() {
				$('#btnFilter').show()
				$('#filter').hide();
				$('#btnHideFilter').hide();
				$(".dataTables_scrollBody").css("max-height", "calc(100vh - 300px)");
			}
		}
	});

	// Row Click
	// 

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