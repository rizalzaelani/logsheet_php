<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<style>
    table>tbody>tr>td {
        min-width: 100px;
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
                                <div class="pl-3 mb-3">
                                    <select class="form-control" name="category" id="category">
                                        <option value="" selected disabled>Select Category Industry</option>
                                        <option value="K3">K3</option>
                                        <option value="Bengkel">Bengkel</option>
                                        <option value="Toilet">Toilet</option>
                                    </select>
                                </div>
                                <div class="pl-3">
                                    <p>Start by downloading the Excel template file by clicking the button below. This file has the required header fields to import the details of your assets.</p>
                                    <a :class="category == 'K3' ? 'btn btn-link p-0' : 'd-none'" data-toggle="tooltip" data-placement="top" title="Download Template" href="<?= base_url('/Asset/downloadSampleAsset'); ?>" target="_blank" style="text-decoration: none;"><i class="fa fa-file-excel"></i> Download Template Excel K3</a>
                                    <a :class="category == 'Bengkel' ? 'btn btn-link p-0' : 'd-none'" data-toggle="tooltip" data-placement="top" title="Download Template" href="<?= base_url('/Asset/downloadSampleAsset'); ?>" target="_blank" style="text-decoration: none;"><i class="fa fa-file-excel"></i> Download Template Excel Bengkel</a>
                                    <a :class="category == 'Toilet' ? 'btn btn-link p-0' : 'd-none'" data-toggle="tooltip" data-placement="top" title="Download Template" href="<?= base_url('/Asset/downloadSampleAsset'); ?>" target="_blank" style="text-decoration: none;"><i class="fa fa-file-excel"></i> Download Template Excel Toilet</a>
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
                    <div class="reviewData hide">
                        <div class="pb-2 pt-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Review Data Uploaded</h5>
                            </div>
                            <div class="d-flex justify-content-end align-items-center">
                                <button class="btn-custom btn-custom-outline-danger mr-1" @click="reloadPage()"><i class="fa fa-times"></i> Cancel</button>
                                <button class="btn-custom btn-custom-primary" @click="btnImport()"><i class="fa fa-upload"></i> Import now</button>
                            </div>
                        </div>
                        <div class="mt-2 d-flex flex-row justify-content-start align-items-left w-100">
                            <ul class="nav nav-tabs w-100 d-flex flex-row align-items-left" role="tablist">
                                <li class="nav-item pr-4">
                                    <a class="nav-link active py-0 pl-0" data-toggle="tab" href="#asset" role="tab" aria-controls="asset" id="tabAsset">
                                        <h5>Asset</h5>
                                    </a>
                                </li>
                                <li class="nav-item pr-4">
                                    <a class="nav-link py-0 pl-0" data-toggle="tab" href="#parameter" role="tab" aria-controls="parameter" id="tabParameter">
                                        <h5>Parameter</h5>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="asset" role="tabpanel" aria-labelledby="tabAsset">
                                <div class="row pt-4">
                                    <div class="col">
                                        <div class="w-100 table-responsive">
                                            <table class="table table-hover display nowrap" id="tableAsset">
                                                <thead>
                                                    <tr style="background-color: #f2f4f8;">
                                                        <th scope="col">Asset</th>
                                                        <th scope="col">Tag Location</th>
                                                        <th scope="col">Tag</th>
                                                        <th scope="col">Schedule</th>
                                                        <th scope="col">RFID</th>
                                                        <th scope="col">Coordinat</th>
                                                        <th scope="col">Operation Status</th>
                                                        <th class="d-none" scope="col">Parameter</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr style="text-align: left;" v-for="(items, i) in dataAsset">
                                                        <td>
                                                            <span>{{ items.assetName }}</span><br>
                                                            <span class="text-muted mr-1">{{ items.assetNumber }}</span><i class="fa fa-eye text-info cursor-pointer" @click="modalDetail(i)"></i>
                                                        </td>
                                                        <td>
                                                            <div v-for="(val, i) in items.tagLocation">
                                                                <span class="badge badge-dark text-white p-1">{{ val }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div v-for="(val, i) in items.tag">
                                                                <span class="badge badge-dark text-white p-1">{{ val }}</span>
                                                            </div>
                                                        </td>
                                                        <td v-if="items.schManual == 'Manual'">
                                                            Manual
                                                        </td>
                                                        <td v-else>
                                                            <div v-if="items.schType == 'Daily'">
                                                                {{ items.schType }}<br>
                                                                ({{ items.schFrequency }})
                                                            </div>
                                                            <div v-else-if="items.schType == 'Weekly'">
                                                                {{ items.schType }}<br>
                                                                ({{ items.schWeekDays }})
                                                            </div>
                                                            <div v-else-if="items.schType == 'Monthly' && items.schDays == ''">
                                                                {{ items.schType }}<br>
                                                                ({{ items.schWeeks }})<br>
                                                                ({{ items.schWeekDays }})
                                                            </div>
                                                            <div v-else-if="items.schType == 'Monthly' && items.schDays != ''">
                                                                {{ items.schType }}<br>
                                                                ({{ items.schDays }})
                                                            </div>
                                                        </td>
                                                        <td>{{ items.rfid }}</td>
                                                        <td>
                                                            <div v-if="items.coordinat != ''">
                                                                <button class="btn btn-sm btn-link m-0 p-0" @click="mapCoordinat(items.coordinat)">Open Map</button>
                                                            </div>
                                                        </td>
                                                        <td>{{ items.assetStatus }}</td>
                                                        <td class="d-none"><button class="btn btn-link btn-sm p-0" @click="modalParameter(i)">Click here</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="parameter" role="tabpanel" aria-labelledby="tabParameter">
                                <div class="row pt-4">
                                    <div class="col">
                                        <div class="table-responsive w-100">
                                            <table class="w-100 table table-hover diwplay nowrap">
                                                <thead>
                                                    <tr style="background-color: #f2f4f8;">
                                                        <th>Parameter</th>
                                                        <th>Description</th>
                                                        <th>Input Type</th>
                                                        <th>Normal</th>
                                                        <th>Abnormal</th>
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
                                                        <td>
                                                            <div v-if="val.inputType == 'select' && val.normal != ''" v-for="(items, i) in val.normal">
                                                                {{ items }}
                                                            </div>
                                                            <div v-else-if="val.inputType == 'input' && val.max != null && val.min != null">
                                                                {{ val.min + ' - ' + val.max }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div v-if="val.inputType == 'select' && val.normal != ''" v-for="(items, i) in val.abnormal">
                                                                {{ items }}
                                                            </div>
                                                            <div v-else-if="val.inputType == 'input' && val.max != null && val.min != null">
                                                                {{ 'x < ' + val.min + '; x > ' + val.max }}
                                                            </div>
                                                        </td>
                                                        <td>{{ val.uom }}</td>
                                                        <td>
                                                            <div v-for="(items, i) in val.option">
                                                                {{ items }}
                                                            </div>
                                                        </td>
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
    </div>
    <!-- modal more details -->
    <div class="modal fade" id="modalMoreDetails" tabindex="-1" role="dialog" aria-labelledby="modalMoreDetailsTitle" aria-hidden="true" style="z-index: 3000;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMoreDetailsTitle">Description</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col">
                            <div v-if="isString(index)">
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
                            <div v-else>
                                <p>{{ descJson }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal maps -->
    <div class="modal fade" id="modalMap" tabindex="-1" role="dialog" aria-labelledby="modalMapTitle" aria-hidden="true" style="z-index: 3000;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMapTitle">Coordinat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="p1 text-center">{{ coordinat }}</h6>
                            <div class="d-flex justify-content-center align-items-center">
                                <div id="mapCoordinat" style="width: 400px !important; height: 300px !important;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal parameter -->
    <div class=" modal fade" id="modalParameter" tabindex="-1" role="dialog" aria-labelledby="parameterTitle" aria-hidden="true" style="z-index: 3000;">
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
        reactive,
        onMounted
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
            var index = null;
            var coordinat = ref("");
            var category = ref("");

            function assetUpload() {
                setTimeout(() => {
                    $('.uploadFile').addClass('d-none');
                    $('.reviewData').removeClass('hide');
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
                if (IsJsonString(this.dataAsset[i].description)) {
                    this.descJson = JSON.parse(this.dataAsset[i].description);
                    this.index = i;
                } else {
                    this.descJson = this.dataAsset[i].description;
                    this.index = i;
                }
            }

            function mapCoordinat(params) {
                this.coordinat = params;
                let coordinat = params.split(",");
                let lat = coordinat[0];
                let long = coordinat[1];

                this.myModal = new coreui.Modal(document.getElementById('modalMap'));
                this.myModal.show();

                $(document).ready(function() {
                    mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';
                    const map = new mapboxgl.Map({
                        container: 'mapCoordinat', // container ID
                        style: 'mapbox://styles/mapbox/streets-v11', // style URL
                        center: [long, lat], // starting position [lng, lat]
                        zoom: 14, // starting zoom
                    });
                    const marker = new mapboxgl.Marker()
                        .setLngLat([long, lat])
                        .addTo(map);
                })
            }

            function isString(i) {
                if (i != null) {
                    return IsJsonString(this.dataAsset[i].description);
                }
            }

            function btnImport() {
                let formdata = new FormData();
                let asset = this.dataAsset;
                let parameter = this.parameter;
                for (let a = 0; a < this.parameter.length; a++) {
                    if (this.parameter[a].abnormal != "") {
                        this.parameter[a].abnormal = (this.parameter[a].abnormal).join(",");
                    }
                    if (this.parameter[a].normal != "") {
                        this.parameter[a].normal = (this.parameter[a].normal).join(",");
                    }
                    if (this.parameter[a].option != "") {
                        this.parameter[a].option = (this.parameter[a].option).join(",");
                    }
                }
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

            onMounted(() => {
                $('#category').select2({
                    theme: 'coreui',
                    placeholder: 'Select Category Industry'
                })
                $('#category').on('change', function(){
                    v.category = $(this).val();
                })
            })

            return {
                asset,
                dataAsset,
                dataImport,
                data,
                assetUpload,
                btnImport,
                modalDetail,
                descJson,
                parameter,
                reloadPage,
                isString,
                index,
                mapCoordinat,
                coordinat,
                category
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
                        let lengthAsset = v.dataAsset.length;
                        for (let i = 0; i < lengthAsset; i++) {
                            v.dataAsset[i].tagLocation = v.dataAsset[i].tagLocation.split(",");
                            v.dataAsset[i].tag = v.dataAsset[i].tag.split(",");
                            if (v.dataAsset[i].schWeekDays != "") {
                                v.dataAsset[i].schWeekDays = v.dataAsset[i].schWeekDays.split(",").map((day) => day[0] + day[1]).join(',');
                            }
                        }
                        let lengthParameter = v.parameter.length;
                        for (let a = 0; a < lengthParameter; a++) {
                            if (v.parameter[a].abnormal != "") {
                                v.parameter[a].abnormal = v.parameter[a].abnormal.split(",");
                            }
                            if (v.parameter[a].normal != "") {
                                v.parameter[a].normal = v.parameter[a].normal.split(",");
                            }
                            if (v.parameter[a].option != "") {
                                v.parameter[a].option = v.parameter[a].option.split(",");
                            }
                        }
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