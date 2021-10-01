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
					<h4><?= $title ?></h4>
					<h5 class="header-icon">
						<a href="javascript:;" class="dt-search" data-target="#tableTrx"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
						<a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
						<div class="dropdown-menu">
							<a class="dropdown-item " href="javascript:;" @click="newRelease()"><i class="fa fa-plus mr-2"></i> New Release</a>
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
									<th>Released At</th>
									<th>Name</th>
									<th>Type</th>
									<th>Version</th>
									<th>By</th>
									<th width="25%">Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>10-09-2021</td>
									<td>Name</td>
									<td>Type</td>
									<td>1.0</td>
									<td>By</td>
									<td>
										<button class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i> Detail</button>
										<button class="btn btn-sm btn-outline-success"><i class="fa fa-edit"></i> Edit</button>
										<button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i> Delete</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal Tambah-->
	<div class="modal fade" id="modalRelease" tabindex="-1" role="dialog" aria-labelledby="modalReleaseTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalReleaseTitle">New Release</h5>
					<!-- <h5 style="display: none;" class="modal-title" id="editTagTitle">Edit Tag</h5> -->
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<form action="">
							<div class="mb-3">
								<label for="applicationName">Application Name <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Application Name"></i></label>
								<input id="applicationName" type="text" class="form-control" required v-model="applicationName" placeholder="Application Name">
							</div>
							<div class="mb-3">
								<label for="version">Version <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Version"></i></label>
								<textarea id="version" type="text" rows="5" class="form-control" required v-model="version" placeholder="Version"></textarea>
							</div>
							<div class="mb-3">
								<label for="type">Type <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Type"></i></label>
								<select name="type" id="type" class="form-control">
									<option value="">Select Type</option>
									<option value="Mobile">Mobile Application</option>
								</select>
							</div>
						</form>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
					<button type="button" class="btn btn-success" @click="add()" id="btnAdd"><i class="fa fa-plus"></i> Add Tag</button>
					<button style="display: none;" type="button" class="btn btn-success" @click="update()" id="btnEdit"><i class="fa fa-check"></i> Save Changes</button>
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
		ref
	} = Vue;
	let v = Vue.createApp({
		el: '#app',
		setup() {
			var table = ref('');
			var myModal = ref('');

			onMounted(() => {
				getData();

				let search = $(".dt-search-input input[data-target='#tableVersionApps']");
				search.unbind().bind("keypress", function(e) {
					if (e.which == 13 || e.keyCode == 13) {
						let searchData = search.val();
						table.search(searchData).draw();
					}
				});
			});

			function getData() {
				this.table = $('#tableVersionApps').DataTable({
					scrollY: "calc(100vh - 272px)",
					language: {
						lengthMenu: "Showing _MENU_ ",
						info: "of _MAX_ entries",
						infoEmpty: 'of 0 entries',
					},
					dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>'
				});
			};

			function newRelease() {
				this.myModal = new coreui.Modal(document.getElementById('modalRelease'));
				this.myModal.show();
			};
			return {
				table,
				myModal,
				getData,
				newRelease
			}
		},
	}).mount('#app');
</script>
<?= $this->endSection(); ?>