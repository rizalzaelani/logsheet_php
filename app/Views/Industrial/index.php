<!DOCTYPE html>
<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Logsheet Digital">
    <meta name="author" content="Nocola IoT Solution">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title><?= $title ?></title>

    <link href="<?= base_url(); ?>/css/style.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/css/custom-style.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/icons/coreui/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/vendors/sweetalert2/sweetalert2.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/vendors/fontawesome/css/all.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/vendors/select2/css/select2-coreui.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/vendors/select2/css/select2.min.css" rel="stylesheet">

    <style>
        .card-industri:hover {
            cursor: pointer !important;
            box-shadow: 0 4px 8px rgb(0 0 0 / 9%) !important;
        }

        .radio-card:checked+.card-industri {
            background-color: #003399;
            transition: all 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
            color: #fff;
        }

        .radio-card:checked+.card-industri .card-title hr {
            border-color: #fff !important;
        }

        .h-100 {
            min-height: 450px !important;
        }
    </style>
</head>

<body class="c-app flex-sm-row align-items-sm-center">
    <div class="container" id="app">
        <div class="row justify-content-center mt-4">
            <div class="col-sm-10 p-0">
                <div class="card card-main pt-0">
                    <div class="card-body pt-0">
                        <div class="card-header px-0 py-4 d-flex justify-content-between">
                            <h3 class="m-0">Select your industrial type</h3>
                        </div>
                        <div class="row pt-4 m-0 w-100">
                            <template v-for="(val, i) in category">
                                <div class="mx-1" style="width: calc(25% - 0.5rem) !important;">
                                    <label class="w-100">
                                        <input type="radio" name="industrial" class="form-control radio-card d-none" :value="val.categoryIndustryId">
                                        <div class="card h-100 card-main card-industri">
                                            <img class="card-img-top" :src="val.image" alt="">
                                            <div class="card-title">
                                                <hr>
                                                <h5 class="text-center m-0 text-uppercase">{{ val.categoryName }}</h5>
                                                <hr>
                                                <p class="card-text">
                                                    {{ val.description }}
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between pb-2">
                        <button class="btn btn-link mr-1 text-secondary" style="text-decoration: none !important;">Skip</button>
                        <button class="btn btn-link" @click="finish()" style="text-decoration: none !important;">Finish</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?= base_url() ?>/vendors/axios/axios.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/vendors/lodash/lodash.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/vendors/moment/moment.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/vendors/moment/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/vendors/vue/vue.global.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/vendors/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/vendors/jquery-ui/jquery-ui.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/vendors/sweetalert2/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/vendors/select2/js/select2.full.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/js/main.js"></script>
    <script>
        const {
            ref,
            reactive
        } = Vue;
        const v = Vue.createApp({
            el: '#app',
            setup() {
                var selected = ref("");
                var category = <?= json_encode($category) ?>;

                const finish = () => {
                    if (selected.value == "") {
                        return swal.fire({
                            icon: 'warning',
                            title: 'Please select your industrial type first!'
                        });
                    }
                    swal.fire({
                        icon: 'info',
                        title: 'Wait a minute..',
                        text: "Don't close this page before you are redirected to another page",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    })
                    axios({
                        url: '<?= base_url('Industrial/doGenerate') ?>',
                        method: 'POST',
                        data: {
                            'selected': selected.value
                        }
                    }).then((res) => {
                        let rsp = res.data;
                        if (rsp.status == 200) {} else {
                            swal.fire({
                                icon: 'error',
                                title: rsp.message
                            })
                        }
                    })
                }
                return {
                    selected,
                    category,

                    finish
                }
            }
        }).mount('#app');

        $('input[type="radio"][name="industrial"]').on('change', function() {
            v.selected = $(this).val();
        })
    </script>
</body>

</html>