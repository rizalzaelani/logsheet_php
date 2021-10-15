<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div id="app">
    <div class="row">
        <div class="col-12">
            <div class="card card-main" id="cardSchedule">
                <div class="card-body">
                    <div class="d-flex justify-content-between pb-4">
                        <h4 class="mb-0"><?= $title ?></h4>
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="card card-main hide" id="cardTable">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center pb-4">
                        <h4 class="mb-0">{{ strDate }}</h4>
                        <div>
                            <button style="border-radius: 0px;" class="btn btn-sm btn-outline-primary mr-1" @click="submit()"><i class="fa fa-save"></i> Submit</button>
                            <button style="border-radius: 0px;" class="btn btn-sm btn-outline-danger" @click="close()"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                    <div class="row mt-2 hide">
                        <div class="col-6">
                            <form action="">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="schType">
                                            Schedule
                                        </label>
                                    </div>
                                    <div class="col-9">
                                        <select name="" id="schType" class="form-control">
                                            <option value="" selected disabled>Schedule</option>
                                            <option value="Daily">Daily</option>
                                            <option value="Weekly">Weekly</option>
                                            <option value="Monthly">Monthly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3" id="daily">
                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" id="schFrequency" placeholder="Factor of 24">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">/ Day</div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Please choose a factor of 24.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="" id="dataAsset">
                        <div id="tableAsset_filter" class="dataTables_filter mb-3">
                            <input type="search" name="dt-search" class="material-input w-100" data-target="#tableAsset" aria-controls="tableAsset" placeholder="Search Data Asset">
                        </div>
                        <table class="table table-bordered w-100" id="tableAsset">
                            <thead>
                                <tr>
                                    <th id="all" class="px-0" style="width: 5% !important;">
                                        <input type="checkbox" name="checkbox" id="select-all" value="_all">
                                    </th>
                                    <th style="width: 20% !important;">Asset</th>
                                    <th style="width: 20% !important;">Number</th>
                                    <th style="width: 20% !important;">Tag</th>
                                    <th style="width: 20% !important;">Location</th>
                                </tr>
                            </thead>
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
    const {
        ref,
        reactive,
        onMounted
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var dateTitle = ref('');
            var json = reactive([{
                    title: 'Test 1',
                    start: '2021-10-30 00:00:00',
                    end: '2021-10-30 23:59:59',
                },
                {
                    title: 'Test 1',
                    start: '2021-11-30 00:00:00',
                    end: '2021-11-30 23:59:59',
                }
            ]);
            var myModal = ref('');
            var strDate = ref('');
            var date = ref('');
            var table = ref('');
            var selectedAsset = ref([]);
            var exist = ref([]);
            var uuid = ref(uuidv4());
            var newChecked = ref([]);

            function getAsset() {
                return new Promise(async (resolve, reject) => {
                    try {
                        table = await $('#tableAsset').DataTable({
                            drawCallback: function(settings) {
                                $(document).ready(function() {
                                    $('#all').removeClass('sorting_asc');
                                });

                                $('input[name="assetId"]').prop('checked', false);
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

            function submit() {
                // get unique from selectedId
                const unique = (value, index, self) => {
                    return self.indexOf(value) === index;
                }
                var isUniqueSelected = v.selectedAsset.filter(unique);
                v.selectedAsset = isUniqueSelected;

                let filt1 = this.selectedAsset.filter((o) => this.exist.indexOf(o) === -1);
                let filt2 = this.exist.filter((o) => this.selectedAsset.indexOf(o) === -1);
                let filtered = filt1.concat(filt2);
                var deselect = _.difference(this.exist, this.selectedAsset);
                if (filtered.length > 0) {
                    axios.post("<?= base_url('Schedule/updateEvent') ?>", {
                        assetId: v.selectedAsset,
                        deselect: deselect,
                        date: v.date
                    }).then(res => {
                        calendar.refetchEvents();
                        axios.post("<?= base_url('Schedule/checkAssetId') ?>", {
                            date: v.date
                        }).then(res => {
                            var dataExist = res.data;
                            var arr = [];
                            dataExist.forEach(item => {
                                arr.push(item.assetId);
                            });
                            v.exist = arr;
                        })
                    })
                } else {
                    swal.fire({
                        title: 'No data changed.',
                        icon: 'error'
                    })
                }
                return;
            }

            function close() {
                $('#cardTable').addClass('hide');
                $('html, body').animate({
                    scrollTop: $("#cardSchedule").offset().top
                }, 1000);
                v.selectedAsset = ref([]);
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
                json,
                myModal,
                strDate,
                table,
                selectedAsset,
                exist,
                date,
                uuid,
                newChecked,
                dateTitle,

                getAsset,
                submit,
                close,
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
            end: 'title'
        },
        weekNumbers: true,
        dayMaxEventRows: true,
        views: {
            dayGridMonth: {
                dayMaxEventRows: 3,
            }
        },
        eventSources: [{
            url: "<?= base_url('Schedule/schJson') ?>",
            method: 'POST',
            display: 'block',
        }],
        viewDidMount: function(view, el) {
            let dateTitle = calendar.currentData.viewTitle;
            v.dateTitle = moment(dateTitle).format('Y-M');
            // console.log(v.json);
            v.json.forEach(i => {
                let start = moment(i.start).format('Y-M');
                if (start == v.dateTitle) {
                    // console.log(i);
                }
            })
        },
        dateClick: function(info) {
            var tbl = $('#tableAsset').DataTable();
            tbl.search('').draw();
            v.selectedAsset = ref([]);

            $(document).ready(function() {
                $('#select-all').change(function() {
                    if (this.checked) {
                        $('input[type="checkbox"]').prop('checked', this.checked);
                        let elm = $('input[name="assetId"]');
                        $.each(elm, function(key, val) {
                            v.selectedAsset.push(val.value);
                        })
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

            v.date = info.dateStr;
            axios.post("<?= base_url('Schedule/checkAssetId') ?>", {
                date: info.dateStr
            }).then(res => {
                var dataExist = res.data;
                var arr = [];
                $('input[name="assetId"]').prop('checked', false);
                dataExist.forEach(item => {
                    arr.push(item.assetId);
                    v.selectedAsset.push(item.assetId);
                    var idChecked = '#id' + item.assetId;
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

                $('input[name="assetId"]').change(function() {
                    if (this.checked) {
                        v.selectedAsset.push($(this).val())
                    } else {
                        let lengthSelected = v.selectedAsset.length;
                        for (let i = 0; i < lengthSelected; i++) {
                            if (v.selectedAsset[i] === $(this).val()) {
                                v.selectedAsset.splice(i, 1);
                            }
                        }
                    }
                })
            })
            let date = moment(info.date).format('LL');
            v.strDate = date;
            $('#cardTable').removeClass('hide');
            $('html, body').animate({
                scrollTop: $("#cardTable").offset().top
            }, 1000);
        },
        windowResize: function(view) {
            console.log(view);
        }
    });
    calendar.render();

    $('.fc-next-button, .fc-prev-button, .fc-nextYear-button, .fc-prevYear-button, .fc-today-button').on('click', function() {
        let dateTitle = calendar.currentData.viewTitle;
        console.log(dateTitle);
    })

    $(document).ready(function() {
        $('#schType').select2({
            theme: 'coreui',
            placeholder: 'Schedule'
        })
    })
</script>
<?= $this->endSection(); ?>