<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<style>
	table>thead>tr>th {
		vertical-align: middle !important;
		text-align: left !important;
	}

	table>tbody>tr>td {
		vertical-align: middle !important;
		text-align: left !important;
	}

	/* .dataTables_scrollHead {
		width: 100% !important;
	}

	.dataTables_scrollHeadInner {
		width: 100% !important;
	} */

	/* .dataTable {
		width: 100% !important;
	} */
</style>
<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="row" id="app">
	<?php $session = \Config\Services::session();
	$sess =  $session->get('adminId'); ?>
	<div class="col-12">
		<div class="card card-main card-border-top fixed-height">
			<div class="card-body m-0">
				<div class="w-100 pb-4 px-0 d-flex justify-content-between">
					<div>
						<h4 class="title"><?= (isset($subtitle) ? $subtitle : ''); ?></h4>
					</div>
				</div>
				<div class="row">
					<div class="container">
						<div class="col-12 m-0 border-light" style="border-radius: 7px;">
							<div class="py-2">
								<div class="row">
									<div class="col-6">
										<div class="row mt-2">
											<div class="col-5">
												<label class="col-form-label">Report Type</label>
											</div>
											<div class="col-7">
												<select class="form-select form-control" id="reportType" aria-label="Report Type">
													<option value="" selected disabled>Select Report Type</option>
													<option value="transaction">Transaction</option>
													<option value="finding">Finding</option>
													<option value="schedule">Schedule</option>
													<option value="daily" data-icon="lock-locked" class="d-flex align-items-center" disabled>Daily Report</option>
												</select>
											</div>
										</div>
										<div class="row mt-2">
											<div class="col-5">
												Date Range
											</div>
											<div class="col-7">
												<div class="p-0" id="reportrange" style="cursor: pointer; padding: 5px 10px; width: 100%">
													<i class="fa fa-calendar"></i>&nbsp;
													<span></span> <i class="fa fa-caret-down"></i>
												</div>
											</div>
										</div>
									</div>
									<div class="col-6">
										<div class="row mt-2">
											<div class="col-4">
												<label class="col-form-label">Tag</label>
											</div>
											<div class="col-8">
												<select class="form-select form-control" multiple id="tag">
													<option v-for="(item, i) in tag" :key="item" :value="item.tagId">{{ item.tagName }}</option>
												</select>
											</div>
										</div>
										<div class="row mt-2">
											<div class="col-4">
												<label class="col-form-label">Tag Location</label>
											</div>
											<div class="col-8">
												<select class="form-select form-control" multiple id="tagLocation">
													<option v-for="(item, i) in tagLocation" :value="item.tagLocationId">{{ item.tagLocationName }}</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="py-4">
							<button class="btn btn-sm btn-primary mr-1" @click="loadData()"><i class="fa fa-sync-alt"></i> Load Data</button>
							<button class="btn btn-sm btn-success" @click="download()"><i class="fa fa-download"></i> Download Data</button>
						</div>
						<div class="table-responsive">
							<table class="table table-hover display nowrap hide" id="dtTransaction">
								<thead class="bg-primary">
									<tr>
										<th>Asset</th>
										<th>Asset Number</th>
										<th>Tag</th>
										<th>Tag Location</th>
										<th>Parameter</th>
										<th>Normal</th>
										<th>Abnormal</th>
										<th>Uom</th>
										<th>Value</th>
										<th>Scanned At</th>
									</tr>
								</thead>
							</table>
						</div>
						<div class="table-responsive">
							<table class="table table-hover display nowrap hide" id="dtFinding">
								<thead class="bg-primary">
									<tr>
										<th>Asset</th>
										<th>Asset Number</th>
										<th>Tag</th>
										<th>Tag Location</th>
										<th>Parameter</th>
										<th>Normal</th>
										<th>Abnormal</th>
										<th>Uom</th>
										<th>Value</th>
										<th>Condition</th>
										<th>Opened At</th>
										<th>Scanned At</th>
									</tr>
								</thead>
							</table>
						</div>
						<div class="table-responsive">
							<table class="table table-hover display nowrap hide" id="dtSchedule">
								<thead class="bg-primary">
									<tr>
										<th>Asset</th>
										<th>Asset Number</th>
										<th>Tag</th>
										<th>Tag Location</th>
										<th>Schedule</th>
										<th>ScheduleFrom</th>
										<th>ScheduleTo</th>
										<th>Sync At</th>
										<th>Scanned At</th>
										<th>Scanned End</th>
										<th>Scanned By</th>
										<th>Scanned With</th>
										<th>Scanned Notes</th>
										<th>Scanned Accuration</th>
										<th>Approved At</th>
										<th>Approved By</th>
										<th>Approved Notes</th>
										<th>Condition</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<!-- table import -->
				<div>
					<table class="table table-bordered d-none" id="DTTransaction">
						<thead>
							<tr>
								<th>Asset</th>
								<th>Asset Number</th>
								<th>Tag</th>
								<th>Tag Location</th>
								<th>Parameter</th>
								<th>Normal</th>
								<th>Abnormal</th>
								<th>Uom</th>
								<th>Value</th>
								<th>Scanned At</th>
							</tr>
						</thead>
						<tbody>
							<tr v-if="dataTransaction != ''" v-for="(item, i) in dataTransaction">
								<td>{{ item.assetName }}</td>
								<td>{{ item.assetNumber }}</td>
								<td>{{ item.tagName }}</td>
								<td>{{ item.tagLocationName }}</td>
								<td>{{ item.parameterName }}</td>
								<td v-if="item.inputType == 'select'">{{ item.normal }}</td>
								<td v-else-if="item.inputType == 'input'">{{ item.min + ' - ' + item.max }}</td>
								<td v-else></td>
								<td v-if="item.inputType == 'select'">{{ item.abnormal }}</td>
								<td v-else-if="item.inputType == 'input'">{{ 'x < ' + item.min + '; x > ' + item.max }}</td>
								<td v-else></td>
								<td>{{ item.uom }}</td>
								<td>{{ item.value }}</td>
								<td>{{ item.scannedAt }}</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-bordered d-none" id="DTFinding">
						<thead>
							<tr>
								<th>Asset</th>
								<th>Asset Number</th>
								<th>Tag</th>
								<th>Tag Location</th>
								<th>Parameter</th>
								<th>Normal</th>
								<th>Abnormal</th>
								<th>Uom</th>
								<th>Value</th>
								<th>Condition</th>
								<th>Opened At</th>
								<th>Scanned At</th>
							</tr>
						</thead>
						<tbody>
							<tr v-if="dataFinding != ''" v-for="(item, i) in dataFinding">
								<td>{{ item.assetName }}</td>
								<td>{{ item.assetNumber }}</td>
								<td>{{ item.tagName }}</td>
								<td>{{ item.tagLocationName }}</td>
								<td>{{ item.parameterName }}</td>
								<td v-if="item.inputType == 'select'">{{ item.normal }}</td>
								<td v-else-if="item.inputType == 'input'">{{ item.min + ' - ' + item.max }}</td>
								<td v-else></td>
								<td v-if="item.inputType == 'select'">{{ item.abnormal }}</td>
								<td v-else-if="item.inputType == 'input'">{{ 'x < ' + item.min + '; x > ' + item.max }}</td>
								<td v-else></td>
								<td>{{ item.uom }}</td>
								<td>{{ item.value }}</td>
								<td>{{ item.condition }}</td>
								<td>{{ item.openedAt }}</td>
								<td>{{ item.scannedAt }}</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-bordered d-none" id="DTSchedule">
						<thead>
							<tr>
								<th>Asset</th>
								<th>Asset Number</th>
								<th>Tag</th>
								<th>Tag Location</th>
								<th>Schedule</th>
								<th>ScheduleFrom</th>
								<th>ScheduleTo</th>
								<th>Sync At</th>
								<th>Scanned At</th>
								<th>Scanned End</th>
								<th>Scanned By</th>
								<th>Scanned With</th>
								<th>Scanned Notes</th>
								<th>Scanned Accuration</th>
								<th>Approved At</th>
								<th>Approved By</th>
								<th>Approved Notes</th>
								<th>Condition</th>
							</tr>
						</thead>
						<tbody>
							<tr v-if="dataSchedule != ''" v-for="(item, i) in dataSchedule">
								<td>{{ item.assetName }}</td>
								<td>{{ item.assetNumber }}</td>
								<td>{{ item.tagName }}</td>
								<td>{{ item.tagLocationName }}</td>
								<td v-if="item.schManual == '1'">
									Manual
								</td>
								<td v-else-if="item.schManual == '0' && item.schType === 'Daily'">
									{{ item.schType }}<br>
									<span class="text-muted">{{ item.schFrequency }}</span>
								</td>
								<td v-else-if="item.schManual == '0' && item.schType === 'Weekly'">
									{{ item.schType }}<br>
									<span class="text-muted">({{ item.schWeekDays }})</span>
								</td>
								<td v-else-if='item.schManual == "0" && (item.schType === "Monthly" && item.schDays != "")'>
									{{ item.schType }}<br>
									<span class="text-muted">({{ item.schDays }})</span>
								</td>
								<td v-else-if='item.schManual == "0" && item.schType === "Monthly" && item.schDays == ""'>
									{{ item.schType }}<br>
									<span class="text-muted">({{ item.schWeeks }})</span><br>
									<span class="text-muted">({{ item.schWeekDays }})</span>
								</td>
								<td v-else>
								</td>
								<td>{{ item.scheduleFrom }}</td>
								<td>{{ item.scheduleTo }}</td>
								<td>{{ item.syncAt }}</td>
								<td>{{ item.scannedAt }}</td>
								<td>{{ item.scannedEnd }}</td>
								<td>{{ item.scannedBy }}</td>
								<td>{{ item.scannedWith }}</td>
								<td>{{ item.scannedNotes }}</td>
								<td>{{ item.scannedAccuration }}</td>
								<td>{{ item.approvedAt }}</td>
								<td>{{ item.approveedBy }}</td>
								<td>{{ item.approvedNotes }}</td>
								<td>{{ item.condition }}</td>
							</tr>
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
		reactive,
		onMounted
	} = Vue;
	let v = Vue.createApp({
		el: '#app',
		setup() {
			const start = moment().subtract(6, 'days');
			const end = moment();
			var userId = ref('<?= $sess ?>');
			var reportType = ref("");
			var tagId = ref("");
			var tagLocationId = ref("");
			var tag = <?= json_encode($tag) ?>;
			var tagLocation = <?= json_encode($tagLocation) ?>;

			var table = ref("");

			var dataTransaction = ref("");
			var dataFinding = ref("");
			var dataSchedule = ref("");

			function loadData() {
				if (this.reportType == 'transaction') {
					if (!($('#dtTransaction').hasClass('hide'))) {
						$('#dtTransaction').addClass('hide');
					}
					if (!($('#dtFinding').hasClass('hide'))) {
						$('#dtFinding').addClass('hide');
					}
					if (!($('#dtSchedule').hasClass('hide'))) {
						$('#dtSchedule').addClass('hide');
					}
					if (this.table != "") {
						this.table.clear().destroy();
						if (this.dataFinding != "") {
							this.dataFinding = ref("");
						}
						if (this.dataSchedule != "") {
							this.dataSchedule = ref("");
						}
					}
					let formdata = new FormData();
					formdata.append('userId', this.userId);
					formdata.append('tagId', this.tagId);
					formdata.append('tagLocationId', this.tagLocationId);
					formdata.append('search', "");
					formdata.append('start', moment(v.start).format("YYYY-MM-DD"));
					formdata.append('end', moment(v.end).format("YYYY-MM-DD"));
					axios({
						url: '<?= base_url('/Report/transaction/') ?>',
						data: formdata,
						method: 'POST'
					}).then((res) => {
						$('#dtTransaction').removeClass('hide');
						let rsp = res.data;
						this.dataTransaction = rsp;
						if (this.dataTransaction.length < 1) {
							return swal.fire({
								icon: 'error',
								title: "There's No Data"
							})
						}
						v.table = $('#dtTransaction').DataTable({
							dom: "t<'mt-2 d-flex justify-content-between align-items-center' <i><p>>",
							ordering: false,
							scrollX: true,
							data: v.dataTransaction,
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
									data: "parameterName",
									name: "parameterName",
								},
								{
									data: "normal",
									name: "normal",
									render: function(data, type, row, meta) {
										if (row.inputType === 'select') {
											return data
										} else if (row.inputType === 'input') {
											return row.min + ' - ' + row.max
										} else {
											return ''
										}
									}
								},
								{
									data: "abnormal",
									name: "abnormal",
									render: function(data, type, row, meta) {
										if (row.inputType === 'select') {
											return data
										} else if (row.inputType === 'input') {
											return 'x < ' + row.min + '; x > ' + row.max
										} else {
											return ''
										}
									}
								},
								{
									data: "uom",
									name: "uom",
								},
								{
									data: "value",
									name: "value",
								},
								{
									data: "scannedAt",
									name: "scannedAt",
								},
							]
						})
					})
				} else if (this.reportType == 'finding') {
					if (!($('#dtTransaction').hasClass('hide'))) {
						$('#dtTransaction').addClass('hide');
					}
					if (!($('#dtFinding').hasClass('hide'))) {
						$('#dtFinding').addClass('hide');
					}
					if (!($('#dtSchedule').hasClass('hide'))) {
						$('#dtSchedule').addClass('hide');
					}

					if (this.table != "") {
						this.table.clear().destroy();
						if (this.dataTransaction != "") {
							this.dataTransaction = ref("");
						}
						if (this.dataSchedule != "") {
							this.dataSchedule = ref("");
						}
					}

					let formdata = new FormData();
					formdata.append('userId', this.userId);
					formdata.append('tagId', this.tagId);
					formdata.append('tagLocationId', this.tagLocationId);
					formdata.append('search', "");
					formdata.append('start', moment(v.start).format("YYYY-MM-DD"));
					formdata.append('end', moment(v.end).format("YYYY-MM-DD"));
					axios({
						url: '<?= base_url('/Report/finding/') ?>',
						data: formdata,
						method: 'POST'
					}).then((res) => {
						$('#dtFinding').removeClass('hide');
						let rsp = res.data;
						this.dataFinding = rsp;
						if (this.dataFinding.length < 1) {
							return swal.fire({
								icon: 'error',
								title: "There's No Data"
							})
						}
						v.table = $('#dtFinding').DataTable({
							dom: "t<'mt-2 d-flex justify-content-between align-items-center' <i><p>>",
							ordering: false,
							scrollX: true,
							data: v.dataFinding,
							columns: [{
									data: "assetName",
									name: "assetName"
								},
								{
									data: "assetNumber",
									name: "assetNumber"
								},
								{
									data: "tagName",
									name: "tagName"
								},
								{
									data: "tagLocationName",
									name: "tagLocationName"
								},
								{
									data: "parameterName",
									name: "parameterName"
								},
								{
									data: "normal",
									name: "normal",
									render: function(data, type, row, meta) {
										if (row.inputType === 'select') {
											return data
										} else if (row.inputType === 'input') {
											return row.min + ' - ' + row.max
										} else {
											return ''
										}
									}
								},
								{
									data: "abnormal",
									name: "abnormal",
									render: function(data, type, row, meta) {
										if (row.inputType === 'select') {
											return data
										} else if (row.inputType === 'input') {
											return 'x < ' + row.min + '; x > ' + row.max
										} else {
											return ''
										}
									}
								},
								{
									data: "uom",
									name: "uom"
								},
								{
									data: "value",
									name: "value"
								},
								{
									data: "condition",
									name: "condition"
								},
								{
									data: "openedAt",
									name: "openedAt"
								},
								{
									data: "scannedAt",
									name: "scannedAt"
								},
							]
						})
					})
				} else if (this.reportType == 'schedule') {
					if (!($('#dtTransaction').hasClass('hide'))) {
						$('#dtTransaction').addClass('hide');
					}
					if (!($('#dtFinding').hasClass('hide'))) {
						$('#dtFinding').addClass('hide');
					}
					if (!($('#dtSchedule').hasClass('hide'))) {
						$('#dtSchedule').addClass('hide');
					}

					if (this.table != "") {
						this.table.clear().destroy();
						if (this.dataTransaction != "") {
							this.dataTransaction = ref("");
						}
						if (this.dataFinding != "") {
							this.dataFinding = ref("");
						}
					}

					let formdata = new FormData();
					formdata.append('userId', this.userId);
					formdata.append('tagId', this.tagId);
					formdata.append('tagLocationId', this.tagLocationId);
					formdata.append('search', "");
					formdata.append('start', moment(v.start).format("YYYY-MM-DD"));
					formdata.append('end', moment(v.end).format("YYYY-MM-DD"));

					axios({
						url: '<?= base_url('/Report/schedule/') ?>',
						data: formdata,
						method: 'POST'
					}).then((res) => {
						$('#dtSchedule').removeClass('hide');
						let rsp = res.data;
						this.dataSchedule = rsp;
						if (this.dataSchedule.length < 1) {
							return swal.fire({
								icon: 'error',
								title: "There's No Data"
							})
						}
						v.table = $('#dtSchedule').DataTable({
							dom: "t<'mt-2 d-flex justify-content-between align-items-center' <i><p>>",
							scrollX: true,
							ordering: false,
							data: v.dataSchedule,
							columns: [{
									data: "assetName",
									name: "assetName"
								},
								{
									data: "assetNumber",
									name: "assetNumber"
								},
								{
									data: "tagName",
									name: "tagName"
								},
								{
									data: "tagLocationName",
									name: "tagLocationName"
								},
								{
									data: "schType",
									name: "schType",
									render: function(data, type, row, meta) {
										if (row.schManual == '1') {
											return 'Manual'
										} else {
											if (data === 'Daily') {
												return '<div>' + data + '<br><span class="text-muted">(' + row.schFrequency + ')</span></div>'
											} else if (data === 'Weekly') {
												return '<div>' + data + '<br><span class="text-muted">(' + row.schWeekDays + ')</span></div>'
											} else if (data === 'Monthly') {
												if (row.schDays === "") {
													return '<div>' + data + '<br><span class="text-muted">(' + row.schWeeks + ')</span><br><span class="text-muted">(' + row.schWeekDays + ')</span></div>'
												}
												return '<div>' + data + '<br><span class="text-muted">(' + row.schDays + ')</span></div>'
											} else {
												return 'none'
											}
										}
									}
								},
								{
									data: "scheduleFrom",
									name: "scheduleFrom"
								},
								{
									data: "scheduleTo",
									name: "scheduleTo"
								},
								{
									data: "syncAt",
									name: "syncAt"
								},
								{
									data: "scannedAt",
									name: "scannedAt"
								},
								{
									data: "scannedEnd",
									name: "scannedEnd"
								},
								{
									data: "scannedBy",
									name: "scannedBy"
								},
								{
									data: "scannedWith",
									name: "scannedWith"
								},
								{
									data: "scannedNotes",
									name: "scannedNotes"
								},
								{
									data: "scannedAccuration",
									name: "scannedAccuration"
								},
								{
									data: "approvedAt",
									name: "approvedAt"
								},
								{
									data: "approvedBy",
									name: "approvedBy"
								},
								{
									data: "approvedNotes",
									name: "approvedNotes"
								},
								{
									data: "condition",
									name: "condition"
								},
							],
						})
					})
				}
			}

			function download() {
				if (this.reportType == 'transaction' && this.dataTransaction != "" && this.dataTransaction.length) {
					tableToExcel('DTTransaction', 'Report Transaction ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
				} else if (this.reportType == 'finding' && this.dataFinding != "" && this.dataFinding.length) {
					tableToExcel('DTFinding', 'Report Finding ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
				} else if (this.reportType == 'schedule' && this.dataSchedule.length) {
					tableToExcel('DTSchedule', 'Report Schedule ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
				}
			}

			const cb = (start, end) => {
				$('#reportrange span').html(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
				$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
					v.start = picker.startDate.format("DD MMM YYYY")
					v.end = picker.endDate.format("DD MMM YYYY")
				})
			}
			onMounted(() => {
				$('#reportType').on('change', function() {
					let val = $(this).val();
					v.reportType = val;
				})

				$('#tag').on('change', function() {
					let val = $(this).val().toString();
					v.tagId = val;
				})

				$('#tagLocation').on('change', function() {
					let val = $(this).val().toString();
					v.tagLocationId = val;
				})

				$('#reportType').select2({
					theme: 'coreui',
					placeholder: 'Select Report Type',
					templateSelection: iFormat,
					templateResult: iFormat,
					allowHtml: true,
				})

				function iFormat(icon) {
					var originalOption = icon.element;
					return $('<span>' + icon.text + '<i class="ml-1 cis-' + $(originalOption).data('icon') + '"></i></span>');
				}

				$('#tag').select2({
					theme: 'coreui',
					placeholder: 'Select Tag'
				})
				$('#tagLocation').select2({
					theme: 'coreui',
					placeholder: 'Select Tag Location'
				})
				$('#reportrange').daterangepicker({
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
			})
			return {
				start,
				end,
				tagId,
				tagLocationId,
				tag,
				tagLocation,
				reportType,
				userId,

				table,

				loadData,
				dataTransaction,
				dataFinding,
				dataSchedule,
				tableToExcel,
				download
			}
		}
	}).mount('#app');
</script>
<?= $this->endSection(); ?>