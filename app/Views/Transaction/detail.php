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
                        <table class="table table-responsive-sm table-hover table-bordered table-outline mb-0" id="detailRecord">
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($arr as $items => $val) : ?>
                                    <tr>
                                        <td class="text-center"><?= $i++; ?></td>
                                        <td><?= $val->parameter; ?></td>
                                        <td class="text-center"><?= (($val->tagNo) == '' ? '' : $val->tagNo) ?></td>
                                        <td class="text-center"><?= $val->specStandard; ?></td>
                                        <td class="text-center"><i><?= (($val->min || $val->deviasi != '') ? $val->min . '/' . $val->deviasi : ''); ?></i></td>
                                        <td class="text-center"><i><?= (($val->max || $val->normal != '') ? $val->max . '/' . $val->normal : ''); ?></i></td>
                                        <td class="text-center"><?= (($val->satuan || $val->option != '') ? $val->satuan . '/' . $val->option : ''); ?></td>
                                        <td class="text-center"><?= $val->value; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <!-- <tr>
                                    <td class="text-center">2</td>
                                    <td>Clutch Oil Pressure</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">2,1-2,3 (MPa)</td>
                                    <td class="text-center">2.1</td>
                                    <td class="text-center">2.3</td>
                                    <td class="text-center">Mpa</td>
                                    <td class="text-center">2.2</td>
                                </tr>
                                <tr>
                                    <td class="text-center">3</td>
                                    <td>LO Cooler Temp. (In)</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">36-40 (C)</td>
                                    <td class="text-center">36</td>
                                    <td class="text-center">40</td>
                                    <td class="text-center">C</td>
                                    <td class="text-center">37</td>
                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td>LO Cooler Temp. (Out)</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">40-80 (C)</td>
                                    <td class="text-center">40</td>
                                    <td class="text-center">80</td>
                                    <td class="text-center">C</td>
                                    <td class="text-center">46</td>
                                </tr>
                                <tr>
                                    <td class="text-center">5</td>
                                    <td>LO Pressure</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">0,1-0,4 (MPa)</td>
                                    <td class="text-center">0.1</td>
                                    <td class="text-center">0.4</td>
                                    <td class="text-center">Mpa</td>
                                    <td class="text-center">0.3</td>
                                </tr>
                                <tr>
                                    <td class="text-center">6</td>
                                    <td>LO Temp. (In)</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">36-40 (C)</td>
                                    <td class="text-center">36</td>
                                    <td class="text-center">40</td>
                                    <td class="text-center">C</td>
                                    <td class="text-center">39</td>
                                </tr>
                                <tr>
                                    <td class="text-center">7</td>
                                    <td>LO Temp. (Out)</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">40-80 (C)</td>
                                    <td class="text-center">40</td>
                                    <td class="text-center">80</td>
                                    <td class="text-center">C</td>
                                    <td class="text-center">46</td>
                                </tr> -->
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