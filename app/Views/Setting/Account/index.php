<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->

<style>
    .table th,
    .table td {
        vertical-align: middle;
        padding-right: .5rem;
        padding-left: .5rem;
    }

    .side-profile {
        flex: 0 0 260px;
        border-right: 1px solid #dfe6e9;
    }

    .main-profile {
        flex: 1 1 0%;
    }

    .img-profile {
        display: block;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        border: 5px #ffffffff solid;
        outline: 5px var(--info) solid;
        margin: 10px auto;
    }

    .img-profile img {
        width: 100%;
    }

    .profile-content-list {
        display: block;
        width: 100%;
    }

    .profile-content-list ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .profile-content-list ul li {
        display: flex;
        align-items: center;
    }

    .profile-content-list ul li span:first-child {
        min-width: 200px;
        display: block;
    }

    .list-title {
        font-size: 14px;
        line-height: 21px;
        color: #636e72;
    }

    .nav-tabs-boxed.nav-tabs-boxed-left .nav-link {
        border-bottom: 0;
    }

    .nav-tabs-boxed.nav-tabs-boxed-left .nav-link.active {
        border-bottom: none;
        border-right: 3px solid var(--primary);
        background-color: #ebfafa;
        color: var(--primary);
    }

    .nav-tabs-boxed.nav-tabs-boxed-left .nav-link:hover,
    .nav-tabs-boxed.nav-tabs-boxed-left .nav-link:focus {
        border-bottom: none;
        /* border-right: 3px solid var(--primary); */
        color: var(--primary) !important;
    }

    .side-profile .nav-link {
        max-width: 260px;
    }

    .nav-tabs-boxed.nav-tabs-boxed-left .nav-link {
        display: flex;
        justify-content: start;
        font-weight: 400;
        padding: 0.8445rem 1rem;
    }

    .nav-tabs-boxed.nav-tabs-boxed-left .nav-link>* {
        margin-bottom: 0.3rem;
    }

    hr {
        margin: 1.5rem 0;
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main p-0">
            <div class="d-flex flex-row">
                <div class="side-profile pb-5 d-none d-sm-block">
                    <div class="d-block text-center pt-4">
                        <div class="img-profile">
                            <img src="<?= base_url("img/avatars/1.jpg") ?>" :alt="user.email">
                        </div>
                        <h3 class="mt-4 mb-0 text-capitalize">{{user.fullname}}</h3>
                        <span class="text-muted">{{user.email}}</span>
                    </div>
                    <div class="nav-tabs-boxed nav-tabs-boxed-left mt-5">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#persInfoTab" aria-controls="persInfoTab" role="tab" aria-selected="true">
                                    <svg class="c-sidebar-nav-icon">
                                        <use xlink:href="http://45.77.45.6/logsheet/icons/coreui/svg/linear.svg#cil-user"></use>
                                    </svg> Personal Information
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#activityMonitorTab" aria-controls="activityMonitorTab" role="tab" aria-selected="false">
                                    <svg class="c-sidebar-nav-icon">
                                        <use xlink:href="http://45.77.45.6/logsheet/icons/coreui/svg/linear.svg#cil-laptop"></use>
                                    </svg> Activity Monitor
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#changePassTab" aria-controls="changePassTab" role="tab" aria-selected="false">
                                    <svg class="c-sidebar-nav-icon">
                                        <use xlink:href="http://45.77.45.6/logsheet/icons/coreui/svg/linear.svg#cil-lock-unlocked"></use>
                                    </svg> Change Password
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="main-profile p-4 pb-5">
                    <ul class="nav nav-tabs mb-3 d-flex d-sm-none" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#persInfoTab" aria-controls="persInfoTab" role="tab" aria-selected="true">
                                Personal Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#activityMonitorTab" aria-controls="activityMonitorTab" role="tab" aria-selected="false">
                                Activity Monitor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#changePassTab" aria-controls="changePassTab" role="tab" aria-selected="false">
                                Change Password
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="persInfoTab" role="tabpanel">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h2 class="font-weight-normal">Personal Information</h2>
                                    <span class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur sodales sit amet nunc et vehicula. Mauris sed lectus nisi.</span>
                                </div>
                            </div>
                            <hr />
                            <div class="profile-content-list">
                                <ul>
                                    <li class="d-block d-md-flex">
                                        <span class="list-title">Fullname</span>
                                        <span class="font-weight-500">{{ user.fullname }}</span>
                                    </li>
                                    <li class="mt-3 d-block d-md-flex">
                                        <span class="list-title">Email</span>
                                        <span class="font-weight-500">{{ user.email }}</span>
                                    </li>
                                    <li class="mt-3 d-block d-md-flex">
                                        <span class="list-title">Company</span>
                                        <span class="font-weight-500">{{ user.company }}</span>
                                    </li>
                                    <li class="mt-3 d-block d-md-flex">
                                        <span class="list-title">Phone Number</span>
                                        <span class="font-weight-500">{{ user.noTelp }}</span>
                                    </li>
                                    <li class="mt-3 d-block d-md-flex">
                                        <span class="list-title">Address</span>
                                        <span class="font-weight-500">{{ user.city + ' ' + user.country + ', ' + user.postalCode }}</span>
                                    </li>
                                    <li class="mt-3 d-block d-md-flex">
                                        <span class="list-title">Tag</span>
                                        <div>
                                            <template v-if="user.tag" v-for="(val, key) in _.uniq(user.tag.split(','))">
                                                <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                            </template>
                                            <template v-else>-</template>
                                        </div>
                                    </li>
                                    <li class="mt-3 d-block d-md-flex">
                                        <span class="list-title">Location</span>
                                        <div>
                                            <template v-if="user.tagLocationName" v-for="(val, key) in _.uniq(user.tagLocationName.split(','))">
                                                <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                            </template>
                                            <template v-else>-</template>
                                        </div>
                                    </li>
                                    <li class="mt-3 d-block d-md-flex">
                                        <span class="list-title">Role</span>
                                        <span class="font-weight-500">{{ user.role }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-pane" id="activityMonitorTab" role="tabpanel">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h2 class="font-weight-normal">Login Activity</h2>
                                    <span class="text-muted">Here is your last 10 login activities log.</span>
                                </div>
                            </div>
                            <hr />
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Name</th>
                                            <th>IP</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Safari on Macbook </td>
                                            <td>278.281.987.111</td>
                                            <td>Nov 12, 2021 08:56 PM</td>
                                        </tr>
                                        <tr>
                                            <td>Chrome on Window </td>
                                            <td>278.281.987.111</td>
                                            <td>Nov 12, 2021 08:56 PM</td>
                                        </tr>
                                        <tr>
                                            <td>Chrome on Macbook </td>
                                            <td>278.281.987.111</td>
                                            <td>Nov 12, 2021 08:56 PM</td>
                                        </tr>
                                        <tr>
                                            <td>Firefox on Window </td>
                                            <td>278.281.987.111</td>
                                            <td>Nov 12, 2021 08:56 PM</td>
                                        </tr>
                                        <tr>
                                            <td>Safari on Macbook </td>
                                            <td>278.281.987.111</td>
                                            <td>Nov 12, 2021 08:56 PM</td>
                                        </tr>
                                        <tr>
                                            <td>Edge on Window </td>
                                            <td>278.281.987.111</td>
                                            <td>Nov 12, 2021 08:56 PM</td>
                                        </tr>
                                        <tr>
                                            <td>Chrome on Macbook </td>
                                            <td>278.281.987.111</td>
                                            <td>Nov 12, 2021 08:56 PM</td>
                                        </tr>
                                        <tr>
                                            <td>Firefox on Window </td>
                                            <td>278.281.987.111</td>
                                            <td>Nov 12, 2021 08:56 PM</td>
                                        </tr>
                                        <tr>
                                            <td>Edge on Window </td>
                                            <td>278.281.987.111</td>
                                            <td>Nov 12, 2021 08:56 PM</td>
                                        </tr>
                                        <tr>
                                            <td>Safari on Macbook </td>
                                            <td>278.281.987.111</td>
                                            <td>Nov 12, 2021 08:56 PM</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="changePassTab" role="tabpanel">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h2 class="font-weight-normal">Change Password</h2>
                                    <span class="text-muted">Set a unique password to protect your account.</span>
                                </div>
                            </div>
                            <hr />
                            <div style="max-width: 350px;">
                                <form action="<?= site_url("user/currentPassword") ?>" method="POST" @submit.prevent="changePassword">
                                    <div class="form-group">
                                        <label for="password">Old Password</label>
                                        <div class="controls">
                                            <div class="input-group" :class="oldPassErr ? 'invalid-value' : ''">
                                                <input class="form-control" type="password" name="oldPass" v-model="oldPass" placeholder="Enter your old Password">
                                                <div class="input-group-append" @click="showOldPass = !showOldPass;" style="cursor: pointer">
                                                    <span class="input-group-text bg-white">
                                                        <i class="fa" :class="showOldPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="help-block text-danger" :class="oldPassErr ? '' : 'd-none'">{{oldPassErr}}</p>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4">
                                        <label class="col-form-label">New Password : </label>
                                        <div class="controls">
                                            <div class="input-group" :class="newPassErr ? 'invalid-value' : ''">
                                                <input class="form-control" name="newPass" type="password" placeholder="At least 6 character" v-model="newPass">
                                                <div class="input-group-append" @click="showNewPass = !showNewPass;" style="cursor: pointer">
                                                    <span class="input-group-text bg-white">
                                                        <i class="fa" :class="showNewPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="help-block text-danger" :class="newPassErr ? '' : 'd-none'">{{newPassErr}}</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Confirm Password : </label>
                                        <div class="controls">
                                            <div class="input-group" :class="conPassErr ? 'invalid-value' : ''">
                                                <input class="form-control" name="conPass" type="password" placeholder="At least 6 character" v-model="conPass">
                                                <div class="input-group-append" @click="showConPass = !showConPass;" style="cursor: pointer">
                                                    <span class="input-group-text bg-white">
                                                        <i class="fa" :class="showConPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="help-block text-danger" :class="conPassErr ? '' : 'd-none'">{{conPassErr}}</p>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100" :disabled="!btnChangePass"><i class="fa fa-spin fa-spinner" v-if="!btnChangePass"></i> {{ btnChangePass ? 'Change Password' : 'Processing...' }}</button>
                                </form>
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

<script>
    let v = Vue.createApp({
        setup() {
            const user = Vue.reactive(<?= json_encode($userData) ?>);
            const oldPass = Vue.ref("");
            const newPass = Vue.ref("");
            const conPass = Vue.ref("");

            const oldPassErr = Vue.ref("");
            const newPassErr = Vue.ref("");
            const conPassErr = Vue.ref("");

            const showOldPass = Vue.ref(null);
            const showNewPass = Vue.ref(null);
            const showConPass = Vue.ref(null);

            const btnChangePass = Vue.ref(true);

            const changePassword = () => {
                if (!oldPass.value || !newPass.value || (newPass.value ?? "").length < 6 || newPass.value != conPass.value) {
                    oldPassErr.value = !oldPass.value ? 'Input your old password' : null;
                    newPassErr.value = !newPass.value ? 'Input your new password' : (newPass.value.length < 6 ? "Password must be at least 6 characters" : null);
                    conPassErr.value = newPass.value != conPass.value ? 'Password doesn\'t match' : null;
                } else {
                    btnChangePass.value = false;
                    axios.post("<?= site_url("user/changePassword") ?>", {
                            currentPassword: oldPass.value,
                            newPassword: newPass.value,
                        }).then((res) => {
                            let resData = res.data;
                            if (resData.status == 200) {
                                Toast.fire({
                                    title: 'Success Change Password!',
                                    icon: 'success'
                                });
                                oldPass.value = "";
                                newPass.value = "";
                                conPass.value = "";

                                setTimeout(() => {
                                    oldPassErr.value = null;
                                    newPassErr.value = null;
                                    conPassErr.value = null;
                                }, 10);
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

                            btnChangePass.value = true;
                        })
                        .catch((rej) => {
                            if (rej.throw) {
                                throw new Error(rej.message);
                            }
                            Toast.fire({
                                icon: 'error',
                                title: 'Internal server error'
                            });

                            btnChangePass.value = true;
                        });
                }
            }

            Vue.watch(
                oldPass,
                (state, prevState) => {
                    oldPassErr.value = !state ? 'Input your old password' : null;
                }
            )

            Vue.watch(
                newPass,
                (state, prevState) => {
                    newPassErr.value = !state ? 'Input your new password' : (state.length < 6 ? "Password must be at least 6 characters" : null);
                    if (conPass.value) conPassErr.value = conPass.value != state ? 'Password doesn\'t match' : null;
                }
            )

            Vue.watch(
                conPass,
                (state, prevState) => {
                    conPassErr.value = newPass.value != state ? 'Password doesn\'t match' : null;
                }
            )

            return {
                user,
                oldPass,
                newPass,
                conPass,
                oldPassErr,
                newPassErr,
                conPassErr,
                showOldPass,
                showNewPass,
                showConPass,
                btnChangePass,

                changePassword
            }
        }
    }).mount("#app")
</script>

<?= $this->endSection(); ?>