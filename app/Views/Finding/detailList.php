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
                    <a href="<?= base_url("Transaction") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
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
                    </table>
                </div>
                <div class="col-sm-6 d-none d-sm-flex flex-row align-items-center">
                    <img src="/img/logo-act.png" alt="Image" class="img-thumbnail m-0 border-0">
                </div>
                <div class="col-12 mb-4">
                    <hr />
                </div>
                <div class="col-12">
                    <div class="table-responsive table-record-fix-width">
                        <table class="table table-responsive-sm table-hover table-bordered table-outline mb-0" id="detailRecord" style="">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Parameter</th>
                                    <th class="text-center">Tag No</th>
                                    <th class="text-center">Spec Standard</th>
                                    <th class="text-center">Min / Abnormal</th>
                                    <th class="text-center">Max / Normal</th>
                                    <th class="text-center">UoM / Option</th>
                                    <th class="text-center">Value</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>Frequency</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">49 - 51 (Hz)</td>
                                    <td class="text-center">49</td>
                                    <td class="text-center">51</td>
                                    <td class="text-center">Hz</td>
                                    <td class=" text-center text-danger font-weight-bold">48</td>
                                    <td><a href="<?= base_url("Finding/detail") ?>?findingId=2b958001-69e5-4421-b3c1-7eb0d21e0d8a" class="btn btn-info btn-block">Is Open</a></td>
                                </tr>
                            </tbody>
                        </table>
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
        data: () => ({
            data: null
        }),
        mounted() {

        },
        methods: {

        }
    })
</script>
<?= $this->endSection(); ?>