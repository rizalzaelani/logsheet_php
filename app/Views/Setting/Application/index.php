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
                                    <label>Application Name</label>
                                    <input type="text" class="form-control" id="appName" v-model="appSetting.appName">
                                    <div class="invalid-feedback">
                                        Field cannot be empty.
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between">
                                        <label>Application Logo (Light)</label>
                                        <span v-if="appSetting.appLogoLight" class="text-danger cursor-pointer" @click="appSetting.appLogoLight = ''">Delete</span>
                                    </div>
                                    <div class="fake-card h-100" :class="!appSetting.appLogoLight ? '' : 'd-none'">
                                        <input type="file" ref="file" class="filepond" name="appLogoLight" id="appLogoLight">
                                        <div class="invalid-feedback">
                                            Field cannot be empty.
                                        </div>
                                    </div>
                                    <div :class="appSetting.appLogoLight ? '' : 'd-none'" class="w-100">
                                        <img class="rounded img-fluid img-thumbnail w-100 mb-3" class="w-100" :src="appSetting.appLogoLight">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between">
                                        <label>Application Logo (Dark)</label>
                                        <span v-if="appSetting.appLogoDark" class="text-danger cursor-pointer" @click="appSetting.appLogoDark = ''">Delete</span>
                                    </div>
                                    <div class="fake-card h-100" :class="!appSetting.appLogoDark ? '' : 'd-none'">
                                        <input type="file" ref="file" class="filepond" name="appLogoDark" id="appLogoDark">
                                        <div class="invalid-feedback">
                                            Field cannot be empty.
                                        </div>
                                    </div>
                                    <div :class="appSetting.appLogoDark ? '' : 'd-none'" class="w-100">
                                        <img class="rounded img-fluid img-thumbnail w-100 mb-3" class="w-100" :src="appSetting.appLogoDark">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between">
                                        <label>Application Logo (Icon)</label>
                                        <span v-if="appSetting.appLogoIcon" class="text-danger cursor-pointer" @click="appSetting.appLogoIcon = ''">Delete</span>
                                    </div>
                                    <div class="fake-card h-100" :class="!appSetting.appLogoIcon ? '' : 'd-none'">
                                        <input type="file" ref="file" class="filepond" name="appLogoIcon" id="appLogoIcon">
                                        <div class="invalid-feedback">
                                            Field cannot be empty.
                                        </div>
                                    </div>
                                    <div :class="appSetting.appLogoIcon ? '' : 'd-none'" class="w-100">
                                        <img class="rounded img-fluid img-thumbnail w-100 mb-3" class="w-100" :src="appSetting.appLogoIcon">
                                    </div>
                                </div>
                            </form>
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

    <!-- Modal -->
    <div class="modal fade" id="cropImage" tabindex="-1" role="dialog" aria-labelledby="CropLogoImage" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-0 w-100">
                    <img id="cropLogo" class="w-100" :src="imgSrcSource">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cancelCrop();">Close</button>
                    <button type="button" class="btn btn-primary" @click="doCrop();">Save</button>
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
        setup() {
            let cropper;
            var userId = uuidv4();
            var appSetting = reactive(<?= json_encode($appSetting); ?>);

            appSetting.appLogoLight1 = appSetting.appLogoLight;
            appSetting.appLogoDark1 = appSetting.appLogoDark;
            appSetting.appLogoIcon1 = appSetting.appLogoIcon;

            let mapAssetStatus = _.map(<?= json_encode($assetStatus ?? []); ?>, (v, k) => {
                v.assetStatusName1 = v.assetStatusName;
                v.isNew = false;
                v.deleted = false;
                return v;
            });
            var assetStatus = reactive(mapAssetStatus);

            var imgSrcSource = ref("");
            var targetFP = ref("");
            let appLogoLightFP;
            let appLogoDarkFP;
            let appLogoIconFP;

            const filepondOpt = {
                acceptedFileTypes: ['image/png', 'image/jpeg'],
                allowImagePreview: true,
                allowImageCrop: true,
                allowMultiple: false,
                credits: false,
                styleLoadIndicatorPosition: 'center bottom',
                styleProgressIndicatorPosition: 'right bottom',
                styleButtonRemoveItemPosition: 'left bottom',
                styleButtonProcessItemPosition: 'right bottom',
            };

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
                    if (appSetting.appName != '' && appSetting.appLogoLight != '' && appSetting.appLogoDark != '' && appSetting.appLogoIcon != '') {
                        let dataPost = {
                            appSettingId: appSetting.appSettingId,
                            userId: appSetting.userId,
                            appName: appSetting.appName,
                            appLogoLight: (appSetting.appLogoLight == appSetting.appLogoLight1 ? '' : appSetting.appLogoLight),
                            appLogoDark: (appSetting.appLogoDark == appSetting.appLogoDark1 ? '' : appSetting.appLogoDark),
                            appLogoIcon: (appSetting.appLogoIcon == appSetting.appLogoIcon1 ? '' : appSetting.appLogoIcon)
                        };

                        axios({
                            url: "<?= base_url('Application/saveSetting') ?>",
                            method: 'POST',
                            data: dataPost,
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
                        });
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

            const createCropper = (ar) => {
                if (cropper) cropper.destroy();
                ar = isNaN(ar) ? (37 / 9) : ar;

                let cropLogo = document.querySelector('#cropLogo');
                cropper = new Cropper(cropLogo, {
                    responsive: true,
                    dragMode: 'move',
                    aspectRatio: ar,
                    autoCropArea: 0.65,
                    restore: false,
                    guides: true,
                    center: false,
                    highlight: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            };

            const doCrop = () => {
                if (targetFP.value == "L") {
                    appLogoLightFP.removeFiles();
                    appSetting.appLogoLight = cropper.getCroppedCanvas().toDataURL("image/png");
                } else if (targetFP.value == "D") {
                    appLogoDarkFP.removeFiles();
                    appSetting.appLogoDark = cropper.getCroppedCanvas().toDataURL("image/png");
                } else if (targetFP.value == "I") {
                    appLogoIconFP.removeFiles();
                    appSetting.appLogoIcon = cropper.getCroppedCanvas().toDataURL("image/png");
                }
                $("#cropImage").modal("hide");
            };

            const cancelCrop = () => {
                if (targetFP.value == "L") {
                    appLogoLightFP.removeFiles();
                } else if (targetFP.value == "D") {
                    appLogoDarkFP.removeFiles();
                } else if (targetFP.value == "I") {
                    appLogoIconFP.removeFiles();
                }
                $("#cropImage").modal("hide");
            };

            const showCropModal = async (file, ar) => {
                imgSrcSource.value = URL.createObjectURL(file);
                if (cropper) {
                    cropper.destroy();
                }

                await $("#cropImage").modal("show");
                setTimeout(() => {
                    createCropper(ar);
                    setTimeout(() => {
                        window.dispatchEvent(new Event('resize'));
                    }, 100);
                }, 100);
            }

            Vue.onMounted(() => {
                FilePond.registerPlugin(FilePondPluginImagePreview);
                appLogoLightFP = FilePond.create(document.querySelector('#appLogoLight'), filepondOpt);
                appLogoDarkFP = FilePond.create(document.querySelector('#appLogoDark'), filepondOpt);
                appLogoIconFP = FilePond.create(document.querySelector('#appLogoIcon'), filepondOpt);

                appLogoLightFP.on('addfile', (error, file) => {
                    targetFP.value = "L";
                    showCropModal(file.file);
                });

                appLogoDarkFP.on('addfile', (error, file) => {
                    targetFP.value = "D";
                    showCropModal(file.file);
                });

                appLogoIconFP.on('addfile', (error, file) => {
                    targetFP.value = "I";
                    showCropModal(file.file, 1);
                });
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
                restoreAssetStatus,
                imgSrcSource,
                createCropper,
                doCrop,
                cancelCrop
            }
        },
    }).mount('#app');
</script>
<?= $this->endSection(); ?>