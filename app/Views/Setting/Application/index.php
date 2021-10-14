<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<style>
    .fake-card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        background-clip: border-box;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-main fixed-height">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4><?= $title; ?></h4>
                            <button class="btn btn-sm btn-outline-primary" type="button" @click="saveSetting()"><i class="fa fa-save"></i> Save Changes</button>
                        </div>
                        <div class="form-group" id="formSetting">
                            <form method="post" enctype="multipart/form-data">
                                <div>
                                    <label for="appName">Application Name</label>
                                    <input type="text" class="form-control" id="appName" v-model="appSetting.appName">
                                    <div class="invalid-feedback">
                                        Field cannot be empty.
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label for="appName">Application Logo</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="fake-card h-100">
                                                <input type="file" ref="file" class="filepond" name="filepond" id="appLogo">
                                                <div class="invalid-feedback">
                                                    Field cannot be empty.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="fake-card h-100 justify-content-center align-items-center" style="border: 0.5px solid rgba(0, 0, 0, 0.2)">
                                                <div id="logo" class="d-flex justify-content-center align-items-center p-1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 d-flex justify-content-end align-items-center">
                                </div>
                            </form>
                        </div>
                        <div class="w-100 mt-3">
                            <div id="cropSample"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-main fixed-height">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4>Asset Status</h4>
                            <button class="btn btn-sm btn-outline-primary" type="button" @click="saveAssetStatus()"><i class="fa fa-save"></i> Save Changes</button>
                        </div>
                        <table class="table-bordered table mt-1" id="tableStatus">
                            <thead>
                                <tr>
                                    <th style="vertical-align: middle;" class="text-center">Status Name</th>
                                    <th width="20%">
                                        <div class="d-flex justify-content-center align-items-center">
                                            #
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(value, key) in assetStatus">
                                    <tr :class="value.deleted ? 'bg-danger' : ''">
                                        <th class="text-center" style="vertical-align: middle;">
                                            <input type="text" name="assetStatusName[]" class="form-control input-transparent text-center" :class="value.deleted ? 'text-white' : ''" v-model="assetStatus[key]['assetStatusName']" placeholder="Status Name">
                                            <div class="invalid-feedback m-0">
                                                Status Name is exist.
                                            </div>
                                        </th>
                                        <th class="text-center" style="vertical-align: middle;">
                                            <i v-if="value.deleted" class="fa fa-undo text-light" role="button" @click="restoreAssetStatus(value.assetStatusId)"></i>
                                            <i v-else class="fa fa-times text-danger" role="button" @click="deleteAssetStatus(value.assetStatusId)"></i>
                                        </th>
                                    </tr>
                                </template>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <input type="text" name="assetStatusName[]" class="form-control input-transparent text-center" @keyup="addAssetStatus($event.target)" placeholder="Status Name">
                                        <div class="invalid-feedback m-0">
                                            Status Name is exist.
                                        </div>
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                    </th>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-2 d-flex justify-content-end align-items-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<script>
    const {
        ref,
        reactive
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var userId = uuidv4();
            var appSetting = <?= json_encode($appSetting); ?>;
            let mapAssetStatus = _.map(<?= json_encode($assetStatus ?? []); ?>, (v, k) => {
                v.assetStatusName1 = v.assetStatusName;
                v.isNew = false;
                v.deleted = false;
                return v;
            });
            var assetStatus = reactive(mapAssetStatus);

            function uuidv4() {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random() * 16 | 0,
                        v = c == 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            };

            const deleteAssetStatus = async (id) => {
                let isNew = false;
                _.map(assetStatus, (v) => {
                    if (v.assetStatusId == id) {
                        v.deleted = true;
                        isNew = v.isNew;
                    }
                });
                if (isNew) {
                    _.remove(assetStatus, (v) => {
                        return v.assetStatusId == id;
                    })
                }
            }

            const restoreAssetStatus = async (id) => {
                _.map(assetStatus, (v) => {
                    if (v.assetStatusId == id) {
                        v.deleted = false;
                    }
                    return v;
                });
            }

            const addAssetStatus = async (e) => {
                let checkStatus = _.filter(assetStatus, {
                    assetStatusName: e.value
                });

                if (checkStatus.length < 1 & e.value != '') {
                    var id = uuidv4();
                    await assetStatus.push({
                        assetStatusId: id,
                        userId: userId,
                        assetStatusName: e.value,
                        assetStatusName1: e.value,
                        isNew: true,
                        deleted: false,
                    })

                    e.value = '';

                    let tableStatus = document.getElementById("tableStatus");
                    let trStatus = tableStatus.rows[tableStatus.rows.length - 2];
                    trStatus.querySelector("input[name='assetStatusName[]']").focus();
                }
            }

            const saveSetting = () => {
                try {
                    if ($('#appName').hasClass('is-invalid')) {
                        $('#appName').removeClass('is-invalid');
                    };
                    if (appSetting.appName != '' && appSetting.appLogo != '') {
                        let formdata = new FormData();
                        formdata.append('appSettingId', appSetting.appSettingId);
                        formdata.append('userId', appSetting.userId);
                        formdata.append('appName', appSetting.appName);
                        formdata.append('appLogo', appSetting.appLogo);
                        axios({
                            url: "<?= base_url('Application/saveSetting') ?>",
                            method: 'POST',
                            data: formdata,
                        }).then(res => {
                            xhrThrowRequest(res)
                                .then(() => {
                                    const swalWithBootstrapButtons = swal.mixin({
                                        customClass: {
                                            confirmButton: 'btn btn-success mr-1',
                                        },
                                        buttonsStyling: false
                                    })
                                    swalWithBootstrapButtons.fire({
                                        title: 'Success!',
                                        text: res.data.message,
                                        icon: 'success'
                                    }).then(okay => {
                                        if (okay) {
                                            swal.fire({
                                                title: 'Please Wait!',
                                                text: 'Reloading page..',
                                                onOpen: function() {
                                                    swal.showLoading()
                                                }
                                            })
                                            location.reload();
                                        }
                                    })
                                })
                                .catch((rej) => {
                                    if (rej.throw) {
                                        throw new Error(rej.message);
                                    }
                                });
                        })
                    } else {
                        const swalWithBootstrapButtons = swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire({
                            title: 'Failed!',
                            text: "All field cannot be empty.",
                            icon: 'error'
                        })
                        if (v.appSetting.appName == '') {
                            $('#appName').addClass('is-invalid');
                        } else {
                            $('#appName').removeClass('is-invalid');
                        }

                        if (v.appSetting.appLogo == '') {
                            $('#appLogo').addClass('is-invalid');
                        } else {
                            $('#appLogo').removeClass('is-invalid');
                        }
                    }
                } catch (error) {
                    console.log(error)
                }
            }

            const saveAssetStatus = () => {
                axios.post("<?= base_url('Application/saveAssetStatus') ?>", assetStatus)
                    .then(res => {
                        xhrThrowRequest(res)
                            .then(() => {
                                const swalWithBootstrapButtons = swal.mixin({
                                    customClass: {
                                        confirmButton: 'btn btn-success mr-1',
                                    },
                                    buttonsStyling: false
                                })
                                swalWithBootstrapButtons.fire({
                                    title: 'Success!',
                                    text: res.data.message,
                                    icon: 'success'
                                }).then(okay => {
                                    if (okay) {
                                        swal.fire({
                                            title: 'Please Wait!',
                                            text: 'Reloading page..',
                                            onOpen: function() {
                                                swal.showLoading()
                                            }
                                        })
                                        // location.reload();
                                    }
                                })
                            })
                            .catch((rej) => {
                                if (rej.throw) {
                                    throw new Error(rej.message);
                                }
                            });
                    })
            }

            Vue.onMounted(() => {
                FilePond.registerPlugin(FilePondPluginImageCrop, FilePondPluginImagePreview, FilePondPluginImageEdit, FilePondPluginFileValidateType);
                var pond = $('#appLogo').filepond({
                    acceptedFileTypes: ['image/png', 'image/jpeg'],
                    allowImagePreview: true,
                    allowImageCrop: true,
                    allowMultiple: false,
                    credits: false,
                    styleLoadIndicatorPosition: 'center bottom',
                    styleProgressIndicatorPosition: 'right bottom',
                    styleButtonRemoveItemPosition: 'left bottom',
                    styleButtonProcessItemPosition: 'right bottom',
                });
                var pondRoot = document.querySelector('#appLogo');
                pondRoot.addEventListener('FilePond:addfile', function() {
                    let fileUploaded = $('#appLogo').filepond('getFiles');
                    v.appSetting.appLogo = fileUploaded[0].file;
                });

                if (appSetting.appLogo != '') {
                    $('#logo').append(`<img class="p-2" id="logoApp" src="${appSetting.appLogo}" alt="${appSetting.appName}" width="70%" onclick="window.open(this.src)" style="cursor: pointer" data-toggle="tooltip" title="click to preview this image">`);
                } else {
                    $('#logo').append(`<div class="noLogo"><p class="m-0"><i>No photos uploaded yet.</i></p></div>`)
                }
            })

            return {
                userId,
                appSetting,
                assetStatus,

                uuidv4,
                saveSetting,
                addAssetStatus,
                saveAssetStatus,
                deleteAssetStatus,
                restoreAssetStatus
            }
        },
    }).mount('#app');
</script>
<?= $this->endSection(); ?>