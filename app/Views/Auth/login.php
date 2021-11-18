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
    <?php if (isset($css)) : ?>
        <?php foreach ($css as $item) : ?>
            <link href="<?= $item ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
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
                            <form class="mt-5" v-on:submit.prevent="login()">
                                <h2>Sign In</h2>
                                <p class="text-medium-emphasis text-muted">Sign In to continue to Losheet Application</p>
                                <div class="input-group my-4" id="email">
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
                                <div class="input-group my-4" id="password">
                                    <div class="input-group-prepend"><span class="input-group-text">
                                            <svg class="c-icon">
                                                <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-lock-locked') ?>"></use>
                                            </svg>
                                        </span>
                                    </div>
                                    <input class="form-control" name="password" type="password" placeholder="Password" v-model="password">
                                    <div class="input-group-append" @click="togglePassword()">
                                        <span class="input-group-text bg-white" @click="togglePassword()">
                                            <i class="fa fa-eye-slash" id="togglePassword" @click="togglePassword()" style="cursor: pointer"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="invalid-feedback-password d-none">
                                    Field cannot be empty.
                                </div>
                                <button class="btn btn-info w-100" @click="login()"><i class="fa fa-sign-in"></i> LOGIN</button>
                            </form>
                        </div>
                    </div>
                    <div class="card card-main d-md-down-none">
                        <img src="<?= base_url('/img/ilustration/login-animate.png') ?>" class="w-100">
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
        const {
            ref
        } = Vue;
        const v = Vue.createApp({
            el: '#app',
            setup() {
                var email = ref('');
                var password = ref('');

                function togglePassword() {
                    // console.log($('#showHidePassword input').attr('type') == 'text' ? true : false);
                    if ($('#password input').attr('type') == 'text') {
                        $('#password input').attr('type', 'password');
                        $('#togglePassword').removeClass('fa-eye');
                        $('#togglePassword').addClass('fa-eye-slash');
                    } else if ($('#password input').attr('type') == 'password') {
                        $('#password input').attr('type', 'text');
                        $('#togglePassword').removeClass('fa-eye-slash');
                        $('#togglePassword').addClass('fa-eye');
                    }
                }

                function login() {
                    if (this.email == "" || this.password == "") {
                        swal.fire({
                            title: 'Invalid Value',
                            icon: 'error'
                        })
                        if (!this.email) {
                            $('#email').addClass('invalid-value');
                            // $('.invalid-feedback-email').removeClass('d-none');
                        } else {
                            $('#email').removeClass('invalid-value');
                            // $('.invalid-feedback-email').addClass('d-none');
                        }

                        if (!this.password) {
                            $('#password').addClass('invalid-value');
                            // $('.invalid-feedback-password').removeClass('d-none');
                        } else {
                            $('#password').removeClass('invalid-value');
                            // $('.invalid-feedback-password').addClass('d-none');
                        }
                    } else {
                        var formdata = new FormData();
                        formdata.append('email', this.email);
                        formdata.append('password', this.password);
                        axios({
                            url: "<?= base_url('Login/auth') ?>",
                            data: formdata,
                            method: 'POST'
                        }).then((res) => {
                            let dt = res.data;
                            if (dt.status == 200) {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    iconColor: 'white',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                    customClass: {
                                        popup: 'colored-toast'
                                    },
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })
                                Toast.fire({
                                    icon: 'success',
                                    title: dt.message
                                })

                                window.location.href = "<?= base_url('/Dashboard') ?>";
                            } else if (dt.status == 400) {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    iconColor: 'white',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                    customClass: {
                                        popup: 'colored-toast'
                                    },
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })
                                Toast.fire({
                                    icon: 'error',
                                    title: dt.message
                                })
                            }
                        })
                    }
                }
                return {
                    email,
                    password,
                    togglePassword,
                    login
                }
            }
        }).mount('#app');
    </script>
</body>

</html>