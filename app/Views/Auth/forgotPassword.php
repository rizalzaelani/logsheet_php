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
    <title>Forgot Password | Logsheet Digital</title>

    <link href="<?= base_url(); ?>/css/style.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/css/custom-style.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/icons/coreui/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/vendors/sweetalert2/sweetalert2.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/vendors/fontawesome/css/all.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/vendors/select2/css/select2-coreui.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/vendors/select2/css/select2.min.css" rel="stylesheet">
</head>
<style>
    .invalid-value {
        border: 1px solid #e55353;
        border-radius: 0.25rem;
    }

    .select2-container {
        width: unset !important;
    }

    .font-weight-500 {
        font-weight: 500;
    }

    @media (max-width: 576px) {
        .card {
            box-shadow: unset !important;
        }

        body {
            background-color: #fff !important;
        }
    }
</style>

<body class="c-app flex-sm-row align-items-sm-center">
    <div class="container" id="app">
        <div class="row justify-content-center">
            <div class="col-sm-10 p-0 p-sm-1">
                <div class="card-group">
                    <div class="card card-main d-md-down-none d-flex flex-row align-items-center">
                        <img src="<?= base_url('/img/ilustration/forgot-password.png') ?>" class="w-100">
                    </div>
                    <div class="card card-main">
                        <div class="card-body p-sm-5">
                            <img src="<?= base_url('/img/ilustration/forgot-password.png') ?>" class="w-100 d-block d-sm-none">
                            <div class="d-sm-flex justify-content-between d-none">
                                <img src="<?= base_url('/img/logo-act.png') ?>" width="120">
                                <a href="<?= site_url("") ?>" class="h6 mb-0 text-muted font-weight-500 text-decoration-none">Sign In</a>
                            </div>
                            <div style="height: 100%;" :class="submited ? 'd-none' : ''">
                                <form class="mt-3 mt-sm-5" action="<?= base_url() ?>/forgotPassword/sendMail" method="POST" ref="form" @submit.prevent="sendMail">
                                    <h2 class="text-center mb-4">Forgot Your Password?</h2>
                                    <div class="input-group mb-0 mt-3" :class="emailErr ? 'invalid-value' : ''">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <svg class="c-icon">
                                                    <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-user') ?>"></use>
                                                </svg>
                                            </span>
                                        </div>
                                        <input class="form-control" name="email" type="email" placeholder="Email" v-model="email">
                                    </div>
                                    <div class="invalid-feedback-password" :class="emailErr ? '' : 'd-none'">{{emailErr}}</div>
                                    <button class="btn btn-info w-100 mt-4" type="submit" v-html="submitBtn"></button>

                                    <div class="w-100 text-center mt-4">
                                        <a href="<?= site_url() ?>" class="text-info font-weight-500">Go back to the sign in page</a>
                                    </div>
                                </form>
                            </div>
                            <div class="align-items-center flex-row" :class="!submited ? 'd-none' : 'd-flex'" style="height: 100%;">
                                <div class="text-center">
                                    <h4>We almost finished</h4>
                                    <p v-html="`Check your email <b>${email}</b>, We have sent you the necessary instructions to recover your password.`"></p>
                                    <a href="<?= site_url() ?>" class="text-info font-weight-500">Go back to the sign in page</a>
                                </div>
                            </div>
                        </div>
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
            ref
        } = Vue;
        const v = Vue.createApp({
            el: '#app',
            setup() {
                var form = ref(null);
                var submitBtn = ref('<i class="fab fa-telegram-plane"></i> Submit');
                var submited = ref(false);

                var email = ref('');
                var emailErr = ref(null);

                const sendMail = () => {
                    if (!email.value) {
                        emailErr.value = 'Input your email account';
                    } else {
                        submitBtn.value = '<i class="fa fa-spin fa-spinner"></i> Processing...';
                        let formdata = new FormData(form.value);
                        axios({
                                url: form.value.getAttribute("action"),
                                data: formdata,
                                method: 'POST'
                            }).then((res) => {
                                let resData = res.data;
                                if (resData.status == 200) {
                                    submited.value = true;

                                } else if (resData.status == 400) {
                                    Toast.fire({
                                        icon: 'warning',
                                        title: resData.message
                                    });
                                } else {
                                    Swal.fire({
                                        title: resData.status,
                                        icon: resData.alertType ?? 'error',
                                        text: resData.message
                                    });
                                }
                                
                                submitBtn.value = '<i class="fab fa-telegram-plane"></i> Submit';
                            })
                            .catch((rej) => {
                                if (rej.throw) {
                                    throw new Error(rej.message);
                                }
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Internal server error'
                                });

                                submitBtn.value = '<i class="fab fa-telegram-plane"></i> Submit';
                            });
                    }
                }

                Vue.onMounted(() => {
                    //
                });

                return {
                    form,
                    submitBtn,
                    submited,
                    email,
                    emailErr,
                    sendMail
                }
            }
        }).mount('#app');
    </script>
</body>

</html>