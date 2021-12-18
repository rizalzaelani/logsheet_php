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
                    <div class="card card-main">
                        <div class="card-body p-sm-5">
                            <img src="<?= base_url('/img/ilustration/login-animate.png') ?>" class="w-100 d-block d-sm-none">
                            <div class="d-sm-flex justify-content-between d-none">
                                <img src="<?= base_url('/img/logo-act.png') ?>" width="120">
                                <a href="<?= site_url("register") ?>" class="h6 mb-0 text-muted font-weight-500 text-decoration-none">Sign Up</a>
                            </div>
                            <form class="mt-3 mt-sm-5" :class="verifyEmail ? 'd-none' : ''" action="<?= base_url() ?>/Login/auth" method="POST" ref="form" @submit.prevent="login">
                                <h2>Sign In</h2>
                                <p class="text-medium-emphasis text-muted">Sign In to continue to Losheet Application</p>
                                <div class="input-group mt-3" :class="emailErr ? 'invalid-value' : ''">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
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
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
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
                                <div class="w-100 text-right mt-2">
                                    <a href="<?= site_url("forgotPassword") ?>" class="text-info font-weight-500">Forgot password?</a>
                                </div>
                                <div class="invalid-feedback-password d-none">
                                    Field cannot be empty.
                                </div>

                                <div class="d-flex justify-content-center mt-3">
                                    <div class="g-recaptcha" name="captcha" data-sitekey="<?= env('site_key') ?>"></div>
                                </div>
                                <button class="btn btn-info w-100 mt-4" type="submit" v-html="loginBtn" :disabled="loginBtn.includes('Processing')"></button>

                                <div class="d-block d-sm-none w-100 text-center mt-4">
                                    <span class="text-muted font-weight-500">New to Logsheet Digital? </span>
                                    <a href="<?= site_url("register") ?>" class="text-info font-weight-500"> Sign Up</a>
                                </div>
                            </form>
                            <template v-if="verifyEmail">
                                <div class="d-flex align-items-center" style="height: 85%;">
                                    <div class="w-100 text-center">
                                        <h4 class="mb-4">Please Verify Your Email Address</h4>
                                        <!-- <p>We noticed your email address has not been verified. By doing so, you will receive important </p> -->
                                        <p>Your email address must be verified before you can sign in. We've been send verification to your email when registration completed. <br />or <a href="javascript:;" @click="sendVerifyEmail()" class="text-info">Send the verification again</a></p>
                                    </div>
                                </div>
                                <div class="w-100 text-center"><a href="javascript:;" @click="verifyEmail = false;" class="text-info font-weight-500">Go back to the sign in page</a></div>
                            </template>
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
    <script type="text/javascript" src="<?= base_url() ?>/js/string-manipulation.js"></script>

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

                var verifyEmail = ref(false);

                var userIdVE = ref('');
                var emailVE = ref('');

                var email = ref('');
                var emailErr = ref(null);
                var password = ref('');
                var passwordErr = ref(null);

                const login = () => {
                    if (!email.value || !password.value) {
                        emailErr.value = (!email.value ? 'Input your email account' : null);
                        passwordErr.value = (!passwordErr.value ? 'Input your password' : null);
                    } else {
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

                                    // localStorage.removeItem("tagData");
                                    // localStorage.removeItem("tagLocationData");

                                    localStorage.setItem("tagLocExpire", moment().add("minutes", 30).valueOf());
                                    localStorage.setItem("tagData", JSON.stringify(resData.data.tagData));
                                    localStorage.setItem("tagLocationData", JSON.stringify(resData.data.tagLocationData));

                                    let urlParams = new URLSearchParams(window.location.search);
                                    let returnUrl = urlParams.get('ReturnUrl');
                                    if (returnUrl == null || returnUrl == "") {
                                        window.location.href = "<?= site_url("Dashboard") ?>";
                                    } else {
                                        window.location.href = location.protocol + "//" + location.host + returnUrl;
                                    }
                                } else if (resData.status == 400) {
                                    if (resData.email) {
                                        userIdVE.value = resData.userId;
                                        emailVE.value = resData.email;

                                        verifyEmail.value = true;
                                    } else {
                                        Toast.fire({
                                            icon: 'warning',
                                            title: resData.message
                                        });
                                    }
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

                const sendVerifyEmail = () => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Wait a minutes',
                        text: "Please don't leave this page",
                        allowOutsideClick: false,
                        showConfirmButton: false
                    })
                    axios({
                            url: '<?= base_url("login/sendMailVerification") ?>',
                            data: {
                                email: emailVE.value,
                                userId: userIdVE.value,
                            },
                            method: 'POST'
                        }).then((res) => {
                            let resData = res.data;
                            if (resData.status == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Verification have been send to your email',
                                    text: "Click on the link you received in the email address to finish the verification"
                                });
                            } else if (resData.status == 400) {
                                if (!isNullEmptyOrUndefined(resData.data) & typeof(resData.data) == "object") {

                                    let outAlert = `<ul class="list-group">`;
                                    for (let r in resData.data) {
                                        outAlert += `<li class="list-group-item list-group-item-warning">${resData.data[r]}</li>`;
                                    }
                                    outAlert += `</ul>`;

                                    Swal.fire({
                                        title: resData.message,
                                        icon: resData.alertType ?? 'warning',
                                        html: outAlert
                                    })
                                } else {
                                    Swal.fire({
                                        title: CapitalizeEachWords(resData.alertType),
                                        text: resData.message,
                                        icon: resData.alertType ?? 'warning',
                                    })
                                }
                            } else {
                                Swal.fire({
                                    title: resData.status,
                                    icon: resData.alertType ?? 'error',
                                    text: resData.message
                                });
                            }
                        })
                        .catch((rej) => {
                            if (rej.throw) {
                                throw new Error(rej.message);
                            }
                            Swal.fire({
                                icon: 'error',
                                title: rej.message
                            });
                        });
                }

                Vue.onMounted(() => {
                    //
                });

                return {
                    form,
                    loginBtn,
                    verifyEmail,
                    showPassword,
                    email,
                    emailErr,
                    password,
                    passwordErr,
                    login,
                    sendVerifyEmail
                }
            }
        }).mount('#app');
    </script>
</body>

</html>