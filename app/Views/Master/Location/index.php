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
                            <a class="dropdown-item" href="javascript:;" onclick="v.table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
                        </div>
                    </h5>
                </div>
                <div class="table-responsive w-100">
                    <table class="table w-100 table-hover" id="tableLocation">
                        <thead class="bg-primary">
                            <tr>
                                <th>#</th>
                                <th>Tag Location</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 1; $i <= 11; $i++) { ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>Gudang Mesin</td>
                                    <td>-6.193125</td>
                                    <td>106.821810</td>
                                    <td>description</td>
                                </tr>
                            <?php } ?>
                        </tbody>
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
                    table.search(searchData).draw();
                }
            });
        },
        methods: {
            getData() {
                this.table = $('#tableLocation').DataTable({
                    scrollY: "calc(100vh - 272px)",
                    language: {
                        lengthMenu: "Showing _MENU_ ",
                        info: "of _MAX_ entries",
						infoEmpty: 'of 0 entries',
                    },
                    dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>'
                });
            },
            handleAdd() {
                this.modalLocation = new coreui.Modal(document.getElementById('modalLocation'), {});
                this.modalLocation.show();
            },
        }
    })
</script>
<?= $this->endSection(); ?>