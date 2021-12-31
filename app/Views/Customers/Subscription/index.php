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

    .btn-custom {
        /* border-radius: 2px; */
        height: 32px;
        padding: 4.8px 15px;
        font-size: 13px;
        font-weight: 400;
        text-align: center;
        transition: all .3s cubic-bezier(.645, .045, .355, 1);
    }

    .btn-custom-primary {
        color: rgba(0, 0, 0, .85);
        background-color: #fff;
        border: 1px solid #1D6DE5;
    }

    .btn-custom-primary:hover {
        color: rgba(0, 0, 0, .85);
        background-color: #fff;
        border: 1px solid #175cc5;
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
                        <input name="dt-search" class="material-input" type="text" data-target="#tableEq" placeholder="Search Data Asset" />
                    </div>
                </div>
                <div>
                    <h2><?= $session->get('name') ?></h2>
                    <span class="text-muted"><?= $session->get('email') ?></span>
                </div>
                <hr>
                <div class="py-2">
                    <div class="row">
                        <div class="col-4">
                            <div class="card card-main h-80 card-subs">
                                <div class="card-body">
                                    <p class="text-muted m-0">Current Package</p>
                                    <h2 class="m-0 text-uppercase"><?= $package ?></h2>
                                    <span class="text-muted">From {{ from }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card card-main h-80 card-subs">
                                <div class="card-body">
                                    <p class="text-muted m-0">Remaining</p>
                                    <h2 class="m-0">{{ range > 1 ? range + ' Days' : range + ' Day' }}</h2>
                                    <span class="text-danger">Until {{ to }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card card-main h-80 p-0" style="background-color: none; box-shadow: none !important;">
                                <div class="card-body p-0">
                                    <div>
                                        <button @click="upgrade()" class="btn btn-lg btn-primary mt-0 w-100"><i class="fa fa-arrow-circle-up"></i> Upgrade</button>
                                    </div>
                                    <div class="mt-4">
                                        <button @click="renew()" class="btn btn-lg btn-primary mb-0 w-100"><i class="fa fa-arrow-circle-up"></i> Renew</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <h5><?= $title ?></h5>
                    <!-- <h5 class="header-icon">
                        <a href="javascript:;" @click="draw();"><i class="fa fa-redo-alt" data-toggle="tooltip" title="Refresh"></i></a>
                        <a href="javascript:;" class="dt-search" data-target="#tableSubs"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
                    </h5> -->
                </div>
                <div class="table-responsive">
                    <table class="table table-hover display nowrap" id="tableSubs">
                        <thead>
                            <tr>
                                <th class="d-none">id</th>
                                <th>Status</th>
                                <th>Number</th>
                                <th>Issue Date</th>
                                <th>Description</th>
                                <th>Due on</th>

                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="subscription">
                                <tr v-for="(val, key) in transaction">
                                    <td class="d-none">{{ val.transactionId }}</td>
                                    <template v-if="val.paidDate != null && val.approvedDate != null && val.attachment != null">
                                        <td class="text-success text-uppercase"><span class="badge badge-success">Paid</span></td>
                                    </template>
                                    <template v-else-if="val.paidDate == null && val.approvedDate == null && val.attachment != null">
                                        <td class="text-primary text-uppercase"><span class="badge badge-primary text-white">Waiting</span></td>
                                    </template>
                                    <template v-else-if="val.paidDate == null && val.approvedDate == null && val.cancelDate == null">
                                        <td class="text-danger text-uppercase"><span class="badge badge-danger">Unpaid</span></td>
                                    </template>
                                    <template v-else>
                                        <td class="text-warning text-uppercase"><span class="badge badge-warning text-white">Cancelled</span></td>
                                    </template>
                                    <td><span class="text-info">{{ val.refNumber }}</span></td>
                                    <td>{{ moment2(val.issueDate) }}</td>
                                    <td>{{ val.description }}</td>
                                    <td v-if="val.paidDate == null && val.cancelDate == null" class="text-danger">
                                        {{ countdown }}
                                    </td>
                                    <td v-else>
                                        0
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- modal trx detail -->
    <div class="modal fade" id="modalTrx" tabindex="-1" role="dialog" aria-labelledby="modalTrxTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTrxTitle">Transaction Detail <span class="text-primary">#{{ dataModal.refNumber }}</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3 d-flex justify-content-between">
                        <div>
                            <h5>Status Invoice</h5>
                            <div>
                                <div v-if="dataModal.paidDate != null && dataModal.approvedDate != null && dataModal.attachment != null">
                                    <h5 class="text-success text-uppercase">Paid</h5>
                                </div>
                                <div v-else-if="dataModal.paidDate == null && dataModal.approvedDate == null && dataModal.attachment != null">
                                    <h5 class="text-primary text-uppercase">Waiting</h5>
                                </div>
                                <div v-else-if="dataModal.paidDate == null && dataModal.approvedDate == null && dataModal.cancelDate == null">
                                    <h5 class="text-danger text-uppercase">Unpaid</h5>
                                </div>
                                <div v-else>
                                    <h5 class="text-warning text-uppercase">Cancelled</h5>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2 btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary"><i class="fa fa-download"></i> Download</button>
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" target="_blank" @click="download()" style="cursor: pointer;"><i class="fa fa-file-pdf mr-1"></i> Pdf</a>
                                </div>
                            </div>
                            <div :class="dataModal.paidDate == null && dataModal.cancelDate == null ? '' : 'd-none'">
                                <h5 class="text-danger">{{ countdown }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="p-3">
                        <h5>Invoice Details</h5>
                        <div class="row">
                            <div class="col-3">
                                Invoice Date
                            </div>
                            <div class="col-9">
                                {{ momentjs(dataModal.issueDate) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                Due Date
                            </div>
                            <div class="col-9">
                                {{ momentjs(dataModal.dueDate) }}
                            </div>
                        </div>
                    </div>
                    <div class="p-3">
                        <h5>Description</h5>
                        <div class="row">
                            <div class="col-3">
                                Package Version
                            </div>
                            <div class="col-4">
                                {{ dataModal.description }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                Duration
                            </div>
                            <div class="col-4">
                                {{ dataModal.period }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                Asset Max
                            </div>
                            <div class="col-4">
                                {{ formatNumber(parseInt(dataModal.assetMax)) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                Parameter Max
                            </div>
                            <div class="col-4">
                                {{ formatNumber(parseInt(dataModal.parameterMax)) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                Tag Max
                            </div>
                            <div class="col-4">
                                {{ formatNumber(parseInt(dataModal.tagMax)) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                Transaction Daily Max
                            </div>
                            <div class="col-4">
                                {{ formatNumber(parseInt(dataModal.trxDailyMax)) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <b>Payment Method</b>
                            </div>
                            <div class="col-4">
                                <b>{{ dataModal.paymentMethod }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <b>Total</b>
                            </div>
                            <div class="col-4">
                                <b>Rp. {{ formatNumber(parseInt(dataModal.paymentTotal)) }}</b>
                            </div>
                        </div>
                        <div :class="dataModal.paidDate == null ? 'row py-4' : 'd-none'">
                            <div class="col">
                                <div class="accordion" id="accordionExample">
                                    <div class="">
                                        <div class="d-flex justify-content-start align-items-center" id="headingThree">
                                            <a class="collapsed text-info decoration-none" type="button" data-toggle="collapse" data-target="#dataCollapse" aria-expanded="false" aria-controls="collapseThree">
                                                <i class="fa fa-caret-down rotate mr-2" id="rotate"></i>How to make payment ?
                                                <!-- <p class="m-0">How to make payment ?</p> -->
                                            </a>
                                        </div>
                                        <div id="dataCollapse" class="collapse py-2" aria-labelledby="headingThree" data-parent="#accordionExample">
                                            <div class="py-2">
                                                <div class="info py-2">
                                                    <p class="mx-2 mb-0"><strong>NOTE :</strong></p>
                                                    <p class="mx-2 mb-0">
                                                        Please make payment only with the account number listed below and confirm payment after you make payment.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="py-2">
                                                <b>Destination Bank</b>
                                            </div>
                                            <div class="my-2">
                                                <select class="py-2" name="bank" id="bank">
                                                    <option selected disabled value="mandiri">MANDIRI, Account Number <b>1800010111674</b> PT. NOCOLA IOT SOLUTION</option>
                                                </select>
                                            </div>
                                            <div class="py-2">
                                                <b>ATM Transfer</b>
                                            </div>
                                            <div class="py-2">
                                                <ol>
                                                    <li class="pb-2">Select the Transfer menu</li>
                                                    <li class="pb-2">Select the menu to Mandiri Account</li>
                                                    <li class="pb-2">Enter the destination account number (select True)</li>
                                                    <li class="pb-2">Enter the amount of money to be transferred (select True)</li>
                                                    <li class="pb-2">Pay attention to the transfer confirmation, if correct select True</li>
                                                    <li class="pb-2">The transaction has been completed, select Exit or press Cancel and then you can do confirmation.</li>
                                                </ol>
                                            </div>
                                            <div class="py-2">
                                                <b>Mobile Banking</b>
                                            </div>
                                            <div class="py-2">
                                                <ol>
                                                    <li class="pb-2">Login to your mobile banking</li>
                                                    <li class="pb-2">Select the Transfer menu</li>
                                                    <li class="pb-2">Select the menu to Mandiri Account, if you have different bank account, choose transfer to another bank</li>
                                                    <li class="pb-2">Enter the destination account number (select True)</li>
                                                    <li class="pb-2">Enter the amount of money to be transferred (select True)</li>
                                                    <li class="pb-2">Pay attention to the transfer confirmation, if correct select True</li>
                                                    <li class="pb-2">The transaction has been completed, you can exit the application and do confirmation.</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div :class="dataModal.paidDate == null && dataModal.approvedDate == null && dataModal.cancelDate == null ? 'd-flex justify-content-between align-items-center modal-footer px-3' : 'modal-footer px-3'">
                    <div v-if="dataModal.paidDate == null && dataModal.approvedDate == null && dataModal.cancelDate == null">
                        <button type="button" class="btn btn-success mr-1" @click="confirm"><i class="fa fa-file-upload"></i> Upload</button>
                        <button type="button" class="btn btn-danger" @click="cancelUp(dataModal.transactionId)"><i class="fa fa-times"></i> Cancel Upgrade</button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal upload attachment -->
    <div class="modal fade" id="modalAttachment" tabindex="-1" role="dialog" aria-labelledby="modalAttachmentTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTrxTitle">Upload Proof of Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="file" class="filepond mt-2 mb-2 w-100" name="filepond" id="attach" accept="image/png, image/jpeg, image/gif" />
                </div>
                <div class="modal-footer">
                    <div>
                        <button @click="send()" type="button" class="btn btn-success mr-1" data-dismiss="modal"><i class="fa fa-paper-plane"></i> Send</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
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
        onMounted,
        ref,
        reactive
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var transaction = <?= $transaction ? json_encode($transaction) : ''; ?>;
            var subscription = <?= $subscription ? json_encode($subscription) : ''; ?>;
            var activeFrom = moment().format("YYYY-MM-DD");
            var activeTo = moment(subscription[0].expiredDate, "YYYY-MM-DD");
            var from = subscription ? moment(subscription[0].activeFrom).format("DD MMM YYYY") : '';
            var to = subscription ? moment(subscription[0].expiredDate).format("DD MMM YYYY") : '';
            var range = activeTo.diff(activeFrom, 'days');
            var myModal = ref("");

            var dueDate = [];
            transaction.forEach((el, i) => {
                if (el.paidDate == null && el.cancelDate == null) {
                    dueDate.push(el.dueDate);
                }
            })
            var now = moment().format("YYYY-MM-DD HH:mm:ss");
            var dd = moment(dueDate[0]).format("YYYY-MM-DD HH:mm:ss")
            var diffCD = moment(dd) - moment(now);
            var duration = moment.duration(diffCD - 1000, 'milliseconds');
            var interval = 1000;
            var countdown = ref("");

            var attachment = ref("");

            if (dueDate.length) {
                setInterval(() => {
                    v.duration = moment.duration(v.duration - v.interval, 'milliseconds');
                    v.countdown = v.duration.hours() + ":" + v.duration.minutes() + ":" + v.duration.seconds()
                    if ((v.transaction[0].cancelDate == null && v.transaction[0].approvedDate == null) && (v.duration._milliseconds == 0000 || (Math.sign(v.duration._milliseconds))) == -1) {
                        let formdata = new FormData();
                        formdata.append('transactionId', v.transaction[0].transactionId);
                        axios({
                            url: "<?= base_url('/Subscription/update') ?>",
                            method: 'POST',
                            data: formdata
                        }).then((res) => {
                            location.reload();
                        })
                        v.subscription.shift();
                    }
                }, 1000);
            }

            var dataModal = ref("");

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            function modalTrx(trxId) {
                this.myModal = new coreui.Modal(document.getElementById('modalTrx'), {});
                this.myModal.show();

                this.transaction.forEach((el, i) => {
                    if (el.transactionId == trxId) {
                        this.dataModal = el;
                    }
                });
            }

            function upgrade() {
                if (transaction.length) {
                    if (transaction[0].paidDate == null && transaction[0].cancelDate == null) {
                        swal.fire({
                            icon: 'warning',
                            title: 'Please make a payment first or cancel the previous transaction',
                        })
                    } else {
                        window.location.href = "<?= base_url('/Subscription/upgrade') ?>";
                    }
                }
            }

            function renew() {
                if (transaction.length) {
                    if (transaction[0].paidDate == null && transaction[0].cancelDate == null) {
                        swal.fire({
                            icon: 'warning',
                            title: 'Please make a payment first or cancel the previous transaction',
                        })
                    } else if (transaction[0].paymentTotal == '0') {
                        swal.fire({
                            icon: 'warning',
                            title: 'You can only upgrade this package',
                        })
                    } else {
                        window.location.href = "<?= base_url('/Subscription/renew') ?>";
                    }
                }
            }

            function cancelUp(trxId) {
                this.myModal.hide();
                let formdata = new FormData();
                formdata.append('transactionId', trxId);
                axios({
                    url: "<?= base_url('/Subscription/cancelUp') ?>",
                    method: 'POST',
                    data: formdata
                }).then((res) => {
                    let rsp = res.data;
                    if (rsp.status == 200) {
                        swal.fire({
                            icon: 'success',
                            title: rsp.message
                        })
                        location.reload();
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: rsp.message
                        })
                    }
                })
            }

            function download() {
                this.myModal.hide();
                let formdata = new FormData();;
                formdata.append('transaction', JSON.stringify(this.dataModal));
                axios({
                    url: '<?= base_url('/Subscription/downloadInvoice') ?>',
                    method: 'POST',
                    data: formdata
                }).then((res) => {
                    // console.log(res);
                    let rsp = res.data;
                    if (rsp.status == 200) {
                        let base64 = rsp.data;
                        const linkSource = `data:applicaiton/pdf;base64,` + base64 + ``;
                        const downloadLink = document.createElement("a");
                        downloadLink.href = linkSource;
                        downloadLink.setAttribute('target', '_blank');
                        downloadLink.download = v.dataModal.refNumber + '.pdf';
                        downloadLink.click();
                    }
                })
            }

            function confirm() {
                let modalconfirm = new coreui.Modal(document.getElementById('modalAttachment'), {});
                modalconfirm.show();
            }

            function send() {
                if (this.attachment) {
                    let formdata = new FormData();
                    formdata.append('transactionId', this.dataModal.transactionId);
                    formdata.append('attachment', this.attachment);
                    axios({
                        url: '<?= base_url('/Subscription/confirmation') ?>',
                        method: 'POST',
                        data: formdata
                    }).then((res) => {
                        let rsp = res.data;
                        if (rsp.status == 200) {
                            swal.fire({
                                icon: 'success',
                                title: rsp.message
                            }).then((ok) => {
                                location.reload();
                            })
                        } else {
                            swal.fire({
                                icon: 'error',
                                title: rsp.message
                            })
                        }
                    })
                } else {
                    swal.fire({
                        icon: 'warning',
                        title: 'Please include a picture!'
                    })
                }
            }

            var getData = () => {
                this.table = $('#tableSubs').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: true,
                    scrollY: "calc(100vh - 272px)",
                    language: {
                        processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
                        lengthMenu: "Showing _MENU_ ",
                        info: "of _MAX_ entries",
                        infoEmpty: 'of 0 entries',
                    },
                    dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
                    data: subscription,
                    // ajax: {
                    //     url: "<?= base_url('/Subscription/datatable'); ?>",
                    //     type: "POST",
                    //     data: {},
                    //     complete: () => {
                    //         resolve();
                    //     }
                    // },
                    columns: [{
                            data: "issueDate",
                            name: "issueDate"
                        },
                        {
                            data: "description",
                            name: "description"
                        },
                        {
                            data: "period",
                            name: "period"
                        },
                        {
                            data: "paidDate",
                            name: "paidDate"
                        },
                        {
                            data: "paymentTotal",
                            name: "paymentTotal"
                        },
                        {
                            data: "paidDate",
                            name: "paidDate",
                            render: function(data, type, row, meta) {
                                if (row.paidDate != '' && row.paidDate != null) {
                                    return '<div class="text-success">Dibayar</div>';
                                } else {
                                    return '<div class="text-danger">Belum Dibayar</div>';
                                }
                            }
                        },
                    ],
                    order: [0, 'asc'],
                });
            };

            function momentjs(date) {
                return moment(date).format("dddd, DD MMM YYYY");
            }

            function moment2(date) {
                return moment(date).format("DD MMM YYYY hh:mm")
            }

            onMounted(() => {
                FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType, FilePondPluginFilePoster);
                let attach = {
                    acceptedFileTypes: ['image/png', 'image/jpeg'],
                    allowImagePreview: true,
                    imagePreviewMaxHeight: 200,
                    allowImageCrop: true,
                    allowMultiple: false,
                    credits: false,
                    styleLoadIndicatorPosition: 'center bottom',
                    styleProgressIndicatorPosition: 'right bottom',
                    styleButtonRemoveItemPosition: 'left bottom',
                    styleButtonProcessItemPosition: 'right bottom',
                };
                let attachment = FilePond.create(document.querySelector('#attach'), attach);
                attachment.on('addfile', (error, file) => {
                    v.attachment = file.file
                })
                attachment.on('removefile', (error, file) => {
                    v.attachment = ref("");
                })
                $('#bank').select2({
                    theme: 'coreui',
                    dropdownParent: $('#modalTrx'),
                })

            })

            return {
                formatNumber,
                momentjs,
                moment2,
                cancelUp,
                upgrade,
                renew,
                modalTrx,

                transaction,
                subscription,
                activeFrom,
                activeTo,
                range,
                from,
                to,
                myModal,
                dataModal,
                dueDate,
                diffCD,
                duration,
                interval,
                countdown,
                now,
                dd,
                download,
                confirm,
                attachment,
                send
            }
        },
    }).mount('#app');
    $(document).ready(function() {
        $('#tableSubs').DataTable({
            order: [2, 'desc'],
            scrollX: true,
            columnDefs: [],
            'createdRow': function(row, data) {
                row.setAttribute("data-id", data[0]);
                row.classList.add("cursor-pointer");
                // row.setAttribute("data-toggle", "tooltip");
                // row.setAttribute("data-html", "true");
                // row.setAttribute("title", "<div>Click to go to detail transaction</div>");
            },
        });
        $(document).on('click', '#tableSubs tbody tr', function() {
            let trxId = $(this).attr("data-id");
            if (trxId) {
                v.myModal = new coreui.Modal(document.getElementById('modalTrx'), {});
                v.myModal.show();

                v.transaction.forEach((el, i) => {
                    if (el.transactionId == trxId) {
                        v.dataModal = el;
                    }
                });
            }
        });
    })
</script>
<?= $this->endSection(); ?>