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
                    <h5 class="header-icon">
                        <a href="<?= $_SERVER['HTTP_REFERER'] ?? site_url("role") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                    </h5>
                </div>
                <div class="pt-4">
                    <div class="row">
                        <div class="col">
                            <label for="type"><span class="text-danger">*</span>Choose Package as You Wish</label>
                            <select name="type" id="type">
                                <option value="" selected disabled>Select Package</option>
                                <?php foreach ($package as $key => $value) : ?>
                                    <option value="<?= $value['packageId'] ?>"><?= $value['packageGroupName'] . ' - ' . $value['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="type"><span class="text-danger">*</span>Choose Time Of Contract</label>
                            <select name="time" id="time">
                                <option value="" selected disabled>Select Package</option>
                                <option value="1-Month">1 Month</option>
                                <option value="3-Month">3 Month</option>
                                <option value="6-Month">6 Month</option>
                                <option value="12-Month">12 Month</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div :class="selected != '' && price != '' ? 'pt-4' : 'd-none'">
                    <div class="row">
                        <div class="col">
                            <h3>
                                Detail Payment
                            </h3>
                            <template v-if="selected != ''">
                                <div class="py-4">
                                    <div class="row">
                                        <div class="col">
                                            <h5>Package</h5>
                                        </div>
                                        <div class="col">
                                            <h5>{{ selected.packageGroupName + ' ' + selected.name + ' - ' + time.replace('-', " ") }}</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h6><i class="fa fa-check text-success"></i> Asset</h6>
                                        </div>
                                        <div class="col">
                                            <h6>{{ formatNumber(selected.assetMax) }}</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h6><i class="fa fa-check text-success"></i> Parameter</h6>
                                        </div>
                                        <div class="col">
                                            <h6>{{ formatNumber(selected.parameterMax) }}</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h6><i class="fa fa-check text-success"></i> Tag</h6>
                                        </div>
                                        <div class="col">
                                            <h6>{{ formatNumber(selected.tagMax) }}</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h6><i class="fa fa-check text-success"></i> Daily Transaction</h6>
                                        </div>
                                        <div class="col">
                                            <h6>{{ formatNumber(selected.trxDailyMax) }}</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h6><i class="fa fa-check text-success"></i> User</h6>
                                        </div>
                                        <div class="col">
                                            <h6>{{ formatNumber(selected.userMax) }}</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h5>Total</h3>
                                        </div>
                                        <div class="col">
                                            <p><b>Rp. {{ formatNumber(price) }}</b></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div :class="selected != '' && price != '' ? 'py-2' : 'd-none'">
                    <h3>
                        Choose Bank Transfer
                    </h3>
                    <div class="btn-group-toggle">
                        <label class="btn btn-outline-primary mx-1  mb-1" style="width: calc(25% - 0.5rem) !important;">
                            <input type="radio" name="options" data-content="mandiri" id="mandiri" autocomplete="off">Mandiri
                        </label>
                        <!-- <label class="btn btn-outline-primary mx-1  mb-1" style="width: calc(25% - 0.5rem) !important;">
                            <input type="radio" name="options" data-content="bri" id="bri" autocomplete="off">BRI
                        </label>
                        <label class="btn btn-outline-primary mx-1  mb-1" style="width: calc(25% - 0.5rem) !important;">
                            <input type="radio" name="options" data-content="bca" id="bca" autocomplete="off">BCA
                        </label>
                        <label class="btn btn-outline-primary mx-1  mb-1" style="width: calc(25% - 0.5rem) !important;">
                            <input type="radio" name="options" data-content="bni" id="bni" autocomplete="off">BNI
                        </label>
                        <label class="btn btn-outline-primary mx-1  mb-1" style="width: calc(25% - 0.5rem) !important;">
                            <input type="radio" name="options" data-content="permata" id="permata" autocomplete="off">Permata
                        </label> -->
                    </div>
                </div>
                <div :class="selected != '' && price != '' ? 'py-2' : 'd-none'">
                    <button @click="payment()" class="btn btn-success"><i class="fa fa-arrow-circle-up"></i> Upgrade Now</button>
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
            var package = <?= json_encode($package) ?>;
            var packagePrice = <?= json_encode($packagePrice) ?>;
            var bank = ref("");
            var selected = ref("");
            var selectedPrice = ref("");
            var price = ref("");
            var type = ref("");
            var time = ref("");

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }

            function payment() {
                if (v.selected != '' && v.selectedPrice != '' && v.bank != '') {
                    // v.selected.price = v.price;
                    v.selected.period = v.time;
                    v.selected.bank = v.bank;
                    v.selected.packagePrice = v.selectedPrice;
                    let formdata = new FormData();
                    formdata.append('package', JSON.stringify(this.selected));
                    axios({
                        url: "<?= base_url('/invoiceUpgrade') ?>",
                        method: 'POST',
                        data: formdata
                    }).then((res) => {
                        let rsp = res.data;
                        if (rsp.status == 200) {
                            window.location.href = "<?= base_url('/payment'); ?>"
                        } else {

                        }
                    })
                } else {
                    if (v.selected == '') {
                        return swal.fire({
                            title: 'Please Select Package',
                            icon: 'warning'
                        })
                    } else if (v.price == '') {
                        return swal.fire({
                            title: 'Please Select Time Contract',
                            icon: 'warning'
                        })
                    } else if (v.bank == '') {
                        return swal.fire({
                            title: 'Please Select Bank',
                            icon: 'warning'
                        })
                    } else {
                        return swal.fire({
                            title: 'Please Select Package, Time Contract and Bank',
                            icon: 'warning'
                        })
                    }
                }
            }
            onMounted(() => {
                $('#type').select2({
                    theme: 'coreui',
                    placeholder: 'Select Package'
                })
                $('#time').select2({
                    theme: 'coreui',
                    placeholder: 'Select Time'
                })
            })
            return {
                package,
                type,
                time,
                packagePrice,
                price,
                selected,
                selectedPrice,
                formatNumber,
                bank,
                payment
            }
        },
    }).mount('#app');

    $('#type').on('change', function() {
        v.type = $(this).val();
        v.packagePrice.forEach((el, i) => {
            if (el.packageId == $(this).val() && el.period == v.time) {
                v.price = el.price;
                // v.selected.packagePriceId = el.packagePriceId;
                v.selectedPrice = el
            }
        });
        v.package.forEach((el, i) => {
            if (el.packageId == $(this).val()) {
                v.selected = el;
            }
        })
    })
    $('#time').on('change', function() {
        v.time = $(this).val();
        v.packagePrice.forEach((el, i) => {
            if (el.packageId == v.type && el.period == $(this).val()) {
                v.price = el.price;
                v.selectedPrice = el
            }
        });
    })
    $('input[type="radio"][name="options"]').on('change', function() {
        if (v.bank != '') {
            let el = '#' + v.bank;
            $(el).parent().removeClass('active');
        }
        let id = $(this)[0].id;
        let text = $(this)[0].dataset.content;
        v.bank = id;
        $(this).parent().addClass('active');
    })
</script>
<?= $this->endSection(); ?>