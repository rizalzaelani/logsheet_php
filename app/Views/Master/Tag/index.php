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
                        <input name="dt-search" class="material-input" type="text" data-target="#tableTag" placeholder="Search Data Tag" />
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <h4><?= $title ?></h4>
                    <h5 class="header-icon">
                        <a href="javascript:;" class="dt-search" data-target="#tableTag"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
                        <a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item cursor-pointer" @click="handleAdd()"><i class="fa fa-plus mr-2"></i> Add Tag</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" type="button" @click="uploadFile()"><i class="fa fa-upload mr-2"></i> Import Data</a>
                            <a class="dropdown-item" target="_blank" href="<?= base_url('/Tag/exportExcel'); ?>"><i class="fa fa-file-excel mr-2"></i> Export Data</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" onclick="v.table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
                        </div>
                    </h5>
                </div>
                <div class="table-responsive w-100">
                    <table class="table w-100 table-hover" id="tableTag">
                        <thead class="bg-primary">
                            <tr>
                                <th style="width: 30%">Tag Name</th>
                                <th style="width: 50%">Description</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- Modal Tambah-->
                <div class="modal fade" id="modalTag" tabindex="-1" role="dialog" aria-labelledby="modalTagTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTagTitle">Add Tag</h5>
                                <h5 style="display: none;" class="modal-title" id="editTagTitle">Edit Tag</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <form action="">
                                        <div class="mb-3">
                                            <label for="tagname">Tag Name <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Name for tag"></i></label>
                                            <input id="tagname" type="text" class="form-control" required v-model="tagName" placeholder="Tag Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="description">Description <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Description for tag"></i></label>
                                            <textarea id="description" type="text" rows="5" class="form-control" required v-model="description" placeholder="Description"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" @click="btnCancel()" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
                                <button type="button" class="btn btn-success" @click="add()" id="btnAdd"><i class="fa fa-plus"></i> Add Tag</button>
                                <button style="display: none;" type="button" class="btn btn-success" @click="update()" id="btnEdit"><i class="fa fa-check"></i> Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal import tag-->
                <div class="modal fade" role="dialog" id="importTagModal">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="titleModalAdd">Upload File</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-3">
                                                <h5>Follow these steps to import your tag.</h5>
                                                <hr>
                                                <div class="mt-3">
                                                    <h6>1. Download file template tag</h6>
                                                    <div class="pl-3">
                                                        <p class="mb-0">Start by downloading the Excel template file by clicking the button below. This file has the required header fields to import the details of your tag.</p>
                                                        <a data-toggle="tooltip" data-placement="top" title="Download Template" href="<?= base_url('/Tag/download'); ?>" target="_blank" class="btn btn-link p-0" style="text-decoration: none;"><i class="fa fa-file-excel"></i> Download Template Excel</a>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <h6>2. Insert the tag data you have into template</h6>
                                                    <div class="pl-3">
                                                        <p>Using Excel or other spreadsheet software, enter the detailed tag location data into our template file. Make sure the data matches the header fields in the template.</p>
                                                        <b>NOTE :</b>
                                                        <p class="m-0">Do not change the column headers in the template. This is required for the import to work.
                                                            A maximum of 30 tag can be imported at one time.
                                                            When importing, the application will only enter new data, no data is deleted or updated.</p>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <h6>3. Upload the updated template here</h6>
                                                    <form action="post" enctype="multipart/form-data">
                                                        <input type="file" class="filepond mt-2 mb-2 w-100" name="fileImportTag" id="fileImportTag" />
                                                    </form>
                                                </div>
                                                <!-- <a href="<?= base_url('/Tag/download'); ?>" class="btn btn-success w-100"><i class="fa fa-file-excel"></i> Download Template</a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal table import tag-->
                <div class="modal fade" role="dialog" id="listImport">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="titleModalAdd">List Parameter</h5>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <table class="table w-100" id="tableImport">
                                        <thead>
                                            <tr>
                                                <th>Tag</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancel"><i class=" fa fa-times"></i> Cancel</button>
                                <button type="button" class="btn btn-success" @click="insertTag()" id="btnAddTag"><i class="fa fa-plus"></i> Add Location</button>
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
    const {
        onMounted,
        ref
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var data = ref('');
            var table = ref('');
            var modalTag = ref('');
            var tagName = ref('');
            var description = ref('');
            var tagId = ref(null);
            var tableImport = ref("");

            function getData() {
                return new Promise(async (resolve, reject) => {
                    try {
                        this.table = await $('#tableTag').DataTable({
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
                                url: "<?= base_url('/Tag/datatable'); ?>",
                                type: "POST",
                                data: {},
                                complete: () => {
                                    resolve();
                                }
                            },
                            columns: [{
                                    data: "tagName",
                                    name: "tagName"
                                },
                                {
                                    data: "description",
                                    name: "description",
                                    render: function(data, type, row, meta) {
                                        return row.description == "" ? '-' : row.description;
                                    }
                                },
                            ],
                            order: [0, 'asc'],
                            columnDefs: [{
                                targets: 2,
                                data: "tagId",
                                render: function(data, type, row, meta) {
                                    return `<div class='d-flex justify-content-start align-items-center'><button class='btn btn-outline-success btn-sm mr-1' id=` + data + ` onclick="editTag(` + `'` + data + `'` + `)"><i class='fa fa-edit'></i> Edit</button>
                                        <button class='btn btn-outline-danger btn-sm' id="` + data + `" onclick="deleteTag(` + `'` + data + `'` + `)"><i class='fa fa-trash'></i> Delete</button></div>`;
                                },
                            }, ]
                        });
                    } catch (er) {
                        console.log(er)
                        reject(er);
                    }
                })
            };

            function handleAdd() {
                $('#btnAdd').show();
                $('#btnEdit').hide();
                $('#modalTagTitle').show();
                $('#editTagTitle').hide();
                this.modalTag = new coreui.Modal(document.getElementById('modalTag'), {});
                this.modalTag.show();
            };

            function add() {
                axios.post("<?= base_url('Tag/add'); ?>", {
                    tagId: this.tagId,
                    tagName: this.tagName,
                    description: this.description
                }).then(res => {
                    if (res.data.status == 'success') {
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire({
                            title: 'Success!',
                            text: res.data.message,
                            icon: 'success',
                            allowOutsideClick: false
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
                    } else {
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire({
                            title: 'Failed!',
                            text: res.data.message,
                            icon: 'error',
                            allowOutsideClick: false
                        })
                    }
                })
            };

            function update() {
                if (this.tagName != '' && this.description != '') {
                    axios.post("<?= base_url('Tag/update'); ?>", {
                        tagId: this.tagId,
                        tagName: this.tagName,
                        description: this.description,
                    }).then(res => {
                        if (res.data.status == 'success') {
                            const swalWithBootstrapButtons = Swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                },
                                buttonsStyling: false
                            })
                            swalWithBootstrapButtons.fire({
                                title: 'Success!',
                                text: res.data.message,
                                icon: 'success',
                                allowOutsideClick: false
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
                } else {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-danger',
                        },
                        buttonsStyling: false
                    })
                    swalWithBootstrapButtons.fire({
                        title: 'Failed!',
                        text: 'All fields cannot be empty.',
                        icon: 'error',
                        allowOutsideClick: false
                    })
                }
            };

            function uploadFile() {
                this.modalLocation = new coreui.Modal(document.getElementById('importTagModal'), {});
                this.modalLocation.show();
            };

            function insertTag() {
                axios.post("<?= base_url('Tag/insertTag'); ?>", {
                    dataTag: importList,
                }).then(res => {
                    console.log(res);
                    if (res.data.status == 'success') {
                        const swalWithBootstrapButtons = swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire(
                            'Success!',
                            'You have successfully add tag.',
                            'success'
                        ).then(okay => {
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
                    } else {
                        const swalWithBootstrapButtons = swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire(
                            'Failed!',
                            res.data.message,
                            'error'
                        )
                    }
                })
            };

            function btnCancel() {
                this.tagName = '';
                this.description = '';
            }

            onMounted(() => {
                getData();

                let search = $(".dt-search-input input[data-target='#tableTag']");
                search.unbind().bind("keypress", function(e) {
                    if (e.which == 13 || e.keyCode == 13) {
                        let searchData = search.val();
                        v.table.search(searchData).draw();
                    }
                });
            });

            return {
                data,
                table,
                modalTag,
                tagName,
                description,
                tagId,
                getData,
                handleAdd,
                add,
                update,
                uploadFile,
                insertTag,
                btnCancel,
                tableImport
            }
        },
    }).mount('#app');

    function editTag(data) {
        $('#btnAdd').hide();
        $('#btnEdit').show();
        $('#modalTagTitle').hide();
        $('#editTagTitle').show();

        axios.post("<?= base_url('Tag/edit'); ?>", {
            tagId: data
        }).then(res => {
            if (res.data.status == 'success') {
                let dataTag = res.data.data[0];
                v.tagId = dataTag.tagId;
                v.tagName = dataTag.tagName;
                v.description = dataTag.description;
                v.modalTag = new coreui.Modal(document.getElementById('modalTag'), {});
                v.modalTag.show();
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

    function deleteTag(data) {
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
                axios.post("<?= base_url('Tag/deleteTag'); ?>", {
                    tagId: data
                }).then(res => {
                    if (res.data.status == 'success') {
                        swalWithBootstrapButtons.fire({
                            title: 'Success!',
                            text: 'You have successfully deleted this data.',
                            icon: 'success',
                            allowOutsideClick: false
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

    $(document).ready(function() {
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        let pond = $('#fileImportTag').filepond({
            acceptedFileTypes: 'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xlsx',
            allowMultiple: false,
            instantUpload: true,
            credits: false,
            server: {
                process: {
                    url: "<?= base_url('Tag/uploadFile'); ?>",
                    method: 'post',
                    onload: (res) => {
                        var rsp = JSON.parse(res);
                        if (rsp.status == "success") {
                            importList = rsp.data;
                            if (importList.length > 0) {
                                loadListImport(importList);
                                $('#importTagModal').modal('hide');
                                this.myModal = new coreui.Modal(document.getElementById('listImport'), {});
                                this.myModal.show();
                                $('#fileImportTag').filepond('removeFiles');
                            }
                        } else if (rsp.status == "failed") {
                            const swalWithBootstrapButtons = swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            })
                            swalWithBootstrapButtons.fire(
                                rsp.message,
                                '',
                                'error'
                            )
                        }
                    }
                }
            }
        });
    })

    var loadListImport = (importList) => {
        if (v.tableImport != "") {
            v.tableImport.clear().destroy()
        }
        v.tableImport = $('#tableImport').DataTable({
            ordering: false,
            paging: true,
            dom: `<"d-flex justify-content-between align-items-center"<i><f>>tp`,
            data: importList,
            columns: [{
                    data: "tagName"
                },
                {
                    data: "description"
                },
            ],
            columnDefs: [{
                targets: 0,
                searchable: false,
                orderable: false,
                className: 'dt-body-center',
            }],
            order: [0, 'asc'],
        });
    }
</script>
<?= $this->endSection(); ?>