<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->

<style>
    .modal-xl {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
        padding: 0.5rem !important;
    }

    @media (min-width: 992px) {

        .modal-xl,
        .modal-xl .modal-dialog {
            max-width: 100% !important;
        }
    }

    @media (min-width: 576px) {

        .modal-xl,
        .modal-xl .modal-dialog {
            max-width: 100% !important;
        }
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main pb-3">
            <div class="d-flex justify-content-between align-items-center mt-1 mb-2">
                <h4 class="mb-0">Asset Detail</h4>
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
                <div class="col-md-6">
                    <img src="<?= base_url(); ?>/img/logo-act.png" alt="Image" class="img-thumbnail mt-1 m-0">
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-main py-4">
            <ul class="nav nav-tabs w-100 d-flex flex-row align-items-center" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#reportingTab" role="tab" aria-controls="detail">
                        <h5 class="mb-0">Reporting</h5>
                    </a>
                </li>
                <li class="nav-item" @click="resizeChart()">
                    <a class="nav-link" data-toggle="tab" href="#trxSummaryTab" role="tab" aria-controls="parameter">
                        <h5 class="mb-0">Summary</h5>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane mt-4 active" id="reportingTab" role="tabpanel">
                    <div class="table-responsive table-fix-width my-2">
                        <table class="table table-responsive-sm table-hover table-bordered table-outline border-bottom" id="detailReport">
                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;"><input type="checkbox" name="checkAll" @click="checkAll($event.target.checked)" :checked="parameterIdSelect.length == parameterData.length" /></th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Parameter</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Trend</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Description</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Normal</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">Abnormal</th>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;">UoM</th>
                                    <template v-for="(val, key) in scheduleGroupData">
                                        <template v-if="checkTrxBySch(val[0].scheduleTrxId).length > 0">
                                            <th style="width: 115px;" class="text-center" :colspan="val.length"><a :href="'<?= base_url("Transaction/detail") ?>?scheduleTrxId=' + val[0].scheduleTrxId" target="_blank">{{ val[0].schType == "Monthly" ? moment(val[0].scheduleFrom).format('MMMM YYYY') : (val[0].schType == "Weekly" ? "Week " + moment(val[0].scheduleFrom).week() + " " + moment(val[0].scheduleFrom).format('YYYY') : moment(val[0].scheduleFrom).format('DD-MM-YYYY') ) }}</a></th>
                                        </template>
                                        <template v-else>
                                            <th style="width: 115px;" class="text-center" :colspan="val.length"><a href="javascript:void(0)" @click="alertSwal('error','This Equipment is ' + (val[0].approvedAt != null ? 'Not Approved' : (val[0].assetStatusName == 'Repair' ? val[0].assetStatusName : val[0].assetStatusName + ' but Not Scanned')))">{{ val[0].schType == "Monthly" ? moment(val[0].scheduleFrom).format('MMMM YYYY') : (val[0].schType == "Weekly" ? "Week " + moment(val[0].scheduleFrom).week() + " " + moment(val[0].scheduleFrom).format('YYYY') : moment(val[0].scheduleFrom).format('DD-MM-YYYY') ) }}</a></th>
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
                                            <td class="text-center">
                                                <input type="checkbox" :value="val.parameterId" class="check-param-trend" @change="checkParameterId(val.parameterId)" :checked="parameterIdSelect.includes(val.parameterId)" />
                                            </td>
                                            <td>{{ (val.parameterName.includes("#") ? val.parameterName.replace(keyGP, "") : val.parameterName) }}</td>
                                            <td>
                                                <div class="chart-wrapper cursor-pointer" data-toggle="tooltip" title="Click To Open Trend">
                                                    <div :id="'miniTrend-' + val.parameterId" style="width: 100px;height: 30px;" @click="showTrend(val.parameterId)"></div>
                                                </div>
                                            </td>
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
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button class="btn btn-outline-primary" @click="tableToExcel('detailReport', assetData.assetName + ' - ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'))"><i class="fa fa-file-excel"></i> Export To Excel</button>
                    </div>
                </div>
                <div class="tab-pane mt-4" id="trxSummaryTab" role="tabpanel">
                    <div id="trxSummaryChart" style="height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="btn-fab" aria-label="fab" :class="parameterIdSelect.length > 0 ? '' : 'd-none'">
        <button type="button" class="btn btn-main btn-success has-tooltip" @click="showTrend()" data-toggle="tooltip" data-placement="top" title="Show Trending"><i class="fa fa-chart-line"></i></button>
    </div>

    <div class="modal modal-xl fade" id="modalTrend" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Trending Parameter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div id="trendDateRange" style="cursor: pointer; padding: 5px 10px; border: none; width: 100%">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div id="canvasTrendM" style="height: 400px;" class="chart"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary font-weight-500" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i> Close</button>
                    <!-- <button type="button" class="btn btn-outline-primary font-weight-500" onclick="modalMailTrend()"><i class="fab fa-telegram-plane"></i> Send Mail</button> -->
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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

            let startTrend = start;
            let endTrend = end;

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

            const parameterIdSelect = Vue.reactive([]);

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

            const cbTrend = (startIn, endIn) => {
                startTrend = startIn;
                endTrend = endIn;

                $('#trendDateRange span').html(startIn.format('D MMM YYYY') + ' - ' + endIn.format('D MMM YYYY'));
                $('#trendDateRange').on('apply.daterangepicker', function(ev, picker) {

                    console.log(startTrend, endTrend)

                    showTrend("", startIn, endIn)
                });
            }

            const checkAll = (checked) => {
                if (checked) {
                    parameterIdSelect.splice(0, parameterIdSelect.length);
                    parameterIdSelect.push(...(_.map(parameterData, (val) => val.parameterId)));
                } else {
                    parameterIdSelect.splice(0, parameterIdSelect.length);
                }
            }

            const checkParameterId = (parameterId) => {
                if (parameterIdSelect.includes(parameterId)) {
                    let indexP = parameterIdSelect.indexOf(parameterId);
                    if (indexP >= 0) parameterIdSelect.splice(indexP, 1);
                } else {
                    parameterIdSelect.push(parameterId);
                }
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

            //chart
            var trxSummaryChart;

            const generateTrxSummaryChart = () => {
                let scheduleTrxMap = _.map(scheduleData, (v) => {
                    return {
                        label: moment(v.scheduleFrom).format("DD-MM-YYYY HH:mm"),
                        time: moment(v.scheduleFrom).valueOf(),
                        scanned: (v.scannedAt ? -5 : null),
                        notScanned: (v.scannedAt ? null : -5),
                        approved: (v.scannedAt && v.approvedAt ? -10 : null),
                        notApproved: (v.scannedAt && !v.approvedAt ? -10 : null),
                        accuration: (v.scannedAt ? (isNaN(v.scannedAccuration) ? 0 : parseFloat(v.scannedAccuration)) : null)
                    }
                });

                let sumTrxSchedule = _.reduce(scheduleTrxMap, (arrSum, vr) => {
                    return {
                        scanned: arrSum.scanned + (vr.scanned ? 1 : 0),
                        notScanned: arrSum.notScanned + (vr.notScanned ? 1 : 0),
                        approved: arrSum.approved + (vr.approved ? 1 : 0),
                        notApproved: arrSum.notApproved + (vr.notApproved ? 1 : 0),
                        accuration: arrSum.accuration + (vr.accuration ?? 0)
                    }
                }, {
                    scanned: 0,
                    notScanned: 0,
                    approved: 0,
                    notApproved: 0,
                    accuration: 0
                });

                if (sumTrxSchedule.accuration > 0) {
                    sumTrxSchedule.accuration = sumTrxSchedule.accuration / _.filter(scheduleData, (v) => {
                        return !isNullEmptyOrUndefined(v.scannedAt)
                    });
                }

                scheduleTrxMap = _.sortBy(scheduleTrxMap, (v) => {
                    return moment(v.label, "DD-MM-YYYY").valueOf()
                });

                let optionChart = {
                    title: {
                        text: 'Summary of Transaction ',
                        left: 'center'
                    },
                    grid: {
                        top: '12%',
                        bottom: '15%',
                        right: '7%',
                        left: '7%',
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'cross'
                        },
                        formatter: function(params, ticket, callback) {
                            let cusTooltip = '';
                            for (let i = 0; i < params.length; i++) {
                                if (params[i].seriesName == ("Scanned : " + sumTrxSchedule.scanned)) {
                                    if (params[i].value < 0) {
                                        cusTooltip += `${params[i].marker} Scanned <br />`;
                                    }
                                } else if (params[i].seriesName == "Not Scanned : " + sumTrxSchedule.notScanned) {
                                    if (params[i].value < 0) {
                                        cusTooltip += `${params[i].marker}  Not Scanned <br />`;
                                    }
                                } else if (params[i].seriesName == ("Approved : " + sumTrxSchedule.approved)) {
                                    if (params[i].value < 0) {
                                        cusTooltip += `${params[i].marker} Approved <br />`;
                                    }
                                } else if (params[i].seriesName == "Not Approved : " + sumTrxSchedule.notApproved) {
                                    if (params[i].value < 0) {
                                        cusTooltip += `${params[i].marker}  Not Approved <br />`;
                                    }
                                } else {
                                    cusTooltip += `${params[i].marker}  ${params[i].seriesName} : ${(isNullEmptyOrUndefined(params[i].value) ? '-' : params[i].value)} <br />`;
                                }
                            }
                            return cusTooltip;
                        }
                    },
                    legend: {
                        show: true,
                        top: '5%',
                        type: 'scroll',
                        orient: 'horizontal',
                        data: [{
                                name: 'Accuration',
                            },
                            {
                                name: 'Scanned : ' + sumTrxSchedule.scanned,
                            },
                            {
                                name: 'Not Scanned : ' + sumTrxSchedule.notScanned,
                            },
                            {
                                name: 'Approved : ' + sumTrxSchedule.approved,
                            },
                            {
                                name: 'Not Approved : ' + sumTrxSchedule.notApproved,
                            },
                        ]
                    },
                    toolbox: {
                        show: true,
                        right: '9%',
                        feature: {
                            saveAsImage: {
                                title: "Save As Image"
                            },
                            dataZoom: {},
                            dataView: {
                                title: "View Data",
                                lang: ['Data Chart', 'Back', 'Refresh'],
                                readOnly: true,
                                buttonColor: '#f0f0f0',
                                buttonTextColor: '#2e2e2e',
                            },
                        }
                    },
                    dataZoom: [{
                        type: 'slider',
                        moveHandleSize: 0,
                        show: true,
                        startValue: 0,
                        endValue: 50
                    }],
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: _.map(scheduleTrxMap, (v) => {
                            return v.label
                        }),
                    },
                    yAxis: {
                        type: 'value',
                        min: function(value) {
                            return (0 < value.min ? 0 : value.min);
                        },
                        max: function(value) {
                            return (100 > value.max ? 100 : value.max);
                        },
                        axisLabel: {
                            margin: 20,
                            formatter: function(v) {
                                if (v < 0) {
                                    return '';
                                } else {
                                    return v;
                                }
                            }
                        },
                        axisLine: {
                            show: false
                        },
                        axisPointer: {
                            snap: true
                        }
                    },
                    series: [{
                            name: 'Accuration',
                            itemStyle: {
                                color: '#2f7ed8'
                            },
                            type: 'line',
                            smooth: true,
                            symbolSize: 7,
                            showAllSymbol: true,
                            symbolKeepAspect: true,
                            data: _.map(scheduleTrxMap, (v) => {
                                return v.accuration
                            })
                        },
                        {
                            name: 'Scanned : ' + sumTrxSchedule.scanned,
                            itemStyle: {
                                color: '#00e966'
                            },
                            type: 'line',
                            smooth: true,
                            symbol: 'rect',
                            symbolSize: 7,
                            data: _.map(scheduleTrxMap, (v) => {
                                return v.scanned
                            })
                        },
                        {
                            name: 'Not Scanned : ' + sumTrxSchedule.notScanned,
                            disabled: true,
                            itemStyle: {
                                color: '#ff2504'
                            },
                            type: 'line',
                            smooth: true,
                            symbol: 'rect',
                            symbolSize: 7,
                            showAllSymbol: true,
                            symbolKeepAspect: true,
                            data: _.map(scheduleTrxMap, (v) => {
                                return v.notScanned
                            })
                        },
                        {
                            name: 'Approved : ' + sumTrxSchedule.approved,
                            itemStyle: {
                                color: '#00e966'
                            },
                            type: 'line',
                            smooth: true,
                            symbolSize: 7,
                            data: _.map(scheduleTrxMap, (v) => {
                                return v.approved
                            })
                        },
                        {
                            name: 'Not Approved : ' + sumTrxSchedule.notApproved,
                            disabled: true,
                            itemStyle: {
                                color: '#ff2504'
                            },
                            type: 'line',
                            smooth: true,
                            symbolSize: 7,
                            showAllSymbol: true,
                            symbolKeepAspect: true,
                            data: _.map(scheduleTrxMap, (v) => {
                                return v.notApproved
                            })
                        }
                    ]
                };

                if (optionChart && typeof optionChart === "object") {
                    if (!trxSummaryChart) {
                        trxSummaryChart = echarts.init(document.getElementById("trxSummaryChart"));
                    }
                    trxSummaryChart.setOption(optionChart, true);
                }
            }

            // Trending
            const showTrend = (parameterId, startTrend, endTrend) => {
                if (!parameterId) parameterId = parameterIdSelect.join(",")
                if (parameterId) {
                    parameterIdSelect.splice(0, parameterIdSelect.length);
                    parameterIdSelect.push(parameterId);
                    $('#modalTrend').modal('show');

                    startTrend = startTrend ?? start;
                    endTrend = endTrend ?? end;
                    let res = axios.get("<?= site_url("ReportingAsset/getRecordByParam") ?>?parameterId=" + parameterId + "&dateFrom=" + startTrend.format("YYYY-MM-DD") + "&dateTo=" + endTrend.format("YYYY-MM-DD"))
                        .then(res => {
                            xhrThrowRequest(res)
                                .then(() => {
                                    let dataTrend = res.data.data;

                                    let label = [];
                                    let dataSet = [];

                                    let paramArr = parameterId.split(",").filter((val) => val);
                                    if (paramArr.length > 1) {
                                        paramArr.forEach((vParam) => {
                                            let filtParam = _.filter(parameterData, (val) => val.parameterId == vParam);
                                            if (filtParam.length > 0) {
                                                let filtRecordTrend = _.filter(dataTrend, (val) => val.parameterId == vParam);

                                                let dataLine = [];
                                                _.sortBy(filtRecordTrend, "scheduleFrom").forEach((val, key) => {
                                                    dataLine.push(val.value != null & val.value != '' ? parseFloat(val.value) : null);
                                                });

                                                dataSet.push({
                                                    name: filtParam[0].parameterName,
                                                    lineStyle: {
                                                        width: 3
                                                    },
                                                    type: 'line',
                                                    smooth: true,
                                                    symbolSize: 4,
                                                    showAllSymbol: true,
                                                    symbolKeepAspect: true,
                                                    data: dataLine
                                                });
                                            }
                                        });

                                        let groupTimestamp = _.groupBy(_.sortBy(dataTrend, "scheduleFrom"), function(v) {
                                            return moment(v.scheduleFrom).format("DD-MM-YYYY HH:mm");
                                        });

                                        _.forEach(groupTimestamp, (val, key) => {
                                            label.push(key);
                                        })

                                        setTimeout(() => {
                                            createTrendChart("Multiple Parameter", label, dataSet);
                                        }, 500)
                                    } else if (paramArr.length == 1) {
                                        let filtParam = _.filter(parameterData, (val) => val.parameterId == paramArr[0]);
                                        if (filtParam.length > 0) {
                                            dataSet = [{
                                                name: filtParam[0].parameterName,
                                                lineStyle: {
                                                    color: '#2f7ed8',
                                                    width: 3
                                                },
                                                itemStyle: {
                                                    color: '#2f7ed8'
                                                },
                                                markArea: {
                                                    label: {
                                                        show: false,
                                                    },
                                                    data: [
                                                        [{
                                                            name: 'Deviasi',
                                                            x: '10%',
                                                            yAxis: filtParam[0].max
                                                        }, {
                                                            name: 'Deviasi',
                                                            x: '90%',
                                                            yAxis: filtParam[0].min
                                                        }]
                                                    ]
                                                },
                                                markLine: {
                                                    symbol: 'circle',
                                                    lineStyle: {
                                                        color: '#f00',
                                                        type: 'solid',
                                                        width: 3
                                                    },
                                                    data: []
                                                },
                                                type: 'line',
                                                smooth: true,
                                                symbolSize: 4,
                                                showAllSymbol: true,
                                                symbolKeepAspect: true,
                                                data: []
                                            }];

                                            if (!filtParam[0].min && !filtParam[0].max) {
                                                dataSet[0].markArea = null;
                                                dataSet[0].markLine = null;
                                            } else {
                                                if (filtParam[0].max) {
                                                    dataSet[0].markLine.data.push([{
                                                            name: 'Max : ' + filtParam[0].max,
                                                            x: '10%',
                                                            yAxis: filtParam[0].max
                                                        },
                                                        {
                                                            name: 'Max',
                                                            x: '90%',
                                                            yAxis: filtParam[0].max
                                                        }
                                                    ]);
                                                } else if (filtParam[0].min) {
                                                    dataSet[0].data.push([{
                                                        name: 'Min : ' + filtParam[0].min ?? "-",
                                                        x: '10%',
                                                        yAxis: filtParam[0].min
                                                    }, {
                                                        name: 'Min',
                                                        x: '90%',
                                                        yAxis: filtParam[0].min
                                                    }])
                                                }
                                            }

                                            _.sortBy(dataTrend, "scheduleFrom").forEach((val, key) => {
                                                label.push(moment(val.scheduleFrom).format("DD-MM-YYYY HH:mm"));
                                                if (val.value == null) {
                                                    dataSet[0].data.push(null);
                                                } else {
                                                    dataSet[0].data.push(parseFloat(val.value));
                                                }
                                            });

                                            setTimeout(() => {
                                                createTrendChart(filtParam[0].parameterName, label, dataSet, filtParam[0].min, filtParam[0].max);
                                            }, 500)
                                        }
                                    } else {
                                        Swal.fire({
                                            title: "Warning",
                                            text: "Please select parameter first or click on trend column",
                                            icon: "warning",
                                        });
                                    }
                                })
                                .catch((rej) => {
                                    if (rej.throw) {
                                        throw new Error(rej.message);
                                    }
                                });
                        });
                } else {
                    $('#modalTrend').modal('hide'); // show bootstrap modal\
                    Swal.fire({
                        title: "Warning",
                        text: "Please select parameter first or click on trend column",
                        icon: "warning",
                    });
                }
            }

            const createTrendChart = (title, label, dataSet, min, max) => {
                let optionChart = {
                    title: {
                        text: title,
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'cross'
                        }
                    },
                    legend: {
                        show: false,
                    },
                    toolbox: {
                        right: '9%',
                        feature: {
                            dataZoom: {},
                            saveAsImage: {
                                title: "Save As Image"
                            },
                            dataView: {
                                title: "View Data",
                                lang: ['Data Chart', 'Back', 'Refresh'],
                                readOnly: true,
                                buttonColor: '#f0f0f0',
                                buttonTextColor: '#2e2e2e',
                            },
                        }
                    },
                    dataZoom: [{
                            type: 'slider',
                            show: true,
                            startValue: 0,
                            endValue: 150
                        },
                        {
                            type: 'inside',
                            orient: 'vertical',
                            show: true,
                            zoomOnMouseWheel: true,
                            moveOnMouseMove: true
                        }
                    ],
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: label
                    },
                    yAxis: {
                        type: 'value',
                        axisLabel: {
                            margin: 20,
                            formatter: '{value}'
                        },
                        axisLine: {
                            show: false
                        },
                        axisPointer: {
                            snap: true
                        }
                    },
                    series: dataSet
                };

                if (min) optionChart.yAxis.min = (value) => {
                    return (min < value.min ? min : value.min)
                };
                if (max) optionChart.yAxis.max = (value) => {
                    return (max < value.max ? max : value.max)
                };

                let chartTrend = echarts.init(document.getElementById("canvasTrendM"));
                if (optionChart && typeof optionChart === "object") {
                    chartTrend.setOption(optionChart, true);
                }

                window.addEventListener('resize', function(event) {
                    if (chartTrend) {
                        chartTrend.resize();
                    }
                }, true);
            }

            const showMiniTrend = () => {
                let optionChart = {
                    title: {
                        show: false,
                    },
                    grid: {
                        width: '100px',
                        height: '30px',
                        top: 0,
                        bottom: 0,
                        right: 0,
                        left: 0,
                    },
                    tooltip: {
                        show: false
                    },
                    legend: {
                        show: false
                    },
                    toolbox: {
                        show: false
                    },
                    xAxis: {
                        show: false,
                        type: 'category',
                        boundaryGap: false
                    },
                    yAxis: {
                        show: false,
                        type: 'value',
                        axisLine: {
                            show: false
                        },
                        axisPointer: {
                            snap: false
                        }
                    },
                    series: [{
                        name: 'record',
                        lineStyle: {
                            color: '#2f7ed8',
                            width: 2
                        },
                        itemStyle: {
                            color: '#2f7ed8'
                        },
                        type: 'line',
                        smooth: true,
                        symbolSize: 0,
                        symbol: 'circle',
                        hoverAnimation: false,
                        data: null
                    }]
                };

                parameterData.forEach((vp) => {
                    let dataSet = _.chain(trxData).sortBy('createdAt').filter((val) => val.parameterId == vp.parameterId).map((val) => val.value).value();
                    optionChart.series[0].data = dataSet;
                    optionChart.xAxis.data = dataSet;
                    let chartMiniTrend = echarts.init(document.getElementById("miniTrend-" + vp.parameterId));
                    if (optionChart && typeof optionChart === "object") {
                        chartMiniTrend.setOption(optionChart, true);
                    }
                })
            }

            const resizeChart = () => {
                if (trxSummaryChart) {
                    trxSummaryChart.resize();
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

                $('#trendDateRange').daterangepicker({
                    startDate: startTrend,
                    endDate: endTrend,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, cbTrend);
                $('#trendDateRange span').html(startTrend.format('D MMM YYYY') + ' - ' + endTrend.format('D MMM YYYY'));

                generateTrxSummaryChart();
                showMiniTrend();

                window.addEventListener('resize', function(event) {
                    resizeChart();
                }, true);
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
                checkAll,
                checkParameterId,
                parameterIdSelect,
                checkTrxBySch,
                alertSwal,
                checkAbnormal,
                moment,
                tableToExcel,
                CapitalizeEachWords,
                showTrend,
                resizeChart,
            };
        }
    }).mount("#app")
</script>

<?= $this->endSection(); ?>