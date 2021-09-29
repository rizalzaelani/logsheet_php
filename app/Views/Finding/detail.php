<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-sm-6">
        <div class="card card-main">
            <div class="card-body">
                <div class="d-flex justify-content-between mt-1 mb-2">
                    <h4><?= $title ?></h4>
                    <h5 class="header-icon">
                        <a href="<?= base_url("Transaction") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                    </h5>
                </div>
                <table class="table mt-2">
                    <tr class="mt-1">
                        <th>Asset</th>
                        <td>: <?= $findingData['assetName'] ?></td>
                    </tr>
                    <tr class="mt-1">
                        <th>Asset Number</th>
                        <td>: <?= $findingData['assetNumber'] ?></td>
                    </tr>
                    <tr class="mt-1">
                        <th>Tag</th>
                        <td>:
                            <?php
                            if ($findingData['tagName'] != '-') {
                                $assetTagValue = (array_values(array_unique(explode(",", $findingData['tagName']))));
                                $length = count($assetTagValue);
                                for ($i = 0; $i < $length; $i++) { ?>
                                    <span class="badge badge-primary p-1 mt-1" style="font-size: 13px;">
                                        <?= $assetTagValue[$i]; ?>
                                    </span>
                            <?php }
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr class="mt-1">
                        <th>Location</th>
                        <td>:
                            <?php
                            if ($findingData['tagLocationName'] != '-') {
                                $assetTagValue = (array_values(array_unique(explode(",", $findingData['tagLocationName']))));
                                $length = count($assetTagValue);
                                for ($i = 0; $i < $length; $i++) { ?>
                                    <span class="badge badge-primary p-1 mt-1" style="font-size: 13px;">
                                        <?= $assetTagValue[$i]; ?>
                                    </span>
                            <?php }
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Parameter</th>
                        <td>: <?= $findingData['parameterName'] ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>: <?= $findingData['description'] ?></td>
                    </tr>
                    <tr>
                        <th>Min / Abnormal</th>
                        <td>: <?= ($findingData['option'] ?? '') == '' ? $findingData['min'] : $findingData['abnormal'] ?></td>
                    </tr>
                    <tr>
                        <th>Max / Normal</th>
                        <td>: <?= ($findingData['option'] ?? '') == '' ? $findingData['max'] : $findingData['normal'] ?></td>
                    </tr>
                    <tr>
                        <th>Unit Of Measure</th>
                        <td>: <?= $findingData['uom'] ?></td>
                    </tr>
                    <tr>
                        <th>Value</th>
                        <td>: <?= $findingData['value'] ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card card-main">
            <div class="card-body">
                <div class="d-flex justify-content-between mt-1 mb-2">
                    <h4>Form Timeline</h4>
                </div>
                <div>
                    <input type="hidden" name="deviasiId" value="2b958001-69e5-4421-b3c1-7eb0d21e0d8a">
                    <div class="form-group d-none">
                        <span class="irs js-irs-0"><span class="irs"><span class="irs-line" tabindex="0"><span class="irs-line-left"></span><span class="irs-line-mid"></span><span class="irs-line-right"></span></span><span class="irs-min" style="visibility: hidden;">0</span><span class="irs-max" style="visibility: visible;">100</span><span class="irs-from" style="visibility: hidden;">0</span><span class="irs-to" style="visibility: hidden;">0</span><span class="irs-single" style="left: 0.341129%;">1</span></span><span class="irs-grid"></span><span class="irs-bar" style="left: 1.56874%; width: 0.968625%;"></span><span class="irs-bar-edge"></span><span class="irs-shadow shadow-single" style="display: none;"></span><span class="irs-slider single" style="left: 0.968625%;"></span></span><input type="text" class="form-control irs-hidden-input" id="deviasi_progress" name="deviasi_progress" value="" tabindex="-1" readonly="">
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Notes..."></textarea>
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <button class="btn btn-primary" id="btnTimelineUpdate" type="submit"><i class="fa fa-sync"></i> Update</button>
                        <button class="btn btn-outline-primary" id="btnCloseDeviasi" type="button" onclick="" style="margin-left: 10px;"><i class="fa fa-check"></i> Close Finding</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card card-main">
            <div class="card-body">
                <div class="d-flex justify-content-between mt-1 mb-4">
                    <h4>Timeline</h4>
                </div>
                <div class="history-tl-container">
                    <ul class="tl" id="listTimeline">
                        <li class="tl-item dot-danger">
                            <div class="item-detail">13 August 2021 13:56:29 <b><i>admin@gmail.com</i></b></div>
                            <div class="item-title"><b>admin</b> Finding Opened By admin@gmail.com</div>
                        </li>
                        <li class="tl-item dot-success">
                            <div class="item-detail">13 August 2021 13:56:42 <b><i>admin@gmail.com</i></b></div>
                            <div class="item-title"><b>admin</b> test</div>
                        </li>
                    </ul>
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
        setup() {
            const findingData = <?= json_encode($findingData) ?>;
        },
        mounted() {

        },
        methods: {

        }
    })
</script>
<?= $this->endSection(); ?>