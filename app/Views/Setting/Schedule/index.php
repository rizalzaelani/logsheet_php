<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div id="app">
    <div class="row">
        <div class="col-12">
            <div class="card card-main mb-2" id="cardSchedule">
                <div class="card-body">
                    <div class="d-flex justify-content-between pb-4">
                        <h4 class="mb-0"><?= $title ?></h4>
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center hide" id="strDate">
                <h4 class="p-3 m-2">{{ strDate }}</h4>
            </div>
            <div class="card card-main hide mt-2" id="cardTable">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>List Asset</h4>
                        </div>
                        <div>
                            <button style="border-radius: 0px;" class="btn btn-sm btn-outline-primary mr-1" @click="submit()"><i class="fa fa-save"></i> Submit</button>
                            <button style="border-radius: 0px;" class="btn btn-sm btn-outline-danger" @click="cancelAsset()"><i class="fa fa-times"></i> Cancel</button>
                        </div>
                    </div>
                    <div class="" id="dataAsset">
                        <div id="tableAsset_filter" class="dataTables_filter mb-3">
                            <input type="search" name="dt-search" class="material-input w-100" data-target="#tableAsset" aria-controls="tableAsset" placeholder="Search Data Asset">
                        </div>
                        <table class="table table-bordered w-100" id="tableAsset">
                            <thead>
                                <tr>
                                    <th id="all">
                                        <input type="checkbox" name="checkbox" id="select-all" value="_all">
                                    </th>
                                    <th>Asset</th>
                                    <th>Number</th>
                                    <th>Tag</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card card-main hide" id="scheduleType">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Set Schedule Type</h4>
                        </div>
                        <div class="hide" id="btnSchType">
                            <button style="border-radius: 0px;" class="btn btn-sm btn-outline-primary mr-1" @click="submitSchType()"><i class="fa fa-save"></i> Submit</button>
                            <button style="border-radius: 0px;" class="btn btn-sm btn-outline-danger" @click="cancelSchType()"><i class="fa fa-times"></i> Cancel</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <label for="schType">
                                        Schedule Type
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <select name="" id="schType" class="form-control">
                                        <option value="" selected disabled>Select Schedule Type</option>
                                        <option value="Daily">Daily</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Field cannot be empty.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row hide" id="weekly">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="schWeekDays">Weekly</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <select name="schWeekDays" id="schWeekDays" class="form-control" multiple>
                                                <option value="Su">Sunday</option>
                                                <option value="Mo">Monday</option>
                                                <option value="Tu">Tuesday</option>
                                                <option value="We">Wednesday</option>
                                                <option value="Th">Thursday</option>
                                                <option value="Fr">Friday</option>
                                                <option value="Sa">Saturday</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide mb-2" id="monthly">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="">Monthly</label>
                                        </div>
                                    </div>
                                    <div class="py-2">
                                        <div class="form-check">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-start align-items-center">
                                                        <div class="mr-4">
                                                            <input class="form-check-input" type="radio" name="radioMonthly" id="radioMonthly1" value="days">
                                                            <label class="form-check-label mr-1" for="radioMonthly1">
                                                                Days
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <input class="form-check-input" type="radio" name="radioMonthly" id="radioMonthly2" value="on">
                                                            <label class="form-check-label mr-1" for="radioMonthly2">
                                                                On
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2" id="days" style="display: none;">
                                <div class="col-12">
                                    <select name="monthly" class="form-control days" id="monthlyDays" multiple>
                                        <?php for ($i = 1; $i <= 31; $i++) {  ?>
                                            <option value="<?= $i; ?>"><?= $i; ?></option>
                                        <?php } ?>
                                        <option value="Last">Last</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Field cannot be empty.
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2" id="on" style="display: none">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6 pr-1">
                                            <select name="onMonth" class="form-control on mr-1" id="monthlyOn" multiple>
                                                <option value="First">First</option>
                                                <option value="Second">Second</option>
                                                <option value="Third">Third</option>
                                                <option value="Fourth">Fourth</option>
                                                <option value="Last">Last</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                        <div class="col-6 pl-1">
                                            <select name="onDays" class="form-control on mr-1" id="monthlyOnDays" multiple>
                                                <option value="Su">Sunday</option>
                                                <option value="Mo">Monday</option>
                                                <option value="Tu">Tuesday</option>
                                                <option value="We">Wednesday</option>
                                                <option value="Th">Thursday</option>
                                                <option value="Fr">Friday</option>
                                                <option value="Sa">Saturday</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    const {
        ref,
        reactive,
        onMounted
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var myModal = ref('');
            var strDate = ref('');
            var date = ref('');
            var table = ref('');
            var selectedAsset = ref([]);
            var exist = ref([]);

            var schType = ref('');
            var schWeekDays = ref('');
            var schDays = ref('');
            var schWeeks = ref('');
            var onDays = ref('');

            var unique = (value, index, self) => {
                return self.indexOf(value) === index;
            }

            function getAsset() {
                return new Promise(async (resolve, reject) => {
                    try {
                        table = await $('#tableAsset').DataTable({
                            drawCallback: function(settings) {
                                $(document).ready(function() {
                                    $('#all').removeClass('sorting_asc');
                                    $('#tableAsset tbody tr td:nth-child(1)').addClass('px-0');
                                    $('#tableAsset thead tr th:nth-child(1)').addClass('px-0');
                                });

                                $('input[name="assetId"]').prop('checked', false);
                                $('#select-all').prop('checked', false);

                                $(document).ready(function() {
                                    $('#select-all').change(function() {
                                        if (this.checked) {
                                            $('input[type="checkbox"]').prop('checked', this.checked);
                                            let elm = $('input[name="assetId"]');

                                            $.each(elm, function(key, val) {
                                                v.selectedAsset.push(val.value);
                                            })

                                            var isUniqueSelected = v.selectedAsset.filter(v.unique);
                                            v.selectedAsset = isUniqueSelected;
                                        } else {
                                            $('input[type="checkbox"]').prop('checked', this.checked);
                                            let elm = $('input[name="assetId"]');
                                            let lengthSelected = v.selectedAsset.length;
                                            $.each(elm, function(key, val) {
                                                for (let i = 0; i < lengthSelected; i++) {
                                                    if (v.selectedAsset[i] === val.value) {
                                                        v.selectedAsset.splice(i, 1);
                                                    }
                                                }
                                            })
                                        }
                                    })
                                    $('#tableAsset tbody').on('change', 'input[type="checkbox"]', function() {
                                        var elm = $('#select-all').get(0);
                                        if (elm && elm.checked && ('indeterminate' in elm)) {
                                            elm.indeterminate = true;
                                        }
                                    })
                                })
                                v.selectedAsset.forEach(item => {
                                    let idSelected = '#id' + item;
                                    $(idSelected).prop('checked', true);
                                });

                                // set #select-all checked
                                var allChecked = $('input:checkbox:checked').length;
                                var lengthRow = $('#tableAsset tbody tr').length;
                                if (allChecked == lengthRow) {
                                    $('#select-all').prop('checked', true);
                                } else {
                                    $('#select-all').prop('checked', false);
                                }

                                $('input[name="assetId"]').change(function() {
                                    if (this.checked) {
                                        v.selectedAsset.push($(this).val())

                                        var isUniqueSelected = v.selectedAsset.filter(v.unique);
                                        v.selectedAsset = isUniqueSelected;

                                        // set #select-all checked
                                        var allChecked = $('input:checkbox:checked').length;
                                        var lengthRow = $('#tableAsset tbody tr').length;
                                        if (allChecked == lengthRow) {
                                            $('#select-all').prop('checked', true);
                                        } else {
                                            $('#select-all').prop('checked', false);
                                        }

                                    } else {
                                        let lengthSelected = v.selectedAsset.length;
                                        for (let i = 0; i < lengthSelected; i++) {
                                            if (v.selectedAsset[i] === $(this).val()) {
                                                v.selectedAsset.splice(i, 1);
                                            }
                                        }
                                    }
                                })
                                v.selectedAsset.forEach(item => {
                                    let idSelected = '#id' + item;
                                    $(idSelected).prop('checked', true);
                                })
                            },
                            processing: true,
                            serverSide: true,
                            responsive: true,
                            destroy: true,
                            language: {
                                processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
                                lengthMenu: "Showing _MENU_ ",
                                info: "of _MAX_ entries",
                                infoEmpty: 'of 0 entries',
                            },
                            dom: '<"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
                            ajax: {
                                url: "<?= base_url('/Schedule/datatable') ?>",
                                type: "POST",
                                data: {},
                                complete: () => {
                                    resolve();
                                }
                            },
                            columns: [{
                                    data: "assetId",
                                    name: "assetId",
                                },
                                {
                                    data: "assetName",
                                    name: "assetName",
                                },
                                {
                                    data: "assetNumber",
                                    name: "assetNumber",
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
                            columnDefs: [{
                                    targets: "_all",
                                    className: "dt-head-center",
                                },
                                {
                                    targets: [3, 4],
                                    render: function(data) {
                                        if (data != '-') {
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
                                    targets: 0,
                                    searchable: false,
                                    orderable: false,
                                    className: 'dt-body-center',
                                    render: function(data) {
                                        return `<input type="checkbox" name="assetId" class="checkbox" id="id${data}" value="${data}">`;
                                    }
                                },
                                {
                                    targets: "_all",
                                    orderable: false,
                                },
                            ],
                        });
                    } catch (er) {
                        console.log(er)
                        reject(er);
                    }
                })
            };

            function submitSchType() {
                if (v.schType != "" && v.schType != null) {
                    $('#schType').removeClass('is-invalid');
                    if (v.schWeekDays != '') {
                        $('#schWeekDays').removeClass('is-invalid');
                    }
                    if (v.schDays != '') {
                        $('#monthlyDays').removeClass('is-invalid');
                    }
                    if (v.schWeeks != '') {
                        $('#monthlyOn').removeClass('is-invalid');
                    }
                    if (v.schWeekDays != '') {
                        $('#monthlyOnDays').removeClass('is-invalid');
                    }

                    if (v.schType == 'Daily') {
                        if (v.schWeekDays != '') {
                            $('#schWeekDays').removeClass('is-invalid');
                            $('#monthlyDays').removeClass('is-invalid');
                            $('#monthlyOn').removeClass('is-invalid');
                            $('#monthlyOnDays').removeClass('is-invalid');
                            return;
                        }
                    } else if (v.schType == 'Weekly') {
                        if (v.schWeekDays == '') {
                            $('#schWeekDays').addClass('is-invalid');
                            return;
                        }
                    } else if (v.schType == 'Monthly') {
                        if (v.onDays == '') {
                            $('#radioMonthly1').addClass('is-invalid');
                            $('#radioMonthly2').addClass('is-invalid');
                            return;
                        } else {
                            $('#radioMonthly1').removeClass('is-invalid');
                            $('#radioMonthly2').removeClass('is-invalid');
                            if (v.onDays == 'days') {
                                if (v.schDays == '') {
                                    $('#monthlyDays').addClass('is-invalid');
                                    return;
                                }
                            } else if (v.onDays = 'on') {
                                if (v.schWeeks == '' || v.schWeekDays == '') {
                                    if (v.schWeeks == '') {
                                        $('#monthlyOn').addClass('is-invalid');
                                    }
                                    if (v.schWeekDays == '') {
                                        $('#monthlyOnDays').addClass('is-invalid');
                                    }
                                    return;
                                }
                            }
                        }
                    }
                    var deselect = _.difference(v.exist, v.selectedAsset);
                    var selected = _.difference(v.selectedAsset, v.exist);
                    axios.post("<?= base_url("Schedule/updateSchedule") ?>", {
                        assetId: selected,
                        deselect: deselect,
                        date: v.strDate,
                        schType: v.schType,
                        schWeekDays: v.schWeekDays,
                        schWeeks: v.schWeeks,
                        schDays: v.schDays
                    }).then(res => {
                        if (res.data.status == 'failed') {
                            swal.fire({
                                icon: 'error',
                                title: res.data.message
                            })
                            calendar.refetchEvents();
                            v.schWeekDays = ref('');
                            v.schWeeks = ref('');
                            v.schDays = ref('');
                            v.schType = ref('');
                            v.onDays = ref('');

                            $('#scheduleType').addClass('hide');
                            $('#schType').val("").trigger("change");
                            $('#schWeekDays').val("").trigger("change");
                            $('#monthlyDays').val("").trigger("change");
                            $('#monthlyOn').val("").trigger("change");
                            $('#monthlyOnDays').val("").trigger("change");

                            $('#weekly').addClass('hide');
                            $('#monthly').addClass('hide');
                            $('#radioMonthly1').prop('checked', false);
                            $('#radioMonthly2').prop('checked', false);
                        } else {
                            axios.post("<?= base_url('Schedule/checkAssetId') ?>", {
                                date: v.strDate,
                            }).then(res => {
                                calendar.refetchEvents();
                                var dataExist = res.data;
                                var arr = [];
                                dataExist.forEach(item => {
                                    arr.push(item);
                                });
                                v.exist = arr;

                                v.schWeekDays = ref('');
                                v.schWeeks = ref('');
                                v.schDays = ref('');
                                v.schType = ref('');
                                v.onDays = ref('');

                                $('#scheduleType').addClass('hide');
                                $('#schType').val("").trigger("change");
                                $('#schWeekDays').val("").trigger("change");
                                $('#monthlyDays').val("").trigger("change");
                                $('#monthlyOn').val("").trigger("change");
                                $('#monthlyOnDays').val("").trigger("change");

                                $('#weekly').addClass('hide');
                                $('#monthly').addClass('hide');
                                $('#radioMonthly1').prop('checked', false);
                                $('#radioMonthly2').prop('checked', false);
                            })

                        }
                    })
                } else {
                    $('#schType').addClass('is-invalid');
                }
            }

            function submit() {
                var deselect = _.difference(v.exist, v.selectedAsset);
                var selected = _.difference(v.selectedAsset, v.exist);
                var lengthDeselect = deselect.length;
                var lengthSelected = selected.length;
                if (lengthDeselect > 0 && lengthSelected < 1) {
                    axios.post("<?= base_url("Schedule/updateSchedule") ?>", {
                        assetId: selected,
                        deselect: deselect,
                        date: v.strDate,
                        schType: v.schType,
                        schWeekDays: v.schWeekDays,
                        schWeeks: v.schWeeks,
                        schDays: v.schDays
                    }).then(res => {
                        axios.post("<?= base_url('Schedule/checkAssetId') ?>", {
                            date: v.strDate,
                        }).then(res => {
                            calendar.refetchEvents();
                            var dataExist = res.data;
                            var arr = [];
                            dataExist.forEach(item => {
                                arr.push(item);
                            });
                            v.exist = arr;

                            v.schWeekDays = ref('');
                            v.schWeeks = ref('');
                            v.schDays = ref('');
                            v.schType = ref('');
                            v.onDays = ref('');


                            $('#scheduleType').addClass('hide');
                            $('#schType').val("").trigger("change");
                            $('#schWeekDays').val("").trigger("change");
                            $('#monthlyDays').val("").trigger("change");
                            $('#monthlyOn').val("").trigger("change");
                            $('#monthlyOnDays').val("").trigger("change");

                            $('#weekly').addClass('hide');
                            $('#monthly').addClass('hide');
                            $('#radioMonthly1').prop('checked', false);
                            $('#radioMonthly2').prop('checked', false);
                        })
                    })
                } else if (lengthDeselect < 1 && lengthSelected < 1) {
                    swal.fire({
                        title: 'No data changed',
                        icon: 'error'
                    })
                } else {
                    $('#scheduleType').removeClass('hide');
                    $('html, body').animate({
                        scrollTop: $("#cardTable").offset().top
                    }, 1000);
                }
                return;
            }

            function cancelAsset() {
                $('#cardTable').addClass('hide');
                $('#strDate').addClass('hide');
                $('html, body').animate({
                    scrollTop: $("#scheduleType").offset().top
                }, 1000);

                v.selectedAsset = ref([]);
                v.exist = ref([]);

                v.schWeekDays = ref('');
                v.schWeeks = ref('');
                v.schDays = ref('');
                v.schType = ref('')
                v.onDays = ref('');

                $('#scheduleType').addClass('hide');
                $('#schType').val("").trigger("change");
                $('#schWeekDays').val("").trigger("change");
                $('#monthlyDays').val("").trigger("change");
                $('#monthlyOn').val("").trigger("change");
                $('#monthlyOnDays').val("").trigger("change");

                $('#weekly').addClass('hide');
                $('#monthly').addClass('hide');
                $('#radioMonthly1').prop('checked', false);
                $('#radioMonthly2').prop('checked', false);
            }

            function cancelSchType() {
                $('html, body').animate({
                    scrollTop: $("#cardTable").offset().top
                }, 1000);

                v.schWeekDays = ref('');
                v.schWeeks = ref('');
                v.schDays = ref('');
                v.schType = ref('')
                v.onDays = ref('');

                $('#scheduleType').addClass('hide');
                $('#schType').val("").trigger("change");
                $('#schWeekDays').val("").trigger("change");
                $('#monthlyDays').val("").trigger("change");
                $('#monthlyOn').val("").trigger("change");
                $('#monthlyOnDays').val("").trigger("change");

                $('#weekly').addClass('hide');
                $('#monthly').addClass('hide');
                $('#radioMonthly1').prop('checked', false);
                $('#radioMonthly2').prop('checked', false);
            }

            onMounted(() => {
                getAsset();
                let search = $("input[data-target='#tableAsset']");
                search.unbind().bind("keypress", function(e) {
                    if (e.which == 13 || e.keyCode == 13) {
                        let searchData = search.val();
                        var tbl = $('#tableAsset').DataTable();
                        tbl.search(searchData).draw();
                    }
                });
            })

            return {
                myModal,
                strDate,
                date,
                table,
                selectedAsset,
                exist,

                schType,
                schWeekDays,
                schDays,
                schWeeks,
                onDays,

                unique,
                getAsset,
                submitSchType,
                submit,
                cancelAsset,
                cancelSchType,
            }
        }
    }).mount('#app');

    function uuidv4() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0,
                v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'bootstrap',
        headerToolbar: {
            start: 'prevYear,prev,today,next,nextYear',
            center: '',
            end: 'title',
        },
        weekNumbers: true,
        views: {
            dayGridMonth: {
                dayMaxEventRows: 5,
            },
        },
        eventOrder: 'groupId',
        eventSources: [{
            url: "<?= base_url('Schedule/schJson') ?>",
            method: 'POST',
            format: 'json',
            display: 'block'
        }],
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
        },
        eventDidMount: function() {
            $('.fc .fc-popover').css('z-index', '100');
        },
        eventMouseEnter: function(args) {
            let el = args.el;
            $(args.el).attr('data-html', 'true');
            var popover = new coreui.Popover(el, {
                content: `
                        <div class="mb-2">
                            <div>Schedule Type :</div>
                            <div> ` + args.event.extendedProps.schType + `</div>
                        </div>
                        <div class="mb-2">
                            <div>Week Days :</div>
                            <div> ` + args.event.extendedProps.schWeekDays + `</div>
                        </div>
                        <div class="mb-2">
                            <div>Weeks :</div>
                            <div> ` + args.event.extendedProps.schWeeks + `</div>
                        </div>
                        <div class="mb-2">
                            <div>Days :</div>
                            <div> ` + args.event.extendedProps.schDays + `</div>
                        </div>
                        <div class="mb-2">
                            <div>Schedule From :</div>
                            <div> ` + args.event.extendedProps.scheduleFrom + `</div>
                        </div>
                        <div class="mb-2">
                            <div>Schedule To :</div>
                            <div> ` + args.event.extendedProps.scheduleTo + `</div>
                        </div>
                `,
                placement: 'top',
                title: (moment(args.event.start).format("HH:mm:ss") == "00:00:00" ? '24:00:00' : moment(args.event.start).format("HH:mm:ss")) + " " + args.event.title,
                trigger: 'hover',
            })
        },
        eventMouseLeave: function(args) {
            let el = args.el;
            var popover = new coreui.Popover(el);
            popover.hide()
        },
        moreLinkClick: 'popover',
        dayPopoverFormat: {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            weekday: 'short',
        },
        dateClick: function(info) {
            v.selectedAsset = ref([]);
            v.date = info.dateStr;
            v.strDate = moment(info.date).format('LL');

            $('#cardTable').removeClass('hide');
            $('html, body').animate({
                scrollTop: $("#cardTable").offset().top
            }, 1000);

            $('#strDate').removeClass('hide');

            v.schWeekDays = ref('');
            v.schWeeks = ref('');
            v.schDays = ref('');
            v.schType = ref('');
            v.onDays = ref('');

            $('#scheduleType').addClass('hide');
            $('#schType').val("").trigger("change");
            $('#schWeekDays').val("").trigger("change");
            $('#monthlyDays').val("").trigger("change");
            $('#monthlyOn').val("").trigger("change");
            $('#monthlyOnDays').val("").trigger("change");

            $('#weekly').addClass('hide');
            $('#monthly').addClass('hide');
            $('#radioMonthly1').prop('checked', false);
            $('#radioMonthly2').prop('checked', false);

            // post date
            axios.post("<?= base_url('Schedule/checkAssetId') ?>", {
                date: v.strDate,
            }).then(res => {
                var dataExist = res.data;

                // get unique from selectedId
                const unique = (value, index, self) => {
                    return self.indexOf(value) === index;
                }
                var isUniqueSelected = dataExist.filter(unique);
                var arr = [];
                $('input[name="assetId"]').prop('checked', false);
                isUniqueSelected.forEach(item => {
                    arr.push(item);
                    v.selectedAsset.push(item);
                    var idChecked = '#id' + item;
                    $(idChecked).prop('checked', true);
                });
                v.exist = arr;

                var allChecked = $('input:checkbox:checked').length;
                var lengthRow = $('#tableAsset tbody tr').length;
                if (allChecked == lengthRow) {
                    $('#select-all').prop('checked', true);
                } else {
                    $('#select-all').prop('checked', false);
                }
            })
        },
    });
    calendar.render();

    $('.fc-header-toolbar div:nth-child(2)').append(`
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center mr-1">
                <span class="mr-1" style="height: 10px; width: 10px; background-color: #003399; border-radius: 50%; display: inline-block;"></span>
                <b> Daily</b>
            </div>
            <div class="d-flex align-items-center mr-1">
                <span class="mr-1" style="height: 10px; width: 10px; background-color: #2eb85c; border-radius: 50%; display: inline-block;"></span>
                <b> Weekly</b>
            </div>
            <div class="d-flex align-items-center mr-1">
                <span class="mr-1" style="height: 10px; width: 10px; background-color: #f9b115; border-radius: 50%; display: inline-block;"></span>
                <b> Monthly</b>
            </div>
        </div>
    `);

    // schTYpe change function
    $('#schType').on('change', function() {
        let val = $(this).val();
        if (val == 'Daily') {
            $('#daily').removeClass('hide');
            $('#weekly').addClass('hide');
            $('#monthly').addClass('hide');
            $('#days').hide();
            $('#on').hide();

            $('#radioMonthly1').prop('checked', false);
            $('#radioMonthly2').prop('checked', false);

            v.schWeekDays = ref('');
            v.schDays = ref('');
            v.schWeeks = ref('');
            v.schType = val;
        } else if (val == 'Weekly') {
            $('#weekly').removeClass('hide');
            $('#daily').addClass('hide');
            $('#monthly').addClass('hide');
            $('#days').hide();
            $('#on').hide();

            $('#radioMonthly1').prop('checked', false);
            $('#radioMonthly2').prop('checked', false);

            v.schWeekDays = ref('');
            v.schWeeks = ref('');
            v.schDays = ref('');
            v.schType = val;
        } else {
            $('#monthly').removeClass('hide');
            $('#daily').addClass('hide');
            $('#weekly').addClass('hide');
            $('#days').hide();
            $('#on').hide();

            v.schWeekDays = ref('');
            v.schType = val;
        }

        $('#btnSchType').removeClass('hide');
    })

    $('#schWeekDays').on('change', function() {
        let schWeekDays = $(this).val();
        v.schWeekDays = schWeekDays.toString();
    })

    $('#monthlyDays').on('change', function() {
        let schDays = $(this).val();
        v.schDays = schDays.toString();
    })

    $('#monthlyOn').on('change', function() {
        let schWeeks = $(this).val();
        v.schWeeks = schWeeks.toString();
    })

    $('#monthlyOnDays').on('change', function() {
        let schWeekDays = $(this).val();
        v.schWeekDays = schWeekDays.toString();
    })

    $(document).ready(function() {
        // select2 theme coreui
        $('#schType').select2({
            theme: 'coreui',
            placeholder: 'Schedule'
        })

        $('#schWeekDays').select2({
            theme: 'coreui',
            placeholder: 'Select Days'
        })

        $('#monthlyDays').select2({
            theme: 'coreui',
            placeholder: 'Select All Days'
        })

        $('#monthlyOn').select2({
            theme: 'coreui',
            placeholder: 'Select Item'
        })

        $('#monthlyOnDays').select2({
            theme: 'coreui',
            placeholder: 'Select Days'
        })
    })

    //radio monthly
    $('input[type="radio"][name="radioMonthly"]').on('change', function() {
        if ($(this).val() == "days") {
            $('#monthlyOn').val("").trigger("change");
            $('#monthlyOnDays').val("").trigger("change");
            $('#days').show();
            $('#on').hide();
            v.schWeekDays = ref('');
            v.schWeeks = ref('');
            v.onDays = "days";
        } else if ($(this).val() == "on") {
            $('#monthlyDays').val("").trigger("change");
            $('#days').hide();
            $('#on').show();
            v.schDays = ref('');
            v.onDays = "on";
        }
    })

    // coreui popover
    document.querySelectorAll('[data-toggle="popover"]').forEach(function(element) {
        new coreui.Popover(element);
    })
</script>
<?= $this->endSection(); ?>