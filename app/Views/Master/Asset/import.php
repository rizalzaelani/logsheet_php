<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<style>
    table>tbody>tr>td {
        min-width: 120px;
        vertical-align: middle !important;
    }

    table>thead>tr>th {
        vertical-align: middle !important;
        font-weight: 400 !important;
        color: rgba(0, 0, 0, .85);
    }

    .btn-custom {
        border-radius: 2px;
        height: 32px;
        padding: 4.8px 15px;
        font-size: 13px;
        font-weight: 400;
        text-align: center;
        transition: all .3s cubic-bezier(.645, .045, .355, 1);
    }

    .btn-custom-outline-danger {
        color: rgba(0, 0, 0, .85);
        background-color: #fff;
        border: 1px solid #e4e9f0;
    }

    .btn-custom-primary {
        color: #fff;
        background-color: #1D6DE5;
        border: none;
    }

    .btn-custom-primary:hover {
        color: #fff;
        background-color: #175cc5;
        border: none;
    }

    .btn-custom-outline-danger:hover {
        border: 1px solid var(--danger);
        color: var(--danger);
        background-color: #fff;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main card-border-top">
            <div class="container-fluid">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <h4><?= $title ?></h4>
                        <h5 class="header-icon">
                            <a href="<?= base_url('Asset'); ?>" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</a>
                        </h5>
                    </div>
                    <div class="w-100 step-parent d-flex justify-content-between align-items-center py-4">
                        <div class="step-child d-flex justify-content-between" style="width: 70%;">
                            <div class="d-flex align-items-center">
                                <div class="step-number mr-1">
                                    <span class="bg-primary p-2">1</span>
                                </div>
                                <div class="step-title">
                                    Upload File
                                </div>
                            </div>
                            <div class="bg-primary" style="height: 2px; width: 70%; margin-top: 12px;">

                            </div>
                        </div>
                        <div class=" step-child d-flex justify-content-between px-2" style="width: 67%;">
                            <div class="d-flex align-items-center">
                                <div class="step-number mr-1">
                                    <span class="bg-secondary review-number p-2">2</span>
                                </div>
                                <div class="step-title">
                                    Review Data
                                </div>
                            </div>
                            <div class="review-line bg-secondary" style="height: 2px; width: 67%; margin-top: 12px;">

                            </div>
                        </div>
                        <div class="step-child d-flex justify-content-between" style="width: 30%;">
                            <div class="d-flex align-items-center">
                                <div class="step-number mr-1">
                                    <span class="bg-secondary complete p-2">3</span>
                                </div>
                                <div class="step-title">
                                    Import Complete
                                </div>
                            </div>
                            <div></div>
                        </div>
                    </div>
                    <div class="uploadFile fade show">
                        <div class="pb-2 pt-4">
                            <h5>Follow these steps to import your assets.</h5>
                            <hr>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <h6>1. Download file template asset</h6>
                                <div class="pl-3">
                                    <p>Start by downloading the Excel template file by clicking the button below. This file has the required header fields to import the details of your assets.</p>
                                    <a data-toggle="tooltip" data-placement="top" title="Download Template" href="<?= base_url('/Asset/downloadSampleAsset'); ?>" target="_blank" class="btn btn-link" style="text-decoration: none;"><i class="fa fa-file-excel"></i> Download Template Excel</a>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <h6>2. Insert the asset data you have into template</h6>
                                <div class="pl-3">
                                    <p>Using Excel or other spreadsheet software, enter the detailed asset data into our template file. Make sure the data matches the header fields in the template.</p>
                                    <b>NOTE :</b>
                                    <p class="m-0">Do not change the column headers in the template. This is required for the import to work.
                                        A maximum of 10 assets can be imported at one time.
                                        Tag names, location tags, status assets that don't exist in the app will be entered as new data.
                                        When importing, the application will only enter new data, no data is deleted or updated.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <h6>3. Upload the updated template here</h6>
                                <div class="pl-3">
                                    <form action="post" enctype="multipart/form-data">
                                        <input type="file" class="uploadAsset" name="importAsset" />
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div :class="asset == true ? 'd-flex justify-content-end mt-4 fade-in' : 'd-none'">
                            <div class="d-flex justify-content-between">
                                <button class="btn-custom btn-custom-outline-danger mr-1" @click="reloadPage()"><i class="fa fa-times"></i> Cancel</button>
                                <button class="btn-custom btn-custom-primary" id="btnAssetUpload" @click="assetUpload()"><i class="fa fa-eye"></i> Review</button>
                            </div>
                        </div>
                    </div>
                    <div class="reviewData d-none">
                        <div class="pb-2 pt-4">
                            <h5>Review Data Uploaded</h5>
                            <hr>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5>Asset</h5>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <button class="btn-custom btn-custom-outline-danger mr-1" @click="reloadPage()"><i class="fa fa-times"></i> Cancel</button>
                                        <button class="btn-custom btn-custom-primary" @click="btnImport()"><i class="fa fa-upload"></i> Import now</button>
                                    </div>
                                </div>
                                <div class="w-100 table-responsive mt-2">
                                    <table class="table table-hover" id="tableAsset">
                                        <thead>
                                            <tr style="background-color: #f2f4f8;">
                                                <th scope="col">Asset Name</th>
                                                <th scope="col">Asset Number</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Tag Location</th>
                                                <th scope="col">Tag</th>
                                                <th scope="col">Set As</th>
                                                <th scope="col">Schedule Type</th>
                                                <th scope="col">Schedule Daily</th>
                                                <th scope="col">Schedule WeekDays</th>
                                                <th scope="col">Schedule Days</th>
                                                <th scope="col">Schedule Weeks</th>
                                                <th scope="col">RFID</th>
                                                <th scope="col">Coordinat</th>
                                                <th scope="col">Operation Status</th>
                                                <th class="d-none" scope="col">Parameter</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="text-align: left;" v-for="(items, i) in dataAsset">
                                                <td>{{ items.assetName }}</td>
                                                <td>{{ items.assetNumber }}</td>
                                                <td v-if="items.descJson == true"><button class="btn btn-link btn-sm p-0" @click="modalDetail(i)">Click here</button></td>
                                                <td v-else>{{ items.description }}</td>
                                                <td>{{ items.tagLocation }}</td>
                                                <td>{{ items.tag }}</td>
                                                <td>{{ items.schManual }}</td>
                                                <td>{{ items.schType }}</td>
                                                <td>{{ items.schFrequency }}</td>
                                                <td>{{ items.schWeekDays }}</td>
                                                <td>{{ items.schDays }}</td>
                                                <td>{{ items.schWeeks }}</td>
                                                <td>{{ items.rfid }}</td>
                                                <td>{{ items.coordinat }}</td>
                                                <td>{{ items.assetStatus }}</td>
                                                <td class="d-none"><button class="btn btn-link btn-sm p-0" @click="modalParameter(i)">Click here</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-4">
                            <div class="col">
                                <div>
                                    <h5>Parameter</h5>
                                </div>
                                <div class="table-responsive w-100">
                                    <table class="w-100 table table-hover">
                                        <thead>
                                            <tr style="background-color: #f2f4f8;">
                                                <th>Parameter</th>
                                                <th>Description</th>
                                                <th>Input Type</th>
                                                <th>Normal</th>
                                                <th>Abnormal</th>
                                                <th>Max</th>
                                                <th>Min</th>
                                                <th>Uom</th>
                                                <th>Option</th>
                                                <th>Show On</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(val, key) in parameter">
                                                <td>{{ val.parameterName }}</td>
                                                <td>{{ val.description }}</td>
                                                <td>{{ val.inputType }}</td>
                                                <td>{{ val.normal }}</td>
                                                <td>{{ val.abnormal }}</td>
                                                <td>{{ val.max }}</td>
                                                <td>{{ val.min }}</td>
                                                <td>{{ val.uom }}</td>
                                                <td>{{ val.option }}</td>
                                                <td>{{ val.showOn }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal more details -->
    <div class="modal fade" id="modalMoreDetails" tabindex="-1" role="dialog" aria-labelledby="modalMoreDetailsTitle" aria-hidden="true" style="z-index: 3000;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMoreDetailsTitle">More Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col">
                            <table class="table table-bordered">
                                <tr v-for="(val, key) in descJson">
                                    <td>
                                        <b class="text-capitalize">{{val.key.replace(/([A-Z])/g, " $1")}}</b>
                                    </td>
                                    <td>
                                        {{ val.value ? val.value : '-' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal parameter -->
    <div class="modal fade" id="modalParameter" tabindex="-1" role="dialog" aria-labelledby="parameterTitle" aria-hidden="true" style="z-index: 3000;">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 v-if="parameter != ''" class="modal-title" id="parameterTitle">Parameter {{ dataAsset[parameter.i].assetName }}</h5> -->
                    <h5 v-else class="modal-title" id="parameterTitle">Hello</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col">
                            <div class="table-responsive w-100">
                                <table class="w-100 table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Description</th>
                                            <th>Input Type</th>
                                            <th>Normal</th>
                                            <th>Abnormal</th>
                                            <th>Max</th>
                                            <th>Min</th>
                                            <th>Uom</th>
                                            <th>Option</th>
                                            <th>Show On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(val, key) in parameter">
                                            <td>{{ val.parameterName }}</td>
                                            <td>{{ val.description }}</td>
                                            <td>{{ val.inputType }}</td>
                                            <td>{{ val.normal }}</td>
                                            <td>{{ val.abnormal }}</td>
                                            <td>{{ val.max }}</td>
                                            <td>{{ val.min }}</td>
                                            <td>{{ val.uom }}</td>
                                            <td>{{ val.option }}</td>
                                            <td>{{ val.showOn }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
<!-- Custom Script Js -->
<script>
    const {
        ref,
        reactive
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var asset = false;
            var dataAsset = ref([{
                'assetName': 'test'
            }]);
            var dataImport = ref('');
            var data = ref([]);
            var descJson = ref([]);
            var parameter = ref([]);

            function assetUpload() {
                setTimeout(() => {
                    $('.uploadFile').addClass('d-none');
                    $('.reviewData').removeClass('d-none');
                }, 200);
                //review
                $('.reviewData').addClass('fade-in');
                $('.review-number').removeClass('bg-secondary');
                $('.review-number').addClass('bg-primary text-white');
                $('.review-line').removeClass('bg-secondary');
                $('.review-line').addClass('bg-primary');
            }

            function modalDetail(i) {
                let modal = new coreui.Modal(document.getElementById('modalMoreDetails'), {});
                modal.show();
                this.descJson = JSON.parse(this.dataAsset[i].description);
            }

            function modalParameter(i) {
                // let modal = new coreui.Modal(document.getElementById('modalParameter'), {});
                // modal.show();
                // this.parameter = this.dataAsset[i].parameter;
                // this.parameter.i = i;
            }

            function btnImport() {
                let formdata = new FormData();
                let asset = this.dataAsset;
                let parameter = this.parameter;
                asset.forEach((item, k) => {
                    formdata.append("dataAsset[]", JSON.stringify(this.dataAsset));
                });
                parameter.forEach((item, i) => {
                    formdata.append("parameter[]", JSON.stringify(this.parameter));
                })
                axios({
                    url: '<?= base_url('Asset/importAsset') ?>',
                    method: 'POST',
                    data: formdata
                }).then((res) => {
                    let rsp = res.data;
                    if (rsp.status == 200) {
                        $('.complete').removeClass('bg-secondary');
                        $('.complete').addClass('bg-primary text-white');
                        swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: rsp.message
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "<?= base_url('Asset'); ?>"
                            }
                        })
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: 'Failed Import Asset'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
                })
            }

            function reloadPage() {
                window.location.reload();
            }

            return {
                asset,
                dataAsset,
                dataImport,
                data,
                assetUpload,
                btnImport,
                modalDetail,
                modalParameter,
                descJson,
                parameter,
                reloadPage
            }
        },
    }).mount('#app');

    const pond = $('.uploadAsset').filepond({
        // instantUpload: true,
        allowMultiple: false,
        credits: false,
        acceptedFileTypes: 'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xlsx',
        server: {
            process: {
                url: '<?= base_url('/Asset/getDataImport') ?>',
                onload: (res) => {
                    // $('#btnAssetUpload').attr('disabled', false);
                    var rsp = JSON.parse(res);
                    if (rsp.status == 200) {
                        v.dataAsset = rsp.data.dataAsset;
                        v.parameter = rsp.data.parameter;
                        v.dataAsset.forEach((items, i) => {
                            v.dataAsset[i].descJson = false;
                            if (IsJsonString(items?.description)) {
                                v.dataAsset[i].descJson = true;
                            }
                        });
                        v.asset = true;
                    } else if (rsp == "failed") {
                        const swalWithBootstrapButtons = swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire(
                            rsp.message,
                            '',
                            'error'
                        )
                    } else {
                        return;
                    }
                }
            },
            fetch: null,
            revert: null,
        }
    });
</script>
<?= $this->endSection(); ?>