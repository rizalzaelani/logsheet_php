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
                <div class="col-md-7">
                    <table class="table mt-2">
                        <tr>
                            <th style="width: 200px;">Asset</th>
                            <td>{{ scheduleTrxData.assetName }}</td>
                        </tr>
                        <tr>
                            <th>Asset Number</th>
                            <td>{{ scheduleTrxData.assetNumber }}</td>
                        </tr>
                        <tr>
                            <th>Tag</th>
                            <td>
                                <template v-if="scheduleTrxData.tagName" v-for="(val, key) in _.uniq(scheduleTrxData.tagName.split(','))">
                                    <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                </template>
                                <template v-else>-</template>
                            </td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>
                                <template v-if="scheduleTrxData.tagLocationName" v-for="(val, key) in _.uniq(scheduleTrxData.tagLocationName.split(','))">
                                    <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                </template>
                                <template v-else>-</template>
                            </td>
                        </tr>
                        <template v-if="scheduleTrxData.descriptionJson">
                            <template v-for="(val, key) in scheduleTrxData.descriptionJson">
                                <tr class="mt-1 descJson">
                                    <th class="text-capitalize">{{val.key.replace(/([A-Z])/g, " $1")}}</th>
                                    <td>{{ val.value ? val.value : '-' }}</td>
                                </tr>
                            </template>
                        </template>
                        <template v-else>
                            <tr>
                                <th>Description</th>
                                <td class="pl-0">{{ scheduleTrxData.description }}</td>
                            </tr>
                        </template>
                    </table>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="p-2 d-flex justify-content-between align-items-center" style="border-radius: .5rem;border: 2px solid #1d6de5;">
                                <h6 class="text-primary text-uppercase font-weight-bold px-2" style="position: absolute;top: -8px;background: #fff;">Scanning</h6>
                                <div class="d-flex justify-content-start">
                                    <img src="<?= base_url("img/icon/qrcode.png") ?>" class="img" style="height: 2.4rem;margin: .3rem" />
                                    <div class="ml-4">
                                        <h4 class="mb-0 text-uppercase">{{ scheduleTrxData.scannedBy }}</h4>
                                        <span class="text-muted font-sm">{{ moment(scheduleTrxData.scannedAt).format("DD MMM YYYY HH:mm") }}</span>
                                    </div>
                                </div>
                                <h6 class="mb-0 mr-2 text-uppercase">{{ scheduleTrxData.scannedWith }}</h6>
                            </div>
                        </div>
                        <div class="col-12 mb-4" v-if="scheduleTrxData.approvedAt">
                            <div class="p-2 d-flex justify-content-between align-items-center" style="border-radius: .5rem;border: 2px solid #1d6de5;">
                                <h6 class="text-primary text-uppercase font-weight-bold px-2" style="position: absolute;top: -8px;background: #fff;">APPROVAL</h6>
                                <div class="d-flex justify-content-start">
                                    <img src="<?= base_url("img/icon/checking.png") ?>" class="img" style="height: 2.4rem;margin: .3rem" />
                                    <div class="ml-4">
                                        <h4 class="mb-0 text-uppercase">{{ scheduleTrxData.approvedBy }}</h4>
                                        <span class="text-muted font-sm">{{ moment(scheduleTrxData.approvedAt).format("DD MMM YYYY HH:mm") }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-4">
                    <hr />
                </div>
                <div class="col-12">
                    <div class="table-responsive table-record-fix-width border-bottom">
                        <table class="table table-responsive-sm table-hover table-bordered table-outline border-bottom" id="detailRecord">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Parameter</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Normal</th>
                                    <th class="text-center">Abnormal</th>
                                    <th class="text-center">UoM</th>
                                    <th class="text-center">Value</th>
                                    <?php if ($scheduleTrxData["approvedAt"] != null) : ?>
                                        <th class="text-center">Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(val, key) in recordParameterData">
                                    <td class="text-center">{{ key+1 }}</td>
                                    <td>{{ val.parameterName }}</td>
                                    <td class="text-center">{{ val.description }}</td>

                                    <td class="text-center">
                                        <span v-if="!val.option" :class="!val.min & !val.max ? 'font-italic' : ''">{{ !val.min & !val.max ? "(Any)" : (!val.min ? ('<' + val.max) : (!val.max ? ('>' + val.min) : (val.min + ' - ' + val.max))) }}</span>
                                        <span v-else :class="!val.normal ? 'font-italic' : ''">{{ !val.normal ? "(Empty)" : val.normal }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span v-if="!val.option" :class="!val.min & !val.max ? 'font-italic' : ''">{{ !val.min & !val.max ? "(Any)" : ((val.min ? ('x < ' + val.min) : '') + '; ' + (val.max ? ('x > ' + val.max) : '')) }}</span>
                                        <span v-else :class="!val.abnormal ? 'font-italic' : ''">{{ !val.abnormal ? "(Empty)" : val.abnormal }}</span>
                                    </td>
                                    <td class="text-center" :class="!val.uom ? 'font-italic' : ''">{{ !val.uom ? "(Empty)" : val.uom }}</td>
                                    <td class="text-center" :class="checkAbnormal(val).class != '' ? 'font-weight-bold text-' + checkAbnormal(val).class : ''">{{ !val.value ? "(Empty)" : val.value }}</td>

                                    <?php if ($scheduleTrxData["approvedAt"] != null) : ?>
                                        <td class="text-center">
                                            <template v-if="isNullEmptyOrUndefined(val.findingId)">
                                                <?php if (checkRoleList("FINDING.OPEN")) { ?>
                                                    <a :href="'<?= base_url() ?>/Finding/issue?trxId=' + val.trxId" class="btn btn-sm" v-if="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class != ''" :class="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class != '' ? 'btn-' + (checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class : ''">{{ checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>').name }}</a>
                                                <?php } else { ?>
                                                    <button class="btn btn-sm" v-if="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class != ''" :class="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class != '' ? 'btn-' + (checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class : ''">{{ checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>').name }}</button>
                                                <?php } ?>
                                            </template>
                                            <template v-else>
                                                <?php if (checkRoleList("FINDING.DETAIL.VIEW")) { ?>
                                                    <a :href="'<?= base_url() ?>/Finding/detail?findingId=' + val.findingId" target="_blank" class="btn btn-sm" v-if="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class != ''" :class="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class != '' ? 'btn-' + (checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class : ''">{{ checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>').name }}</a>
                                                <?php } else { ?>
                                                    <button class="btn btn-sm" v-if="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class != ''" :class="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class != '' ? 'btn-' + (checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class : ''">{{ checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>').name }}</button>
                                                <?php } ?>
                                            </template>
                                        </td>
                                    <?php endif; ?>
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
    let v = Vue.createApp({
        setup() {
            var scheduleTrxData = <?= json_encode($scheduleTrxData); ?>;
            var recordParameterData = <?= json_encode($trxData ?? []) ?>;

            if (IsJsonString(scheduleTrxData?.description)) {
                scheduleTrxData.descriptionJson = JSON.parse(scheduleTrxData.description);
            } else {
                scheduleTrxData.descriptionJson = [];
            }

            return {
                scheduleTrxData,
                recordParameterData,
                isNullEmptyOrUndefined,
                xhrThrowRequest,
                moment,
                checkAbnormal
            }
        }
    }).mount("#app")
</script>
<?= $this->endSection(); ?>