<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title; ?> | Logsheet Digital</title>
    <style>
        body {
            background-color: #f3f7fe !important;
        }

        .card {
            border: 0px !important;
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
    <?php if (isset($css)) : ?>
        <?php foreach ($css as $item) : ?>
            <link href="<?= $item ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body id="app">
    <div class="container container-sm py-4" style="padding-left: 3rem; padding-right: 3rem;">
        <div class="row">
            <div class="col">
                <div class="card card-main">
                    <div class="card-header pl-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="text-uppercase">Detail Invoice {{ invoice[0].ref_number }}</h2>
                            <button class="btn btn-sm btn-outline-primary" @click="downloadInvoice()"><i class="fa fa-file-pdf"></i> Download Invoice</button>
                        </div>
                    </div>
                    <div class="card-body pl-0 my-2" style="border: 1px solid #f0f0f0 !important;">
                        <div class="px-4 py-2 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div v-if="invoice[0].status_id == 1">
                                        <h4 class="m-0 text-danger">Unpaid</h4>
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
                                <h6 class="text-primary">{{ invoice[0].contact.name }}</h6>
                                <div class="ml-1">
                                    <table>
                                        <tr>
                                            <td>
                                                <p class="text-muted mb-1"><i class="fa fa-id-card"></i></p>
                                            </td>
                                            <td>
                                                <p class="text-muted mb-1">{{ invoice[0].contact.company }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="text-muted mb-1"><i class="fa fa-home"></i></p>
                                            </td>
                                            <td>
                                                <p class="text-muted mb-1">{{ invoice[0].contact.address }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="text-muted mb-1"><i class="fa fa-phone"></i></p>
                                            </td>
                                            <td>
                                                <p class="text-muted mb-1">{{ invoice[0].contact.phone }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-1">
                                    <p><b>Nomor</b></p>
                                    <h6 class="text-uppercase">{{ invoice[0].ref_number }}</h6>
                                </div>
                                <div>
                                    <p><b>Description</b></p>
                                    <h6>{{ invoice[0].memo }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="row px-4 py-2">
                            <div class="col">
                                <p><b>Tanggal Transaksi</b></p>
                                <h6>{{ invoice[0].trans_date }}</h6>
                            </div>
                            <div class="col">
                                <p><b>Tanggal Jatuh Tempo</b></p>
                                <h6>{{ invoice[0].due_date }}</h6>
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
                                                <td>{{ dataInvoice.data.items[0].product.name }}</td>
                                                <td>{{ dataInvoice.data.items[0].desc }}</td>
                                                <td>{{ dataInvoice.data.items[0].qty }}</td>
                                                <td>Package</td>
                                                <td>{{ dataInvoice.data.items[0].discount_percent }}%</td>
                                                <td>{{ dataInvoice.data.items[0].amount }}</td>
                                                <td v-if="(dataInvoice.data.items[0]).item_tax != null">
                                                    {{ dataInvoice.data.items[0].item_tax.name }}
                                                </td>
                                                <td v-else>
                                                    -
                                                </td>
                                                <!-- <td>{{ dataInvoice.data.items[0].item_tax.name }}</td> -->
                                                <td>{{ dataInvoice.data.items[0].amount }}</td>
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
                                            <th>{{ dataInvoice.data.items[0].subtotal }}</th>
                                        </tr>
                                        <tr>
                                            <th>PPN 10%</th>
                                            <th>{{ dataInvoice.data.items[0].tax }}</th>
                                        </tr>
                                        <tr>
                                            <th>Total</td>
                                            <th>{{ dataInvoice.data.items[0].price_after_tax }}</td>
                                        </tr>
                                        <tr>
                                            <th>Sisa Tagihan</th>
                                            <th>{{ dataInvoice.data.items[0].price_after_tax }}</th>
                                        </tr>
                                    </table>
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
                </div>
            </div>
        </div>
    </div>
    <?php if ($js) : ?>
        <?php foreach ($js as $item) : ?>
            <script type="text/javascript" src="<?= $item ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    <script>
        const {onMounted} = Vue;
        let v = Vue.createApp({
            el: '#app',
            setup() {
                var invoice = <?= json_encode($invoice) ?>;
                var dataInvoice = JSON.parse(localStorage.getItem('invoice'));

                function downloadInvoice() {
                    let formdata = new FormData();
                    formdata.append('invoice', JSON.stringify(dataInvoice));
                    axios({
                        url: '<?= base_url('Wizard/Invoice/download') ?>',
                        method: 'POST',
                        data: formdata,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(res => {
                        let rsp = res.data;
                        if (rsp.status == 200) {
                            let base64 = rsp.data;
                            const linkSource = `data:applicaiton/pdf;base64,` + base64 + ``;
                            const downloadLink = document.createElement("a");
                            downloadLink.href = linkSource;
                            downloadLink.download = v.invoice[0].ref_number + '.pdf';
                            downloadLink.click();
                        }
                    })
                }
                onMounted(() => {
                    $('#bank').select2({
                        theme: 'coreui'
                    })
                })
                return {
                    invoice,
                    dataInvoice,
                    downloadInvoice,
                }
            }
        }).mount('#app');
    </script>
</body>

</html>