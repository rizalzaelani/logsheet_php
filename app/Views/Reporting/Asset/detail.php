<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main pb-4">
            <div class="d-flex justify-content-between my-1">
                <h4><?= $title ?></h4>
                <h5 class="header-icon">
                    <a href="<?= base_url("ReportingAsset") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                </h5>
            </div>

            <div class="row mt-2">
                <div class="col-sm-6">
                    <table class="table mt-2">
                        <tr class="mt-1">
                            <th>Asset</th>
                            <td>: IPC</td>
                        </tr>
                        <tr class="mt-1">
                            <th>Number</th>
                            <td>: 001</td>
                        </tr>
                        <tr class="mt-1">
                            <th>Tag</th>
                            <td>: ROUTER, CCTV</td>
                        </tr>
                        <tr class="mt-1">
                            <th>Location</th>
                            <td>: GEDUNG FINANCE</td>
                        </tr>

                        <tr class="mt-1">
                            <th>Frequency</th>
                            <td>: WEEKLY</td>
                        </tr>
                        <tr class="mt-1">
                            <th>Description</th>
                            <td>: DESC</td>
                        </tr>
                        <tr class="mt-1">
                            <th>Select Date</th>
                            <td class="d-flex align-items-center">
                                :<div class="pl-1" id="reportrange" style="cursor: pointer; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6 d-none d-sm-flex flex-row align-items-center">
                    <img src="/img/logo-act.png" alt="Image" class="img-thumbnail m-0 border-0">
                </div>
                <div class="col-12 mb-4">
                    <hr />
                </div>
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-responsive-sm table-hover table-bordered w-100 nowrap" id="detailReport">
                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Parameter</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Tag No</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Min</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Max</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">UoM</th>
                                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                                        <th class="text-center" style="vertical-align: middle;"><?= $i; ?> Aug 2021</th>

                                    <?php } ?>
                                </tr>
                                <tr>
                                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                                        <th class="text-center" style="vertical-align: middle;">00:00</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($arr as $items => $val) : ?>
                                    <tr>
                                        <td class="text-center"><?= $i++; ?></td>
                                        <td><?= $val->parameter; ?></td>
                                        <td class="text-center"><?= (($val->tagNo) == '' ? '' : $val->tagNo) ?></td>
                                        <td class="text-center"><i><?= (($val->min == '' ? '(empty)' : $val->min)) ?></i></td>
                                        <td class="text-center"><i><?= (($val->max == '' ? '(empty)' : $val->max)) ?></i></td>
                                        <td class="text-center"><?= $val->satuan ?></td>
                                        <td><i>(NC)</i></td>
                                        <td><i>(NC)</i></td>
                                        <td><i>(RUN)</i></td>
                                        <td><i>(NC)</i></td>
                                        <td><i>(NC)</i></td>
                                        <td><i>(RUN)</i></td>
                                        <td><i>(NC)</i></td>
                                        <td><i>(NC)</i></td>
                                        <td><i>(RUN)</i></td>
                                        <td><i>(NC)</i></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 mt-1" style="width: 100%;">
                    <button class="btn btn-success" style="width: 100%;"><i class="fa fa-file-excel"></i> Export To Excel</button>
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
        data: () => ({
            data: null
        }),
        mounted() {
            this.getData();
        },
        methods: {
            getData() {
                $('#detailReport').DataTable({
                    scrollX: '100%',
                    scrollCollapse: true,
                    ordering: false,
                    responsive: false,
                    dom: 't',
                })
            }
        }
    })

    $('.dataTables_scrollHeadInner').removeClass('dataTables_scrollHeadInner');

    $('#status').select2({
        theme: 'coreui'
    })
    $('.select2-container').addClass('ml-1');
</script>
<?= $this->endSection(); ?>