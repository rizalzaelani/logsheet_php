<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main pb-3">
            <div class="d-flex justify-content-between align-items-center mt-1 mb-2">
                <h4 class="mb-0"><?= $title ?></h4>
                <h5 class="header-icon mb-0">
                    <a href="<?= base_url("ReportingAsset") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                </h5>
            </div>

            <div class="row mt-2">
                <div class="col-md-6">
                    <table class="table mt-2">
                        <tr>
                            <th style="width: 200px;">Asset</th>
                            <td>{{ assetData.assetName }}</td>
                        </tr>
                        <tr>
                            <th>Asset Number</th>
                            <td>{{ assetData.assetNumber }}</td>
                        </tr>
                        <tr>
                            <th>Tag</th>
                            <td>
                                <template v-if="assetData.tagName" v-for="(val, key) in _.uniq(assetData.tagName.split(','))">
                                    <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                </template>
                                <template v-else>-</template>
                            </td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>
                                <template v-if="assetData.tagLocationName" v-for="(val, key) in _.uniq(assetData.tagLocationName.split(','))">
                                    <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                </template>
                                <template v-else>-</template>
                            </td>
                        </tr>
                        <template v-if="assetData.descriptionJson">
                            <!-- <tr>
                                <th>Description</th>
                                <td>
                                    <template v-for="(val, key) in assetData.descriptionJson">
                                        {{ CapitalizeEachWords(val.key.replace(/([A-Z])/g, " $1")) + ': ' + val.value + ', ' }}
                                    </template>
                                </td>
                            </tr> -->
                            <template v-for="(val, key) in assetData.descriptionJson">
                                <tr class="mt-1 descJson">
                                    <th class="text-capitalize">{{val.key.replace(/([A-Z])/g, " $1")}}</th>
                                    <td>{{ val.value ? val.value : '-' }}</td>
                                </tr>
                            </template>
                        </template>
                        <template v-else>
                            <tr>
                                <th>Description</th>
                                <td class="pl-0">
                                    <?= $assetData['description']; ?>
                                </td>
                            </tr>
                        </template>
                        <tr>
                            <th>Select Date</th>
                            <td class="d-flex align-items-center">
                                <div class="pl-1" id="daterange" style="cursor: pointer; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6" >
                    <img src="<?= base_url(); ?>/img/logo-act.png" alt="Image" class="img-thumbnail mt-1 m-0">
                </div>
                <div class="col-12 mb-4">
                    <hr />
                </div>
                <div class="col-12">
                    <div class="table-responsive table-fix-width">
                        <table class="table table-responsive-sm table-hover table-bordered table-outline border-bottom" id="detailReport">
                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Parameter</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Description</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Min / Abnormal</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Max / Normal</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">UoM / Option</th>
                                    <template v-for="(val, key) in scheduleGroupData">
                                        <template v-if="checkTrxBySch(val[0].scheduleTrxId).length > 0">
                                            <th style="width: 115px;" class="text-center"><a :href="'<?= base_url("Transaction/detail") ?>?scheduleTrxId=' + val[0].scheduleTrxId" target="_blank">{{ val[0].schType == "Monthly" ? moment(val[0].scheduleFrom).format('MMMM YYYY') : (val[0].schType == "Weekly" ? "Week " + moment(val[0].scheduleFrom).week() + " " + moment(val[0].scheduleFrom).format('YYYY') : moment(val[0].scheduleFrom).format('DD-MM-YYYY') ) }}</a></th>
                                        </template>
                                        <template v-else>
                                            <th style="width: 115px;" class="text-center"><a href="javascript:void(0)" @click="alertSwal('error','This Equipment is ' + (val[0].approvedAt != null ? 'Not Approved' : (val[0].assetStatusName == 'Repair' ? val[0].assetStatusName : val[0].assetStatusName + ' but Not Scanned')))">{{ val[0].schType == "Monthly" ? moment(val[0].scheduleFrom).format('MMMM YYYY') : (val[0].schType == "Weekly" ? "Week " + moment(val[0].scheduleFrom).week() + " " + moment(val[0].scheduleFrom).format('YYYY') : moment(val[0].scheduleFrom).format('DD-MM-YYYY') ) }}</a></th>
                                        </template>
                                    </template>
                                </tr>
                                <tr>
                                    <template v-for="(valGS, keyGS) in scheduleGroupData">
                                        <template v-for="(val, key) in valGS">
                                            <template v-if="checkTrxBySch(val.scheduleTrxId).length > 0">
                                                <th style="width: 115px;" class="text-center"><a :href="'<?= base_url("Transaction/detail") ?>?scheduleTrxId=' + val.scheduleTrxId" target="_blank">{{ ( val.schType == "Daily" ? moment(val.scheduleFrom).format("HH:mm") : keyGS) }}</a></th>
                                            </template>
                                            <template v-else>
                                                <th style="width: 115px;" class="text-center"><a href="javascript:void(0)" @click="alertSwal('error','This Equipment is ' + (val.approvedAt != null ? 'Not Approved' : (val.assetStatusName == 'Repair' ? val.assetStatusName : val.assetStatusName + ' but Not Scanned')))">{{ ( val.schType == "Daily" ? moment(val.scheduleFrom).format("HH:mm") : keyGS) }}</a></th>
                                            </template>
                                        </template>
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(valGP, keyGP, iGP) in parameterGroupData">
                                    <template v-for="(val, key) in valGP">
                                        <template v-if="key == 0 & keyGP != val.parameterName">
                                            <tr>
                                                <td :rowspan="valGP.length + 1" class="text-center">{{ iGP+1 }}</td>
                                                <th :colspan="7 + scheduleData.length">{{ keyGP.replace(/#$/, "") }}</th>
                                            </tr>
                                        </template>
                                        <tr>
                                            <template v-if="key == 0 & keyGP == val.parameterName">
                                                <td class="text-center">{{ iGP + 1 }}</td>
                                            </template>
                                            <td>{{ (val.parameterName.includes("#") ? val.parameterName.replace(keyGP, "") : val.parameterName) }}</td>
                                            <td class="text-center">{{ val.description }}</td>

                                            <template v-if="!val.option">
                                                <td class="text-center">{{ !val.min ? "(Empty)" : val.min }}</td>
                                                <td class="text-center" v-if="!val.option" :class="!val.max ? 'font-italic' : ''">{{ !val.max ? "(Empty)" : val.max }}</td>
                                                <td class="text-center" v-if="!val.option" :class="!val.uom ? 'font-italic' : ''">{{ !val.uom ? "(Empty)" : val.uom }}</td>
                                            </template>
                                            <template v-else>
                                                <td :class="!val.abnormal ? 'font-italic text-center' : ''">{{ !val.abnormal ? "(Empty)" : val.abnormal }}</td>
                                                <td :class="!val.normal ? 'font-italic text-center' : ''">{{ !val.normal ? "(Empty)" : val.normal }}</td>
                                                <td :class="!val.option ? 'font-italic text-center' : ''">{{ !val.option ? "(Empty)" : val.option }}</td>
                                            </template>

                                            <template v-for="(valGS, keyGS) in scheduleGroupData">
                                                <template v-for="(valS, keyS) in valGS">
                                                    <template v-if="checkTrxBySch(valS.scheduleTrxId).length > 0">
                                                        <td :class="'text-center sch_' + valS.scheduleTrxId + ' ' + checkAbnormal((_.filter(checkTrxBySch(valS.scheduleTrxId), { 'parameterId': val.parameterId })[0]), valS.approvedAt).class" :set="filtTrxParam = _.filter(checkTrxBySch(valS.scheduleTrxId), { 'parameterId': val.parameterId })">
                                                            {{ filtTrxParam ? filtTrxParam[0].value : '(Empty)' }}
                                                        </td>
                                                    </template>
                                                    <template v-else>
                                                        <template v-if="valS.assetStatusName == 'Running'">
                                                            <td title="Not Scanned" class="text-center" style="background-color: #c2cfd6;color: #f86c6b;"><i>(RUN)</i></td>
                                                        </template>
                                                        <template v-else>
                                                            <td class="text-center" style="background-color: #c2cfd6;"><i>{{ (valS.assetStatusName == "Standby" ? "SB" : (valS.assetStatusName == "Repair" ? "RP" : "RUN")) }}</i></td>
                                                        </template>
                                                    </template>
                                                </template>
                                            </template>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 mt-3" style="width: 100%;">
                    <button class="btn btn-success" style="width: 100%;" @click="tableToExcel('detailReport', assetData.assetName + ' - ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'))"><i class="fa fa-file-excel"></i> Export To Excel</button>
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
            const start = moment("<?= $dateFrom ?>");
            const end = moment("<?= $dateTo ?>");

            const assetData = <?= json_encode($assetData) ?>;
            const parameterData = <?= json_encode($parameterData) ?>;
            const scheduleData = <?= json_encode($scheduleData) ?>;
            const trxData = <?= json_encode($trxData) ?>;
            const scheduleGroupData = _.chain(scheduleData).sortBy("scheduleFrom").groupBy((v, k) => {
                return moment(v.scheduleFrom).format("DD-MM-YYYY")
            }).value()
            const parameterGroupData = _.groupBy(parameterData, function(val) {
                return val.parameterName.includes("#") ? val.parameterName.split("#")[0] + "#" : val.parameterName;
            });

            if (IsJsonString(assetData?.description)) {
                assetData.descriptionJson = JSON.parse(assetData.description);
            } else {
                assetData.descriptionJson = [];
            }

            const cb = (startIn, endIn) => {
                $('#daterange span').html(startIn.format('D MMM YYYY') + ' - ' + endIn.format('D MMM YYYY'));
                $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                    window.location.href = "<?= base_url("ReportingAsset/detail") ?>?assetId=" + assetData.assetId + "&dateFrom=" + startIn.format("YYYY-MM-DD") + "&dateTo=" + endIn.format("YYYY-MM-DD");
                });
            }

            const checkTrxBySch = (scheduleTrxId) => {
                return _.filter(trxData, {
                    'scheduleTrxId': scheduleTrxId
                });
            }

            const alertSwal = (type, message) => {
                Swal.fire({
                    title: message,
                    icon: (type == "" || type == null ? "info" : type),
                });
            }

            const checkRecordParam = (shcId, paramId) => {
                return _.filter(trxData, {
                    'scheduleTrxId': schId,
                    'parameterId': paramId
                });
            }

            const checkAbnormal = (val, approvedAt) => {
                if (!isNullEmptyOrUndefined(approvedAt)) {
                    if (val.condition != 'Normal' & val.condition != '' & val.condition != null & val.condition != undefined) {
                        if (val.findingId == null) {
                            return {
                                'class': 'btn-danger',
                                'name': 'Follow Up'
                            };
                        } else {
                            if (val.condition == 'Closed') {
                                return {
                                    'class': 'btn-primary',
                                    'name': 'Is Closed'
                                };
                            } else if (val.condition == 'Open') {
                                return {
                                    'class': 'btn-warning',
                                    'name': 'Is Followed Up'
                                };
                            } else {
                                return {
                                    'class': 'btn-danger',
                                    'name': 'Follow Up'
                                };
                            }
                        }
                    } else {
                        return {
                            'class': '',
                            'name': 'Normal'
                        };
                    }
                } else {
                    if (val.inputType == "input") {
                        if ((val.min != null & val.max != null & val.value >= val.min & val.value <= val.max) || (val.min != null & val.max == null & val.value >= val.min) || (val.min == null & val.max != null & val.value <= val.max) || (val.min == null & val.max == null) || isNaN(val.value)) {
                            return {
                                'class': '',
                                'name': 'Normal'
                            };
                        } else {
                            return {
                                'class': 'btn-danger',
                                'name': 'Follow Up'
                            };
                        }
                    } else if (val.inputType == "select") {
                        let itmAbnormal = val.abnormal.toLowerCase().split(",");
                        let isContain = _.includes(itmAbnormal, val.value.toLowerCase());
                        if (isNullEmptyOrUndefined(val.abnormal) || isNullEmptyOrUndefined(val.option) || isContain == false) {
                            return {
                                'class': '',
                                'name': 'Normal'
                            };
                        } else {
                            return {
                                'class': 'btn-danger',
                                'name': 'Follow Up'
                            };
                        }
                    } else {
                        return {
                            'class': '',
                            'name': 'Normal'
                        };
                    }
                }
            }

            Vue.onMounted(() => {
                $('#daterange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, cb);

                cb(start, end);
            });

            return {
                start,
                end,
                assetData,
                parameterData,
                parameterGroupData,
                scheduleData,
                scheduleGroupData,
                trxData,
                checkTrxBySch,
                alertSwal,
                checkAbnormal,
                moment,
                tableToExcel,
                CapitalizeEachWords
            };
        }
    }).mount("#app")
</script>

<?= $this->endSection(); ?>