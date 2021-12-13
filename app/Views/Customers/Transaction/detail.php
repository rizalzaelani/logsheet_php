<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main fixed-height">
            <div class="card-body">
                <div class="dt-search-input">
                    <div class="input-container">
                        <a href="javascript:void(0)" class="suffix text-decoration-none dt-search-hide"><i class="c-icon cil-x" style="font-size: 1.5rem;"></i></a>
                        <input name="dt-search" class="material-input" type="text" data-target="#tableLocation" placeholder="Search Data Transaction" />
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <h4><?= $title ?> <span class="text-info">#{{ transaction[0].refNumber }}</span></h4>
                    <h5 class="header-icon">
                        <a href="<?= $_SERVER['HTTP_REFERER'] ?? site_url("role") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                    </h5>
                    <!-- <h5 class="header-icon">
                        <a href="<?= base_url('CustomersTransaction'); ?>" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</a>
                    </h5> -->
                </div>
                <div style="border: 1px solid #f0f0f0 !important;">
                    <div>
                        <div class="px-4 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div v-if="transaction[0].paidDate == null && transaction[0].cancelDate == null">
                                        <h4 class="m-0 text-danger">Unpaid</h4>
                                    </div>
                                    <div v-else-if="transaction[0].paidDate == null && transaction[0].cancelDate != null">
                                        <h4 class="m-0 text-warning">Cancelled</h4>
                                    </div>
                                    <div v-else>
                                        <h4 class="m-0 text-success">Paid</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="border-top: 1px solid #f0f0f0;"></div>
                        <div class="row px-4 py-2">
                            <div class="col">
                                <p><b>Pelanggan</b></p>
                                <h6 class="text-primary"></h6>
                                <div class="ml-1">
                                    <table>
                                        <tr>
                                            <td>
                                                <p class="text-muted mb-1"><i class="fa fa-id-card"></i></p>
                                            </td>
                                            <td>
                                                <p class="text-muted mb-1">{{ contact.company }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="text-muted mb-1"><i class="fa fa-home"></i></p>
                                            </td>
                                            <td>
                                                <p class="text-muted mb-1">{{ contact.address }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="text-muted mb-1"><i class="fa fa-phone"></i></p>
                                            </td>
                                            <td>
                                                <p class="text-muted mb-1">{{ contact.phone }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-1">
                                    <p><b>Nomor</b></p>
                                    <h6 class="text-uppercase">{{ transaction[0].refNumber }}</h6>
                                </div>
                                <div>
                                    <p><b>Description</b></p>
                                    <h6>{{ transaction[0].description }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="row px-4 py-2">
                            <div class="col">
                                <p><b>Tanggal Transaksi</b></p>
                                <h6>{{ transaction[0].issueDate }}</h6>
                            </div>
                            <div class="col">
                                <p><b>Tanggal Jatuh Tempo</b></p>
                                <h6>{{ transaction[0].dueDate }}</h6>
                            </div>
                        </div>
                        <div class="row px-4 py-2">
                            <div class="col">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th>Deskripsi</th>
                                                <th>Kuantitas</th>
                                                <th>Satuan</th>
                                                <th>Discount</th>
                                                <th>Harga</th>
                                                <th>Pajak</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ package[0].description.split(" -") ? package[0].description.split(" -")[0] : '' }}</td>
                                                <td>{{ package[0].description }}</td>
                                                <td>1</td>
                                                <td>Package</td>
                                                <td>0%</td>
                                                <td><b>Rp. {{ formatNumber(parseInt(transaction[0].paymentTotal)) }}</b></td>
                                                <td>0</td>
                                                <td><b>Rp. {{ formatNumber(parseInt(transaction[0].paymentTotal)) }}</b></td>
                                                <!-- <td v-if="(dataInvoice.data.items[0]).item_tax != null">
                                                    {{ dataInvoice.data.items[0].item_tax.name }}
                                                </td>
                                                <td v-else>
                                                    -
                                                </td> -->
                                                <!-- <td>{{ dataInvoice.data.items[0].item_tax.name }}</td> -->
                                                <!-- <td>{{ dataInvoice.data.items[0].amount }}</td> -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row px-4 py-2">
                            <div class="col-6">

                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end align-items-left">
                                    <table class="table">
                                        <tr>
                                            <th>Sub Total</th>
                                            <th>Rp. {{ formatNumber(parseInt(transaction[0].paymentTotal)) }}</th>
                                        </tr>
                                        <tr>
                                            <th>PPN 10%</th>
                                            <th>-</th>
                                        </tr>
                                        <tr>
                                            <th>Total</td>
                                            <th>Rp. {{ formatNumber(parseInt(transaction[0].paymentTotal)) }}</th>
                                        </tr>
                                        <tr>
                                            <th>Sisa Tagihan</th>
                                            <th>Rp. {{ formatNumber(parseInt(transaction[0].paymentTotal)) }}</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div :class="transaction[0].paidDate == null && transaction[0].cancelDate == null ? 'mt-2' : 'd-none'" style="border: 1px solid #f0f0f0 !important;">
                    <div>
                        <div class="px-4 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div>
                                        <h6 class="m-0">Approve Payment</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="border-top: 1px solid #f0f0f0;"></div>
                        <div class="row px-4 py-2">
                            <div class="col">
                                <label for="total">Total Dibayar</label>
                                <input class="form-control text-right" type="text" :value="formatNumber(parseInt(transaction[0].paymentTotal))">
                            </div>
                            <div class="col">
                                <label for="total">Payment Date</label>
                                <input class="form-control" type="datetime-local" v-model="approveDate" id="approveDate">
                            </div>
                        </div>
                        <div class="px-4 pt-2 pb-4 d-flex justify-content-start">
                            <div>
                                <button @click="deleteTrx()" class="btn btn-danger mr-1"><i class="fa fa-trash"></i> Delete</button>
                                <button @click="approve()" class="btn btn-success"><i class="fa fa-check"></i> Approve</button>
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
        reactive,
        ref
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var transaction = <?= json_encode($transaction) ?>;
            var package = <?= json_encode($package) ?>;
            var packagePrice = <?= json_encode($packagePrice) ?>;
            var approveDate = ref("");
            var contact = <?= json_encode($contact) ?>;

            function approve() {
                if (this.approveDate != '') {
                    let formdata = new FormData();
                    formdata.append('approveDate', this.approveDate)
                    formdata.append('transactionId', this.transaction[0].transactionId)
                    formdata.append('subscriptionId', this.transaction[0].subscriptionId)
                    axios({
                        url: '<?= base_url('CustomersTransaction/approve') ?>',
                        method: 'POST',
                        data: formdata
                    }).then((res) => {
                        let rsp = res.data;
                        if (rsp.status == 200) {
                            swal.fire({
                                icon: 'success',
                                title: rsp.message
                            }).then((r) => {
                                location.reload();
                            })
                        }
                        console.log(res);
                    })
                } else {
                    swal.fire({
                        icon: 'warning',
                        title: 'Please specify the date of payment first'
                    })
                }
            }

            function deleteTrx() {
                let formdata = new FormData();
                formdata.append('transactionId', this.transaction[0].transactionId)
                formdata.append('subscriptionId', this.transaction[0].subscriptionId)
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger ml-1'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure delete this transaction?',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: "<i class='fa fa-times'></i> Cancel",
                    confirmButtonText: "<i class='fa fa-check'></i> Yes, delete!",
                    reverseButtons: false
                }).then((res) => {
                    if (res.isConfirmed) {
                        axios({
                            url: '<?= base_url('CustomersTransaction/delete') ?>',
                            method: 'POST',
                            data: formdata
                        }).then((r) => {
                            let rsp = r.data;
                            if (rsp.status == 200) {
                                swal.fire({
                                    icon: 'success',
                                    title: rsp.message
                                })
                            }
                        })
                    }
                })
            }

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            return {
                transaction,
                package,
                packagePrice,
                approveDate,
                formatNumber,
                approve,
                deleteTrx,
                contact
            };
            onMounted(() => {})
        },
    }).mount('#app');
    $(document).ready(function() {
        let min = moment(v.transaction[0].issueDate).format("YYYY-MM-DD hh:mm").replace(" ", "T");
        let max = moment(v.transaction[0].dueDate).format("YYYY-MM-DD hh:mm").replace(" ", "T");
        $('#approveDate').attr('min', min);
        $('#approveDate').attr('max', max);
    })
</script>
<?= $this->endSection(); ?>