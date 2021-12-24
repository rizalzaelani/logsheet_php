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
                        <input name="dt-search" class="material-input" type="text" data-target="#tableLocation" placeholder="Search Data Transaction" />
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <h4><?= $title ?></h4>
                    <h5 class="header-icon">
                        <a href="javascript:;" class="dt-search" data-target="#tableLocation"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
                        <a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= base_url('/Location/add'); ?>"><i class="fa fa-plus mr-2"></i> Add Tag Location</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" type="button" @click="uploadFile()"><i class="fa fa-upload mr-2"></i> Import Data</a>
                            <a class="dropdown-item" target="_blank" href="<?= base_url('/Location/exportExcel'); ?>"><i class="fa fa-file-excel mr-2"></i> Export Data</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" @click="table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
                        </div>
                    </h5>
                </div>
                <div class="table-responsive w-100">
                    <table class="table w-100 table-hover" id="tableLocation">
                        <thead class="bg-primary">
                            <tr>
                                <th style="width: 40%;">Tag Location</th>
                                <th style="width: 60%;">Description</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Modal Tambah-->
                <div class="modal fade" id="modalLocation" tabindex="-1" role="dialog" aria-labelledby="modalLocationTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLocationTitle">Add Location</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <form action="">
                                        <div class="mb-3">
                                            <label for="tagLocationName">Tag Location Name <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="tagLocationName"></i></label>
                                            <input id="tagLocationName" type="text" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="latitude">Latitude <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="latitude"></i></label>
                                            <input id="latitude" type="text" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="longitude">Longitude <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="longitude"></i></label>
                                            <input id="longitude" type="text" class="form-control" required>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal import location-->
                <div class="modal fade" role="dialog" id="importLocationModal">
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
                                                <h5>Follow these steps to import your tag location.</h5>
                                                <hr>
                                                <div class="mt-3">
                                                    <h6>1. Download file template tag location</h6>
                                                    <div class="pl-3">
                                                        <p class="mb-0">Start by downloading the Excel template file by clicking the button below. This file has the required header fields to import the details of your tag location.</p>
                                                        <a data-toggle="tooltip" data-placement="top" title="Download Template" href="<?= base_url('/Location/download'); ?>" target="_blank" class="btn btn-link p-0" style="text-decoration: none;"><i class="fa fa-file-excel"></i> Download Template Excel</a>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <h6>2. Insert the tag location data you have into template</h6>
                                                    <div class="pl-3">
                                                        <p>Using Excel or other spreadsheet software, enter the detailed tag location data into our template file. Make sure the data matches the header fields in the template.</p>
                                                        <b>NOTE :</b>
                                                        <p class="m-0">Do not change the column headers in the template. This is required for the import to work.
                                                            A maximum of 30 tag location can be imported at one time.
                                                            When importing, the application will only enter new data, no data is deleted or updated.</p>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <h6>3. Upload the updated template here</h6>
                                                    <form action="post" enctype="multipart/form-data">
                                                        <input type="file" class="filepond mt-2 mb-2 w-100" name="fileImportLocation" id="fileImportLocation" />
                                                    </form>
                                                </div>
                                                <!-- <a href="<?= base_url('/Location/download'); ?>" class="btn btn-success w-100"><i class="fa fa-file-excel"></i> Download Template</a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal table import location-->
                <div class="modal fade" role="dialog" id="listImport">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="titleModalAdd">List Tag Location</h5>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <table class="table w-100" id="tableImport">
                                        <thead>
                                            <tr>
                                                <th style="width: 30% !important;">Location</th>
                                                <th style="width: 70% !important;">Description</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancel"><i class=" fa fa-times"></i> Cancel</button>
                                <button type="button" class="btn btn-success" @click="insertLocation()" id="btnAddLocation"><i class="fa fa-plus"></i> Add Location</button>
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
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var data = Vue.ref(null);
            var modalLocation = Vue.ref('');
            var table = Vue.ref(null);
            var dataLocation = Vue.ref('');
            var tableImport = Vue.ref("");

            const getData = () => {
                return new Promise(async (resolve, reject) => {
                    try {
                        table.value = await $('#tableLocation').DataTable({
                            drawCallback: function(settings) {
                                $(document).ready(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                })
                            },
                            processing: true,
                            serverSide: true,
                            scrollY: "calc(100vh - 272px)",
                            language: {
                                processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
                                lengthMenu: "Showing _MENU_ ",
                                info: "of _MAX_ entries",
                                infoEmpty: 'of 0 entries',
                            },
                            dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
                            ajax: {
                                url: "<?= base_url('/Location/datatable'); ?>",
                                type: "POST",
                                data: {},
                                complete: () => {
                                    resolve();
                                }
                            },
                            columns: [{
                                    data: "tagLocationName",
                                    name: "tagLocationName"
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
                            'createdRow': function(row, data) {
                                row.setAttribute("data-id", data.tagLocationId);
                                row.classList.add("cursor-pointer");
                                row.setAttribute("data-toggle", "tooltip");
                                row.setAttribute("title", "Click to go to location detail");
                            },
                        });
                    } catch (er) {
                        console.log(er)
                        reject(er);
                    }
                })

            };

            const handleAdd = () => {
                this.modalLocation = new coreui.Modal(document.getElementById('modalLocation'), {});
                this.modalLocation.show();
            };

            const uploadFile = () => {
                this.modalLocation = new coreui.Modal(document.getElementById('importLocationModal'), {});
                this.modalLocation.show();
            };

            const insertLocation = () => {
                axios.post("<?= base_url('Location/insertLocation'); ?>", {
                    dataLocation: importList,
                    tagLocationId: uuidv4()
                }).then(res => {
                    if (res.data.status == 200) {
                        swal.fire({
                            title: res.data.message,
                            icon: 'success'
                        }).then(okay => {
                            if (okay) {
                                $('#listImport').modal('hide');
                                v.table.draw();
                            }
                        })
                    }else{
                        swal.fire({
                            icon: 'error',
                            title: res.data.message
                        })
                    }
                })
            }

            Vue.onMounted(() => {
                getData();
                let search = $(".dt-search-input input[data-target='#tableLocation']");
                search.unbind().bind("keypress", function(e) {
                    if (e.which == 13 || e.keyCode == 13) {
                        let searchData = search.val();
                        table.value.search(searchData).draw();
                    }
                });

                $(document).on('click', '#tableLocation tbody tr', function() {
                    if ($(this).attr("data-id")) window.location.href = "<?= site_url('Location/detail') ?>/" + $(this).attr("data-id");
                });
            });

            return {
                data,
                modalLocation,
                table,
                dataLocation,
                getData,
                uploadFile,
                dataLocation,
                tableImport,
                insertLocation
            }
        },
    }).mount('#app');

    $(document).ready(function() {
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        let pond = $('#fileImportLocation').filepond({
            acceptedFileTypes: 'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xlsx',
            allowMultiple: false,
            instantUpload: true,
            credits: false,
            server: {
                process: {
                    url: "<?= base_url('Location/uploadFile'); ?>",
                    method: 'post',
                    onload: (res) => {
                        var rsp = JSON.parse(res);
                        if (rsp.status == "success") {
                            importList = rsp.data;
                            if (importList.length > 0) {
                                loadListImport(importList);
                                $('#importLocationModal').modal('hide');
                                this.myModal = new coreui.Modal(document.getElementById('listImport'), {});
                                this.myModal.show();
                                $('#fileImportLocation').filepond('removeFiles');
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
                    data: "locationName"
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