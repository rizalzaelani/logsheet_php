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
                            <td>: <?= $scheduleTrxData["assetName"] ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th>Number</th>
                            <td>: <?= $scheduleTrxData["assetNumber"] ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th>Tag</th>
                            <td>: <?= $scheduleTrxData["tagName"] ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th>Location</th>
                            <td>: <?= $scheduleTrxData["tagLocationName"] ?></td>
                        </tr>

                        <tr class="mt-1">
                            <th>Schedule Type</th>
                            <td>: <?= $scheduleTrxData["schType"] ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th>Description</th>
                            <td>: <?= $scheduleTrxData["description"] ?></td>
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
                    <div class="table-responsive table-record-fix-width border-bottom">
                        <table class="table table-responsive-sm table-hover table-bordered table-outline mb-0" id="detailRecord">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Parameter</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Min / Abnormal</th>
                                    <th class="text-center">Max / Normal</th>
                                    <th class="text-center">UoM / Option</th>
                                    <th class="text-center">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($trxData as $items => $val) : ?>
                                    <tr>
                                        <td class="text-center"><?= $i++; ?></td>
                                        <td><?= $val['parameterName']; ?></td>
                                        <td class="text-center"><?= $val['description']; ?></td>
                                        <td class="text-center"><i><?= (($val['option'] == '' || $val["option"] == null) ? $val['min'] : $val['abnormal']); ?></i></td>
                                        <td class="text-center"><i><?= (($val['option'] == '' || $val["option"] == null) ? $val['max'] : $val['normal']); ?></i></td>
                                        <td class="text-center"><?= (($val['option'] == '' || $val["option"] == null) ? $val['uom'] : $val['option']); ?></td>
                                        <td class="text-center"><?= $val['value']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <button type="button" class="btn btn-success w-100">Approve</button>
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