<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div id="app">
	<div class="row mb-4">
		<div class="col-12 d-flex justify-content-between align-items-center">
			<div class="title">
				<h3 class="mb-0 text-uppercase"><?= $title; ?></h3>
			</div>
			<div class="d-flex justify-content-center align-items-center">
				<div class="mr-1" id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
					<i class=" fa fa-calendar"></i>&nbsp;
					<span></span> <i class="fa fa-caret-down"></i>
				</div>
				<!-- <div class="filter bg-white d-flex justify-content-center align-items-center" role="button" data-toggle="dropdown" data-display="static" style="padding: 0.3rem 0.75rem !important; box-shadow: 0 !important;border: 1px solid rgb(204, 204, 204) !important;">
					<h5 class="mb-0">
						<i class="fa fa-filter"></i>
					</h5>
				</div>
				<div class="dropdown-menu dropdown-menu-lg-right" style="width: 500px !important;">
					<form class="p-3">
						<div class="row">
							<div class="col-6">
								<div class="form-group mr-2">
									<label for="tag">Tag</label>
									<select name="tag" id="tag" class="form-control" multiple="multiple">
										<option value="tag1">Tag 1</option>
										<option value="tag1">Tag 1</option>
										<option value="tag1">Tag 1</option>
										<option value="tag1">Tag 1</option>
										<option value="tag1">Tag 1</option>
									</select>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
									<label for="tagLocation">Tag Location</label>
									<select name="tagLocation" id="tagLocation" class="form-control" multiple="multiple">
										<option value="loc1">Tag Location</option>
										<option value="loc1">Tag Location</option>
										<option value="loc1">Tag Location</option>
										<option value="loc1">Tag Location</option>
										<option value="loc1">Tag Location</option>
									</select>
								</div>
							</div>
						</div>
					</form>
				</div> -->
			</div>
		</div>
	</div>
	<div class="row mb-4">
		<div class="col-4">
			<div class="card h-100 card-asset">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center mt-2">
						<p class="m-0" style="height: 2rem; width: 2rem; font-size: 1.5rem"><?= $totalAsset ?></p>
						<div>
							<svg class="c-icon mr-4" style="width: 2rem; height: 2rem; font-size: 2rem">
								<use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-list-rich') ?>"></use>
							</svg>
						</div>
					</div>
					<div>
						<h5 class="py-1 mb-0">Asset</h5>
					</div>
				</div>
				<div class="card-footer" style="padding: 0.5rem">
					<span>
						Available <?= $availableAsset; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="col-4">
			<div class="card h-100 card-tag">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center mt-2">
						<p class="m-0" style="height: 2rem; width: 2rem; font-size: 1.5rem"><?= $totalTag ?></p>
						<div>
							<svg class="c-icon mr-4" style="width: 2rem; height: 2rem; font-size: 2rem">
								<use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-tags') ?>"></use>
							</svg>
						</div>
					</div>
					<div>
						<h5 class="py-1 mb-0">Tag</h5>
					</div>
				</div>
				<div class="card-footer" style="padding: 0.5rem">
					<span>
						Available <?= $availableTag; ?>
					</span>
				</div>
			</div>
		</div>
		<div class="col-4">
			<div class="card h-100 card-tag-location">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center  mt-2">
						<p class="m-0" style="height: 2rem; width: 2rem; font-size: 1.5rem"><?= $totalLocation ?></p>
						<div>
							<svg class="c-icon mr-4" style="width: 2rem; height: 2rem; font-size: 2rem">
								<use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-location-pin') ?>"></use>
							</svg>
						</div>
					</div>
					<div>
						<h5 class="py-1  mb-0">Tag Location</h5>
					</div>
				</div>
				<div class="card-footer" style="padding: 0.5rem">
					<span>
						Available <?= $availableTagLocation; ?>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="row mb-4">
		<div class="col-6">
			<div class="card card-main h-100">
				<div class="card-body">
					<div id="findingChart" style="height: 300px; width: 100%;"></div>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="card card-main h-100">
				<div class="card-body">
					<div id="approveChart" style="height: 300px; width: 100%;"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row d-none">
		<div class="col-12">
			<div class="card card-main">
				<div class="card-body">
					<div id="accuration" style="height: 400px; width: 100%;"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="card">
				<div class="card-body">
					<div id="map" style="height: 300px; width: 100%;"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->

<script type="text/javascript">
	const {
		ref
	} = Vue;
	let v = Vue.createApp({
		el: '#app',
		setup() {
			var normal = ref(<?= $normal ?>);
			var finding = ref(<?= $finding ?>);
			var open = ref(<?= $open ?>);
			var closed = ref(<?= $closed ?>);
			var approveNull = ref(<?= $approveNull ?>);
			var approveNotNull = ref(<?= $approveNotNull ?>);

			return {
				normal,
				finding,
				open,
				closed,
				approveNull,
				approveNotNull
			}
		},
	}).mount('#app');

	$(document).ready(function() {

		var app = {};

		var findingDom = document.getElementById('findingChart');
		var findingChart = echarts.init(findingDom);
		var findingOpt;

		const posList = [
			'left',
			'right',
			'top',
			'bottom',
			'inside',
			'insideTop',
			'insideLeft',
			'insideRight',
			'insideBottom',
			'insideTopLeft',
			'insideTopRight',
			'insideBottomLeft',
			'insideBottomRight'
		];
		app.configParameters = {
			rotate: {
				min: -90,
				max: 90
			},
			align: {
				options: {
					left: 'left',
					center: 'center',
					right: 'right'
				}
			},
			verticalAlign: {
				options: {
					top: 'top',
					middle: 'middle',
					bottom: 'bottom'
				}
			},
			position: {
				options: posList.reduce(function(map, pos) {
					map[pos] = pos;
					return map;
				}, {})
			},
			distance: {
				min: 0,
				max: 50
			}
		};
		app.config = {
			rotate: 90,
			align: 'left',
			verticalAlign: 'middle',
			position: 'insideBottom',
			distance: 15,
			onChange: function() {
				const labelOption = {
					rotate: app.config.rotate,
					align: app.config.align,
					verticalAlign: app.config.verticalAlign,
					position: app.config.position,
					distance: app.config.distance
				};
				myChart.setOption({
					series: [{
							label: labelOption
						},
						{
							label: labelOption
						},
						{
							label: labelOption
						},
						{
							label: labelOption
						}
					]
				});
			}
		};
		const labelOption = {
			// show: true,
			position: app.config.position,
			distance: app.config.distance,
			align: app.config.align,
			verticalAlign: app.config.verticalAlign,
			rotate: app.config.rotate,
			formatter: '{c}  {name|{a}}',
			fontSize: 16,
			rich: {
				name: {}
			}
		};
		findingOpt = {
			color: [
				'#91cc75',
				'#ee6666',
				'#5470c6',
				'#fac858'
			],
			title: {
				text: 'Finding',
				left: 'center'
			},
			tooltip: {
				trigger: 'axis',
				axisPointer: {
					type: 'shadow'
				}
			},
			legend: {
				data: ['Normal', 'Finding', 'Open', 'Close'],
				top: 'bottom'
			},
			dataset: {
				source: [
					['product', 'Normal', 'Finding', 'Open', 'Close'],
					['20', v.normal, v.finding, v.open, v.closed],
				]
			},
			xAxis: [{
				type: 'category',
				axisTick: {
					show: false
				},
			}],
			yAxis: {},
			series: [{
				type: 'bar'
			}, {
				type: 'bar'
			}, {
				type: 'bar'
			}, {
				type: 'bar'
			}]
		};

		findingOpt && findingChart.setOption(findingOpt);
	})

	$(document).ready(function() {

		var approveDom = document.getElementById('approveChart');
		var approveChart = echarts.init(approveDom);
		var approveOpt;

		approveOpt = {
			color: [
				'#91cc75',
				'#ee6666',
			],
			title: {
				text: 'Approve & Waiting',
				left: 'center'
			},
			tooltip: {
				trigger: 'item'
			},
			legend: {
				top: 'bottom'
			},
			series: [{
				name: 'Schedule',
				type: 'pie',
				radius: ['30%', '70%'],
				avoidLabelOverlap: false,
				itemStyle: {
					borderRadius: 15,
					borderColor: '#fff',
					borderWidth: 2
				},
				label: {
					show: false,
					position: 'center'
				},
				emphasis: {
					label: {
						show: true,
						fontSize: '10',
						fontWeight: 'bold'
					}
				},
				labelLine: {
					show: false
				},
				data: [{
						value: v.approveNotNull,
						name: 'Approve'
					},
					{
						value: v.approveNull,
						name: 'Waiting'
					},
				]
			}]
		};

		approveOpt && approveChart.setOption(approveOpt);

	})

	$(document).ready(function() {

		var accuDom = document.getElementById('accuration');
		var accuChart = echarts.init(accuDom);
		var accuOpt;

		accuOpt = {
			title: {
				text: 'Scanning Accuration'
			},
			tooltip: {
				trigger: 'axis'
			},
			legend: {
				data: ['Email', 'Union Ads', 'Video Ads', 'Direct', 'Search Engine']
			},
			grid: {
				left: '3%',
				right: '4%',
				bottom: '3%',
				containLabel: true
			},
			toolbox: {
				feature: {
					saveAsImage: {}
				}
			},
			xAxis: {
				type: 'category',
				boundaryGap: false,
				data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
			},
			yAxis: {
				type: 'value'
			},
			series: [{
					name: 'Email',
					type: 'line',
					smooth: true,
					data: [120, 132, 101, 134, 90, 230, 210]
				},
				{
					name: 'Union Ads',
					type: 'line',
					smooth: true,
					data: [220, 182, 191, 234, 290, 330, 310]
				},
				{
					name: 'Video Ads',
					type: 'line',
					smooth: true,
					data: [150, 232, 201, 154, 190, 330, 410]
				},
				{
					name: 'Direct',
					type: 'line',
					smooth: true,
					data: [320, 332, 301, 334, 390, 330, 320]
				},
				{
					name: 'Search Engine',
					type: 'line',
					smooth: true,
					data: [820, 932, 901, 934, 1290, 1330, 1320]
				}
			]
		};

		accuOpt && accuChart.setOption(accuOpt);

	})

	$(document).ready(function() {
		mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';
		const map = new mapboxgl.Map({
			container: 'map', // container ID
			style: 'mapbox://styles/mapbox/streets-v11', // style URL
			center: [109.005913, -7.727989], // starting position [lng, lat]
			zoom: 14, // starting zoom
		});
		map.addControl(new mapboxgl.FullscreenControl());
		map.resize();
		const marker = new mapboxgl.Marker()
			.setLngLat([109.005913, -7.727989])
			.addTo(map);
	})

	// $(document).ready(function() {
	// 	window.onresize = function() {
	// 		findingChart.resize();
	// 	}
	// })

	// daterangepicker
	$(function() {
		var start = moment().subtract(29, 'days');
		var end = moment();

		function cb(start, end) {
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		}

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

	});

	//filter
	$(document).ready(function() {
		$('#tag').select2({
			theme: 'coreui',
			placeholder: 'Select Tag'
		})

		$('#tagLocation').select2({
			theme: 'coreui',
			placeholder: 'Select Tag Location'
		})
	})
</script>
<?= $this->endSection(); ?>