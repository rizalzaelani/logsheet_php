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
							<a class="dropdown-item " href="javascript:;" @click="handleModal()"><i class="fa fa-plus mr-2"></i> New Release</a>
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
									<th>Version</th>
									<th>By</th>
									<th width="10%">Action</th>
								</tr>
							</thead>
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
					<h5 style="display: none;" class="modal-title" id="editReleaseTitle">Edit Version App</h5>
					<button @click="btnCancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<form action="" method="post" enctype="multipart/form-data">
							<div class="mb-3">
								<label for="applicationName">Application Name</label>
								<input id="applicationName" type="text" class="form-control" required v-model="applicationName" placeholder="Application Name">
								<div class="invalid-feedback">
                                    Field cannot be empty.
                                </div>
							</div>
							<div class="mb-3">
								<label for="version">Version</label>
								<input id="version" type="text" class="form-control" required v-model="version" placeholder="Version"></input>
								<div class="invalid-feedback">
                                    Field cannot be empty.
                                </div>
							</div>
							<div class="mb-3">
								<label for="description">Description</label>
								<textarea id="description" type="text" rows="5" class="form-control" required v-model="description" placeholder="Description"></textarea>
								<div class="invalid-feedback">
                                    Field cannot be empty.
                                </div>
							</div>
							<div class="mb-3">
								<label for="file">File</label>
								<input id="file" type="file" ref="file" class="form-control" @change="handleFile()"></input>
								<div class="invalid-feedback">
                                    Field cannot be empty.
                                </div>
							</div>
						</form>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" @click="btnCancel()" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
					<button type="button" class="btn btn-success" @click="newRelease()" id="btnAdd"><i class="fa fa-plus"></i> Add Application</button>
					<button style="display: none;" type="button" class="btn btn-success" @click="update()" id="btnEdit"><i class="fa fa-check"></i> Save Changes</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal Detail-->
	<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalDetailTitle">Detail Version App</h5>
					<button @click="btnCancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table">
						<tr>
							<th>Name</th>
							<td>{{ applicationName }}</td>
						</tr>
						<tr>
							<th>Version</th>
							<td>{{ version }}</td>
						</tr>
						<tr>
							<th>Description</th>
							<td>{{ description }}</td>
						</tr>
						<tr>
							<th>By</th>
							<td>{{ userId }}</td>
						</tr>
						<tr>
							<th>Date</th>
							<td>{{ createdAt }}</td>
						</tr>
						<tr>
							<th>Application</th>
							<td>
								<!-- <button @click="download()" class="btn btn-outline-primary">Download</button> -->
								<a v-bind:href="downloadApk" class="btn btn-outline-primary">Download</a>
							</td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" @click="btnCancel()" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
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
			var userId = ref('');
			var versionAppId = ref('');
			var applicationName = ref('');
			var version = ref('');
			var description = ref('');
			var createdAt = ref('');
			var fileApp = ref('');
			var file = ref('');
			var fileUploaded = ref('');
			var downloadApk = ref('');

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
                return new Promise(async (resolve, reject) => {
                    try {
                        this.table = await $('#tableVersionApps').DataTable({
                            processing: true,
                            serverSide: true,
                            responsive: true,
                            autoWidth: true,
                            scrollY: "calc(100vh - 272px)",
                            language: {
                                processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
                                lengthMenu: "Showing _MENU_ ",
                                info: "of _MAX_ entries",
                                infoEmpty: 'of 0 entries',
                            },
                            dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
                            ajax: {
                                url: "<?= base_url('/VersionApps/datatable'); ?>",
                                type: "POST",
                                data: {},
                                complete: () => {
                                    resolve();
                                }
                            },
                            columns: [{
                                    data: "createdAt",
                                    name: "createdAt",
									render: function (data) {
										return moment(data).format('llll');
									}
                                },
                                {
                                    data: "name",
                                    name: "name"
                                },
								{
                                    data: "version",
                                    name: "version"
                                },
								{
                                    data: "userId",
                                    name: "UserId"
                                },
                            ],
                            order: [0, 'asc'],
                            columnDefs: [{
                                targets: 4,
                                data: "versionAppId",
                                render: function(data, type, row, meta) {
                                    return `<div class='d-flex justify-content-start align-items-center'>
										<button class='btn btn-outline-primary btn-sm mr-1' id=` + data + ` onclick="detailApps(` + `'` + data + `'` + `)"><i class='fa fa-eye'></i> Detail</button>`;
                                },
										// <button class='btn btn-outline-success btn-sm mr-1' id=` + data + ` onclick="editApps(` + `'` + data + `'` + `)"><i class='fa fa-edit'></i> Edit</button>
                                        // <button class='btn btn-outline-danger btn-sm' id="` + data + `" onclick="deleteApps(` + `'` + data + `'` + `)"><i class='fa fa-trash'></i> Delete</button></div>`;
                            },
							]
                        });
                    } catch (er) {
                        console.log(er)
                        reject(er);
                    }
                })
            };

			function handleModal() {
				$('#modalReleaseTitle').show();
				$('#btnAdd').show();
				$('#editReleaseTitle').hide();
				$('#btnEdit').hide();
				this.myModal = new coreui.Modal(document.getElementById('modalRelease'));
				this.myModal.show();
			}

			function newRelease() {
				if (v.applicationName != '') {
					$('#applicationName').removeClass('is-invalid');
				}
				if (v.version != '') {
					$('#version').removeClass('is-invalid');
				}
				if (v.description != '') {
					$('#description').removeClass('is-invalid');
				}
				if (v.fileUploaded != '') {
					$('#file').removeClass('is-invalid');
				}
				if (this.applicationName != '' && this.version != '' && this.description != '' && this.fileUploaded != '') {
					var formdata = new FormData();
					formdata.append('versionAppId', uuidv4());
					formdata.append('userId', uuidv4());
					formdata.append('name', this.applicationName);
					formdata.append('version', this.version);
					formdata.append('description', this.description);
					formdata.append('fileApp', this.fileUploaded);
					axios({
						url: '<?= base_url('VersionApps/new') ?>',
						data: formdata,
						method: 'POST'
					}).then(res => {
						if (res.data.status == 'success') {
                                const swalWithBootstrapButtons = swal.mixin({
                                    customClass: {
                                        confirmButton: 'btn btn-success mr-1',
                                    },
                                    buttonsStyling: false
                                })
                                swalWithBootstrapButtons.fire({
                                    title: 'Success!',
                                    text: res.data.message,
                                    icon: 'success'
                                }).then(okay => {
                                    if (okay) {
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
                            } else if (res.data.status == 'failed') {
                                const swalWithBootstrapButtons = swal.mixin({
                                    customClass: {
                                        confirmButton: 'btn btn-danger',
                                    },
                                    buttonsStyling: false
                                })
                                swalWithBootstrapButtons.fire({
                                    title: 'Failed!',
                                    text: res.data.message,
                                    icon: 'error'
                                })
                            }
					})
				}else{
                    const swalWithBootstrapButtons = swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-danger',
                        },
                        buttonsStyling: false
                    })
                    swalWithBootstrapButtons.fire({
                        title: 'Failed!',
                        text: 'Invalid value.',
                        icon: 'error'
                    })
					if (v.applicationName == '') {
						$('#applicationName').addClass('is-invalid');
					}
					if (v.version == '') {
						$('#version').addClass('is-invalid');
					}
					if (v.description == '') {
						$('#description').addClass('is-invalid');
					}
					if (v.fileUploaded == '') {
					$('#file').addClass('is-invalid');
				}
				}
			}

			function uuidv4() {
            	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                	var r = Math.random() * 16 | 0,
                    v = c == 'x' ? r : (r & 0x3 | 0x8);
                	return v.toString(16);
            	});
        	}

			function btnCancel() {
				this.versionAppId = '';
				this.applicationName = '';
				this.version = '';
				this.description = '';
				this.createdAt = '';
				this.downloadApk = '';
			}

			function update() {
				if (v.applicationName != '') {
					$('#applicationName').removeClass('is-invalid');
				}
				if (v.version != '') {
					$('#version').removeClass('is-invalid');
				}
				if (v.description != '') {
					$('#description').removeClass('is-invalid');
				}
				if (this.applicationName != '' && this.version != '' && this.description != '') {
					let formdata = new FormData();
					formdata.append('userId', this.userId);
					formdata.append('versionAppId', this.versionAppId);
					formdata.append('name', this.applicationName);
					formdata.append('version', this.version);
					formdata.append('description', this.description);
					formdata.append('fileApp', this.fileUploaded == "" ? "" : this.fileUploaded);
					axios({
						url: 'VersionApps/update',
						method: 'POST',
						data: formdata
					}).then(res => {
						if (res.data.status == 'success') {
                                const swalWithBootstrapButtons = swal.mixin({
                                    customClass: {
                                        confirmButton: 'btn btn-success mr-1',
                                    },
                                    buttonsStyling: false
                                })
                                swalWithBootstrapButtons.fire({
                                    title: 'Success!',
                                    text: res.data.message,
                                    icon: 'success'
                                }).then(okay => {
                                    if (okay) {
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
                            } else{
                                const swalWithBootstrapButtons = swal.mixin({
                                    customClass: {
                                        confirmButton: 'btn btn-danger',
                                    },
                                    buttonsStyling: false
                                })
                                swalWithBootstrapButtons.fire({
                                    title: 'Failed!',
                                    text: res.data.message,
                                    icon: 'error'
                                })
                            }
					})
				}else{
					const swalWithBootstrapButtons = swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-danger',
                        },
                        buttonsStyling: false
                    })
                    swalWithBootstrapButtons.fire({
                        title: 'Failed!',
                        text: 'All Field cannot be empty.',
                        icon: 'error'
                    })
					if (v.applicationName == '') {
						$('#applicationName').addClass('is-invalid');
					}
					if (v.version == '') {
						$('#version').addClass('is-invalid');
					}
					if (v.description == '') {
						$('#description').addClass('is-invalid');
					}
				}
			}
			
			function handleFile() {
				let fileUploaded = this.$refs.file.files[0];
				this.fileUploaded = fileUploaded;
			}
			
			function download() {
				axios.post('VersionApps/download',{
					data: v.versionAppId
				})
			}

			return {
				table,
				myModal,
				userId,
				versionAppId,
				applicationName,
				version,
				description,
				createdAt,
				fileApp,
				file,
				fileUploaded,
				download,

				getData,
				handleModal,
				newRelease,
				uuidv4,
				detailApps,
				editApps,
				btnCancel,
				update,
				handleFile,
				downloadApk
			}
		},
	}).mount('#app');

	function detailApps(id) {
		axios.post("VersionApps/detail", {
			versionAppId: id
		}).then(res => {
			if (res.data.status == 'success') {
				v.myModal = new coreui.Modal(document.getElementById('modalDetail'));
				v.myModal.show();
				let data = res.data.data;
				let date = moment(data.createdAt).format('llll');
				v.versionAppId = data.versionAppId;
				v.downloadApk = "<?= base_url('VersionApps/download') ?>/" + data.versionAppId;
				v.applicationName = data.name;
				v.version = data.version;
				v.description = data.description;
				v.userId = data.userId;
				v.createdAt = date;
			}else{
				const swalWithBootstrapButtons = swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-danger',
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Failed!',
                    text: res.data.message,
                    icon: 'error'
                })
			}
		})
	}

	function editApps(id) {
		axios.post("VersionApps/edit", {
			versionAppId: id
		}).then(res => {
			if (res.data.status == 'success') {
				$('#modalReleaseTitle').hide();
				$('#btnAdd').hide();
				$('#editReleaseTitle').show();
				$('#btnEdit').show();
				v.myModal = new coreui.Modal(document.getElementById('modalRelease'));
				v.myModal.show()
				let data = res.data.data;
				v.userId = data.userId;
				v.versionAppId = data.versionAppId;
				v.applicationName = data.name;
				v.version = data.version;
				v.description = data.description;
				v.fileApp = data.fileApp;
			}else{
				const swalWithBootstrapButtons = swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-danger',
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Failed!',
                    text: res.data.message,
                    icon: 'error'
                })
			}
		})
	}

	function deleteApps(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger ml-1'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Delete this data?',
            text: "You will delete this data!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: "<i class='fa fa-times'></i> Cancel",
            confirmButtonText: "<i class='fa fa-check'></i> Yes, delete!",
            reverseButtons: false
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post("<?= base_url('VersionApps/delete'); ?>", {
                    versionAppId: id
                }).then(res => {
                    if (res.data.status == 'success') {
                        swalWithBootstrapButtons.fire({
                            title: 'Success!',
                            text: 'You have successfully deleted this data.',
                            icon: 'success',
                            allowOutsideClick: false
                        }).then(okay => {
                            if (okay) {
                                v.table.draw();
                            }
                        })
                    } else {
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire({
                            title: 'Failed!',
                            text: 'Bad Request!',
                            icon: 'error',
                            allowOutsideClick: false
                        })
                    }
                })
            }
        })
    }
</script>
<?= $this->endSection(); ?>