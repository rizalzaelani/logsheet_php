<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>

<!-- Custom Style Css -->
<style>
    /* .modal-fullscreen {
        padding: 0 !important;
    } */
    .modal-fs {
        width: 100%;
        max-width: 100%;
        padding: 0;
        margin: 0;
    }

    .modal-fs .modal-content {
        min-height: 100vh;
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div id="app">
    <div class="row">
        <div class="col-12">
            <div class="card card-main mb-2" id="cardSchedule">
                <div class="card-body">
                    <ul class="nav nav-tabs w-100 d-flex flex-row align-items-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#calendarTab" role="tab" aria-controls="calendarTab" aria-selected="true">
                                <h5 class="mb-0">Calendar</h5>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#rawDataTab" role="tab" aria-controls="rawDataTab" aria-selected="false">
                                <h5 class="mb-0">Raw Data</h5>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane mt-4 active" id="calendarTab" role="tabpanel">
                            <div id="calendar"></div>
                        </div>
                        <div class="tab-pane mt-4" id="rawDataTab" role="tabpanel">
                            <div class="dt-search-input" style="top: 4.6rem;">
                                <div class="input-container">
                                    <a href="javascript:void(0)" class="suffix text-decoration-none dt-search-hide"><i class="c-icon cil-x" style="font-size: 1.5rem;"></i></a>
                                    <input name="dt-search" class="material-input" type="text" data-target="#tableRawData" placeholder="Search Data Transaction">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <div class="">
                                    <?php if (checkRoleList("SCHEDULE.ADD")) : ?>
                                        <button type="button" class="btn btn-primary mr-2" @click="showModalAM()"><i class="fa fa-plus"></i> Add</button>
                                    <?php endif; ?>
                                    <?php if (checkRoleList("SCHEDULE.IMPORT")) : ?>
                                        <button type="button" class="btn btn-success mr-2" @click="showModalIS()"><i class="fa fa-upload"></i> Import</button>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="btn btn-outline-primary dt-search" data-target="#tableRawData"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover w-100" id="tableRawData">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Asset</th>
                                            <th>Number</th>
                                            <th>Tag</th>
                                            <th>Location</th>
                                            <th>Schedule</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (checkRoleList("SCHEDULE.ADD,SCHEDULE.IMPORT")) : ?>
        <!-- Modal -->
        <div class="modal fade" id="modalAddSchManual" tabindex="-1" role="dialog" aria-labelledby="modalAddSchManualLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-fs" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddSchManualLabel">Asset Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" :class="formAM == 1 ? 'd-none' : ''" id="formAM1">
                            <div class="col-md-8">
                                <div class="dt-search-input" style="top: -0.7rem;width: calc(100% - 1rem)">
                                    <div class="input-container">
                                        <a href="javascript:void(0)" class="suffix text-decoration-none dt-search-hide"><i class="c-icon cil-x" style="font-size: 1.5rem;"></i></a>
                                        <input name="dt-search" class="material-input" type="text" data-target="#tableAssetManual" placeholder="Search Data Transaction">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">{{ assetIdAM.length + ' Asset Selected' }}</h5>
                                    <!-- <button type="button" class="btn btn-outline-primary"><i class="fa fa-arrow-right"></i> Next</button> -->
                                    <button type="button" class="btn btn-outline-primary dt-search" data-target="#tableAssetManual"><i class="fa fa-search"></i></button>
                                </div>
                                <div class="table table-responsive mb-0">
                                    <table class="table table-striped w-100" id="tableAssetManual">
                                        <thead>
                                            <tr>
                                                <th style="width: 20px;">#</th>
                                                <th>Asset</th>
                                                <th>Number</th>
                                                <th>Tag</th>
                                                <th>Location</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5 class="mb-3 text-center" id="labelScheduleAMDR">{{ moment().format("DD MMM YYYY") + ' to ' + moment().format("DD MMM YYYY") }}</h5>
                                <div class="form-group text-center">
                                    <input type="text" id="scheduleAMDR" class="form-control d-none" />
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary" @click="nextFormAM()"><i class="fa fa-arrow-right"></i> Next</button>
                                </div>
                            </div>
                        </div>
                        <div class="row" :class="formAM != 1 ? 'd-none' : ''" id="formAM2">
                            <div class="col-12">
                                <div class="d-flex justify-content-between mb-3">
                                    <button type="button" class="btn btn-primary" @click="formAM = 0"><i class="fa fa-arrow-left"></i> Prev</button>
                                    <button type="button" class="btn btn-primary" @click="saveAddAM()"><i class="fab fa-telegram-plane"></i> Save</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered w-100">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="vertical-align: middle">Asset</th>
                                                <template v-for="(val, key) in dateRangeSchAM()">
                                                    <th class="text-center" :colspan="val.length">{{ key }}</th>
                                                </template>
                                            </tr>
                                            <tr>
                                                <template v-for="(val, key) in dateRangeSchAM()">
                                                    <template v-for="(v, k) in val">
                                                        <th class="text-center">{{ v.day }}</th>
                                                    </template>
                                                </template>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(va, ka) in getDataCKAM()">
                                                <td>{{ va.assetName }}</td>
                                                <template v-for="(val, key) in dateRangeSchAM()">
                                                    <template v-for="(v, k) in val">
                                                        <td class="text-center" :class="_.filter(adviceDateAM, (vf) => vf.assetId == va.assetId && vf.date == v.date).length > 0 ? 'bg-success' : ''" @click="addAdviceDate(va.assetId, v.date)"></td>
                                                    </template>
                                                </template>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-none">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="saveAddAM()"><i class="fa fa-check"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (checkRoleList("SCHEDULE.IMPORT")) : ?>
        <div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalImportLabel">Import Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" class="filepond" name="importSch" id="importSch">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success"><i class="far fa-file-excel"></i> Template</button>
                        <!-- <button type="button" class="btn btn-primary"><i class="fab fa-telegram-plane"></i> Submit</button> -->
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <div class="modal fade" id="modalDetailAssetList" tabindex="-1" role="dialog" aria-labelledby="modalDetailAssetListLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailAssetListLabel">{{ assetListEventTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover w-100">
                            <thead class="bg-primary">
                                <tr>
                                    <th>Asset</th>
                                    <th>Number</th>
                                    <th class="text-center">Tag</th>
                                    <th class="text-center">Location</th>
                                    <!-- <th class="text-center">Schedule</th> -->
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<script>
    const {
        ref,
        reactive,
        onMounted
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            const assetManualData = <?= json_encode($assetManualData) ?>;
            var scheduleData = reactive([]);
            var calendar;
            var table;
            var tableAM;
            var tableAE;
            var assetIdAM = reactive([]);
            var adviceDateAM = reactive([]);
            var pickerAddAM;
            var formAM = ref(0);
            var startSAM = ref(null);
            var endSAM = ref(null);

            var assetListEvent = reactive([]);
            var assetListEventTitle = ref("");

            let importSchFP;

            const rawDataDT = () => {
                table = $("#tableRawData").DataTable({
                    drawCallback: function(settings) {
                        $(document).ready(function() {
                            $('[data-toggle="tooltip"]').tooltip();
                        })
                    },
                    data: scheduleData,
                    processing: true,
                    // serverSide: false,
                    // scrollY: "calc(100vh - 272px)",
                    // responsive: true,
                    language: {
                        processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
                        lengthMenu: "Showing _MENU_ ",
                        info: "of _MAX_ entries",
                        infoEmpty: 'of 0 entries',
                    },
                    dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
                    columns: [{
                            data: "assetNumber",
                            name: "assetNumber",
                        },
                        {
                            data: "assetName",
                            name: "assetName",
                        },
                        {
                            data: "tagName",
                            name: "tagName",
                        },
                        {
                            data: "tagLocationName",
                            name: "tagLocationName",
                        },
                        {
                            data: "scheduleFrom",
                            name: "scheduleFrom",
                        },
                    ],
                    order: [0, 'asc'],
                    columnDefs: [{
                            targets: "_all",
                            className: "dt-head-center",
                        },
                        {
                            targets: [2, 3],
                            render: function(data) {
                                if (data != '-') {
                                    // unique = Array.from(new Set(data));
                                    var dt = Array.from(new Set(data.split(',')));
                                    var list_dt = '';
                                    $.each(dt, function(key, value) {
                                        list_dt += '<span class="badge badge-dark mr-1 mb-1" style="font-size: 13px; padding: 5px !important;">' + value + '</span>';
                                    })
                                    return list_dt;
                                } else {
                                    return data;
                                }
                            }
                        },
                        {
                            targets: -1,
                            render: function(data, type, row) {
                                return moment(row.scheduleFrom).format("DD MMM YYYY") + " - " + moment(row.scheduleTo).format("DD MMM YYYY")
                            }
                        }
                    ],
                    'createdRow': function(row, data) {
                        // row.setAttribute("data-id", data.scheduleTrxId);
                        // row.classList.add("cursor-pointer");
                        // row.setAttribute("data-toggle", "tooltip");
                        // row.setAttribute("data-html", "true");
                        // row.setAttribute("title", "<div>Click to go to asset detail</div>");
                    },
                });
            }

            const assetEventDT = () => {
                tableAE = $("#modalDetailAssetList table").DataTable({
                    data: assetListEvent,
                    processing: true,
                    columns: [{
                            data: "assetNumber",
                            name: "assetNumber",
                        },
                        {
                            data: "assetName",
                            name: "assetName",
                        },
                        {
                            data: "tagName",
                            name: "tagName",
                        },
                        {
                            data: "tagLocationName",
                            name: "tagLocationName",
                        },
                    ],
                    order: [0, 'asc'],
                    columnDefs: [{
                            targets: "_all",
                            className: "dt-head-center",
                        },
                        {
                            targets: [2, 3],
                            render: function(data) {
                                if (data != '-') {
                                    // unique = Array.from(new Set(data));
                                    var dt = Array.from(new Set(data.split(',')));
                                    var list_dt = '';
                                    $.each(dt, function(key, value) {
                                        list_dt += '<span class="badge badge-dark mr-1 mb-1" style="font-size: 13px; padding: 5px !important;">' + value + '</span>';
                                    })
                                    return list_dt;
                                } else {
                                    return data;
                                }
                            }
                        }
                    ],
                });
            }

            const refreshEvent = () => {
                let listEvent = calendar.getEvents();
                listEvent.forEach(event => {
                    event.remove()
                });

                scheduleData.forEach((v, k) => {
                    calendar.addEvent({
                        title: v.assetName,
                        start: moment(v.scheduleFrom).valueOf(),
                        end: moment(v.scheduleTo).valueOf(),
                        allDay: false
                    });
                });
            }

            <?php if (checkRoleList("SCHEDULE.ADD,SCHEDULE.IMPORT")) : ?>

                const assetManualDT = () => {
                    tableAM = $("#tableAssetManual").DataTable({
                        drawCallback: function(settings) {
                            $(document).ready(function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            })
                        },
                        data: assetManualData,
                        processing: true,
                        // serverSide: false,
                        // scrollY: "calc(100vh - 310px)",
                        responsive: true,
                        language: {
                            processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
                            lengthMenu: "Showing _MENU_ ",
                            info: "of _MAX_ entries",
                            infoEmpty: 'of 0 entries',
                        },
                        dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
                        columns: [{
                                data: "assetId",
                                name: "assetId"
                            },
                            {
                                data: "assetNumber",
                                name: "assetNumber",
                            },
                            {
                                data: "assetName",
                                name: "assetName",
                            },
                            {
                                data: "tagName",
                                name: "tagName",
                            },
                            {
                                data: "tagLocationName",
                                name: "tagLocationName",
                            },
                        ],
                        order: [1, 'asc'],
                        columnDefs: [{
                                targets: "_all",
                                className: "dt-head-center"
                            },
                            {
                                targets: 0,
                                orderable: false,
                                render: function(data) {
                                    return `<input type="checkbox" name="ckAssetManual[]" value="${data}" onchange="v.changeCKAM(event)" ${assetIdAM.includes(data) ? 'checked' : ''} />`;
                                },
                            },
                            {
                                targets: [3, 4],
                                render: function(data) {
                                    if (data != '-') {
                                        // unique = Array.from(new Set(data));
                                        var dt = Array.from(new Set(data.split(',')));
                                        var list_dt = '';
                                        $.each(dt, function(key, value) {
                                            list_dt += '<span class="badge badge-dark mr-1 mb-1" style="font-size: 13px; padding: 5px !important;">' + value + '</span>';
                                        })
                                        return list_dt;
                                    } else {
                                        return data;
                                    }
                                }
                            }
                        ],
                    });
                };

                const showModalAM = () => {
                    $('#tableAssetManual').dataTable().fnClearTable();
                    $('#tableAssetManual').dataTable().fnAddData(assetManualData);
                    $("#modalAddSchManual").modal("show");
                }
            <?php endif; ?>

            <?php if (checkRoleList("SCHEDULE.IMPORT")) : ?>
                const showModalIS = () => {
                    $("#modalImport").modal("show");
                }
            <?php endif; ?>

            <?php if (checkRoleList("SCHEDULE.ADD,SCHEDULE.IMPORT")) : ?>
                const changeCKAM = (ev) => {
                    let valAM = ev.target.value;
                    if (ev.target.checked) {
                        assetIdAM.push(valAM);
                    } else {
                        let inVOf = assetIdAM.findIndex((v) => v == valAM);
                        if (inVOf != undefined) {
                            assetIdAM.splice(inVOf, 1);
                        }
                    }
                    let assetIdAMTemp = _.uniqBy(assetIdAM);
                    assetIdAM.splice(0, assetIdAM.length);
                    assetIdAM.push(...assetIdAMTemp);
                }

                const getDataCKAM = () => {
                    return _.map(assetIdAM, (v) => {
                        let a = _.filter(assetManualData, (vf) => vf.assetId == v);
                        if (a.length > 0) {
                            return a[0];
                        }
                    })
                }

                const dateRangeSchAM = () => {
                    let startDR = moment(startSAM.value, "YYYY-MM-DD");
                    let endDR = moment(endSAM.value, "YYYY-MM-DD");

                    let duration = moment.duration(endDR.diff(startDR));
                    let diffDay = Math.round(duration.asDays());

                    let dataDate = [];
                    for (let i = 0; i <= diffDay; i++) {
                        let dateTemp = moment(startSAM.value, "YYYY-MM-DD").add("days", i);
                        dataDate.push({
                            "MY": dateTemp.format("MMM YYYY"),
                            "date": dateTemp.format("YYYY-MM-DD"),
                            "day": dateTemp.format("DD")
                        })
                    }

                    return _.groupBy(dataDate, "MY");
                }

                const nextFormAM = () => {
                    if (assetIdAM.length > 0 && pickerAddAM.getStartDate() && pickerAddAM.getEndDate()) {
                        formAM.value = 1;
                    } else {
                        Swal.fire({
                            title: "Data Is Not Valid",
                            text: "Please Select Asset and Date First",
                            icon: "warning"
                        })
                    }
                }

                const saveAddAM = () => {
                    let dataAssetAM = [];
                    assetIdAM.forEach((assetId) => {
                        let getAsset = _.filter(assetManualData, (v) => v.assetId == assetId);
                        if (getAsset.length > 0) {
                            let ck = _.filter(adviceDateAM, (v) => v.assetId == assetId);
                            if (ck.length > 0) {
                                dataAssetAM.push({
                                    "assetId": assetId,
                                    "assetStatusId": getAsset[0].assetStatusId,
                                    "adviceDate": ck[0].date
                                });
                            } else {
                                dataAssetAM.push({
                                    "assetId": assetId,
                                    "assetStatusId": getAsset[0].assetStatusId,
                                    "adviceDate": ""
                                });
                            }
                        }
                    });

                    if (dataAssetAM.length > 0 && pickerAddAM.getStartDate() && pickerAddAM.getEndDate()) {
                        let response = axios.post("<?= base_url() ?>/Schedule/addScheduleAM", {
                            "dataAssetAM": JSON.stringify(dataAssetAM),
                            "start": pickerAddAM.getStartDate().format("YYYY-MM-DD"),
                            "end": pickerAddAM.getEndDate().format("YYYY-MM-DD")
                        }).then((res) => {
                            xhrThrowRequest(res)
                                .then(() => {
                                    Swal.fire({
                                        title: res.data.message,
                                        icon: "success",
                                        timer: 3000
                                    }).then(() => {
                                        scheduleData.push(...res.data.data);

                                        formAM.value = 0;
                                        adviceDateAM.splice(0, adviceDateAM.length);
                                        assetIdAM.splice(0, assetIdAM.length);

                                        $("#modalAddSchManual").modal("hide");
                                        $('#tableAssetManual').dataTable().fnClearTable();
                                        $('#tableAssetManual').dataTable().fnAddData(assetManualData);

                                        let dtClndr = _.chain(scheduleData)
                                            .groupBy((v) => (moment(v.scheduleFrom).format("DD MMM YYYY") + ' to ' + moment(v.scheduleTo).format("DD MMM YYYY")))
                                            .map((v, k) => {
                                                return {
                                                    title: v.length + ' Asset (' + k + ')',
                                                    start: moment(v[0].scheduleFrom).valueOf(),
                                                    end: moment(v[0].scheduleTo).valueOf(),
                                                    allDay: false,
                                                    data: v
                                                }
                                            }).value();

                                        calendar.removeAllEvents();
                                        dtClndr.forEach((v, k) => {
                                            calendar.addEvent({
                                                title: v.title,
                                                start: v.start,
                                                end: v.end,
                                                allDay: false,
                                                data: v.data
                                            })
                                        });

                                        setTimeout(() => {
                                            if ($.fn.DataTable.isDataTable('#tableRawData'))
                                                table.clear().rows.add(scheduleData).draw();
                                            else
                                                rawDataDT();
                                        }, 10);
                                    });
                                })
                                .catch((rej) => {
                                    if (rej.throw) {
                                        throw new Error(rej.message);
                                    }
                                    $('#slideApprove').removeClass('unlocked');
                                    $('#slideApprove').html(`<i class="fa fa-check font-xl"></i>`);
                                });
                        });
                    } else {
                        Swal.fire({
                            title: "Data Is Not Valid",
                            text: "Please Select Asset and Date First",
                            icon: "warning"
                        })
                    }
                }

                const addAdviceDate = (assetId, date) => {
                    if (assetId && date) {
                        let cekAD = _.filter(adviceDateAM, (val) => val.assetId == assetId);
                        if (cekAD.length < 1) {
                            adviceDateAM.push({
                                'assetId': assetId,
                                'date': date
                            });
                        } else {
                            let itemIndex = adviceDateAM.findIndex(i => i.assetId == assetId);
                            if (itemIndex != undefined) {
                                if (cekAD[0].date == date) {
                                    adviceDateAM.splice(itemIndex, 1);
                                } else {
                                    adviceDateAM[itemIndex].date = date;
                                }
                            }
                        }
                    }
                };
            <?php endif; ?>


            <?php if (checkRoleList("SCHEDULE.IMPORT")) : ?>
                const filepondOpt = {
                    acceptedFileTypes: '.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel',
                    allowMultiple: false,
                    credits: false,
                    styleLoadIndicatorPosition: 'center bottom',
                    styleProgressIndicatorPosition: 'right bottom',
                    styleButtonRemoveItemPosition: 'left bottom',
                    styleButtonProcessItemPosition: 'right bottom',
                };
            <?php endif; ?>

            onMounted(() => {
                document.addEventListener('DOMContentLoaded', function() {
                    var calendarEl = document.getElementById('calendar');
                    calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        dateClick: function(a) {
                            // console.log(a);
                        },
                        events: function(info, successCallback, failureCallback) {
                            let dateFC = moment(info.endStr).add('month', -1);
                            let checkMY = _.filter(scheduleData, (v) => {
                                return moment(v.scheduleFrom).format("MM-YYYY") == dateFC.format("MM-YYYY")
                            });

                            if (checkMY.length < 1) {
                                axios.get(
                                    "<?= base_url() ?>/Schedule/getDataByMonth?month=" + dateFC.format("M") + "&year=" + dateFC.format("YYYY")
                                ).then((res) => {
                                    xhrThrowRequest(res)
                                        .then(() => {
                                            scheduleData.push(...res.data.data);

                                            let dtClndr = _.chain(scheduleData)
                                                .groupBy((v) => (moment(v.scheduleFrom).format("DD MMM YYYY") + ' to ' + moment(v.scheduleTo).format("DD MMM YYYY")))
                                                .map((v, k) => {
                                                    return {
                                                        title: v.length + ' Asset (' + k + ')',
                                                        start: moment(v[0].scheduleFrom).valueOf(),
                                                        end: moment(v[0].scheduleTo).valueOf(),
                                                        allDay: false,
                                                        data: v
                                                    }
                                                }).value();
                                            successCallback(dtClndr);

                                            setTimeout(() => {
                                                if ($.fn.DataTable.isDataTable('#tableRawData'))
                                                    table.clear().rows.add(scheduleData).draw();
                                                else
                                                    rawDataDT();
                                            }, 10);
                                        })
                                        .catch((rej) => {
                                            if (rej.throw) {
                                                failureCallback(rej.message);
                                                throw new Error(rej.message);
                                            }
                                        });
                                });
                            }
                        },
                        eventClick: function(info) {
                            console.log(info)
                            let dt = info.event._def.extendedProps.data;
                            if (dt) {
                                assetListEvent.splice(0, assetListEvent.length);
                                assetListEvent.push(...dt);
                                assetListEventTitle.value = info.event._def.title;


                                if ($.fn.DataTable.isDataTable('#modalDetailAssetList table'))
                                    tableAE.clear().rows.add(assetListEvent).draw();
                                else
                                    assetEventDT();

                                $("#modalDetailAssetList").modal("show");
                            }
                        }
                    });
                    calendar.render();
                });

                let searchRawData = $(".dt-search-input input[data-target='#tableRawData']");
                searchRawData.unbind().bind("keyup", function(e) {
                    let searchData = searchRawData.val();
                    table.search(searchData).draw();
                });

                <?php if (checkRoleList("SCHEDULE.ADD,SCHEDULE.IMPORT")) : ?>
                    assetManualDT();
                    let searchAM = $(".dt-search-input input[data-target='#tableAssetManual']");
                    searchAM.unbind().bind("keyup", function(e) {
                        let searchData = searchAM.val();
                        tableAM.search(searchData).draw();
                    });

                    pickerAddAM = new Lightpick({
                        field: document.getElementById('scheduleAMDR'),
                        singleDate: false,
                        inline: true,
                        format: 'DD MMM YYYY',
                        minDate: moment(),
                        onSelect: function(start, end) {
                            let str = '';
                            str += start ? start.format('DD MMM YYYY') + ' - ' : '';
                            str += end ? end.format('DD MMM YYYY') : '...';
                            document.getElementById('labelScheduleAMDR').innerHTML = str;

                            if (start) startSAM.value = start.format('YYYY-MM-DD');
                            if (end) endSAM.value = end.format('YYYY-MM-DD');
                        }
                    });
                <?php endif; ?>

                <?php if (checkRoleList("SCHEDULE.IMPORT")) : ?>
                    FilePond.registerPlugin(FilePondPluginFileValidateType);
                    importSchFP = FilePond.create(document.querySelector('#importSch'), filepondOpt);

                    importSchFP.on('addfile', (error, file) => {
                        let formData = new FormData();
                        formData.append("importSch", file.file);
                        axios.post("<?= site_url("Schedule/importSchedule") ?>", formData)
                            .then((res) => {
                                xhrThrowRequest(res)
                                    .then(() => {
                                        $("#modalImport").modal("hide");
                                        importSchFP.removeFiles();

                                        startSAM.value = res.data.data.start;
                                        endSAM.value = res.data.data.end;

                                        pickerAddAM.setDateRange(moment(startSAM.value, "YYYY-MM-DD"), moment(endSAM.value, "YYYY-MM-DD"))

                                        assetIdAM.splice(0, assetIdAM.length);
                                        adviceDateAM.splice(0, adviceDateAM.length);
                                        res.data.data.data.forEach((v, k) => {
                                            let checkAssetM = _.filter(assetManualData, (vf) => vf.assetNumber == v.assetNumber);
                                            if (checkAssetM.length > 0) {
                                                assetIdAM.push(checkAssetM[0].assetId);
                                                adviceDateAM.push({
                                                    'assetId': checkAssetM[0].assetId,
                                                    'date': v.adviceScan
                                                });
                                            }
                                        });

                                        setTimeout(() => {
                                            nextFormAM();
                                            showModalAM();
                                        }, 10)
                                    })
                                    .catch((rej) => {
                                        if (rej.throw) {
                                            throw new Error(rej.message);
                                        }
                                        $('#slideApprove').removeClass('unlocked');
                                        $('#slideApprove').html(`<i class="fa fa-check font-xl"></i>`);
                                    });
                            });
                    });
                <?php endif; ?>
            });

            return {
                moment,
                assetManualData,
                scheduleData,
                rawDataDT,
                formAM,
                assetListEvent,
                assetListEventTitle,

                <?= (checkRoleList("SCHEDULE.ADD,SCHEDULE.IMPORT") ? "showModalAM,adviceDateAM,changeCKAM,assetIdAM,getDataCKAM,dateRangeSchAM,nextFormAM,saveAddAM,addAdviceDate," : "") ?>
                <?= (checkRoleList("SCHEDULE.ADD,SCHEDULE.IMPORT") ? "showModalIS," : "") ?>
            }
        }
    }).mount('#app');
</script>
<?= $this->endSection(); ?>