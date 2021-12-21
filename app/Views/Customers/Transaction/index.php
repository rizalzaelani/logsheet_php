<?= $this->extend('Layout/main'); ?>
<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<style>
    .select2-container .select2-selection--multiple {
        max-height: 50px !important;
    }

    .card-subs {
        box-shadow: 0 2px 4px rgb(0 0 0 / 15%) !important;
    }

    .h-80 {
        height: 80% !important;
    }

    .rotate {
        -moz-transition: all .1s linear;
        -webkit-transition: all .1s linear;
        transition: all .1s linear;
    }

    a[aria-expanded='false']>.rotate {
        -ms-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        -webkit-transform: rotate(-90deg);
        transform: rotate(-90deg);
    }

    table>thead>tr>th {
        vertical-align: middle !important;
    }

    table>tbody>tr>td {
        vertical-align: middle !important;
    }

    .info {
        background-color: #e7f3fe;
        border-left: 6px solid #2196F3;
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<?php
$session = \Config\Services::session();
?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main fixed-height">
            <div class="card-body">
                <div class="dt-search-input">
                    <div class="input-container">
                        <a href="javascript:void(0)" class="suffix text-decoration-none dt-search-hide"><i class="c-icon cil-x" style="font-size: 1.5rem;"></i></a>
                        <input name="dt-search" class="material-input" type="text" data-target="#tableEq" placeholder="Search Data Transaction" />
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h4><?= $title ?></h4>
                    <h5 class="header-icon">
                        <a href="#filterDT" onclick="return false;" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="filterDT" id="filter"><i class="fa fa-filter" data-toggle="tooltip" title="Filter"></i></a>
                        <!-- <a href="javascript:;" onclick="table.ajax.reload();"><i class="fa fa-redo-alt" data-toggle="tooltip" title="Refresh"></i></a> -->
                        <a href="javascript:;" class="dt-search" data-target="#tableEq"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
                        <!-- <a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= base_url('/Asset/add'); ?>"><i class="fa fa-plus mr-2"></i> Add Asset</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('/Asset/import'); ?>"><i class="fa fa-upload mr-2"></i> Import Data</a>
                            <a class="dropdown-item" href="<?= base_url('/Asset/export'); ?>"><i class="fa fa-file-excel mr-2"></i> Export Data</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" @click="draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
                        </div> -->
                    </h5>
                </div>
                <div class="row mt-2 collapse" id="filterDT">
                    <div class="col-4">
                        <div class="form-group" id="filterNumber">
                            <select class="form-control bg-transparent select2-multiple w-100 number" name="number" id="number" multiple="multiple">
                                <option value="all">All</option>
                                <option value="all">All</option>
                                <option value="all">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <fieldset class="form-group">
                            <div class="" id="filterUser">
                                <select class="form-control bg-transparent select2-multiple w-100 user" name="user" id="user" multiple="multiple">
                                    <option value="all">All</option>
                                    <option value="all">All</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-4">
                        <div class="form-group" id="filterstatus">
                            <select class="form-control bg-transparent select2-multiple w-100 status" name="status" id="status" multiple="multiple">
                                <option value="all">All</option>
                                <option value="all">All</option>
                                <option value="all">All</option>
                                <option value="all">All</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive w-100">
                    <table class="table table-hover w-100" id="tableTrx">
                        <thead class="bg-primary">
                            <tr>
                                <th>Number</th>
                                <th>Description</th>
                                <!-- <th>Customer</th> -->
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Total Payment</th>
                            </tr>
                        </thead>
                    </table>
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
        onMounted,
        ref,
        reactive
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {

            function GetData() {
                return new Promise(async (resolve, reject) => {
                    try {
                        this.table = await $('#tableTrx').DataTable({
                            drawCallback: function(settings) {
                                $(document).ready(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                })
                            },
                            processing: true,
                            serverSide: true,
                            scrollY: "calc(100vh - 272px)",
                            scrollX: true,
                            responsive: true,
                            language: {
                                processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
                                lengthMenu: "Showing _MENU_ ",
                                info: "of _MAX_ entries",
                                infoEmpty: 'of 0 entries',
                            },
                            dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
                            ajax: {
                                url: "<?= base_url('/CustomersTransaction/datatable') ?>",
                                type: "POST",
                                data: {},
                                complete: () => {
                                    resolve();
                                }
                            },
                            columns: [{
                                    data: "refNumber",
                                    name: "refNumber",
                                    render: function(data, type, row, meta) {
                                        return "<span class='text-info'>" + data + "</span>"
                                    }
                                },
                                {
                                    data: "description",
                                    name: "description",
                                    width: '20%'
                                },
                                {
                                    data: "issueDate",
                                    name: "issueDate",
                                    render: function(data, type, row, meta) {
                                        return moment(data).format('DD MMM YYYY hh:mm')
                                    }
                                },
                                {
                                    data: "dueDate",
                                    name: "dueDate",
                                    render: function(data, type, row, meta) {
                                        return moment(data).format('DD MMM YYYY hh:mm')
                                    }
                                },
                                {
                                    data: "paidDate",
                                    name: "paidDate",
                                    render: function(data, type, row, meta) {
                                        if (data == null && row.cancelDate == null && row.attachment == null) {
                                            return '<span class="badge badge-danger text-uppercase">Unpaid</span>'
                                        } else if (data == null && row.cancelDate != null) {
                                            return '<span class="badge badge-warning text-white text-uppercase">Cancelled</span>'
                                        } else if(data == null && row.cancelDate == null && row.attachment != null){
                                            return '<span class="badge badge-primary text-uppercase">Waiting</span>'
                                        }else{
                                            return '<span class="badge badge-success text-uppercase">Paid</span>'
                                        }
                                    }
                                },
                                {
                                    data: "paymentTotal",
                                    name: "paymentTotal",
                                    render: function(data, type, row, meta) {
                                        return "<b>Rp. " + formatNumber(parseInt(data)) + "</b>";
                                    }
                                },

                            ],
                            order: [2, 'desc'],
                            columnDefs: [],
                            'createdRow': function(row, data) {
                                row.setAttribute("data-id", data.transactionId);
                                row.classList.add("cursor-pointer");
                                // row.setAttribute("data-toggle", "tooltip");
                                // row.setAttribute("data-html", "true");
                                // row.setAttribute("title", "<div>Click to go to detail transaction</div>");
                            },
                        });
                    } catch (er) {
                        console.log(er)
                        reject(er);
                    }
                })
            };

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            onMounted(() => {
                GetData();
                $(document).on('click', '#tableTrx tbody tr', function() {
                    window.location.href = "<?= site_url('Invoice/detail') ?>/" + $(this).attr("data-id");
                });


                $('#number').select2({
                    theme: 'coreui',
                    placeholder: 'Select Number Invoice'
                })
                $('#user').select2({
                    theme: 'coreui',
                    placeholder: 'Select User'
                })
                $('#status').select2({
                    theme: 'coreui',
                    placeholder: 'Select Status'
                })
            })

            return {
                GetData,
                formatNumber,
            }
        },
    }).mount('#app');
</script>
<?= $this->endSection(); ?>