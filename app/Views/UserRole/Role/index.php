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
						<input name="dt-search" class="material-input" type="text" data-target="#tableRole" placeholder="Search Data Transaction" />
					</div>
				</div>
				<div class="d-flex justify-content-between mb-1">
					<h4><?= $title ?></h4>
					<h5 class="header-icon">
						<a href="javascript:;" class="dt-search" data-target="#tableRole"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
						<a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="javascript:;" @click="getRoleList()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
							<?php if (checkRoleList("ROLE.ADD")) { ?>
								<a class="dropdown-item" href="<?= site_url("role/detail") ?>"><i class="cil cil-lock-locked mr-2"></i> Add Role</a>
							<?php } ?>
						</div>
					</h5>
				</div>
				<!-- datatable -->
				<div class="table-responsive">
					<table class="table table-hover w-100" id="tableRole">
						<thead class="bg-primary">
							<tr>
								<th>Name</th>
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
		el: '#app',
		setup() {
			const table = Vue.ref();
			var roleData = Vue.reactive([]);

			const getData = () => {
				return new Promise(async (resolve, reject) => {
					try {
						table.value = await $('#tableRole').DataTable({
							processing: true,
							scrollY: "calc(100vh - 272px)",
							responsive: true,
							data: roleData,
							language: {
								processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
								lengthMenu: "Showing _MENU_ ",
								info: "of _MAX_ entries",
								infoEmpty: 'of 0 entries',
							},
							dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
							columns: [{
								data: "name",
							}],
							order: [0, 'asc'],
							// columnDefs: [],
							'createdRow': function(row, data) {
								<?php if (checkRoleList("ROLE.DETAIL.VIEW")) : ?>
									row.setAttribute("data-id", data.groupId);
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

			const getRoleList = () => {
				axios.get("<?= site_url("role/groupList") ?>")
					.then((res) => {
						xhrThrowRequest(res)
							.then(() => {
								roleData.splice(0);
								roleData.push(...res.data.data);

								$('#tableRole').dataTable().fnClearTable();
								if (roleData.length > 0) $('#tableRole').dataTable().fnAddData(roleData);
							})
							.catch((rej) => {
								if (rej.throw) {
									throw new Error(rej.message);
								}
							});
					});
			}

			Vue.onMounted(() => {
				getData();
				getRoleList();

				let search = $(".dt-search-input input[data-target='#tableRole']");
				search.unbind().bind("keypress", function(e) {
					if (e.which == 13 || e.keyCode == 13) {
						let searchData = search.val();
						table.value.search(searchData).draw();
					}
				});

				<?php if (checkRoleList("ROLE.DETAIL.VIEW")) : ?>
					$(document).on('click', '#tableRole tbody tr', function() {
						if($(this).attr("data-id")) window.location.href = "<?= site_url('role/detail') ?>?groupId=" + $(this).attr("data-id");
					});
				<?php endif; ?>
			});

			return {
				getRoleList
			}
		}
	}).mount("#app");
</script>
<?= $this->endSection(); ?>