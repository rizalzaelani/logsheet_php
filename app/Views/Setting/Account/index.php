<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->

<style>
    .table th, .table td {
        border-top: 0 !important;
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main fixed-height">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4><?= $title; ?></h4>
                </div>
                <div class="form-group">

                    <table class="table mt-2">
                        <tr class="mt-1">
                            <th style="width: 150px;">Fullname</th>
                            <td style="width: 10px;">:</td>
                            <td>{{ user.fullname }}</td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Email</th>
                            <td style="width: 10px;">:</td>
                            <td>{{ user.email }}</td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Company</th>
                            <td style="width: 10px;">:</td>
                            <td>{{ user.company }}</td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Phone Number</th>
                            <td style="width: 10px;">:</td>
                            <td>{{ user.noTelp }}</td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Address</th>
                            <td style="width: 10px;">:</td>
                            <td>{{ user.city + ' ' + user.country + ', ' + user.postalCode }}</td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Tag</th>
                            <td style="width: 10px;">:</td>
                            <td>
                                <template v-if="user.tag" v-for="(val, key) in _.uniq(user.tag.split(','))">
                                    <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                </template>
                                <template v-else>-</template>
                            </td>
                        </tr>
                        <tr class="mt-1">
                            <th style="width: 150px;">Location</th>
                            <td style="width: 10px;">:</td>
                            <td>
                                <template v-if="user.tagLocationName" v-for="(val, key) in _.uniq(user.tagLocationName.split(','))">
                                    <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                </template>
                                <template v-else>-</template>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>

<script>
    let v = Vue.createApp({
        setup(){
            const user = Vue.reactive(<?= json_encode($userData) ?>);

            return {user}
        }
    }).mount("#app")
</script>

<?= $this->endSection(); ?>