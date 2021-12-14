<?= $this->extend('Layout/main'); ?>
<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<style>
    .select2-container .select2-selection--multiple {
        max-height: 50px !important;
    }

    .card-bill {
        box-shadow: 0 2px 4px rgb(0 0 0 / 15%) !important;
    }

    .info {
        background-color: #e7f3fe;
        border-left: 6px solid #2196F3;
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
</style>
<?= $this->endSection(); ?>
<?= $this->section('content') ?>
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
                <div class="d-flex justify-content-between align-items-center">
                    <h2><?= $title ?> <span class="text-info">#{{ transaction[0].refNumber }}</span></h2>
                    <h5 class="header-icon">
                        <a href="<?= base_url('/Subscription') ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                    </h5>
                </div>
                <div style="border: 1px solid #f0f0f0 !important;">
                    <div>
                        <div class="px-4 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div v-if="transaction[0].paidDate == null && transaction[0].cancelDate == null">
                                        <h4 class="m-0 text-danger text-uppercase">Unpaid</h4>
                                    </div>
                                    <div v-else-if="transaction[0].paidDate == null && transaction[0].cancelDate != null">
                                        <h4 class="m-0 text-warning text-uppercase">Cancelled</h4>
                                    </div>
                                    <div v-else>
                                        <h4 class="m-0 text-success text-uppercase">Paid</h4>
                                    </div>
                                </div>
                                <button @click="download()" class="btn btn-sm btn-outline-primary"><i class="fa fa-file-pdf"></i> Download Pdf</button>
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
                                                <td>{{ transaction[0].packageDescription.split(" -") ? transaction[0].packageDescription.split(" -")[0] : '' }}</td>
                                                <td>{{ transaction[0].packageDescription }}</td>
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
                    <div class="row px-4 py-2">
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
                <!-- <div class="d-none mt-2 d-flex flex-row justify-content-start align-items-left w-100">
                    <ul class="nav nav-tabs w-100 d-flex flex-row" role="tablist">
                        <li class="nav-item pr-4">
                            <a class="nav-link active py-0 pl-0" data-toggle="tab" href="#asset" role="tab" aria-controls="asset" id="tabAsset">
                                <h5>ATM Transfer</h5>
                            </a>
                        </li>
                        <li class="nav-item pr-4">
                            <a class="nav-link py-0 pl-0" data-toggle="tab" href="#parameter" role="tab" aria-controls="parameter" id="tabParameter">
                                <h5>MBanking</h5>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="d-none tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="asset" role="tabpanel" aria-labelledby="tabAsset">
                        <div class="row pt-4">
                            <div class="col">
                                <div class="info py-2">
                                    <p class="mx-2 mb-0"><strong>NOTE :</strong></p>
                                    <p class="mx-2 mb-0">
                                        Please make payment only with the account number listed below and confirm payment after you make payment.
                                    </p>
                                </div>
                                <div class="py-2">
                                    <b>Destination Bank</b>
                                </div>
                                <div class="my-2">
                                    <select class="py-2" name="bank" id="bank1">
                                        <option selected disabled value="mandiri">MANDIRI, Account Number <b>1800010111674</b> PT. NOCOLA IOT SOLUTION</option>
                                    </select>
                                </div>
                                <ol class="my-4">
                                    <li class="pb-2">Select the Transfer menu</li>
                                    <li class="pb-2">Select the menu to Mandiri Account</li>
                                    <li class="pb-2">Enter the destination account number (select True)</li>
                                    <li class="pb-2">Enter the amount of money to be transferred (select True)</li>
                                    <li class="pb-2">Pay attention to the transfer confirmation, if correct select True</li>
                                    <li class="pb-2">The transaction has been completed, select Exit or press Cancel and then you can do confirmation.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="parameter" role="tabpanel" aria-labelledby="tabParameter">
                        <div class="row pt-4">
                            <div class="col">
                                <div class="info py-2">
                                    <p class="mx-2 mb-0"><strong>NOTE :</strong></p>
                                    <p class="mx-2 mb-0">
                                        Please make payment only with the account number listed below and confirm payment after you make payment.
                                    </p>
                                </div>
                                <div class="py-2">
                                    <b>Destination Bank</b>
                                </div>
                                <div class="my-2">
                                    <select class="py-2" name="bank" id="bank2">
                                        <option selected disabled value="mandiri">MANDIRI, Account Number <b>1800010111674</b> PT. NOCOLA IOT SOLUTION</option>
                                    </select>
                                </div>
                                <ol class="my-4">
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
                </div> -->
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
            var transaction = <?= json_encode($transaction) ?>;
            var contact = <?= json_encode($contact) ?>;

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            function download() {
                let formdata = new FormData();;
                formdata.append('transaction', JSON.stringify(this.transaction[0]));
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
                        downloadLink.download = v.transaction[0].refNumber + '.pdf';
                        downloadLink.click();
                    }
                })
            }

            onMounted(() => {
                $('#bank').select2({
                    theme: 'coreui'
                })
                $('#bank1').select2({
                    theme: 'coreui'
                })
                $('#bank2').select2({
                    theme: 'coreui'
                })
            })
            return {
                transaction,
                contact,
                formatNumber,
                download
            }
        },
    }).mount('#app');
</script>
<?= $this->endSection(); ?>