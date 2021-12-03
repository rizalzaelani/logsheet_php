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
						<input name="dt-search" class="material-input" type="text" data-target="#tableUser" placeholder="Search Data Transaction" />
					</div>
				</div>
				<div class="d-flex justify-content-between mb-1">
					<h4><?= $title ?></h4>
					<h5 class="header-icon">
						<a href="#filterDT" onclick="return false;" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="filterDT"><i class="fa fa-filter" data-toggle="tooltip" title="Filter"></i></a>
						<a href="javascript:;" class="dt-search" data-target="#tableUser"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
						<a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="javascript:;" @click="getUserList()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
							<a class="dropdown-item" href="javascript:;" @click="showUserModal()"><i class="fa fa-user-plus mr-2"></i> Add User</a>
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
				<!-- datatable -->
				<div class="table-responsive">
					<table class="table table-hover w-100" id="tableUser">
						<thead class="bg-primary">
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Role</th>
								<th>Tag</th>
								<th>Location</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" role="dialog" id="userModal" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">User Form</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="form-group mb-0 mt-3">
						<label for="name">Name</label>
						<input class="form-control" type="text" name="name" v-model="userForm.name" @keyup="userFormErr.name = (userForm.name ? '' : 'Name is required')">
					</div>
					<span class="invalid-feedback-password" :class="userFormErr.name ? '' : 'd-none'">
						<svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
							<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
						</svg>
						{{ userFormErr.name }}
					</span>
					<div class="form-group mb-0 mt-3">
						<label for="email">Email</label>
						<input class="form-control" type="email" name="email" v-model="userForm.email" @keyup="userFormErr.email = (userForm.email ? '' : 'Email is required')" placeholder="user@example.com">
					</div>
					<span class="invalid-feedback-password" :class="userFormErr.email ? '' : 'd-none'">
						<svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
							<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
						</svg>
						{{ userFormErr.email }}
					</span>
					<div class="form-group mb-0 mt-3">
						<label for="name">Role</label>
						<select class="form-control" name="groupId" v-model="userForm.groupId" @change="userFormErr.groupId = (userForm.groupId ? '' : 'Please select the role')">
							<?php foreach ($groupData as $row) : ?>
								<option value="<?= $row->groupId ?>"><?= $row->name ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<span class="invalid-feedback-password" :class="userFormErr.groupId ? '' : 'd-none'">
						<svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
							<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
						</svg>
						{{ userFormErr.groupId }}
					</span>
					<div class="form-group mb-0 mt-3">
						<label for="name">Tag</label>
						<select class="form-control bg-transparent w-100" name="tag" multiple="multiple">
							<option v-for="(val) in tagData" :value="val.tagId" :checked="(userForm.tagId ?? []).includes(val.tagId)">{{val.tagName}}</option>
						</select>
					</div>
					<div class="form-group mb-0 mt-3">
						<label for="name">Location</label>
						<select class="form-control bg-transparent w-100" name="tagLocation" multiple="multiple">
							<option v-for="(val) in tagLocData" :value="val.tagLocationId" :checked="(userForm.tagLocationId ?? []).includes(val.tagLocationId)">{{val.tagLocationName}}</option>
						</select>
					</div>
					<div class="row">
						<div class="form-group mb-0 mt-3 col-sm-6 mb-0 mt-3">
							<label for="password">Password</label>
							<input class="form-control" id="password" v-model="userForm.password" @keyup="userFormErr.password = (userForm.password ? '' : 'Enter your password')" type="password" placeholder="Enter your Password">
						</div>
						<div class="form-group mb-0 mt-3 col-sm-6 mb-0 mt-3">
							<label for="confirmPass">Confirm</label>
							<input class="form-control" id="confirmPass" v-model="userForm.confirmPass" type="password" placeholder="Confirm your passsword">
						</div>
					</div>
					<span class="invalid-feedback-password" :class="userFormErr.password ? '' : 'd-none'">
						<svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
							<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
						</svg>
						{{ userFormErr.password }}
					</span>
				</div>
				<div class="modal-footer d-flex" :class="userForm.userId ? 'justify-content-between' : ''">
					<button type="button" class="btn btn-danger" v-if="userForm.userId" @click="deleteUser(userForm.userId)"><i class="fa fa-trash"></i> Delete</button>
					<div>
						<button type="button" class="btn btn-outline-dark ml-2" data-dismiss="modal" id="cancel"><i class=" fa fa-times"></i> Cancel</button>
						<button type="button" class="btn btn-success ml-2 " @click="saveUser()"><i class="fa fa-save"></i> Save</button>
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
	let v = Vue.createApp({
		el: '#app',
		setup() {
			const table = null;
			const tagData = <?= json_encode($tagData) ?>;
			const tagLocData = <?= json_encode($tagLocationData) ?>;
			var userData = Vue.reactive([]);
			var userForm = Vue.reactive({});
			var userFormErr = Vue.reactive({});

			const getData = () => {
				return new Promise(async (resolve, reject) => {
					try {
						this.table = await $('#tableUser').DataTable({
							processing: true,
							scrollY: "calc(100vh - 272px)",
							responsive: true,
							data: userData,
							language: {
								processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
								lengthMenu: "Showing _MENU_ ",
								info: "of _MAX_ entries",
								infoEmpty: 'of 0 entries',
							},
							dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
							columns: [{
									data: "name",
								},
								{
									data: "email",
								},
								{
									data: "groupName",
								},
								{
									data: "tagName",
								},
								{
									data: "tagLocationName",
								}
							],
							order: [0, 'asc'],
							columnDefs: [{
								targets: [3, 4],
								// width: '27.5%',
								className: "text-center",
								render: function(data, meta, row) {
									if (data != '-') {
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
								<?php //if (checkRoleList("FINDING.DETAIL.LIST.VIEW")) : 
								?>
								row.setAttribute("data-id", data.userId);
								row.classList.add("cursor-pointer");
								<?php //endif; 
								?>
							},
						});
					} catch (er) {
						console.log(er)
						reject(er);
					}
				})
			}

			const getUserList = () => {
				axios.get("<?= site_url("user/userList") ?>")
					.then((res) => {
						xhrThrowRequest(res)
							.then(() => {
								let dataUser = res.data.data;

								dataUser.map((val) => {
									let param = JSON.parse(IsJsonString(val.parameters) ? val.parameters : "{}");
									val.tagId = param?.tag ?? "";
									val.tagLocationId = param?.tagLocation ?? "";

									let tagName = [];
									val.tagId.split(",").forEach((vt) => {
										let filtVT = _.filter(tagData, (vtf) => vtf.tagId == vt);
										if (filtVT.length > 0) {
											tagName.push(filtVT[0].tagName);
										}
									})
									val.tagName = tagName.length > 0 ? tagName.join(",") : "ALL";

									let tagLocationName = [];
									val.tagLocationId.split(",").forEach((vtl) => {
										let filtVT = _.filter(tagLocData, (vtf) => vtf.tagLocationId == vtl);
										if (filtVT.length > 0) {
											tagLocationName.push(filtVT[0].tagLocationName);
										}
									})
									val.tagLocationName = tagLocationName.length > 0 ? tagLocationName.join(",") : "ALL";

									return val;
								});

								userData.splice(0);
								userData.push(...dataUser);

								$('#tableUser').dataTable().fnClearTable();
								if (userData.length > 0) $('#tableUser').dataTable().fnAddData(userData);
							})
							.catch((rej) => {
								if (rej.throw) {
									throw new Error(rej.message);
								}
							});
					});
			}

			const showUserModal = () => {
				if (userForm.userId) {
					userForm.userId = "";
					userForm.name = "";
					userForm.email = "";
					userForm.groupId = "";
					userForm.password = "";
					userForm.confirmPass = "";
					userForm.tagId = [];
					userForm.tagLocationId = [];

					userFormErr.name = null;
					userFormErr.email = null;
					userFormErr.groupId = null;
					userFormErr.password = null;
				}

				$("#userModal").modal("show");
			}

			const detailUser = (userId) => {
				let filtUser = _.filter(userData, (val) => val.userId == userId);
				if (filtUser.length > 0) {
					userForm.userId = filtUser[0].userId;
					userForm.name = filtUser[0].name;
					userForm.email = filtUser[0].email;
					userForm.groupId = filtUser[0].groupId;
					userForm.tagId = (filtUser[0].tagId ?? "").split(",");
					userForm.tagLocationId = (filtUser[0].tagLocationId ?? "").split(",");

					$("select[name=tag]").val(userForm.tagId).trigger("change");
					$("select[name=tagLocation]").val(userForm.tagLocationId).trigger("change");

					userFormErr.name = null;
					userFormErr.email = null;
					userFormErr.groupId = null;
					userFormErr.password = null;

					$("#userModal").modal("show");
				}
			}

			const saveUser = () => {
				if (userForm.name && userForm.email && userForm.groupId && ((userForm.password && userForm.confirmPass) || userForm.userId)) {
					if (userForm.password != userForm.confirmPass) {
						userFormErr.password = 'Those passwords didn’t match. Try again.';
					} else {
						let res = axios.post("<?= site_url("user/saveUser") ?>", {
							userId: userForm.userId ?? "",
							name: userForm.name,
							email: userForm.email,
							password: userForm.password,
							groupId: userForm.groupId,
							tagId: (userForm.tagId ?? "").join(","),
							tagLocationId: (userForm.tagLocationId ?? "").join(",")
						}).then(res => {
							xhrThrowRequest(res)
								.then(() => {
									Toast.fire({
										title: 'Success Save User!',
										icon: 'success'
									});
									$("#userModal").modal("hide");

									userForm.userId = "";
									userForm.name = "";
									userForm.email = "";
									userForm.groupId = "";
									userForm.password = "";
									userForm.confirmPass = "";
									userForm.tagId = [];
									userForm.tagLocationId = [];

									$("select[name=tag]").val([]).trigger("change");
									$("select[name=tagLocation]").val([]).trigger("change");

									userFormErr.name = null;
									userFormErr.email = null;
									userFormErr.groupId = null;
									userFormErr.password = null;

									getUserList();
								})
								.catch((rej) => {
									if (rej.throw) {
										throw new Error(rej.message);
									}
								});
						})
					}
				} else {
					if (!userForm.name) userFormErr.name = "Name is required";
					if (!userForm.email) userFormErr.email = "Email is required";
					if (!userForm.groupId) userFormErr.groupId = "Please select the role";
					if (!userForm.password) userFormErr.password = "Enter a password";
				}
			}

			const deleteUser = (userId) => {
				Swal.fire({
					title: 'Are you sure?',
					text: "You won't be able to revert this!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.isConfirmed) {
						let res = axios.post("<?= site_url("user/deleteUser") ?>", {
							userId: userForm.userId ?? ""
						}).then(res => {
							xhrThrowRequest(res)
								.then(() => {
									Toast.fire({
										title: 'Success Delete User!',
										icon: 'success'
									});
									$("#userModal").modal("hide");

									userForm.userId = "";
									userForm.name = "";
									userForm.email = "";
									userForm.groupId = "";
									userForm.password = "";
									userForm.confirmPass = "";
									userForm.tagId = [];
									userForm.tagLocationId = [];

									$("select[name=tag]").val([]).trigger("change");
									$("select[name=tagLocation]").val([]).trigger("change");

									userFormErr.name = null;
									userFormErr.email = null;
									userFormErr.groupId = null;
									userFormErr.password = null;

									getUserList();
								})
								.catch((rej) => {
									if (rej.throw) {
										throw new Error(rej.message);
									}
								});
						})
					}
				})
			}

			Vue.onMounted(() => {
				getData();
				getUserList();

				let search = $(".dt-search-input input[data-target='#tableUser']");
				search.unbind().bind("keypress", function(e) {
					if (e.which == 13 || e.keyCode == 13) {
						let searchData = search.val();
						table.search(searchData).draw();
					}
				});

				<?php //if (checkRoleList("FINDING.DETAIL.LIST.VIEW")) : 
				?>
				$(document).on('click', '#tableUser tbody tr', function() {
					detailUser($(this).attr("data-id"));
					// window.location.href = "<?= site_url('user/detail') ?>?userId=" + $(this).attr("data-id");
				});
				<?php //endif; 
				?>

				let tagS2 = $("select[name=tag]").select2({
					theme: 'coreui'
				});
				let tagLocS2 = $("select[name=tagLocation]").select2({
					theme: 'coreui'
				});

				tagS2.on("select2:close", (v) => {
					userForm.tagId = $("select[name=tag]").val();
				})

				tagLocS2.on("select2:close", (v) => {
					userForm.tagLocationId = $("select[name=tagLocation]").val();
				})
			});

			// Vue.watch(
			// 	() => userForm.tagId,
			// 	(state, prevState) => {
			// 		tagS2 = $("select[name=tag]").select2({
			// 			theme: 'coreui'
			// 		});
			// 	}
			// )

			// Vue.watch(
			// 	() => userForm.tagId,
			// 	(state, prevState) => {
			// 		tagLocS2 = $("select[name=tagLocation]").select2({
			// 			theme: 'coreui'
			// 		});
			// 	}
			// )

			return {
				tagData,
				tagLocData,
				table,
				getUserList,
				userData,
				userForm,
				userFormErr,
				saveUser,
				showUserModal,
				deleteUser
			}
		}
	}).mount("#app");
</script>
<?= $this->endSection(); ?>