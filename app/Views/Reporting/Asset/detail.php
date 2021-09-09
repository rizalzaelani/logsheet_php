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
                                    <?php
                                    foreach ($groupSch as $key => $value) {
                                        $length = count($value);
                                        if ($length > 1) { ?>
                                            <th class="text-center" colspan="<?= $length; ?>">
                                                <?= $key; ?>
                                            </th>
                                        <?php } else if ($length <= 1) { ?>
                                            <th class="text-center"><?= $key; ?></th>
                                    <?php }
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <?php foreach ($dataSchedule as $key) { ?>
                                        <th class="text-center"><?= date('h:i:s', strtotime($key->scheduleFrom)); ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($dataParameter as $items) : ?>
                                    <tr>
                                        <td class="text-center"><?= $i++; ?></td>
                                        <td><?= $items->parameter; ?></td>
                                        <td class="text-center"><?= (($items->tagNo) == '' ? '' : $items->tagNo) ?></td>
                                        <td class="text-center"><i><?= (($items->min == '' ? '(empty)' : $items->min)) ?></i></td>
                                        <td class="text-center"><i><?= (($items->max == '' ? '(empty)' : $items->max)) ?></i></td>
                                        <td class="text-center"><?= $items->satuan ?></td>
                                        <?php
                                        $lengthSchedule = count($dataSchedule);
                                        foreach ($dataSchedule as $rowSch) :
                                            $filtRec = array_values(array_filter($dataRecord, function ($val) use ($rowSch, $items) {
                                                return $val->scheduleHistoryId == $rowSch->scheduleHistoryId && $val->parameterId == $items->parameterId;
                                            }));
                                            if (!empty($filtRec)) {
                                                echo "<td class='text-center'>" . $filtRec[0]->value . "</td>";
                                            } else {
                                                echo "<td class='text-center text-danger'><i>(RUN)</i></td>";
                                            }
                                        endforeach;
                                        ?>
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
    moment().format();
    var now = moment(Date(1628614800000));
    // console.log(now);
    let v = new Vue({
        el: '#app',
        data: () => ({
            data: null,
            date: now
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

    $.ajax({
        url: '<?= base_url('ReportingAsset/tableDetail'); ?>',
        type: 'POST',
        data: {},
        success: {
            if (res) {
                console.log(res);
            }
        }
    });
</script>
<?= $this->endSection(); ?>