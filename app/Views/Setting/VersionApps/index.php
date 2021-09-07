<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="apps">
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
					<h4><?= $title ?></h4>
					<h5 class="header-icon">
						<a href="javascript:;" class="dt-search" data-target="#tableTrx"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
						<a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="javascript:;" onclick="v.table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
						</div>
					</h5>
				</div>
				<!-- datatable -->
				<div class="row">
					<div class="col-12">
						<table class="table table-hover table-striped w-100" id="tableVersionApps">
							<thead class="bg-primary">
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Type</th>
									<th>Version</th>
									<th>By</th>
									<th>Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Name</td>
									<td>Type</td>
									<td>1.0</td>
									<td>By</td>
									<td>10-09-2021</td>
									<td>
										<button class="btn btn-sm btn-info text-white"><i class="fa fa-eye"></i></button>
										<button class="btn btn-sm btn-success"><i class="fa fa-edit"></i></button>
										<button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
									</td>
								</tr>
							</tbody>
						</table>
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
		data: {
			data: null,
			table: null,
		},
		mounted() {
			this.getData();

			let search = $(".dt-search-input input[data-target='#tableVersionApps']");
			search.unbind().bind("keypress", function(e) {
				if (e.which == 13 || e.keyCode == 13) {
					let searchData = search.val();
					table.search(searchData).draw();
				}
			});
		},
		methods: {
			getData() {
				this.table = $('#tableVersionApps').DataTable({
					scrollY: "calc(100vh - 272px)",
					language: {
						lengthMenu: "Showing _MENU_ ",
						info: "of _MAX_ entries",
						infoEmpty: 'of 0 entries',
					},
					dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>'
				});
			}
		}
	})
</script>
<?= $this->endSection(); ?>