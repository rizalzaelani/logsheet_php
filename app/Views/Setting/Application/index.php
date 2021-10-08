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
        <div class="card card-main fixed-height">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
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
                    </div>
                    <div class="col-6">
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
                                            <!-- <button class="btn btn-sm btn-success mr-1" id="addStatus" @click="addStatus()"><i class="fa fa-plus"></i> Add</button> -->
                                            #
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(key, idx) in assetStatus">
                                    <tr>
                                        <th class="text-center" style="vertical-align: middle;">
                                            <input type="text" :name="'assetStatus' + idx" class="form-control input-transparent text-center" @keyup.enter="updateStatus($event.target, idx)" :value="key.assetStatusName" placeholder="Status Name">
                                            <div class="invalid-feedback m-0">
                                                Status Name is exist.
                                            </div>
                                        </th>
                                        <th class="text-center" style="vertical-align: middle;">
                                            <i class="fa fa-times text-danger" role="button" @click="deleteStatus(key, idx)"></i>
                                        </th>
                                    </tr>
                                </template>
                                <template v-for="(key, idx) in statusName">
                                    <tr>
                                        <th class="text-center" style="vertical-align: middle;">
                                            <input type="text" name="key[]" class="form-control input-transparent text-center" v-model="statusName[idx]['assetStatusName']" placeholder="Status Name">
                                        </th>
                                        <th class="text-center" style="vertical-align: middle;">
                                            <i class="fa fa-times text-danger" role="button" @click="statusName.splice(idx, 1)"></i>
                                        </th>
                                    </tr>
                                </template>
                                    <tr>
                                        <th class="text-center" style="vertical-align: middle;">
                                            <input type="text" name="statusName" v-model="tempStatus" class="form-control input-transparent text-center" @keyup.enter="addStatusName($event.target)" placeholder="Status Name">
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
    const { ref, reactive } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup(){
            var userId = uuidv4();
            var appSetting = <?php $lengthApp = count($appSetting);
                                if ($lengthApp > 0) {
                                    echo 'reactive(' . json_encode($appSetting[0]) . ')';
                                }else{
                                    echo "reactive({
                                        userId: '" . uuidv4() . "',
                                        appName: '',
                                        appLogo: '',
                                        appSettingId: '" . uuidv4() . "',
                                    })";
                                }
            ?>;
            var assetStatus = <?php $lengthStatus = count($assetStatus);
                                if ($lengthStatus > 0) {
                                    echo 'reactive(' . json_encode($assetStatus) . ')';
                                }else{
                                    echo "reactive({
                                        assetStatusId: '',
                                        userId: '',
                                        assetStatusName: '',
                                    })";
                                }
            ?>;
            var tempStatus = ref('');
            var statusName = reactive([]);
            var assetStatusUpdate = reactive([]);
            var assetStatusDelete = reactive([]);

            function uuidv4() {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random() * 16 | 0,
                    v = c == 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            };

            const updateStatus = async (e, i) => {
                let checkAssetStatus = _.filter(assetStatus, {
                    assetStatusName: e.value
                });
                if (checkAssetStatus < 1) {
                    let id = 'assetStatus' + i;
                    $('input[name='+id+']').removeClass('is-invalid');
                    var assetStatusIdx = assetStatus[i].assetStatusId;
                    assetStatusUpdate.push({
                        assetStatusId: assetStatusIdx,
                        assetStatusName: e.value
                    });
                    $('input[name='+id+']').blur();
                }else{
                    let id = 'assetStatus' + i;
                    $('input[name='+id+']').addClass('is-invalid');
                }
            }

            const deleteStatus = async (e, i) => {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger ml-1'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Delete this data?',
                    text: "You will delete this data!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: "<i class='fa fa-times'></i> Cancel",
                    confirmButtonText: "<i class='fa fa-check'></i> Yes, delete!",
                    reverseButtons: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        var assetStatusIdx = assetStatus[i].assetStatusId;
                        axios.post("<?= base_url("Application/deleteAssetStatus") ?>",{
                            assetStatusId: assetStatusIdx
                        }).then(res => {
                            if (res.data.status == 'exist') {
                                var date = res.data.data;
                                swal.fire({
                                    title: res.data.message + moment(date).format('LL'),
                                    icon: 'warning'
                                })
                            }else if(res.data.status == 'noexist'){
                                assetStatusDelete.push(assetStatusIdx);
                                assetStatus.splice(i, 1);
                            }
                        })
                    }
                })
            }

            const addStatusName = async (e) => {
                let checkStatus = _.filter(statusName, {
                    assetStatusName: e.value
                });
                let checkAssetStatus = _.filter(assetStatus, {
                    assetStatusName: e.value
                });

                if (checkStatus.length < 1 && checkAssetStatus < 1) {
                    var id = uuidv4();
                    $('input[name=statusName]').removeClass("is-invalid");
                    await statusName.push({
                        assetStatusId: id,
                        userId: userId,
                        assetStatusName: e.value,
                    })

                    e.value = '';
                    tempStatus.value = '';

                    let tableStatus = document.getElementById("tableStatus");
                    let trStatus = tableStatus.rows[tableStatus.rows.length - 1];
                    trStatus.querySelector("input[name='key[]']").focus();
                }else{
                    $('input[name=statusName]').addClass("is-invalid");
                }
            }

            function saveSetting() {
                try {
                    if ($('#appName').hasClass('is-invalid')) {
                        $('#appName').removeClass('is-invalid');
                    };
                    if (this.appSetting.appName != '' && this.appSetting.appLogo != '') {
                        let formdata = new FormData();
                        formdata.append('appSettingId', this.appSetting.appSettingId);
                        formdata.append('userId', this.appSetting.userId);
                        formdata.append('appName', this.appSetting.appName);
                        formdata.append('appLogo', this.appSetting.appLogo);
                        axios({
                            url: "<?= base_url('Application/saveSetting')?>",
                            method: 'POST',
                            data: formdata,
                        }).then(res => {
                            if (res.data.status == 'success') {
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
                            }else{
                                const swalWithBootstrapButtons = swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-danger',
                                },
                                buttonsStyling: false
                                })
                                swalWithBootstrapButtons.fire({
                                    title: 'Failed!',
                                    text: res.data.message,
                                    icon: 'error'
                                })
                            }
                        })
                    }else{
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
                        }else{
                            $('#appName').removeClass('is-invalid');
                        }

                        if (v.appSetting.appLogo == '') {
                            $('#appLogo').addClass('is-invalid');
                        }else{
                            $('#appLogo').removeClass('is-invalid');
                        }
                    }
                } catch (error) {
                    console.log(error)
                }
            }

            function saveAssetStatus() {
                let lengthStatusUpdate  = assetStatusUpdate.length;
                let lengthStatusDelete  = assetStatusDelete.length;
                let lengthStatusName    = statusName.length;
                if (lengthStatusUpdate > 0 || lengthStatusName > 0 || lengthStatusDelete > 0) {
                    axios.post("<?= base_url('Application/saveStatus') ?>", {
                        statusName: statusName,
                        statusUpdate: assetStatusUpdate,
                        statusDelete: assetStatusDelete,
                    }).then(res => {
                        if (res.status == 200) {
                            const swalWithBootstrapButtons = swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-success mr-1',
                                },
                                buttonsStyling: false
                            })
                            swalWithBootstrapButtons.fire({
                                title: 'Success!',
                                text: 'You have successfully save data.',
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
                        }
                    })
                }else{
                    swal.fire({
                        title: 'No changes to save!',
                        icon: 'warning'
                    })
                }
            }

            return {
                userId,
                appSetting,
                assetStatus,
                assetStatusUpdate,
                assetStatusDelete,
                tempStatus,
                statusName,

                uuidv4,
                saveSetting,
                saveAssetStatus,
                addStatusName,
                updateStatus,
                deleteStatus
            }
        },
    }).mount('#app');

    function del(idx) {
        var row = '#row' + idx;
        $(row).remove();
    }

    $(document).ready(function() {
        if (v.appSetting.appLogo != '') {
            $('#logo').append("<img class='p-2' id='logoApp' src='/assets/uploads/img/" + v.appSetting.appLogo + "' alt='' width='70%' onclick='window.open(this.src)' style='cursor: pointer' data-toggle='tooltip' title='click to preview this image'>");
        }else{
            $('#logo').append("<div class='noLogo'><p class='m-0'><i>No photos uploaded yet.</i></p></div>")
        }
    })

    $(document).ready(function() {
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
        pondRoot.addEventListener('FilePond:addfile', function(){
            let fileUploaded = $('#appLogo').filepond('getFiles');
            v.appSetting.appLogo = fileUploaded[0].file;
        });
    })
</script>
<?= $this->endSection(); ?>