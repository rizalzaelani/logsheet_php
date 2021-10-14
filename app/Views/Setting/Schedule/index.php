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
                    <div class="d-flex justify-content-between mb-1">
                        <h4><?= $title ?></h4>
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="card card-main hide" id="cardTable">
                <div class="card-body">
                    <h5>{{ strDate }}</h5>
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
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4>Asset</h4>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-success mb-2 mr-1" @click="submit()"><i class="fa fa-save"></i> Submit</button>
                                <button class="btn btn-sm btn-danger mb-2" @click="close()"><i class="fa fa-times"></i> Close</button>
                            </div>
                        </div>
                        <table class="table table-bordered w-100" id="tableAsset">
                            <thead>
                                <tr>
                                    <th id="all" width="5%">
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
            var dataEvent = ref([]);
            var strDate = ref('');
            var date = ref('');
            var table = ref('');
            var selectedAsset = ref([]);
            var exist = ref([]);
            var uuid = ref(uuidv4());
            var tempId = ref([]);

            function getAsset() {
                return new Promise(async (resolve, reject) => {
                    try {
                        table = await $('#tableAsset').DataTable({
                            drawCallback: function(settings) {
                                $(document).ready(function() {
                                    $('#all').removeClass('sorting_asc');
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
                            dom: '<"d-flex justify-content-between align-items-center"<"form">f><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
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
                                    render: function(data) {
                                        return `<input type="checkbox" name="name[]" class="checkbox" id="id${data}" value="${data}">`;
                                    }
                                },
                                // {
                                //     targets: 0,
                                //     checkboxes: {
                                //         selectRow: true
                                //     },
                                // },
                                {
                                    targets: "_all",
                                    orderable: false,
                                },
                            ],
                            // select: {
                            //     style: 'multi',
                            // },
                        });
                    } catch (er) {
                        console.log(er)
                        reject(er);
                    }
                })
            };

            function submit() {
                var tbl = $('#tableAsset').DataTable();
                tbl.$('input[type="checkbox"]').each(function() {
                    if (this.checked) {
                        v.selectedAsset.push(this.value);
                    }
                })
                var deselect = _.difference(this.exist, this.selectedAsset);
                axios.post("<?= base_url('Schedule/addEvent') ?>", {
                    assetId: v.selectedAsset,
                    deselect: deselect,
                    date: v.date
                }).then(res => {
                    calendar.refetchEvents()
                    this.selectedAsset = ref([]);
                })
                return;
                var tbl = $('#tableAsset').DataTable();
                var selected = tbl.column(0).checkboxes.selected();
                $.each(selected, function(idx, id) {
                    v.selectedAsset.push(id);
                })
                if (this.selectedAsset.length > 0) {
                    axios.post("<?= base_url('Schedule/addEvent') ?>", {
                        assetId: v.selectedAsset,
                        date: v.date
                    }).then(res => {
                        calendar.refetchEvents();
                        this.selectedAsset = ref([]);
                    })
                } else {
                    swal.fire({
                        title: 'No data selected.',
                        icon: 'error'
                    })
                }
            }

            function close() {
                $('#cardTable').addClass('hide');
                $('html, body').animate({
                    scrollTop: $("#cardSchedule").offset().top
                }, 1000);
            }

            // onMounted(() => {
            //     getAsset();
            // })

            return {
                myModal,
                dataEvent,
                strDate,
                table,
                selectedAsset,
                exist,
                date,
                uuid,
                tempId,

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

    $(document).ready(function() {
        var tbl = $('#tableAsset').DataTable();
        $('#select-all').on('click', function() {
            $('input[type="checkbox"]').prop('checked', this.checked);
        })
        $('#tableAsset tbody').on('change', 'input[type="checkbox"]', function() {
            var elm = $('#select-all').get(0);
            if (elm && elm.checked && ('indeterminate' in elm)) {
                elm.indeterminate = true;
            }
        })
    })

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'bootstrap',
        headerToolbar: {
            start: 'prev,today,next',
            center: 'title',
            end: 'dayGridMonth,dayGridWeek,listWeek'
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
        }],
        eventDidMount: function(info) {
            if (info.event.extendedProps.status === 'done') {

                // Change background color of row
                info.el.style.backgroundColor = 'white';

                // Change color of dot marker
                var dotEl = info.el.getElementsByClassName('fc-event-dot')[0];
                if (dotEl) {
                    dotEl.style.backgroundColor = 'white';
                }
            }
        },
        dateClick: function(info) {
            v.date = info.dateStr;
            axios.post("<?= base_url('Schedule/checkAssetId') ?>", {
                date: info.dateStr
            }).then(res => {
                var dataChecked = res.data;
                var arr = [];
                dataChecked.forEach(item => {
                    arr.push(item.assetId);
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

                // $(".form").html(`
                //     <div>
                //         <input type="datetime-local" class="form-control" placeholder="">
                //     </div>
                // `)
            })
            let date = moment(info.date).format('LL');
            v.strDate = date;
            $('#cardTable').removeClass('hide');
            $('html, body').animate({
                scrollTop: $("#cardTable").offset().top
            }, 1000);
            $('#tableAsset').DataTable().clear().destroy();
            v.getAsset();
            v.tempId = v.uuid;
        },
        windowResize: function(view) {
            console.log(view);
        }
    });
    calendar.render();

    $(document).ready(function() {
        $('#schType').select2({
            theme: 'coreui',
            placeholder: 'Schedule'
        })
    })
</script>
<?= $this->endSection(); ?>