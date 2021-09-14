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
                            <a class="dropdown-item" href="<?= base_url('/Location/add'); ?>"><i class="fa fa-plus mr-2"></i> Add Location</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('/Location/import'); ?>"><i class="fa fa-upload mr-2"></i> Import Data</a>
                            <a class="dropdown-item" href="<?= base_url('/Location/export'); ?>"><i class="fa fa-file-excel mr-2"></i> Export Data</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" onclick="v.table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
                        </div>
                    </h5>
                </div>
                <div class="table-responsive w-100">
                    <table class="table w-100 table-hover" id="tableLocation">
                        <thead class="bg-primary">
                            <tr>
                                <th>Tag Location</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Description</th>
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
            modalLocation: null,
            table: null
        },
        mounted() {
            this.getData();

            let search = $(".dt-search-input input[data-target='#tableLocation']");
            search.unbind().bind("keypress", function(e) {
                if (e.which == 13 || e.keyCode == 13) {
                    let searchData = search.val();
                    v.table.search(searchData).draw();
                }
            });

            $(document).on('click', '#tableLocation tbody tr', function() {
                window.location.href = "<?= site_url('Location/detail') ?>/" + $(this).attr("data-id");
            });
        },
        methods: {
            getData() {
                return new Promise(async (resolve, reject) => {
                    try {
                        this.table = await $('#tableLocation').DataTable({
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
                                    data: "latitude",
                                    name: "latitude"
                                },
                                {
                                    data: "longitude",
                                    name: "longitude"
                                },
                                {
                                    data: "description",
                                    name: "description"
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

            },
            handleAdd() {
                this.modalLocation = new coreui.Modal(document.getElementById('modalLocation'), {});
                this.modalLocation.show();
            },
        }
    })
</script>
<?= $this->endSection(); ?>