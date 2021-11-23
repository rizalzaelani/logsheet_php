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
    <title>Login Page | Logsheet Digital</title>

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
</style>

<body class="c-app flex-row align-items-center">
    <div class="container" id="app">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card-group shadow">
                    <div class="card card-main">
                        <div class="card-body p-5">
                            <div class="d-flex justify-content-between">
                                <img src="<?= base_url('/img/logo-act.png') ?>" width="120">
                                <a href="<?= site_url("register") ?>" class="h6 mb-0 text-muted font-weight-500 text-decoration-none">Sign Up</a>
                            </div>
                            <form class="mt-5" action="<?= base_url() ?>/Login/auth" method="POST" ref="form" @submit.prevent="login">
                                <h2>Sign In</h2>
                                <p class="text-medium-emphasis text-muted">Sign In to continue to Losheet Application</p>
                                <div class="input-group mt-3" :class="appCodeErr ? 'invalid-value' : ''">
                                    <select class="form-control w-100" name="appCode" v-model="appCode">
                                        <?php foreach ($appCode as $row) : ?>
                                            <option value="<?= $row; ?>"><?= $row; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="input-group mt-3" :class="emailErr ? 'invalid-value' : ''">
                                    <div class="input-group-prepend"><span class="input-group-text">
                                            <svg class="c-icon">
                                                <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-user') ?>"></use>
                                            </svg>
                                        </span>
                                    </div>
                                    <input class="form-control" name="email" type="email" placeholder="Email" v-model="email">
                                </div>
                                <div class="invalid-feedback-email d-none">
                                    Field cannot be empty.
                                </div>
                                <div class="input-group mt-3 mb-0" :class="passwordErr ? 'invalid-value' : ''">
                                    <div class="input-group-prepend"><span class="input-group-text">
                                            <svg class="c-icon">
                                                <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-lock-locked') ?>"></use>
                                            </svg>
                                        </span>
                                    </div>
                                    <input class="form-control" name="password" :type="showPassword ? 'text' : 'password'" placeholder="Password" v-model="password">
                                    <div class="input-group-append" @click="showPassword = !showPassword;" style="cursor: pointer">
                                        <span class="input-group-text bg-white">
                                            <i class="fa" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="w-100 text-right">
                                    <a href="" class="text-info">forgot password?</a>
                                </div>
                                <div class="invalid-feedback-password d-none">
                                    Field cannot be empty.
                                </div>

                                <div class="d-flex justify-content-center mt-3">
                                    <div class="g-recaptcha" name="captcha" data-sitekey="<?= env('site_key') ?>"></div>
                                </div>
                                <button class="btn btn-info w-100 mt-4" type="submit" v-html="loginBtn"></button>
                            </form>
                        </div>
                    </div>
                    <div class="card card-main d-md-down-none d-flex flex-row align-items-center">
                        <img src="<?= base_url('/img/ilustration/login-animate.png') ?>" class="w-100">
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

    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <script>
        const {
            ref
        } = Vue;
        const v = Vue.createApp({
            el: '#app',
            setup() {
                var form = ref(null);
                var loginBtn = ref('<i class="fas fa-sign-in-alt"></i> Sign In');
                var showPassword = ref(false);

                var appCode = ref('<?= $appCodeSelected ?>');
                var appCodeErr = ref(null);
                var email = ref('');
                var emailErr = ref(null);
                var password = ref('');
                var passwordErr = ref(null);

                const login = () => {
                    if (!email.value || !password.value) {
                        emailErr.value = (!email.value ? 'Input your email account' : null);
                        passwordErr.value = (!passwordErr.value ? 'Input your password' : null);
                    } else {
                        console.log(form.value)
                        loginBtn.value = '<i class="fa fa-spin fa-spinner"></i> Processing...';
                        let formdata = new FormData(form.value);
                        axios({
                                url: form.value.getAttribute("action"),
                                data: formdata,
                                method: 'POST'
                            }).then((res) => {
                                let resData = res.data;
                                if (resData.status == 200) {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Signed in successfully'
                                    });
                                    window.location.href = "<?= base_url('/Dashboard') ?>";
                                } else if (resData.status == 400) {
                                    Toast.fire({
                                        icon: 'warning',
                                        title: resData.message
                                    });
                                    loginBtn.value = '<i class="fas fa-sign-in-alt"></i> Sign In';
                                    password.value = '';
                                    grecaptcha.reset();
                                } else {
                                    Swal.fire({
                                        title: resData.status,
                                        icon: resData.alertType ?? 'error',
                                        text: resData.message
                                    });
                                    loginBtn.value = '<i class="fas fa-sign-in-alt"></i> Sign In';
                                    password.value = '';
                                    grecaptcha.reset();
                                }
                            })
                            .catch((rej) => {
                                if (rej.throw) {
                                    throw new Error(rej.message);
                                }
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Internal server error'
                                });

                                loginBtn.value = '<i class="fas fa-sign-in-alt"></i> Sign In';
                                grecaptcha.reset();
                            });
                    }
                }

                Vue.onMounted(() => {
                    $('select[name=appCode]').select2({
                        theme: 'coreui',
                        placeholder: "Application Code"
                    })
                });

                return {
                    form,
                    loginBtn,
                    showPassword,
                    appCode,
                    appCodeErr,
                    email,
                    emailErr,
                    password,
                    passwordErr,
                    login
                }
            }
        }).mount('#app');
    </script>
</body>

</html>