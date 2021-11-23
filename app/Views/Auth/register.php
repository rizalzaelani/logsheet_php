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
    <title>Register Page | Logsheet Digital</title>


    <link href="<?= base_url(); ?>/css/style.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/css/custom-style.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/icons/coreui/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/vendors/sweetalert2/sweetalert2.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/vendors/fontawesome/css/all.css" rel="stylesheet">
</head>
<style>
    .invalid-value {
        border: 1px solid #e55353;
        border-radius: 0.25rem;
    }

    .invalid-feedback-password {
        color: #d93025;
        font-size: 12px;
    }
</style>

<body class="c-app flex-row align-items-center">
    <div class="container" id="app">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card-group">
                    <div class="card card-main">
                        <div class="card-body p-5">
                            <form v-on:submit.prevent="">
                                <h2>Sign Up</h2>
                                <p class="text-medium-emphasis text-muted">Register new account</p>
                                <div id="form1" :class="formPos == 1 ? '' : 'd-none'">
                                    <div class="form-group mb-0 mt-3">
                                        <label for="fullname">Full Name</label>
                                        <input class="form-control" id="fullname" v-model="registerData.fullname" @keyup="errInput.fullname = (registerData.fullname ? '' : 'Enter your full name')" type="text" placeholder="Enter your Full Name">
                                    </div>
                                    <span class="invalid-feedback-password" :class="errInput.fullname ? '' : 'd-none'">
                                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                                        </svg>
                                        {{ errInput.fullname }}
                                    </span>
                                    <div class="form-group mb-0 mt-3">
                                        <label for="noTelp">Phone Number</label>
                                        <input class="form-control" id="noTelp" v-model="registerData.noTelp" @keyup="errInput.noTelp = (registerData.noTelp ? '' : 'Enter your no telp')" type="text" placeholder="Enter Phone Number">
                                    </div>
                                    <span class="invalid-feedback-password" :class="errInput.noTelp ? '' : 'd-none'">
                                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                                        </svg>
                                        {{ errInput.noTelp }}
                                    </span>
                                    <div class="form-group mb-0 mt-3">
                                        <label for="email">E-mail</label>
                                        <input class="form-control" id="email" v-model="registerData.email" @keyup="errInput.email = (registerData.email ? '' : 'Enter your email')" type="email" placeholder="Enter your E-mail">
                                    </div>
                                    <span class="invalid-feedback-password" :class="errInput.email ? '' : 'd-none'">
                                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                                        </svg>
                                        {{ errInput.email }}
                                    </span>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mb-0 mt-3">
                                            <label for="password">Password</label>
                                            <input class="form-control" id="password" v-model="registerData.password" @keyup="errInput.password = (registerData.password ? '' : 'Enter your password')" type="password" placeholder="Enter your Password">
                                        </div>
                                        <div class="form-group col-sm-6 mb-0 mt-3">
                                            <label for="confirmPass">Confirm</label>
                                            <input class="form-control" id="confirmPass" v-model="registerData.confirmPass" type="password" placeholder="Confirm your passsword">
                                        </div>
                                    </div>
                                    <span class="invalid-feedback-password" :class="errInput.password ? '' : 'd-none'">
                                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                                        </svg>
                                        {{ errInput.password }}
                                    </span>
                                </div>
                                <div id="form2" :class="formPos == 2 ? '' : 'd-none'">
                                    <div class="d-flex justify-content-center col-form-label">
                                        <div class="form-check form-check-inline mr-3">
                                            <input class="form-check-input" id="radioAdmin" type="radio" value="admin" name="inline-radios" @change="adminClient = 1" :checked="adminClient == 1">
                                            <label class="form-check-label" for="radioAdmin">Admin</label>
                                        </div>
                                        <div class="form-check form-check-inline mr-3">
                                            <input class="form-check-input" id="radioClient" type="radio" value="client" name="inline-radios" @change="adminClient = 2" :checked="adminClient == 2">
                                            <label class="form-check-label" for="radioClient">Client</label>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="adminClient == 1 ? '' : 'd-none'">
                                        <label for="street">Application Name</label>
                                        <input class="form-control" id="appName" v-model="registerData.appName" type="text" placeholder="Enter App Name">
                                    </div>
                                    <div class="form-group" :class="adminClient == 2 ? '' : 'd-none'">
                                        <label for="street">Application Code</label>
                                        <input class="form-control" id="appCode" v-model="registerData.appCode" type="text" placeholder="Enter App Code">
                                    </div>
                                    <div class="form-group">
                                        <label for="street">Street</label>
                                        <input class="form-control" id="street" v-model="registerData.street" type="text" placeholder="Enter Street">
                                    </div>
                                    <span class="invalid-feedback-password" :class="errInput.street ? '' : 'd-none'">
                                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                                        </svg>
                                        {{ errInput.street }}
                                    </span>
                                    <div class="row">
                                        <div class="form-group col-sm-8">
                                            <label for="city">City</label>
                                            <input class="form-control" id="city" v-model="registerData.city" type="text" placeholder="Enter your city">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="postal-code">Postal Code</label>
                                            <input class="form-control" id="postalCode" v-model="registerData.postalCode" type="text" placeholder="Postal Code">
                                        </div>
                                    </div>
                                    <span class="invalid-feedback-password" :class="errInput.city ? '' : 'd-none'">
                                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                                        </svg>
                                        {{ errInput.city }}
                                    </span>
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input class="form-control" id="country" v-model="registerData.country" type="text" placeholder="Enter Country">
                                    </div>
                                    <span class="invalid-feedback-password" :class="errInput.country ? '' : 'd-none'">
                                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                                        </svg>
                                        {{ errInput.country }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary text-center px-3" :class="formPos > 1 ? '' : 'd-none'" @click="formPos = 1">Back</button>
                                    <a href="<?= site_url() ?>" type="button" class="btn btn-link text-info font-weight-500 text-decoration-none h6 mb-0" :class="formPos <= 1 ? '' : 'd-none'">Sign in instead</a>
                                    <button type="button" class="btn btn-info text-center px-3" :class="formPos <= 1 ? '' : 'd-none'" @click="validateForm(1)">Next</button>
                                    <button type="button" class="btn btn-info text-center px-3" :class="formPos > 1 ? '' : 'd-none'" @click="doRegister()">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card d-flex flex-row align-items-center d-md-down-none">
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
    <script>
        const {
            ref,
            reactive
        } = Vue;
        const v = Vue.createApp({
            el: '#app',
            setup() {
                var registerData = reactive([]);
                var errInput = reactive([]);
                var formPos = ref(1);
                var adminClient = ref(1);

                const validateForm = (formType) => {
                    if (registerData.fullname && registerData.noTelp && registerData.email && registerData.password && registerData.confirmPass) {
                        if (registerData.password != registerData.confirmPass) {
                            errInput.password = 'Those passwords didn’t match. Try again.';
                            formPos.value = 1;
                        } else {
                            errInput.fullname = null;
                            errInput.noTelp = null;
                            errInput.email = null;
                            errInput.password = null;

                            formPos.value = 2;

                            if (formType == 2) {
                                if (registerData.street && registerData.city && registerData.postalCode && registerData.country) {
                                    return true;
                                } else {
                                    if (!registerData.street) errInput.street = 'Enter your street address';
                                    if (!registerData.city) errInput.city = 'Enter your city';
                                    if (!registerData.postalCode) errInput.city = 'Enter your postalCode';
                                    if (!registerData.city && !registerData.postalCode) errInput.city = 'Enter your city & postalCode';
                                    if (!registerData.country) errInput.country = 'Enter your country';
                                }
                            } else {
                                errInput.street = '';
                                errInput.city = '';
                                errInput.country = '';
                            }
                        }
                    } else {
                        if (!registerData.fullname) errInput.fullname = 'Enter your full name';
                        if (!registerData.noTelp) errInput.noTelp = 'Enter your no telp';
                        if (!registerData.email) errInput.email = 'Enter your email';
                        if (!registerData.password) errInput.password = 'Enter a password';

                        formPos.value = 1;
                    }
                }

                const doRegister = () => {
                    if (registerData.fullname && registerData.noTelp && registerData.email && registerData.password && registerData.confirmPass && registerData.street && registerData.city && registerData.postalCode && registerData.country) {
                        let formData = new FormData();
                        formData.append("fullname", registerData.fullname);
                        formData.append("noTelp", registerData.noTelp);
                        formData.append("email", registerData.email);
                        formData.append("password", registerData.password);
                        formData.append("street", registerData.street);
                        formData.append("city", registerData.city);
                        formData.append("postalCode", registerData.postalCode);
                        formData.append("country", registerData.country);

                        if (adminClient.value == 1) {
                            formData.append("appName", registerData.appName);
                            formData.append("appCode", "");
                        } else {
                            formData.append("appName", "");
                            formData.append("appCode", registerData.appCode);
                        }

                        axios({
                                url: "<?= base_url('register/doRegister') ?>",
                                data: formData,
                                method: 'POST'
                            }).then((res) => {
                                let resData = res.data;
                                if (resData.status == 200) {
                                    Swal.fire({
                                        title: "Success registered new account",
                                        icon: 'success',
                                    }).then((sres) => {
                                        window.location.href = "<?= base_url() ?>";
                                    })
                                } else if (resData.status == 400) {
                                    Swal.fire({
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
                            })
                            .catch((rej) => {
                                if (rej.throw) {
                                    throw new Error(rej.message);
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Internal server error, please try again',
                                    text: rej.message
                                });
                            });
                    } else {
                        validateForm(2);
                    }
                }

                Vue.onMounted(() => {

                });

                return {
                    registerData,
                    errInput,
                    formPos,
                    adminClient,

                    validateForm,
                    doRegister
                }
            }
        }).mount("#app");
    </script>
</body>

</html>