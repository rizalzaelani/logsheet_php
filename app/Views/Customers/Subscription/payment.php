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
                    <h2><?= $title ?></h2>
                    <a href="<?= base_url('/upgrade') ?>" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
                <div class="mt-2 d-flex flex-row justify-content-start align-items-left w-100">
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
                <div class="tab-content" id="myTabContent">
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
            onMounted(() => {
                $('#bank1').select2({
                    theme: 'coreui'
                })
                $('#bank2').select2({
                    theme: 'coreui'
                })
            })
            return {}
        },
    }).mount('#app');
</script>
<?= $this->endSection(); ?>