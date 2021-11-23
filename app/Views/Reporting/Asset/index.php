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
							<select class="form-control bg-transparent select2-multiple w-100 asset" name="asset" id="asset" multiple="multiple">
								<option value="all">All</option>
								<?php foreach ($asset as $key) : ?>
									<option value="<?= $key; ?>"><?= $key; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-4">
						<fieldset class="form-group">
							<div class="" id="filterArea">
								<select class="form-control bg-transparent select2-multiple w-100 location" name="location" id="location" multiple="multiple">
									<option value="all">All</option>
									<?php foreach ($tagLocation as $key) : ?>
										<option value="<?= $key; ?>"><?= $key; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</fieldset>
					</div>
					<div class="col-4">
						<div class="form-group" id="filterUnit">
							<select class="form-control bg-transparent select2-multiple w-100 tag" name="tag" id="tag" multiple="multiple">
								<option value="all">All</option>
								<?php foreach ($tag as $key) : ?>
									<option value="<?= $key; ?>"><?= $key; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="table-responsive w-100">
					<table class="table table-hover w-100 display" id="tableEq" @current-items="getFiltered()">
						<thead class="bg-primary">
							<tr>
								<th style="width: 30%;">Asset</th>
								<th style="width: 35%;">Tag</th>
								<th style="width: 35%;">Location</th>
							</tr>
						</thead>
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
	const {
		onMounted,
		ref,
		reactive
	} = Vue;
	let v = Vue.createApp({
		el: '#app',
		setup() {
			var myModal = ref(null);
			var table = ref(null);

			function GetData() {
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
								url: "<?= base_url('/ReportingAsset/datatable') ?>",
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
										return '<div>' + row.assetName + '<br><span class="sub-text">' + row.assetNumber + '<i class="text-lowercase"> ' + (row.schType == "" ? '' : '(' + row.schType + ')') + '</i></span></div>'
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
									targets: "_all",
									// className: "dt-head-center",
								},
								{
									targets: [1, 2],
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
			}

			onMounted(() => {
				GetData()
				let search = $(".dt-search-input input[data-target='#tableEq']");
				search.unbind().bind("keypress", function(e) {
					if (e.which == 13 || e.keyCode == 13) {
						let searchData = search.val();
						v.table.search(searchData).draw();
					}
				});

				$(document).on('click', '#tableEq tbody tr', function() {
					window.location.href = "<?= site_url('ReportingAsset/detail') ?>?assetId=" + $(this).attr("data-id");
				});
			});
			return {
				myModal,
				table,
				GetData
			}
		},
	}).mount('#app');

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

	$('#asset').on('change', function() {
		let valArea = $('#location').val() ?? '';
		let valUnit = $('#tag').val() ?? '';
		let filter = v.table.columns(1).search($(this).val());
		if (valArea != '' && valUnit != '') {
			filter.columns(2).search(valArea).columns(3).search(valUnit);
		}
		filter.draw();
	})

	$('#location').on('change', function() {
		let valCompany = $('#asset').val() ?? '';
		let valUnit = $('#tag').val() ?? '';
		var value = $(this).val();
		let filter = v.table.columns(2).search(value);
		if (valCompany != '' && valUnit != '') {
			filter.columns(1).search(valCompany).columns(3).search(valUnit);
		}
		filter.draw();
	})

	$('#tag').on('change', function() {
		let valCompany = $('#asset').val() ?? '';
		let valArea = $('#location').val();
		let filter = v.table.columns(3).search($(this).val());
		if (valCompany != '' && valArea != '') {
			filter.columns(1).search(valCompany).columns(2).search(valArea);
		}
		filter.draw();
	})

	$('#asset').select2({
		theme: 'coreui',
		placeholder: "Select Asset",
		allowClear: true
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