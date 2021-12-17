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
                        <img src="<?= base_url('/img/ilustration/reset-password.png') ?>" class="w-100">
                    </div>
                    <div class="card card-main">
                        <div class="card-body p-sm-5">
                            <img src="<?= base_url('/img/ilustration/forgot-password.png') ?>" class="w-100 d-block d-sm-none">
                            <div class="d-sm-flex justify-content-between d-none">
                                <img src="<?= base_url('/img/logo-act.png') ?>" width="120">
                                <a href="<?= site_url("") ?>" class="h6 mb-0 text-muted font-weight-500 text-decoration-none">Sign In</a>
                            </div>
                            <div style="height: 100%;" :class="token ? '' : 'd-none'">
                                <form class="mt-3 mt-sm-5" action="<?= base_url() ?>/resetPassword" method="POST" ref="form" @submit.prevent="resetPassword">
                                    <input name="token" type="hidden" v-model="token">
                                    <h2 class="">Reset Password</h2>
                                    <span class="text-muted">Email verification is done. Please choose another password</span>
                                    <div class="form-group mt-4">
                                        <label class="col-form-label">Password : </label>
                                        <div class="controls">
                                            <div class="input-group" :class="passwordErr ? 'invalid-value' : ''">
                                                <input class="form-control" name="password" type="password" placeholder="At least 6 character" v-model="password">
                                                <div class="input-group-append" @click="showPassword = !showPassword;" style="cursor: pointer">
                                                    <span class="input-group-text bg-white">
                                                        <i class="fa" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="help-block text-danger" :class="passwordErr ? '' : 'd-none'">{{passwordErr}}</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Confirm Password : </label>
                                        <div class="controls">
                                            <div class="input-group" :class="confPassErr ? 'invalid-value' : ''">
                                                <input class="form-control" name="confPass" type="password" placeholder="At least 6 character" v-model="confPass">
                                                <div class="input-group-append" @click="showConfPassword = !showConfPassword;" style="cursor: pointer">
                                                    <span class="input-group-text bg-white">
                                                        <i class="fa" :class="showConfPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="help-block text-danger" :class="confPassErr ? '' : 'd-none'">{{confPassErr}}</p>
                                        </div>
                                    </div>
                                    <button class="btn btn-info w-100 mt-4" type="submit" v-html="submitBtn"></button>

                                    <div class="w-100 text-center mt-4">
                                        <a href="<?= site_url() ?>" class="text-info font-weight-500">Go back to the sign in page</a>
                                    </div>
                                </form>
                            </div>
                            <div class="align-items-center flex-row" :class="token ? 'd-none' : 'd-flex'" style="height: 100%;">
                                <div class="text-center">
                                    <h4>Invalid Email & Token</h4>
                                    <p>Make sure that you get access this page from inbox email or do <a href="<?= base_url("forgotPassword") ?>" class="text-info">forgot password?</a> again</p>
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
                const form = ref(null);
                const submitBtn = ref('Reset Password');

                const token = ref('<?= $token ?>');
                const password = ref('');
                const confPass = ref('');
                const passwordErr = ref(null);
                const confPassErr = ref(null);
                
                const showPassword = ref(null);
                const showConfPassword = ref(null);

                const resetPassword = () => {
                    if (!password.value || (password.value ?? "").length < 6 || password.value != confPass.value) {
                        passwordErr.value = !password.value ? 'Input your password' : (password.value.length < 6 ? "Password must be at least 6 characters" : null);
                        confPassErr.value = password.value != confPass.value ? 'Password doesn\'t match' : null;
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
                                    Swal.fire({
                                        title: resData.status,
                                        icon: 'success',
                                        text: resData.message,
                                        timer: 3000
                                    }).then(() => {
                                        location.href = "<?= base_url() ?>";
                                    });
                                } else if (resData.status == 400) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: "Bad Request",
                                        text: resData.message
                                    });
                                } else {
                                    Swal.fire({
                                        title: resData.status,
                                        icon: resData.alertType ?? 'error',
                                        text: resData.message
                                    });
                                }

                                submitBtn.value = 'Reset Password';
                            })
                            .catch((rej) => {
                                if (rej.throw) {
                                    throw new Error(rej.message);
                                }
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Internal server error'
                                });

                                submitBtn.value = 'Reset Password';
                            });
                    }
                }

                Vue.watch(
                    password,
                    (state, prevState) => {
                        passwordErr.value = !state ? 'Input your password' : (state.length < 6 ? "Password must be at least 6 characters" : null);
                    }
                )

                Vue.watch(
                    confPass,
                    (state, prevState) => {
                        confPassErr.value = password.value != state ? 'Password doesn\'t match' : null;
                    }
                )

                return {
                    form,
                    submitBtn,
                    token,
                    password,
                    confPass,
                    passwordErr,
                    confPassErr,
                    resetPassword
                }
            }
        }).mount('#app');
    </script>
</body>

</html>