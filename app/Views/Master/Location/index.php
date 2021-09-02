<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main card-border-top fixed-height">
            <div class="container-fluid">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <h4 class="title"><?= (isset($subtitle) ? $subtitle : '')  ?></h4>
                        <div>
                            <div class="btn-group">
                                <button data-toggle="tooltip" data-placement="top" title="Add Data" class="btn btndark btn-sm" @click="handleAdd()"><i class="fa fa-plus"></i> Add</button>
                            </div>
                            <div class="btn-group">
                                <button data-toggle="tooltip" data-placement="top" title="Filter" class="btn btndark btn-sm" id="btnFilter" @click="btnFilter()"><i class="fa fa-filter"></i> Filter</button>
                                <button data-toggle="tooltip" data-placement="top" title="Hide Filter" class="btn btndark btn-sm" id="btnHideFilter" @click="btnHideFilter()" style="display: none;"><i class="fa fa-eye-slash"></i> Hide Filter</button>
                            </div>
                            <div class="btn-group">
                                <button data-toggle="tooltip" data-placement="top" title="Import / Export" class="btn btndark btn-sm" type="button"><i class="fa fa-upload"></i> Import</button>
                                <button class="btn btndark btn-sm dropdown-toggle dropdown-toggle-split" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span></button>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-start" style="position: absolute; transform: translate3d(71px, 34px, 0px); top: 0px; left: 0px; will-change: transform; font-size: 12px;">
                                    <a class="dropdown-item" href="<?= base_url('/Asset/import'); ?>"><i class="fa fa-upload"></i> Import Data</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?= base_url('/Asset/export'); ?>"><i class="fa fa-file-excel"></i> Export Excel</a>
                                    <a class="dropdown-item" href="<?= base_url('/Asset/exportCsv'); ?>"><i class="fa fa-file-csv"></i> Export CSV</a>
                                    <a class="dropdown-item" href="<?= base_url('/Asset/exportOds'); ?>"><i class="fa fa-file-alt"></i> Export ODS</a>
                                </div>
                            </div>
                            <div class="btn-group">
                                <a style="text-decoration: none; " href="<?= base_url('Asset/domPdf'); ?>" data-toggle="tooltip" data-placement="top" title="Print Pdf" id="print" class="btn btndark btn-sm" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i> Print</a>
                            </div>
                            <div class="btn-group">
                                <div class="dt-search-input">
                                    <div class="input-container">
                                        <input type="text" id="myInputTextField" class="form-control form-control-sm" style="display: none; text-decoration: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="btn-group">
                                <button data-toggle="tooltip" data-placement="top" title="Search" class="btn btndark btn-sm" id="btnSearch" type="button" @click="btnSearch()"><i class="fa fa-search"></i> Search</button>
                                <button data-toggle="tooltip" data-placement="top" title="Hide Search" class="btn btndark btn-sm" id="btnHide" type="button" @click="btnHide()" style="display: none;"><i class="fa fa-eye-slash"></i> Hide</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive w-100">
                        <table class="table dt-responsive w-100 table-hover" id="tableLocation">
                            <tr>
                                <thead class="bg-primary">
                                    <th style="border-top-left-radius: 5px;">#</th>
                                    <th>Tag Location</th>
                                    <th>Latitude</th>
                                    <th style="border-top-right-radius: 5px;">Longitude</th>
                                </thead>
                                <tbody>
                                    <?php for ($i = 1; $i <= 11; $i++) { ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td>Gudang Mesin</td>
                                            <td>-6.193125</td>
                                            <td>106.821810</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </tr>
                        </table>
                    </div>

                    <!-- Modal Tambah-->
                    <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Location</h5>
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
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<script>
    let v = new Vue({
        el: '#app',
        data: {
            myModal: ''
        },
        mounted() {
            this.getData;
        },
        methods: {
            getData() {
                $('#tableLocation').DataTable({
                    'scrollY': "calc(100vh - 300px)",
                    'paging': true,
                    'dom': '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-flex justify-content-between"<""i><"d-flex justify-content-end align-items-center" <"mt-2 mr-2"l>pr>>>',
                });
            },
            handleAdd() {
                this.myModal = new coreui.Modal(document.getElementById('exampleModalScrollable'), {});
                this.myModal.show();
            },
        }
    })
</script>
<?= $this->endSection(); ?>