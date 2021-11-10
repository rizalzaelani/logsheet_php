<!DOCTYPE html>
<!--
* CoreUI Pro based Bootstrap Admin Template
* @version v3.2.0
* @link https://coreui.io/pro/
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* License (https://coreui.io/pro/license)
-->
<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>CoreUI Pro Bootstrap Admin Template</title>
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
                <div class="card-group shadow" style="border-radius: 20px 20px;">
                    <div class="card card-main bg-primary d-md-down-none" style="border-radius: 20px 0px 0px 20px; padding: 5rem 1rem;">
                        <div class="card-body">
                            <h1 class="text-white">Logsheet Digital</h1>
                            <p class="text-white">Sign In to your account</p>
                            <div class="input-group my-4" id="email">
                                <div class="input-group-prepend"><span class="input-group-text">
                                        <svg class="c-icon">
                                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-user') ?>"></use>
                                        </svg></span></div>
                                <input class="form-control" type="email" placeholder="Email" v-model="email">
                            </div>
                            <div class="invalid-feedback-email d-none">
                                Field cannot be empty.
                            </div>
                            <div class="input-group my-4" id="password">
                                <div class="input-group-prepend"><span class="input-group-text">
                                        <svg class="c-icon">
                                            <use xlink:href="<?= base_url('/icons/coreui/svg/linear.svg#cil-lock-locked') ?>"></use>
                                        </svg></span></div>
                                <input class="form-control" type="password" placeholder="Password" v-model="password">
                                <div class="input-group-append" @click="togglePassword()">
                                    <span class="input-group-text bg-white" @click="togglePassword()">
                                        <i class="fa fa-eye-slash" id="togglePassword" @click="togglePassword()" style="cursor: pointer"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="invalid-feedback-password d-none">
                                Field cannot be empty.
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn w-100 px-4 text-primary" style="background-color: white;" @click="submit()"><i class="fa fa-sign-in"></i> LOGIN</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-main d-md-down-none" style="padding: 5rem 0; border-radius: 0px 20px 20px 0px;">
                        <div class="card-body d-flex justify-content-center align-items-center text-center">
                            <div class="row">
                                <img src="<?= base_url('/img/logo-act.png') ?>" height="80" width="324" class="c-sidebar-brand-full">
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
                    } else if($('#password input').attr('type') == 'password'){
                        $('#password input').attr('type', 'text');
                        $('#togglePassword').removeClass('fa-eye-slash');
                        $('#togglePassword').addClass('fa-eye');
                    }
                }

                function submit() {
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

                        })
                    }
                }
                return {
                    email,
                    password,
                    togglePassword,
                    submit
                }
            }
        }).mount('#app');
    </script>
</body>

</html>