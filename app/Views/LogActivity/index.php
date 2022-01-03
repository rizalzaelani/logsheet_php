<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<style>
	.card-main.fixed-height {
		min-height: calc(100vh - 130px) !important;
		margin-bottom: 0.75rem !important;
	}
</style>
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
	<div class="col-12">
		<div class="d-flex justify-content-end">
			<div class="mb-1" id="rangechangelog" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
				<i class=" fa fa-calendar"></i>&nbsp;
				<span></span> <i class="fa fa-caret-down"></i>
			</div>
		</div>
		<div class="card card-main fixed-height">
			<div class="card-body">
				<div class="dt-search-input">
					<div class="input-container">
						<a href="javascript:void(0)" class="suffix text-decoration-none dt-search-hide"><i class="c-icon cil-x" style="font-size: 1.5rem;"></i></a>
						<input name="dt-search" class="material-input" type="text" data-target="#tableLogActivity" placeholder="Search Data Log Activity" />
					</div>
				</div>
				<div class="d-flex justify-content-between mb-1">
					<h4><?= $title ?></h4>
					<h5 class="header-icon">
						<a href="javascript:;" class="dt-search" data-target="#tableLogActivity"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
						<a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="javascript:;" @click="table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
						</div>
					</h5>
				</div>
				<div class="table-responsive w-100">
					<table class="table display nowrap w-100" id="tableLogActivity">
						<thead class="bg-primary">
							<tr>
								<th>Date</th>
								<th>Username</th>
								<th>IP Address</th>
								<th>Activity</th>
							</tr>
						</thead>
						<tbody>
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
	const {
		ref,
		onMounted,
		reactive
	} = Vue;
	let v = Vue.createApp({
		setup() {
			var myModal = ref(null);
			var table = ref(null);
			var logActivity = reactive([]);
			const start = moment().subtract(6, 'days');
			const end = moment();

			var table = ref(null);

			const getData = () => {
				return new Promise(async (resolve, reject) => {
					try {
						table.value = await $('#tableLogActivity').DataTable({
							processing: true,
							serverSide: true,
							scrollY: "calc(100vh - 322px)",
							responsive: true,
							language: {
								processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
								lengthMenu: "Showing _MENU_ ",
								info: "of _MAX_ entries",
								infoEmpty: 'of 0 entries',
							},
							dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
							ajax: {
								url: "<?= base_url('/LogActivity/getDatatable') ?>" + '/' + moment(start).format("YYYY-MM-DD") + '/' + moment(end).format("YYYY-MM-DD"),
								type: "POST",
								data: {},
								error: function(jqXHR, textStatus, errorThrown) {
									if (jqXHR.status == 401) {
										Swal.fire({
											title: "Session is Timeout, Please Login Again",
											icon: "warning",
											onAfterClose: function() {
												window.location.reload();
											}
										});
									} else {
										Swal.fire({
											title: "Error While Processing Data, Please Try Again",
											text: jqXHR.statusText,
											icon: "error",
										});
									}
								},
								complete: () => {
									resolve();
								}
							},
							columns: [{
									data: 'time',
									name: 'time',
									render: function(data, type, row, meta) {
										return moment(data).format("DD MMM YYYY hh:mm:ss");
									}
								},
								{
									data: 'username',
									name: 'username'
								},
								{
									data: 'ip',
									name: 'ip'
								},
								{
									data: 'activity',
									name: 'activity'
								},
							],
							order: [0, 'desc']
						});
					} catch (er) {
						console.log(er)
						reject(er);
					}
				})
			};

			// const getData = () => {
			// 	table.value = $('#tableLogActivity').DataTable({
			// 		scrollY: "calc(100vh - 272px)",
			// 		responsive: true,
			// 		dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
			// 		processing: true,
			// 		language: {
			// 			processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
			// 			lengthMenu: "Showing _MENU_ ",
			// 			info: "of _MAX_ entries",
			// 			infoEmpty: 'of 0 entries',
			// 		},
			// 		data: logActivity,
			// 		columns: [{
			// 				data: 'time',
			// 				name: 'time',
			// 				// render: function(data, type, row, meta) {
			// 				// 	return moment(data).format("DD MMM YYYY hh:mm:ss");
			// 				// }
			// 			},
			// 			{
			// 				data: 'username',
			// 				name: 'username'
			// 			},
			// 			{
			// 				data: 'ip',
			// 				name: 'ip'
			// 			},
			// 			{
			// 				data: 'activity',
			// 				name: 'activity'
			// 			},
			// 		],
			// 		order: [0, 'desc'],
			// 		columnDefs: [
			// 			{
			// 				targets: 0,
			// 				render: function (data, type, row, meta) {
			// 					return moment(data).format("DD MMM YYYY hh:mm:ss");
			// 				}
			// 			}
			// 		]
			// 	});
			// }

			const getLogActivity = () => {
				axios.get("<?= site_url("LogActivity/getLogActivity") ?>")
					.then((res) => {
						xhrThrowRequest(res)
							.then(() => {
								logActivity.splice(0);
								logActivity.push(...res.data.data);

								$('#tableLogActivity').dataTable().fnClearTable();
								if (logActivity.length > 0) $('#tableLogActivity').dataTable().fnAddData(logActivity);
							})
							.catch((rej) => {
								if (rej.throw) {
									throw new Error(rej.message);
								}
							});
					});
			}

			const cb = (start, end) => {
				$('#rangechangelog span').html(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
				$('#rangechangelog').on('apply.daterangepicker', function(ev, picker) {
					v.start = picker.startDate.format("YYYY-MM-DD hh:mm:ss")
					v.end = picker.endDate.format("YYYY-MM-DD hh:mm:ss")
					let urlDataTable = "<?= site_url('LogActivity/getDatatable') ?>" + '/' + moment(v.start).format("YYYY-MM-DD") + '/' + moment(v.end).format("YYYY-MM-DD");
					v.table.ajax.url(urlDataTable).load(); //reload datatable ajax
				})
			}

			onMounted(() => {
				getData();
				getLogActivity();
				let search = $(".dt-search-input input[data-target='#tableLogActivity']");
				search.unbind().bind("keypress", function(e) {
					if (e.which == 13 || e.keyCode == 13) {
						let searchData = search.val();
						table.value.search(searchData).draw();
					}
				});

				$('#rangechangelog').daterangepicker({
					startDate: start,
					endDate: end,
					ranges: {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Last 7 Days': [moment().subtract(6, 'days'), moment()],
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					}
				}, cb);
				cb(start, end);
			});
			return {
				table,
				logActivity,
				getLogActivity,
				table,
				start,
				end
			}
		},
	}).mount('#app');
</script>
<?= $this->endSection(); ?>