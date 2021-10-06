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
                        <h4 class="mb-4"><?= $title; ?></h4>
                        <div class="form-group" id="formSetting">
                            <form method="post" enctype="multipart/form-data">
                                <div class="mt-2">
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
                                    <button class="btn btn-sm btn-outline-primary" type="button" @click="saveSetting()"><i class="fa fa-save"></i> Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="mb-4">Asset Status</h4>
                        <table class="table" id="tableStatus">
                            <thead class="bg-primary">
                                <tr>
                                    <th style="vertical-align: middle;" class="text-center" width="10%">No</th>
                                    <th style="vertical-align: middle;" class="text-center">Status Name</th>
                                    <th width="20%">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <button class="btn btn-sm btn-success mr-1" id="addStatus" @click="addStatus()"><i class="fa fa-plus"></i> Add</button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($assetStatus as $key): ?>
                                    <tr>
                                        <td class="text-center" style="vertical-align: middle;"><?= $i++; ?></td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <p class="m-0" id="status<?= $key['assetStatusId'] ?>"><?= $key['assetStatusName']; ?></p>
                                            <input type="text" class="form-control text-center" @keyup.enter="keyupStatus('<?= $key['assetStatusId'] ?>', $event.target.value)" value="<?= $key['assetStatusName'] ?>" id="inputStatus<?= $key['assetStatusId'] ?>" style="display: none">
                                        </td>
                                        <td class="d-flex justify-content-center align-items-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary mr-1" @click="editStatus('<?= $key['assetStatusId'] ?>')"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" @click="deleteStatus('<?= $key['assetStatusId'] ?>')"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="mt-2 d-flex justify-content-end align-items-center">
                            <button class="btn btn-sm btn-outline-primary" type="button" @click="saveStatus()"><i class="fa fa-save"></i> Save Changes</button>
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
            var statusName = ref([]);

            function uuidv4() {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random() * 16 | 0,
                    v = c == 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            }

            function addStatus() {
                const lengthTableStatus = $('#tableStatus tbody tr').length;
                $('#tableStatus').append(
                    `<tr id="row${lengthTableStatus + 1}">
                        <td class="text-center">`+ (lengthTableStatus + 1) +`</td>
                        <td class="text-center">
                            <input name="input" id="input${lengthTableStatus + 1}" type="text" class="form-control text-center">
                        </td>
                        <td>
                            <div class="d-flex justify-content-end align-items-center">
                                <button class="btn btn-sm btn-danger" onclick="del(${lengthTableStatus + 1})"><i class="fa fa-trash"></i> Delete</button>
                            </div>
                        </td>
                    </tr>`
                )
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

            function saveStatus() {
                var arr = [];
                    $('#tableStatus tbody tr input[name=input]').each(function() {
                        var rowDataArray = [];
                        var actualData = $(this).val();
                        arr.push(actualData);
                    })
                v.statusName = arr;
                // var arr = [];
                // const lengthTableStatus = $('#tableStatus tbody tr').length;
                // const lengthData = <?= count($assetStatus); ?>;
                // const dif = lengthTableStatus - lengthData;
                // if (lengthTableStatus > lengthData) {
                //     for (let index = lengthData+1; index <= lengthTableStatus; index++) {
                //         var id = '#input' + index;
                //         var valueInput = $(id).val();
                //         arr.push(valueInput);
                //     }
                // }
                // this.statusName = arr;

                var lengthStatusName = this.statusName.length;
                if (lengthStatusName > 0) {
                    let formdata = new FormData();
                    formdata.append('assetStatusId', null);
                    formdata.append('userId', this.userId);
                    var status = this.statusName;
                    status.forEach((item, i) => {
                        formdata.append('statusName[]', item);
                    })

                    axios({
                        url: "<?= base_url('Application/saveStatus') ?>",
                        method: 'POST',
                        data: formdata,
                    }).then(res => {
                        console.log(res);
                    })
                }
            }

            function editStatus(id) {
                var statusId = '#status' + id;
                var inputStatus = '#inputStatus' + id;
                $(statusId).hide();
                $(inputStatus).show();
            }

            function keyupStatus(id, val) {
                axios.post("<?= base_url('Application/updateStatus') ?>", {
                    assetStatusId: id,
                    assetStatusName: val
                }).then(res => {
                    console.log(res);
                    var statusId = '#status' + id;
                    var inputStatus = '#inputStatus' + id;
                    $(statusId).text(val);
                    $(statusId).show();
                    $(inputStatus).hide();
                })
                // console.log(id);
                // console.log(val);
            }

            function deleteStatus(id) {
                axios.post("<?= base_url('Application/deleteStatus') ?>", {
                    assetStatusId: id
                }).then(res => {
                    if (res.data.status == 'success') {
                        console.log("success")
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
            }

            return {
                appSetting,
                statusName,

                uuidv4,
                addStatus,
                saveSetting,
                saveStatus,
                editStatus,
                keyupStatus,
                deleteStatus
            }
        },
    }).mount('#app');

    function del(idx) {
        var row = '#row' + idx;
        $(row).remove();
        const lengthTableStatus = $('#tableStatus tbody tr').length;
        const lengthData = <?= count($assetStatus); ?>;
        var newIdx = idx+1;
        for (let index = newIdx; index <= lengthTableStatus; index++) {
            console.log('input'+(newIdx++));
            console.log('input'+index);
            console.log("-----");
            // var id = 'input' + (idx++);
            // var newId = 'input' + index;
            // document.getElementById(id).id = newId;
        }
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
            // console.log(fileUploaded[0].file);
        });
    })
</script>
<?= $this->endSection(); ?>