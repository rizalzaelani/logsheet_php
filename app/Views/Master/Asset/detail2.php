<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<style>
    table>thead>tr>th {
        vertical-align: middle !important;
        text-align: left;
    }

    table>tbody>tr>td {
        /* vertical-align: middle !important; */
        text-align: left;
    }

    /* table #tableChangeLogParam thead tr th{
        vertical-align: middle !important;
    }
    table #tableChangeLogParam tbody tr td{
        vertical-align: middle !important;
    } */

    .modal-fs {
        width: 100%;
        max-width: 100%;
        padding: 0;
        margin: 0;
    }

    .modal-fs .modal-content {
        min-height: 100vh;
        background-color: rgba(0, 0, 0, 0.8) !important;
    }

    .new {
        background-color: #DFFFE6 !important;
    }

    .old {
        background-color: #FFE6DF !important;
    }

    .btn-group-fab {
        position: fixed;
        width: 50px;
        height: auto;
        right: 20px;
        bottom: 20px;
    }

    .btn-group-fab div {
        position: relative;
        width: 100%;
        height: auto;
    }

    .btn-group-fab .btn {
        position: absolute;
        bottom: 0;
        border-radius: 50%;
        display: block;
        margin-bottom: 4px;
        width: 40px;
        height: 40px;
        margin: 4px auto;
    }

    .btn-group-fab .btn-main {
        width: 50px;
        height: 50px;
        right: 50%;
        margin-right: -25px;
        z-index: 9;
    }

    .btn-group-fab .btn-sub {
        bottom: 0;
        z-index: 8;
        right: 50%;
        margin-right: -20px;
        -webkit-transition: all 2s;
        transition: all 0.5s;
    }

    .btn-group-fab.active .btn-sub:nth-child(2) {
        bottom: 60px;
    }

    .btn-group-fab.active .btn-sub:nth-child(3) {
        bottom: 110px;
    }

    .btn-group-fab.active .btn-sub:nth-child(4) {
        bottom: 160px;
    }

    .btn-group-fab .btn-sub:nth-child(5) {
        bottom: 210px;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<?php
$assetTagId = (array_values(array_unique(explode(",", $assetData['tagId']))));
$assetLocationId = (array_values(array_unique(explode(",", $assetData['tagLocationId']))));
$assetStatus = array($assetData['assetStatusId']);
$assetTaggingType = array('rfid', 'coordinat', 'uhf');
$schFreq = array('1', '2', '3', '4', '6', '8', '12', '24');
$schDay = array('Su' => 'Sunday', 'Mo' => 'Monday', 'Tu' => 'Tuesday', 'We' => 'Wednesday', 'Th' => 'Thursday', 'Fr' => 'Friday', 'Sa' => 'Saturday');
$schDays = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 'Last');
$session = \Config\Services::session();
$sess = $session->get('adminId');
$assetId = $assetData['assetId'];


?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main card-border-top">
            <div class="card-body">
                <div class="d-flex flex-row justify-content-between align-items-center w-100">
                    <ul class="nav nav-tabs w-100 d-flex flex-row align-items-center" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" id="detail_tab" @click="checkTabDetail = true;  checkTabParameter = false;  checkTabSetting = false;">
                                <svg class="c-icon">
                                    <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-list-rich"></use>
                                </svg> Detail <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Contains brief data about assets. You can view asset data and data change log.</div>"></i></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#parameter" role="tab" aria-controls="parameter" id="parameter_tab" @click="checkTabDetail = false;  checkTabParameter = true;  checkTabSetting = false;">
                                <svg class="c-icon">
                                    <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-timeline"></use>
                                </svg> Parameter <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Contains parameter data of an asset. You can set the order of the parameters for the assets you own.</div>"></i></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting" role="tab" aria-controls="setting" id="setting_tab" @click="checkTabDetail = false;  checkTabParameter = false;  checkTabSetting = true;">
                                <svg class="c-icon">
                                    <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-cog"></use>
                                </svg> Setting <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Contains all data from assets. You can change all the data about the assets you own.</div>"></i></a></li>
                        <li class="nav-item ml-auto">
                            <h5 class="header-icon">
                                <a href="<?= $_SERVER['HTTP_REFERER'] ?? site_url("role") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                            </h5>
                            <!-- <a href="<?= base_url('/Asset'); ?>" class="btn btn-sm btn-success" style="display: flex;"><i class="fa fa-arrow-left"></i> Back</a> -->
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <!-- tab detail -->
                    <div class="tab-pane active" id="detail" role="tabpanel">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <table class="table mt-2">
                                    <tr>
                                        <th style="width: 200px;">Asset</th>
                                        <td>{{ assetData.assetName }}</td>
                                    </tr>
                                    <tr>
                                        <th>Asset Number</th>
                                        <td>{{ assetData.assetNumber }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tag</th>
                                        <td>
                                            <div style="max-height: 75px !important; overflow-y: auto;">
                                                <template v-if="assetData.tagName" v-for="(val, key) in _.uniq(assetData.tagName.split(','))">
                                                    <span class="badge badge-primary p-1 mr-1 mb-1" style="font-size: 13px;">{{ val }}</span>
                                                </template>
                                                <template v-else>-</template>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td>
                                            <div style="max-height: 75px !important; overflow-y: auto;">
                                                <template v-if="assetData.tagLocationName" v-for="(val, key) in _.uniq(assetData.tagLocationName.split(','))">
                                                    <span class="badge badge-primary p-1 mr-1 mb-1" style="font-size: 13px;">{{ val }}</span>
                                                </template>
                                                <template v-else>-</template>
                                            </div>
                                        </td>
                                    </tr>
                                    <template v-if="assetData.descriptionJson">
                                        <template v-for="(val, key) in assetData.descriptionJson">
                                            <tr class="mt-1 descJson">
                                                <th class="text-capitalize">{{val.key.replace(/([A-Z])/g, " $1")}}</th>
                                                <td>{{ val.value ? val.value : '-' }}</td>
                                            </tr>
                                        </template>
                                    </template>
                                    <template v-if="assetData.description">
                                        <tr>
                                            <th>Description</th>
                                            <td>
                                                {{ assetData.description }}
                                            </td>
                                        </tr>
                                    </template>
                                </table>
                            </div>
                            <div class="col-md-6 d-flex justify-content-center align-items-center imgMap">
                                <img src="<?= base_url() ?>/img/img-alt.png" alt="Image" :class="assetData.photo == null || assetData.photo == '' ? 'mt-1 m-0' :'d-none'" style="width: 200px !important; height: 200px !important;">
                                <img onclick="modalImgAsset()" :src="assetData.photo" alt="Image" :class="((assetData.photo != null && assetData.photo != '') ? 'mt-1 m-0' : 'd-none')" style="height: 200px !important; cursor: pointer;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <?php
                                        if (!checkLimitAsset()) { ?>
                                            <button @click="duplicate(assetData.assetId)" type="button" class="btn btn-sub btn-outline-primary has-tooltip disabled mr-1" disabled style="cursor: not-allowed !important"> <i class="fa fa-copy"></i> Duplicate
                                                <svg class="c-icon mr-1">
                                                    <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-lock-locked"></use>
                                                </svg>
                                            </button>
                                        <?php } else { ?>
                                            <button @click="duplicate(assetData.assetId)" type="button" class="btn btn-sub btn-outline-primary has-tooltip mr-1"> <i class="fa fa-copy"></i> Duplicate</button>
                                        <?php } ?>
                                        <a href="<?= base_url('Asset/exportPerAsset') . '/' . $assetId ?>" terget="_blank" class="btn btn-sub btn-outline-success"> <i class="fa fa-file-excel"></i> Export</a>
                                    </div>
                                    <button @click="deleteAsset()" type="button" class="btn btn-sub btn-outline-danger"> <i class="fa fa-trash"></i> Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- tab parameter -->
                    <div class=" tab-pane" id="parameter" role="tabpanel">
                        <h5 class="pt-3 pb-2 m-0"><b>Sorting Parameter</b></h5>
                        <div class="table-responsive w-100 mt-2">
                            <table class="table dt-responsive table-hover w-100" id="tableParam">
                                <thead>
                                    <tr class="bg-primary text-center">
                                        <th>Parameter</th>
                                        <th>Description</th>
                                        <th>Normal</th>
                                        <th>Abnormal</th>
                                        <th>UoM</th>
                                        <th>Show On</th>
                                        <th>Sorting</th>
                                    </tr>
                                    <tr class="d-none">
                                        <th width="10%">Parameter</th>
                                        <th width="10%">Description</th>
                                        <th width="20%">Normal</th>
                                        <th width="20%">Abnormal</th>
                                        <th width="10%">UoM</th>
                                        <th width="20%">Show On</th>
                                        <th width="10%">Sorting</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($parameter as $key) : ?>
                                        <tr>
                                            <td style="display: none;"><?= $i++; ?></td>
                                            <td style="display: none;"><?= $key['parameterId']; ?></td>
                                            <td><?= $key['parameterName']; ?></td>
                                            <td><?= $key['description']; ?></td>
                                            <td style="max-width: 150px !important">
                                                <?php if ($key['max'] != '' && $key['max'] != null && $key['inputType'] == 'input') {
                                                    echo $key['min'] . ' - ' . $key['max'];
                                                } else if ($key['normal'] != '' && $key['inputType'] == 'select') {
                                                    echo $key['normal'];
                                                } else {
                                                    echo '<i>-</i>';
                                                }
                                                ?>
                                            </td>
                                            <td style="max-width: 150px !important">
                                                <?php if ($key['min'] != '' && $key['min'] != null && $key['inputType'] == 'input') {
                                                    echo 'x < ' . $key['min'] . '; x > ' . $key['max'];
                                                } else if ($key['abnormal'] != '' && $key['inputType'] == 'select') {
                                                    echo $key['abnormal'];
                                                } else {
                                                    echo '<i>-</i>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($key['uom'] != '') {
                                                    echo $key['uom'];
                                                } else if ($key['option'] != '') {
                                                    echo $key['option'];
                                                } else {
                                                    echo '<i>-</i>';
                                                }
                                                ?>
                                            </td>
                                            <td><?= $key['showOn']; ?></td>
                                            <td class="handle" style="cursor: move;"><i class="fa fa-bars"></i></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- tab setting -->
                    <div class="tab-pane" id="setting" role="tabpanel">
                        <div class="row row-eq-height mt-3">
                            <div class="col-md-6 h-100">
                                <form enctype="multipart/form-data" method="post">
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="assetName">Asset <span class="required">*</span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="assetName" name="assetName" v-model="assetData.assetName" placeholder="Asset Name" required>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="assetNumber">Number <span class="required">*</span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="assetNumber" name="assetNumber" v-model="assetData.assetNumber" placeholder="Asset Number" required>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="moreDetails">More Details</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <label class="ml-1 c-switch c-switch-pill c-switch-label c-switch-opposite-success m-0">
                                                <input type="checkbox" name="moreDetailAssetInput" class="c-switch-input" @change="moreDetailAsset = $event.target.checked">
                                                <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row" :class="moreDetailAsset == true ? 'd-none' : ''">
                                        <div class="col-sm-3">
                                            <label for="asssetDesc">Description</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <textarea type="text" class="form-control" id="assetDesc" name="asssetDesc" v-model="assetData.description" rows="8" placeholder="Asset Description"></textarea>
                                        </div>
                                    </div>
                                    <div :class="moreDetailAsset == true ? '' : 'd-none'">
                                        <table class="table table-bordered table-sm" id="tableDescJson">
                                            <thead>
                                                <tr>
                                                    <th class="px-3">Key</th>
                                                    <th class="px-3">Value</th>
                                                    <th class="text-center">#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template v-for="(val, key) in assetData.descriptionJson">
                                                    <tr>
                                                        <th><input type="text" name="key[]" class="form-control input-transparent" v-model="descJson[key]['key']" placeholder="Key"></th>
                                                        <th><input type="text" name="value[]" class="form-control input-transparent" v-model="descJson[key]['value']" placeholder="Value"></th>
                                                        <th class="text-center"><button class="btn btn-link text-danger" @click="descJson.splice(key, 1)"><i class="fa fa-times"></i></button></th>
                                                    </tr>
                                                </template>
                                                <tr>
                                                    <th><input type="text" name="descJsonKey" class="form-control input-transparent" @keyup="addDescJson($event.target)" placeholder="Key"></th>
                                                    <th><input type="text" v-model="descJsonValue" class="form-control input-transparent" placeholder="Value"></th>
                                                    <th class="text-center"></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 h-100">
                                <div class="valueDefault w-100">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="<?= base_url() ?>/img/img-alt.png" alt="Image" :class="assetData.photo == null || assetData.photo == '' ? 'mt-1 m-0' : 'd-none'" style="width: 200px !important; height: 200px !important;">
                                        <!-- <div :class="((assetData.photo == null || assetData.photo == '') ? 'd-none' : '')">
                                        </div> -->
                                        <!-- <div :class="((assetData.photo != null && assetData.photo != '') ? '' : 'd-none')">
                                            </div> -->
                                        <img :src="assetData.photo" alt="Image" :class="((assetData.photo != null && assetData.photo != '') ? 'mt-1 m-0' : 'd-none')" style="height: 200px !important;">
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center">
                                        <input type="file" class="filepond mt-2 mb-2 w-100" name="filepond" id="logo" accept="image/png, image/jpeg, image/gif" />
                                    </div>
                                </div>
                                <template v-if="assetData.photo != null && assetData.photo != ''">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <label class="ml-1 c-switch c-switch-pill c-switch-label c-switch-opposite-danger m-0">
                                            <input type="checkbox" id="deleteAssetPhoto" name="deleteAssetPhoto" class="c-switch-input" @change="deleteAssetPhoto = $event.target.checked">
                                            <span class="c-switch-slider" data-checked="Yes" data-unchecked="No"></span>
                                        </label>
                                        <label class="ml-2 mb-0" for="showOn">Delete Photo <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="If asset photo exist, you can turn switch button to delete the photo."></i></label>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal add parameter-->
                <div class="modal fade" role="dialog" id="addParameterModal">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 :class="checkModalAdd == true ? 'modal-title' : 'd-none'" id="titleModalAdd">Add Parameter</h5>
                                <h5 :class="checkModalAdd == true ? 'd-none': 'modal-title'" id="titleModalEdit">Edit Parameter</h5>
                            </div>
                            <div :class="checkModalAdd == true || param.photo1 == '' || param.photo1 == null || this.param.deletePhoto == true ? 'modal-body' : 'modal-body pt-0'">
                                <div style="display: none !important;" class="row mb-3" id="previewImg">
                                    <div class="col p-0 d-flex justify-content-center align-items-center" style="height:150px; background-color: #f2f4f8" id="preview"></div>
                                </div>
                                <div class="container">
                                    <div class="form-group">
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="row mb-3">
                                                <label class="col-sm-3" for="parameter">Parameter <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Name for your parameter."></i></label>
                                                <div class="col-sm-9 p-0">
                                                    <input type="text" class="form-control parameter" name="parameter" placeholder="Parameter Name" v-model="param.parameterName" :required="true">
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-sm-3" for="type">Type <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Type input for your parameter."></i></label>
                                                <div class="col-sm-9 p-0">
                                                    <select class="form-control type" name="type" placeholder="Select Type">
                                                        <option value="" selected disabled>Select Type</option>
                                                        <option value="input">Input</option>
                                                        <option value="select">Select</option>
                                                        <option value="checkbox">Checkbox</option>
                                                        <option value="textarea">Free Text</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                            <div :class="param.inputType == 'input' ? 'row mb-3' : 'd-none'">
                                                <label class="col-sm-3" for="min">Min <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Min value when input type is 'input'."></i></label>
                                                <input type="number" class="form-control col-sm-9 min" name="min" placeholder="Min Value" v-model="param.min">
                                                <div class="invalid-feedback">
                                                    Field cannot be empty.
                                                </div>
                                            </div>
                                            <div :class="param.inputType == 'input' ? 'row mb-3' : 'd-none'">
                                                <label class="col-sm-3" for="max">Max <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Max value when input type is 'input'"></i></label>
                                                <input type="number" class="form-control col-sm-9 max" name="max" placeholder="Max Value" v-model="param.max">
                                                <div class="invalid-feedback">
                                                    Field cannot be empty.
                                                </div>
                                            </div>
                                            <div :class="param.inputType == 'select' ? 'row mb-3' : 'd-none'">
                                                <label class="col-sm-3" for="normal">Normal <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Normal value when input type is 'select'"></i></label>
                                                <div class="col-sm-9 p-0">
                                                    <select class="form-control normalAbnormal normal" name="normal" id="normal" multiple></select>
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                            <div :class="param.inputType == 'select' ? 'row mb-3' : 'd-none'">
                                                <label class="col-sm-3" for="abnormal">Abnormal <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Abormal value when input type is 'select'"></i></label>
                                                <div class="col-sm-9 p-0">
                                                    <select class="form-control normalAbnormal abnormal" name="abnormal" id="abnormal" multiple></select>
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                            <div :class="((param.inputType == 'input') || (param.inputType == 'select') ? 'row mb-3' : 'd-none')">
                                                <label class="col-sm-3" for="uom">Unit Of Measure <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Unit Of Measure of your parameter."></i></label>
                                                <input type="text" class="form-control col-sm-9 uom" name="uom" placeholder="Unit Of Measure" v-model="param.uom">
                                                <div class="invalid-feedback">
                                                    Field cannot be empty.
                                                </div>
                                            </div>
                                            <div :class="((param.inputType == 'select') || (param.inputType == 'checkbox') ? 'row mb-3' : 'd-none')">
                                                <label class="col-sm-3">Option <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Option value for your parameter."></i></label>
                                                <div class="col-sm-9 p-0">
                                                    <input class="form-control" type="text" name="option" id="option" v-model="param.option" placeholder="Option Value">
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-sm-3" for="showOn">Parameter Status <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Status parameter you have."></i></label>
                                                <div class="col-sm-9 p-0">
                                                    <select class="form-control showOn" name="showOn" id="showOn" multiple>
                                                        <?php foreach ($statusData as $key => $value) : ?>
                                                            <option value="<?= $value->assetStatusName ?>"><?= $value->assetStatusName ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-sm-3" for="description">Description <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Description of parameter."></i></label>
                                                <textarea class="form-control col-sm-9 description" rows="9" name="description" placeholder="Description of parameter" v-model="param.description"></textarea>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-3" for="photo">Photo <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Image of parameter."></i></label>
                                                <div class="col-9 p-0">
                                                    <input type="file" class="filepond mt-2 mb-2 w-100" name="photoParam" id="photoParam" />
                                                </div>
                                            </div>
                                            <div :class="checkModalAdd == false && param.photo1 != '' && param.photo1 != null ? 'row mb-3' : 'd-none'">
                                                <div class="col-sm-3">

                                                </div>
                                                <div class="col-sm-9 p-0">
                                                    <div class="d-flex justify-content-start align-items-center">
                                                        <label class="ml-1 c-switch c-switch-pill c-switch-label c-switch-opposite-danger m-0">
                                                            <input type="checkbox" id="deletePhoto" name="deletePhoto" class="c-switch-input" @change="deletePhoto = $event.target.checked">
                                                            <span class="c-switch-slider" data-checked="Yes" data-unchecked="No"></span>
                                                        </label>
                                                        <label class="ml-2 mb-0" for="showOn">Delete Photo <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="If parameter photo exist, you can turn switch button to delete the photo."></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" id="cancel" @click="btnCancelModalParam()"><i class=" fa fa-times"></i> Cancel</button>
                                <template v-if="checkModalAdd">
                                    <button type="submit" :class="checkModalAdd == true ? 'btn btn-success' : 'd-none'" @click="addTempParameter()" id="btnAddParam"><i class="fa fa-plus"></i> Add Parameter</button>
                                </template>
                                <template v-else>
                                    <template v-if="checkModalExist">
                                        <button type="button" class="btn btn-success" @click="updateExistParameter()" id="btnUpdateExistParameter"><i class="fa fa-check"></i> Save Changes</button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-success" @click="updateTempParameter()" id="btnUpdateParam"><i class="fa fa-check"></i> Save Changes</button>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal import parameter-->
                <div class="modal fade" role="dialog" id="importParameterModal">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="titleModalAdd">Upload File</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-3">
                                                <h5>Follow these steps to import your parameter.</h5>
                                                <hr>
                                                <div class="mt-3">
                                                    <h6>1. Download file template parameter</h6>
                                                    <div class="pl-3">
                                                        <p class="mb-0">Start by downloading the Excel template file by clicking the button below. This file has the required header fields to import the details of your parameter.</p>
                                                        <a data-toggle="tooltip" data-placement="top" title="Download Template" href="<?= base_url('/Asset/downloadSampleParameter'); ?>" target="_blank" class="btn btn-link p-0" style="text-decoration: none;"><i class="fa fa-file-excel"></i> Download Template Excel</a>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <h6>2. Insert the tag data you have into template</h6>
                                                    <div class="pl-3">
                                                        <p>Using Excel or other spreadsheet software, enter the detailed tag location data into our template file. Make sure the data matches the header fields in the template.</p>
                                                        <b>NOTE :</b>
                                                        <p class="m-0">Do not change the column headers in the template. This is required for the import to work.
                                                            A maximum of 30 parameter can be imported at one time.
                                                            When importing, the application will only enter new data, no data is deleted or updated.</p>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <h6>3. Upload the updated template here</h6>
                                                    <form action="post" enctype="multipart/form-data">
                                                        <input type="file" class="filepond mt-2 mb-2 w-100" name="importParam" id="fileImportParam" />
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal table import parameter-->
                <div class="modal fade" role="dialog" id="listImport">
                    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="titleModalAdd">List Parameter</h5>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive w-100">
                                    <table class="table w-100" id="tableImport">
                                        <thead>
                                            <tr>
                                                <th id="all">
                                                    <input type="checkbox" name="checkbox" id="select-all" value="_all">
                                                </th>
                                                <th>Parameter</th>
                                                <th>Description</th>
                                                <th>Normal</th>
                                                <th>Abnormal</th>
                                                <!-- <th>input type</th> -->
                                                <th>UoM</th>
                                                <th>Option</th>
                                                <th>show On</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancel"><i class=" fa fa-times"></i> Cancel</button>
                                <button type="button" class="btn btn-success" @click="insertParam()" id="btnAddParam"><i class="fa fa-plus"></i> Add Parameter</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal add tag -->
                <div class="modal fade" id="modalAddTag" tabindex="-1" role="dialog" aria-labelledby="modalTagTitle" aria-hidden="true" style="z-index: 3000;">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTagTitle">Add Tag</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <form action="">
                                        <div class="mb-3">
                                            <label for="addTagName">Tag Name <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Name for tag"></i></label>
                                            <input id="addTagName" type="text" class="form-control" required v-model="addTag.addTagName" placeholder="Tag Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="addTagDesc">Description <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Description for tag"></i></label>
                                            <textarea id="addTagDesc" class="form-control" required v-model="addTag.addTagDesc" rows="8" placeholder="Description of tag"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
                                <button type="button" class="btn btn-success" @click="addNewTag()"><i class="fa fa-plus"></i> Add Tag</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal add location -->
                <div class="modal fade" id="modalAddLocation" tabindex="-1" role="dialog" aria-labelledby="modalTagTitle" aria-hidden="true" style="z-index: 3000;">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTagTitle">Add Tag Location</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <div class="col-6">
                                        <form action="">
                                            <div class="mb-3">
                                                <label for="addLocName">Tag Location Name <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Name for tag"></i></label>
                                                <input id="addLocName" type="text" class="form-control" required v-model="addLocation.addLocationName" placeholder="Tag Location Name">
                                            </div>
                                            <div class="mb-3">
                                                <label for="latitude">Latitude <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Latitude"></i></label>
                                                <input id="latitude" type="text" class="form-control" required v-model="addLocation.addLocationLatitude" placeholder="Location Latitude">
                                            </div>
                                            <div class="mb-3">
                                                <label for="longitude">Longitude <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Longitude"></i></label>
                                                <input id="longitude" type="text" class="form-control" required v-model="addLocation.addLocationLongitude" placeholder="Location Longitude">
                                            </div>
                                            <div class="mb-3">
                                                <label for="addLocDesc">Description <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Description for tag"></i></label>
                                                <textarea id="addLocDesc" class="form-control" required v-model="addLocation.addLocationDesc" rows="8" placeholder="Description of tag location"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <div id="mapAddLocation" style="min-width: 100% !important; height: 500px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
                                <button type="button" class="btn btn-success" @click="addTagLocation()"><i class="fa fa-plus"></i> Add Tag Location</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal preview image -->
                <div class="modal fade pr-0" id="modalPreviewImg" tabindex="-1" role="dialog" aria-labelledby="modalPreview" aria-hidden="true">
                    <div class="modal-dialog modal-fs" role="document">
                        <div class="modal-content">
                            <div class="d-flex justify-content-end p-3">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class="text-white">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="fullPreview" class="d-flex justify-content-center aliign-items-center">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal preview change log -->
                <div class="modal fade pr-0" id="modalChange" tabindex="-1" role="dialog" aria-labelledby="modalPreview" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTagTitle">Detail Change Log</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mt-2">
                                    <h6>Changes {{ momentchangelog(dataChangeLog.time) }} by <b class="text-info">{{ dataChangeLog.username }}</b></h6>
                                </div>
                                <div class="mt-4">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Property Name</th>
                                                <th>Old Value</th>
                                                <th>New Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-if="changelog != ''">
                                                <template v-for="(val, i) in dataAB.data_before">
                                                    <template v-if="JSON.stringify(val) == JSON.stringify(dataAB.data_after[i])">
                                                        <tr>
                                                            <td>{{ _.startCase(i) }}</td>
                                                            <template v-if="IsArray(val)">
                                                                <td style="max-width: 200px !important">
                                                                    <div v-for="(value, key) in val">
                                                                        <div class="row">
                                                                            <div class="col-4">
                                                                                <b>{{value.key}}</b>
                                                                            </div>
                                                                            <div class="col-8">
                                                                                : {{value.value}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <template v-if="isUrl(val)">
                                                                    <template v-if="isFileExist(val)">
                                                                        <td>
                                                                            <img :src="val" alt="img" style="width: 200px"><br>
                                                                            <a :href="val" target="_blank">Open In New Tab</a>
                                                                        </td>
                                                                    </template>
                                                                    <template v-else>
                                                                        <td>
                                                                            File Removed
                                                                        </td>
                                                                    </template>
                                                                </template>
                                                                <template v-else>
                                                                    <td style="max-width: 200px !important">{{ val }}</td>
                                                                </template>
                                                            </template>

                                                            <template v-if="IsArray(dataAB.data_after[i])">
                                                                <td style="max-width: 200px !important">
                                                                    <div v-for="(value, key) in dataAB.data_after[i]">
                                                                        <div class="row">
                                                                            <div class="col-4">
                                                                                <b>{{value.key}}</b>
                                                                            </div>
                                                                            <div class="col-8">
                                                                                : {{value.value}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <template v-if="isUrl(dataAB.data_after[i])">
                                                                    <template v-if="isFileExist(dataAB.data_after[i])">
                                                                        <td>
                                                                            <img :src="dataAB.data_after[i]" alt="img" style="width: 200px"><br>
                                                                            <a :href="dataAB.data_after[i]" target="_blank">Open In New Tab</a>
                                                                        </td>
                                                                    </template>
                                                                    <template v-else>
                                                                        <td>
                                                                            File Removed
                                                                        </td>
                                                                    </template>
                                                                </template>
                                                                <template v-else>
                                                                    <td style="max-width: 200px !important">{{ dataAB.data_after[i] }}</td>
                                                                </template>
                                                            </template>
                                                        </tr>
                                                    </template>
                                                    <template v-else>
                                                        <tr>
                                                            <td>{{ _.startCase(i) }}</td>
                                                            <template v-if="IsArray(val)">
                                                                <td style="max-width: 200px !important" class="old">
                                                                    <div v-for="(value, key) in val">
                                                                        <div class="row">
                                                                            <div class="col-4">
                                                                                <b>{{value.key}}</b>
                                                                            </div>
                                                                            <div class="col-8">
                                                                                : {{value.value}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <template v-if="isUrl(val)">
                                                                    <template v-if="isFileExist(val)">
                                                                        <td class="old">
                                                                            <img :src="val" alt="img" style="width: 200px"><br>
                                                                            <a :href="val" target="_blank">Open In New Tab</a>
                                                                        </td>
                                                                    </template>
                                                                    <template v-else>
                                                                        <td class="old">
                                                                            File removed
                                                                        </td>
                                                                    </template>
                                                                </template>
                                                                <template v-else>
                                                                    <td style="max-width: 200px !important" class="old">{{ val }}</td>
                                                                </template>
                                                            </template>
                                                            <template v-if="IsArray(dataAB.data_after[i])">
                                                                <td style="max-width: 200px !important" class="new">
                                                                    <div v-for="(value, key) in dataAB.data_after[i]">
                                                                        <div class="row">
                                                                            <div class="col-4">
                                                                                <b>{{value.key}}</b>
                                                                            </div>
                                                                            <div class="col-8">
                                                                                : {{value.value}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <template v-if="isUrl(dataAB.data_after[i])">
                                                                    <template v-if="isFileExist(dataAB.data_after[i])">
                                                                        <td class="new">
                                                                            <img :src="dataAB.data_after[i]" alt="img" style="width: 200px"><br>
                                                                            <a :href="dataAB.data_after[i]" target="_blank">Open In New Tab</a>
                                                                        </td>
                                                                    </template>
                                                                    <template v-else>
                                                                        <td class="new">
                                                                            File Removed
                                                                        <td>
                                                                    </template>
                                                                </template>
                                                                <template v-else>
                                                                    <td style="max-width: 200px !important" class="new">{{ dataAB.data_after[i] }}</td>
                                                                </template>
                                                            </template>
                                                        </tr>
                                                    </template>
                                                </template>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal preview parameter log -->
                <div class="modal fade pr-0" id="modalChangeParam" tabindex="-1" role="dialog" aria-labelledby="modalPreviewParam" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalChangeParamTitle">Detail Change Log</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mt-2">
                                    <h6>Changes {{ momentchangelog(dataChangeLog.time) }} by <b class="text-info">{{ dataChangeLog.username }}</b></h6>
                                </div>
                                <div class="mt-4">
                                    <div class="table-responsive w-100">
                                        <table class="table display nowrap" id="tableChangeLogParam">
                                            <thead>
                                                <tr>
                                                    <th>Parameter</th>
                                                    <th>Description</th>
                                                    <th>Input Type</th>
                                                    <th>Normal</th>
                                                    <th>Abnormal</th>
                                                    <th>Option</th>
                                                    <th>Max</th>
                                                    <th>Min</th>
                                                    <th>Uom</th>
                                                    <th>Show On</th>
                                                    <th>Photo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template v-for="(val, i) in parameterGroupChangeLog">
                                                    <template v-if="val.length == 1">
                                                        <template v-for="(value, b) in val">
                                                            <tr>
                                                                <td>{{value.parameterName}}</td>
                                                                <td>{{value.description}}</td>
                                                                <td>{{value.inputType}}</td>
                                                                <td>{{value.normal}}</td>
                                                                <td>{{value.abnormal}}</td>
                                                                <td>{{value.option}}</td>
                                                                <td>{{value.max}}</td>
                                                                <td>{{value.min}}</td>
                                                                <td>{{value.uom}}</td>
                                                                <td>{{value.showOn}}</td>
                                                                <td>
                                                                    <template v-if="isUrl(value.photo1)">
                                                                        <template v-if="isFileExist(value.photo1)">
                                                                            <img :src="val[1].photo1" alt="img" style="width: 50px;"><br>
                                                                            <a :href="val[1].photo1" target="_blank">Open new tab</a>
                                                                        </template>
                                                                        <template>
                                                                            File Removed
                                                                        </template>
                                                                    </template>
                                                                    <template v-else>
                                                                        {{value.photo1}}
                                                                    </template>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </template>
                                                    <template v-else>
                                                        <tr>
                                                            <template v-if="val[0].parameterName != val[1].parameterName">
                                                                <td>
                                                                    <div class="old p-1">
                                                                        {{val[0].parameterName}}
                                                                    </div>
                                                                    <div class="new p-1">
                                                                        {{val[1].parameterName}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td>
                                                                    <div>
                                                                        {{val[0].parameterName}}
                                                                    </div>
                                                                    <div>
                                                                        {{val[1].parameterName}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-if="val[0].description != val[1].description">
                                                                <td>
                                                                    <div class="old p-1">
                                                                        {{val[0].description}}
                                                                    </div>
                                                                    <div class="new p-1">
                                                                        {{val[1].description}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td>
                                                                    <div>
                                                                        {{val[0].description}}
                                                                    </div>
                                                                    <div>
                                                                        {{val[1].description}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-if="val[0].inputType != val[1].inputType">
                                                                <td>
                                                                    <div class="old p-1">
                                                                        {{val[0].inputType}}
                                                                    </div>
                                                                    <div class="new p-1">
                                                                        {{val[1].inputType}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td>
                                                                    <div>
                                                                        {{val[0].inputType}}
                                                                    </div>
                                                                    <div>
                                                                        {{val[1].inputType}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-if="val[0].normal != val[1].normal">
                                                                <td>
                                                                    <div class="old p-1">
                                                                        {{val[0].normal}}
                                                                    </div>
                                                                    <div class="new p-1">
                                                                        {{val[1].normal}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td>
                                                                    <div>
                                                                        {{val[0].normal}}
                                                                    </div>
                                                                    <div>
                                                                        {{val[1].normal}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-if="val[0].abnormal != val[1].abnormal">
                                                                <td>
                                                                    <div class="old p-1">
                                                                        {{val[0].abnormal}}
                                                                    </div>
                                                                    <div class="new p-1">
                                                                        {{val[1].abnormal}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td>
                                                                    <div>
                                                                        {{val[0].abnormal}}
                                                                    </div>
                                                                    <div>
                                                                        {{val[1].abnormal}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-if="val[0].option != val[1].option">
                                                                <td>
                                                                    <div class="old p-1">
                                                                        {{val[0].option}}
                                                                    </div>
                                                                    <div class="new p-1">
                                                                        {{val[1].option}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td>
                                                                    <div>
                                                                        {{val[0].option}}
                                                                    </div>
                                                                    <div>
                                                                        {{val[1].option}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-if="val[0].max != val[1].max">
                                                                <td>
                                                                    <div class="old p-1">
                                                                        {{val[0].max}}
                                                                    </div>
                                                                    <div class="new p-1">
                                                                        {{val[1].max}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td>
                                                                    <div>
                                                                        {{val[0].max}}
                                                                    </div>
                                                                    <div>
                                                                        {{val[1].max}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-if="val[0].min != val[1].min">
                                                                <td>
                                                                    <div class="old p-1">
                                                                        {{val[0].min}}
                                                                    </div>
                                                                    <div class="new p-1">
                                                                        {{val[1].min}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td>
                                                                    <div>
                                                                        {{val[0].min}}
                                                                    </div>
                                                                    <div>
                                                                        {{val[1].min}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-if="val[0].uom != val[1].uom">
                                                                <td>
                                                                    <div class="old p-1">
                                                                        {{val[0].uom}}
                                                                    </div>
                                                                    <div class="new p-1">
                                                                        {{val[1].uom}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td>
                                                                    <div>
                                                                        {{val[0].uom}}
                                                                    </div>
                                                                    <div>
                                                                        {{val[1].uom}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-if="val[0].showOn != val[1].showOn">
                                                                <td>
                                                                    <div class="old p-1">
                                                                        {{val[0].showOn}}
                                                                    </div>
                                                                    <div class="new p-1">
                                                                        {{val[1].showOn}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td>
                                                                    <div>
                                                                        {{val[0].showOn}}
                                                                    </div>
                                                                    <div>
                                                                        {{val[1].showOn}}
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-if="val[0].photo1 != val[1].photo1">
                                                                <td>
                                                                    <div class="old p-1">
                                                                        <template v-if="isUrl(val[0].photo1)">
                                                                            <template v-if="isFileExist(val[0].photo1)">
                                                                                <img :src="val[0].photo1" alt="img" style="width: 50px;"><br>
                                                                                <a :href="val[0].photo1" target="_blank">Open new tab</a>
                                                                            </template>
                                                                            <template v-else>
                                                                                File Removed
                                                                            </template>
                                                                        </template>
                                                                        <template v-else>
                                                                            {{val[0].photo1}}
                                                                        </template>
                                                                    </div>
                                                                    <div class="new p-1">
                                                                        <template v-if="isUrl(val[1].photo1)">
                                                                            <template v-if="isFileExist(val[1].photo1)">
                                                                                <img :src="val[1].photo1" alt="img" style="width: 50px;"><br>
                                                                                <a :href="val[1].photo1" target="_blank">Open new tab</a>
                                                                            </template>
                                                                            <template v-else>
                                                                                File Removed
                                                                            </template>
                                                                        </template>
                                                                        <template v-else>
                                                                            {{val[1].photo1}}
                                                                        </template>
                                                                    </div>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td>
                                                                    <div>
                                                                        <template v-if="isUrl(val[0].photo1)">
                                                                            <template v-if="isFileExist(val[0].photo1)">
                                                                                <img :src="val[0].photo1" alt="img" style="width: 50px;"><br>
                                                                                <a :href="val[0].photo1" target="_blank">Open new tab</a>
                                                                            </template>
                                                                            <template v-else>
                                                                                File Removed
                                                                            </template>
                                                                        </template>
                                                                        <template v-else>
                                                                            {{val[0].photo1}}
                                                                        </template>
                                                                    </div>
                                                                    <div>
                                                                        <template v-if="isUrl(val[1].photo1)">
                                                                            <template v-if="isFileExist(val[1].photo1)">
                                                                                <img :src="val[1].photo1" alt="img" style="width: 50px;"><br>
                                                                                <a :href="val[1].photo1" target="_blank">Open new tab</a>
                                                                            </template>
                                                                            <template v-else>
                                                                                File Removed
                                                                            </template>
                                                                        </template>
                                                                        <template v-else>
                                                                            {{val[1].photo1}}
                                                                        </template>
                                                                    </div>
                                                                </td>
                                                            </template>
                                                        </tr>
                                                    </template>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- change log -->
        <div :class="checkTabDetail == true ? 'card card-main' : 'd-none'" id="cardChangeLog">
            <div class="row">
                <div class="mt-2 col-12 d-flex justify-content-between align-items-center">
                    <h5><b>Change Log</b></h5>
                    <div>
                        <div class="p-0" id="rangechangelog" style="cursor: pointer; padding: 5px 10px; width: 100%">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                </div>
                <div class="table-responsive w-100 mt-2 col-12">
                    <table class="table table-hover nowrap" id="tableChangeLog">
                        <thead class="bg-primary">
                            <tr>
                                <th>Date</th>
                                <th>Ip Address</th>
                                <th>Username</th>
                                <th>Activity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Location and Tag -->
        <div :class="checkTabSetting == true ? '' : 'd-none'" id="cardLocationTag">
            <div class="row">
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardLocationTag">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                                <h5>
                                    <svg class="c-icon">
                                        <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-location-pin"></use>
                                    </svg>
                                </h5>
                            </div>
                            <div>
                                <h5 class="mb-0">
                                    Asset Tag Location <span class="required">*</span>
                                </h5>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="location">Location</label>
                            <div class="col-md-9">
                                <select class="form-control" name="location" id="location" multiple="multiple">
                                    <?php foreach ($locationData as $val) : ?>
                                        <option class="optionLocation" value="<?= $val->tagLocationId; ?>" <?= in_array($val->tagLocationId, $assetLocationId) ? 'selected' : ''; ?>><?= $val->tagLocationName; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Field cannot be empty.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardLocationTag">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                                <h5>
                                    <svg class="c-icon mr-1">
                                        <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-tags"></use>
                                    </svg>
                                </h5>
                            </div>
                            <div>
                                <h5 class="mb-0">
                                    Asset Tag <span class="required">*</span>
                                </h5>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <?php
                            ?>
                            <label class="col-md-3 col-form-label" for="tag">Tag</label>
                            <div class="col-md-9">
                                <select class="form-control" name="tag" id="tag" multiple>
                                    <?php foreach ($tagData as $val) : ?>
                                        <option value="<?= $val->tagId; ?>" <?= in_array($val->tagId, $assetTagId) ? 'selected' : ''; ?>><?= $val->tagName; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Field cannot be empty.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule and Tagging -->
        <div :class="checkTabSetting == true ? '' : 'd-none'" id="cardScheduleOpt">
            <div class="row">
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardSchedule">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                                <h5>
                                    <svg class="c-icon mr-1">
                                        <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-calendar"></use>
                                    </svg>
                                </h5>
                            </div>
                            <div>
                                <h5 class="mb-0">
                                    Schedule
                                </h5>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="setSch">Set As <span class="required">*</span></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="setSch" id="setSch">
                                            <option value="Manual" selected>Manual</option>
                                            <option value="Automatic">Automatic</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Field cannot be empty.
                                        </div>
                                        <div :class="setSch == 'Manual' ? 'mt-1' : 'd-none'" style="font-size: 80%; width: 100%; color: #20202a">
                                            Please set schedule on schedule page
                                        </div>
                                    </div>
                                </div>
                                <div :class="setSch == 'Automatic' ? 'form-group row d-flex align-items-center schType' : 'd-none'">
                                    <div class="col-sm-3">
                                        <label for="schType">Schedule <span class="required">*</span></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="schType" id="schType">
                                            <option value="" selected disabled>Select Schedule Type</option>
                                            <option value="Daily">Daily</option>
                                            <option value="Weekly">Weekly</option>
                                            <option value="Monthly">Monthly</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Field cannot be empty.
                                        </div>
                                    </div>
                                </div>
                                <div :class="((assetData.schType == 'Daily') && (setSch == 'Automatic') ? 'mt-3' :'d-none')" id="daily">
                                    <div class="row">
                                        <div class="col">
                                            <div class="btn-group-toggle w-100 d-flex justify-content-between align-items-center" id="schFreq" data-toggle="buttons">
                                                <?php foreach ($schFreq as $val) : ?>
                                                    <label class="btn btn-sm btn-outline-primary" style="width: 12% !important;">
                                                        <input type="radio" name="schFreq" data-content="<?= $val ?>" id="schFreq<?= $val ?>" autocomplete="off"><?= $val ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div :class="((assetData.schType == 'Weekly') && (setSch == 'Automatic') ? 'mt-2' : 'd-none')" id="weekly">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="btn-group-toggle" id="schWeekly">
                                                        <?php foreach ($schDay as $key => $val) : ?>
                                                            <label class="btn btn-sm btn-outline-primary mx-1 mb-1" style="width: calc(25% - 0.5rem)" for="schWeekly<?= $key ?>">
                                                                <input class="form-check-input" name="schWeekly" id="schWeekly<?= $key ?>" type="checkbox" value="<?= $key ?>">
                                                                <?= $val ?>
                                                            </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div :class="((assetData.schType == 'Monthly') && (setSch == 'Automatic') ? '' : 'd-none')" id="monthly">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-3">
                                                    <label for="">Set Monthly As <span class="required">*</span></label>
                                                </div>
                                                <div class="col-9">
                                                    <div class="d-flex justify-content-start">
                                                        <div class="ml-4">
                                                            <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="days">
                                                            <label class="form-check-label mr-1" for="gridRadios1">
                                                                Days
                                                            </label>
                                                        </div>
                                                        <div class="ml-4">
                                                            <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="on">
                                                            <label class="form-check-label mr-1" for="gridRadios2">
                                                                On
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div :class="onDays == 'days' ? 'col' : 'd-none'" id="days">
                                                    <div class="btn-group-toggle" id="monthlyDays">
                                                        <?php foreach ($schDays as $key => $val) : ?>
                                                            <label class="btn btn-sm btn-outline-primary mx-1 mb-1" for="schMonthlyDays<?= $val ?>" style="width: calc(14.28% - 0.5rem) !important; display: inline-table">
                                                                <input class="form-check-input" name="schMonthlyDays" id="schMonthlyDays<?= $val ?>" type="checkbox" value="<?= $val ?>">
                                                                <?= $val ?>
                                                            </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div :class="onDays == 'on' ? 'col-12' : 'd-none'" id="on">
                                                    <div class="row">
                                                        <div class="col-6 pr-1">
                                                            <select name="onMonth" class="form-control on mr-1" id="monthlyOn" multiple>
                                                                <option value="First">First</option>
                                                                <option value="Second">Second</option>
                                                                <option value="Third">Third</option>
                                                                <option value="Fourth">Fourth</option>
                                                                <option value="Last">Last</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Field cannot be empty.
                                                            </div>
                                                        </div>
                                                        <div class="col-6 pl-1">
                                                            <select name="onDays" class="form-control on mr-1" id="monthlyOnDays" multiple>
                                                                <option value="Su">Sunday</option>
                                                                <option value="Mo">Monday</option>
                                                                <option value="Tu">Tuesday</option>
                                                                <option value="We">Wednesday</option>
                                                                <option value="Th">Thursday</option>
                                                                <option value="Fr">Friday</option>
                                                                <option value="Sa">Saturday</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Field cannot be empty.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardTagging">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                                <h5>
                                    <svg class="c-icon mr-1">
                                        <use xlink:href="<?= base_url() ?>/icons/coreui/svg/solid.svg#cis-qr-code"></use>
                                    </svg>
                                </h5>
                            </div>
                            <div>
                                <h5 class="mb-0">
                                    Asset Tagging
                                </h5>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <ul class="nav nav-tabs w-100 d-flex justify-content-center align-items-center" role="tablist">
                                <li class="nav-item"><a :class="['nav-link', (assetTagging[0].assetTaggingtype == 'rfid' ? 'active' : '')]" data-toggle="tab" href="#tabRfid" role="tab" aria-controls="tabRfid" id="rfid_tab">
                                        <svg class="c-icon">
                                            <use xlink:href="<?= base_url() ?>/icons/coreui/svg/solid.svg#cis-qr-code"></use>
                                        </svg> rfid </a></li>
                                <li class="nav-item"><a :class="['nav-link', (assetTagging[0].assetTaggingtype == 'coordinat' ? 'active' : '')]" data-toggle="tab" href="#tabCoordinate" role="tab" aria-controls="tabCoordinate" id="coordinate_tab">
                                        <svg class="c-icon">
                                            <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-map"></use>
                                        </svg> coordinate </a></li>
                                <li class="nav-item"><a :class="['nav-link', (assetTagging[0].assetTaggingtype == 'uhf' ? 'active' : 'disabled')]" data-toggle="tab" href="#tabUhf" role="tab" aria-controls="tabUhf" id="uhf_tab" @click="uhf()">
                                        <svg class="c-icon">
                                            <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-waves"></use>
                                        </svg> uhf <svg class="c-icon">
                                            <use xlink:href="<?= base_url() ?>/icons/coreui/svg/duotone.svg#cid-lock-locked"></use>
                                        </svg> </a></li>
                            </ul>
                            <div class="tab-content">
                                <div :class="['tab-pane', (assetTagging[0].assetTaggingtype == 'rfid' ? 'active' : '')]" id="tabRfid" role="tabpanel">
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <div class="form-group row d-flex align-items-center">
                                                <div class="col-3">
                                                    <label for="asset">Value <span class="required">*</span></label>
                                                </div>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" id="valRfid" name="valRfid" placeholder="Tagging Value" v-model="rfidValue" required>
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div :class="['tab-pane', (assetTagging[0].assetTaggingtype == 'coordinat' ? 'active' : '')]" id="tabCoordinate" role="tabpanel">
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <div class="form-group row d-flex align-items-center">
                                                <div class="col-3">
                                                    <label for="asset">Value <span class="required">*</span></label>
                                                </div>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" id="valCoordinate" name="valCoordinate" placeholder="Latitude, Longitude" v-model="coordinatValue" required>
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1" id="mapTagging" style="min-width: 100% !important; height: 200px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div :class="['tab-pane', (assetTagging[0].assetTaggingtype == 'uhf' ? 'active' : '')]" id="tabUhf" role="tabpanel">
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <div class="form-group row d-flex align-items-center">
                                                <div class="col-3">
                                                    <label for="asset">Value<span class="required">*</span></label>
                                                </div>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" id="valUhf" name="valUhf" placeholder="Tagging Value" v-model="assetTagging.assetTaggingValue" required>
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
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
            </div>
        </div>

        <!-- Operation and Config -->
        <div :class="checkTabSetting == true ? '' : 'd-none'" id="cardAssetTagging">
            <div class="row">
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardOperation">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                                <h5>
                                    <svg class="c-icon mr-1">
                                        <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-cog"></use>
                                    </svg>
                                </h5>
                            </div>
                            <div>
                                <h5 class="mb-0">
                                    Change Operation Mode
                                </h5>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="btn-group-toggle w-100" data-toggle="buttons" id="operation">
                                    <?php foreach ($statusData as $key => $val) : ?>
                                        <label class="btn btn-outline-primary mx-1  mb-1" style="width: calc(25% - 0.5rem) !important;">
                                            <input type="radio" name="options" data-content="<?= $val->assetStatusName ?>" id="<?= $val->assetStatusId ?>" autocomplete="off"><?= $val->assetStatusName ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <div class="invalid-feedback">
                                    Field cannot be empty.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardOther">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                                <h5>
                                    <svg class="c-icon mr-1">
                                        <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-settings-alt"></use>
                                    </svg>
                                </h5>
                            </div>
                            <div>
                                <h5 class="mb-0">
                                    Other Config
                                </h5>
                            </div>
                        </div>
                        <hr>
                        <table>
                            <tr class="mt-1">
                                <td width="40%">Show Last Value</td>
                                <td>:</td>
                                <td class="d-flex justify-content-start align-items-start">
                                    <div class="mr-2">
                                        <label class="ml-1 c-switch c-switch-pill c-switch-label c-switch-opposite-success m-0">
                                            <input type="checkbox" class="c-switch-input" disabled>
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                    <div>
                                        <svg class="c-icon mr-1">
                                            <use xlink:href="<?= base_url() ?>/icons/coreui/svg/duotone.svg#cid-lock-locked"></use>
                                        </svg>
                                    </div>
                                </td>
                            </tr>
                            <tr class="mt-1">
                                <td width="40%">Bypass Tagging RFID</td>
                                <td>:</td>
                                <td class="d-flex justify-content-start align-items-start">
                                    <div class="mr-2">
                                        <label class="ml-1 c-switch c-switch-pill c-switch-label c-switch-opposite-success m-0">
                                            <input type="checkbox" class="c-switch-input" disabled>
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                    <div>
                                        <svg class="c-icon mr-1">
                                            <use xlink:href="<?= base_url() ?>/icons/coreui/svg/duotone.svg#cid-lock-locked"></use>
                                        </svg>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- parameter -->
        <div :class="checkTabSetting == true ? 'card card-main' : 'd-none'" id="cardParameter">
            <div class="dt-search-input">
                <div class="input-container">
                    <a href="javascript:void(0)" class="suffix text-decoration-none dt-search-hide"><i class="c-icon cil-x" style="font-size: 1.5rem;"></i></a>
                    <input id="srcParameter" name="dt-search" class="material-input py-4" type="text" data-target="#tableParameter" placeholder="Search Data Parameter" />
                </div>
            </div>
            <div class="mt-2 d-flex justify-content-between align-items-center">
                <h5>
                    <b class="d-flex justify-content-start align-item-center">
                        <svg class="c-icon mr-1">
                            <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-timeline"></use>
                        </svg>
                        <p class="m-0"> Parameter <span class="required">*</span></p>
                    </b>
                </h5>
                <div class="d-flex justify-content-end">
                    <template v-if="checkLimitParameter()">
                        <button class="btn btn-sm btn-outline-primary mr-1" @click="importParameter()"><i class="fa fa-upload"></i> Import Parameter</button>
                    </template>
                    <template v-else>
                        <div>
                            <button class="btn btn-sm btn-outline-primary mr-1 disabled align-items-center" disabled @click="importParameter()" style="cursor: not-allowed !important"><i class="fa fa-upload"></i> Import Parameter
                                <svg class="c-icon mr-1">
                                    <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-lock-locked"></use>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <template v-if="checkLimitParameter()">
                        <button class="btn btn-sm btn-outline-primary" @click="addParameter(); checkModalAdd = true"><i class="fa fa-plus"></i> Add Parameter</button>
                    </template>
                    <template v-else>
                        <div>
                            <button class="btn btn-sm btn-outline-primary disabled" disabled @click="addParameter(); checkModalAdd = true" style="cursor: not-allowed !important"><i class="fa fa-plus"></i> Add Parameter
                                <svg class="c-icon mr-1">
                                    <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-lock-locked"></use>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
            <div class="table-responsive mt-2 w-100">
                <table class="table table-hover w-100 display nowrap" id="tableParameter">
                    <thead class="bg-primary">
                        <tr>
                            <th>#</th>
                            <th>Parameter</th>
                            <th>Description</th>
                            <th>Normal</th>
                            <th>Abnormal</th>
                            <th>UoM</th>
                            <th>Show On</th>
                            <th><i>Status</i></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr v-for="(items, i) in params" :key="i">
                            <td>{{ items.parameterName}}</td>
                            <td>{{ items.description}}</td>
                            <td v-if="items.max != null && items.inputType == 'input'">
                                {{ items.min + ' - ' + items.max }}
                            </td>
                            <td v-else-if="items.normal != '' && items.inputType == 'select'" style="max-height: 150px !important;">
                                {{ items.normal }}
                            </td>
                            <td v-else>
                                <i>-</i>
                            </td>
                            <td v-if="items.min != null && items.inputType == 'input'">
                                {{ 'x < ' + items.min + '; x > ' + items.max }}
                            </td>
                            <td v-else-if="items.abnormal != '' && items.inputType == 'select'" style="max-width: 150px !important">
                                {{ items.abnormal }}
                            </td>
                            <td v-else>
                                <i>-</i>
                            </td>
                            <td v-if="items.uom != ''">
                                {{ items.uom }}
                            </td>
                            <td v-else-if="items.option != ''">
                                {{ items.option }}
                            </td>
                            <td v-else>
                                <i>-</i>
                            </td>
                            <td>{{ items.showOn}}</td>
                            <td><i class="text-success"><span class="badge badge-success text-white">New!</span></i></td>
                            <td style="min-width: 90px !important">
                                <button class="btn btn-sm btn-outline-success mr-1" @click="editTempParameter(i); checkModalAdd = false; checkModalExist = false"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger" @click="removeTempParameter(i)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr v-for="(items, i) in parameter" :key="i">
                            <td>{{ items.parameterName}}</td>
                            <td>{{ items.description}}</td>
                            <td v-if="items.max != null && items.max != '' && items.inputType == 'input'">
                                {{ items.min + ' - ' + items.max }}
                            </td>
                            <td v-else-if="items.normal != '' && items.inputType == 'select'" style="max-width: 150px !important">
                                {{ items.normal }}
                            </td>
                            <td v-else>
                                <i>-</i>
                            </td>
                            <td v-if="items.min != null && items.min != '' && items.inputType == 'input'">
                                {{ 'x < ' + items.min + '; x > ' + items.max }}
                            </td>
                            <td v-else-if="items.abnormal != '' && items.inputType == 'select'" style="max-width: 150px !important">
                                {{ items.abnormal }}
                            </td>
                            <td v-else>
                                <i>-</i>
                            </td>
                            <td v-if="items.uom != ''">
                                {{ items.uom }}
                            </td>
                            <td v-else-if="items.option != ''">
                                {{ items.option }}
                            </td>
                            <td v-else>
                                <i>-</i>
                            </td>
                            <td>{{ items.showOn }}</td>
                            <td v-if="items.status == 'old'">
                                <i class="text-success"><span class="badge badge-info text-white">Old</span></i>
                            </td>
                            <td v-else-if="items.status == 'updated'">
                                <i class="text-warning"><span class="badge badge-warning text-white">Updated</span></i>
                            </td>
                            <td style="min-width: 90px !important">
                                <button class="btn btn-sm btn-outline-success mr-1" @click="editExistParameter(i); checkModalAdd = false; checkModalExist = true"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger" @click="removeExistParameter(i)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr> -->
                        <!-- <template v-if="tempParameterGroupData != ''" v-for="(valGP, keyGP, iGP) in tempParameterGroupData">
                                <template v-for="(val, key) in valGP">
                                    <template v-if="key == 0 & keyGP != val.parameterName">
                                            <tr>
                                                <td :rowspan="valGP.length + 1" class="text-center" style="vertical-align: text-top!important;">{{ iGP+1 }}</td>
                                                <td :rowspan="valGP.length + 1" class="text-center" style="vertical-align: text-top!important;">#</td>
                                                <th :colspan="8">{{ keyGP.replace(/#$/, "") }}</th>
                                            </tr>
                                    </template>
                                        <tr>
                                            <template v-if="key == 0 && keyGP == val.parameterName">
                                                <td class="text-center">{{ iGP + 1 }}</td>
                                                <td class="text-center">#</td>
                                            </template>
                                            <td>{{ (val.parameterName.includes("#") ? val.parameterName.replace(keyGP, "") : val.parameterName) }}</td>
                                            <td>{{ val.description }}</td>
                                            <template v-if="!val.option">
                                                <td v-if="!val.option" :class="!val.max ? 'font-italic' : ''">{{ !val.max ? "(Empty)" :val.min + ' - ' + val.max }}</td>
                                                <td>{{ !val.min ? "(Empty)" : 'x < ' + val.min + '; x > ' + val.max }}</td>
                                                <td style="max-width: 160px !important;" v-if="!val.option" :class="!val.uom ? 'font-italic' : ''" style="max-width: 160px;">{{ !val.uom ? "(Empty)" :val.uom }}</td>
                                            </template>
                                            <template v-else>
                                                <td :class="!val.abnormal ? 'font-italic text-center' : ''">{{ !val.abnormal ? "(Empty)": val.abnormal }}</td>
                                                <td :class="!val.normal ? 'font-italic text-center' : ''">{{ !val.normal ? "(Empty)" :val.normal }}</td>
                                                <td style="max-width: 160px !important;" :class="!val.option ? 'font-italic text-center' : ''" style="max-width: 160px;">{{ !val.option ? "(Empty)" :val.option }}</td>
                                            </template>
                                            <template v-if="val.uom != ''">
                                                <td>
                                                    {{ val.uom }}
                                                </td>
                                            </template>
                                            <template  v-else-if="val.option != ''">
                                                <td style="max-width: 160px !important;">
                                                    {{ val.option }}
                                                </td>
                                            </template>
                                            <template  v-else>
                                                <td>
                                                </td>
                                            </template>
                                            <td>
                                                <i class="text-success"><span class="badge badge-success text-white"><i>New!</i></span></i>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success mr-1" @click="editTempParameter(keyGP, key); checkModalAdd = false"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-danger" @click="removeTempParameter(keyGP, key)"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                </template>
                            </template> -->
                        <template v-for="(valGP, keyGP, iGP) in parameterGroupData">
                            <template v-for="(val, key) in valGP">
                                <template v-if="key == 0 & keyGP != val.parameterName">
                                    <tr>
                                        <td :rowspan="valGP.length + 1" class="text-center" style="vertical-align: text-top!important;">{{ iGP+1 }}</td>
                                        <!-- <td :rowspan="valGP.length + 1" class="text-center" style="vertical-align: text-top!important;">#</td> -->
                                        <th :colspan="8">{{ keyGP.replace(/#$/, "") }}</th>
                                    </tr>
                                </template>
                                <tr>
                                    <template v-if="key == 0 & keyGP == val.parameterName">
                                        <td style="vertical-align: text-top !important;">{{ iGP + 1 }}</td>
                                        <!-- <td style="vertical-align: text-top !important;">#</td> -->
                                    </template>
                                    <td>{{ (val.parameterName.includes("#") ? val.parameterName.replace(keyGP, "") : val.parameterName) }}</td>
                                    <td>{{ val.description }}</td>
                                    <template v-if="!val.option">
                                        <td v-if="!val.option" :class="!val.max ? 'font-italic' : ''">{{ !val.max ? "(Empty)" : val.min + ' - ' + val.max }}</td>
                                        <td>{{ val.min === "" || val.min == null ? "(Empty)" : 'x < ' + val.min + '; x > ' + val.max }}</td>
                                        <td style="max-width: 160px !important;" v-if="!val.option" :class="!val.uom ? 'font-italic' : ''">{{ !val.uom ? "(Empty)" : val.uom }}</td>
                                    </template>
                                    <template v-else>
                                        <td :class="!val.normal ? 'font-italic' : ''">{{ !val.normal ? "(Empty)" :val.normal }}</td>
                                        <td :class="!val.abnormal ? 'font-italic' : ''">{{ !val.abnormal ? "(Empty)": val.abnormal }}</td>
                                        <td style="max-width: 160px !important;" :class="!val.option ? 'font-italic' : ''">{{ !val.option ? "(Empty)" :val.option }}</td>
                                    </template>
                                    <td style="max-width: 1 60px !important;">
                                        {{ val.showOn }}
                                    </td>
                                    <template v-if="val.status == 'old'">
                                        <td>
                                            <i class="text-success"><span class="badge badge-info text-white">Old</span></i>
                                        </td>
                                    </template>
                                    <template v-else-if="val.status == 'updated'">
                                        <td>
                                            <i class="text-warning"><span class="badge badge-warning text-white">Updated</span><i>
                                        </td>
                                    </template>
                                    <template v-else>
                                        <td>
                                            <i class="text-success"><span class="badge badge-success text-white"><i>New!</i></span><i>
                                        </td>
                                    </template>
                                    <td style="min-width: 90px !important">
                                        <template v-if="val.status == 'New'">
                                            <button id="tempEdit" class="btn btn-sm btn-outline-success mr-1" @click="editTempParameter(keyGP, key, val.parameterId);checkModalAdd = false; checkModalExist = false"><i class="fa fa-edit"></i></button>
                                            <button id="tempDel" class="btn btn-sm btn-outline-danger" @click="removeTempParameter(keyGP, key, val.parameterId)"><i class="fa fa-trash"></i></button>
                                        </template>
                                        <template v-else>
                                            <button class="btn btn-sm btn-outline-success mr-1" @click="editExistParameter(keyGP, key, val.parameterId);checkModalAdd = false; checkModalExist = true"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-danger" @click="removeExistParameter(keyGP, key, val.parameterId)"><i class="fa fa-trash"></i></button>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <div :class="checkTabDetail == true ? '' : 'd-none'">
            <div class="btn-group-fab" role="group" aria-label="FAB Menu">
                <div>
                    <button type="button" class="btn btn-main btn-success has-tooltip" data-placement="left" title="Menu"> <i class="fa fa-bars"></i> </button>
                    <button @click="deleteAsset()" type="button" class="btn btn-sub btn-danger has-tooltip" data-toggle="tooltip" data-placement="left" title="Delete Asset"> <i class="fa fa-trash"></i> </button>
                    <button @click="duplicate(assetData.assetId)" type="button" class="btn btn-sub btn-primary has-tooltip" data-toggle="tooltip" data-placement="left" title="Duplicate Asset"> <i class="fa fa-copy"></i> </button>
                </div>
            </div>
        </div> -->
        <div :class="((checkTabSetting == true) || (checkTabParameter == true && parameter.length > 0) ? 'btn-fab' : 'd-none')" aria-label="fab">
            <div>
                <button type="button" id="btnSaveSetting" @click="checkTabSetting == true ? btnSaveSetting() : btnSaveSorting()" class="btn btn-main btn-success has-tooltip" data-toggle="tooltip" data-placement="top" title="Save Changes">
                    <i class="fa fa-save"></i>
                </button>
            </div>
        </div>
    </div>
    <?= $this->endSection(); ?>
    <?= $this->section('customScripts'); ?>
    <!-- Custom Script Js -->
    <script>
        const {
            onMounted,
            reactive,
            ref,
            computed,
            toRaw
        } = Vue;

        const v = Vue.createApp({
            el: '#app',
            setup() {
                var subscription = <?= json_encode($subscription) ?>;
                var masterTag = <?= json_encode($tagData) ?>;
                var masterTagLocation = <?= json_encode($locationData) ?>;
                var masterParameter = <?= json_encode($parameterData) ?>;
                const start = moment().subtract(6, 'days');
                const end = moment();

                var tableChangeLog = ref("");
                var changelog = ref("");
                var dataChangeLog = ref("");
                var dataAB = ref("");
                var parameterChangeLog = reactive([]);
                var parameterGroupChangeLog = reactive([]);

                var checkTabDetail = ref(true);
                var checkTabParameter = ref(false);
                var checkTabSetting = ref(false);
                var assetData = reactive(<?= json_encode($assetData); ?>);
                var statusData = ref(<?= json_encode($statusData); ?>);
                var assetPhoto = ref("");
                var deleteAssetPhoto = ref(false);
                var parameter = reactive(<?= json_encode($parameter); ?>);
                var compareParameter = reactive(<?= json_encode($parameter); ?>);
                var deletedParameter = ref([]);
                var editedParameter = ref([]);
                var sortingParameter = ref([]);
                var myModal = ref('');
                var checked = ref('');
                //check modal param
                var checkModalAdd = ref(false);
                var checkModalExist = ref(false);
                var file = ref('');
                var setSch = ref('');
                var schFreq = ref([]);
                var selectedSchWeekly = ref([]);
                var selectedSchMonthlyDays = ref([]);
                var onDays = ref('');
                var assetTagging = reactive(<?= json_encode($tagging); ?>);
                var valRfid = ref('');
                var valCoordinate = ref('');
                var valUhf = ref('');
                var addTag = reactive({
                    addTagId: uuidv4(),
                    addTagName: '',
                    addTagDesc: ''
                });
                var tag = ref([]);
                var tags = ref([]);
                var addLocation = reactive({
                    addLocationId: uuidv4(),
                    addLocationName: '',
                    addLocationLatitude: '',
                    addLocationLongitude: '',
                    addLocationDesc: '',
                });
                var tagLocation = ref([]);
                var locations = ref([]);
                var param = reactive({
                    parameterId: uuidv4(),
                    sortId: null,
                    parameterName: '',
                    photo: '',
                    photo1: '',
                    photo2: '',
                    photo3: '',
                    description: '',
                    uom: '',
                    min: null,
                    max: null,
                    normal: '',
                    abnormal: '',
                    option: '',
                    inputType: '',
                    showOn: '',
                    deletePhoto: false
                });
                var tempParameterGroupData = ref("");
                var allParameter = [];
                const parameterGroupData = ref("");

                var tempPhoto = ref('');
                var params = ref([]);
                var paramPhoto = ref("");
                var deletePhoto = ref(false);
                var pathParamPhoto = ref("../uploads/Asset/file" + "<?= $sess ?>" + "/");
                var importList = reactive({});
                var tableImportParam = ref("");
                var listNewParam = ref([]);
                var submited = ref(false);
                var descJsonValue = ref('');
                var moreDetailAsset = ref(IsJsonString(assetData?.description))
                if (assetData.schWeekDays != "" && assetData.schType == "Monthly") {
                    assetData.schMonthlyWeekDays = assetData.schWeekDays;
                    if (assetData.schMonthlyWeekDays != "") {
                        assetData.schWeekDays = ref("");
                    }
                }
                if (IsJsonString(assetData?.description)) {
                    assetData.descriptionJson = JSON.parse(assetData.description);
                    assetData.description = '';
                } else {
                    assetData.descriptionJson = [];
                }

                var descJson = reactive(assetData.descriptionJson);

                function momentchangelog(date) {
                    return moment(date).format("DD MMM YYYY hh:mm:ss")
                }

                function modalAddTag() {
                    if (!(this.checkLimitTag())) {
                        return swal.fire({
                            icon: 'info',
                            title: "Your tag has reached the limit"
                        });
                    }
                    this.myModal = new coreui.Modal(document.getElementById('modalAddTag'));
                    this.myModal.show();
                };

                function addNewTag() {
                    if (!(this.checkLimitTag())) {
                        return swal.fire({
                            icon: 'info',
                            title: "Your tag has reached the limit"
                        });
                    }
                    if (this.addTag.addTagName == '') {
                        const swalWithBootstrapButtons = swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire({
                            title: 'Failed!',
                            text: 'Field tag name cannot be empty!.',
                            icon: 'error',
                            allowOutsideClick: false
                        })
                    } else {
                        $('#tag').append(`<option class="optTag` + this.addTag.addTagId + `" value="` + this.addTag.addTagId + `" selected>` + this.addTag.addTagName + `</option>`);
                        this.tag.push($(`.optTag` + this.addTag.addTagId + ``).val());
                        this.tags.push(this.addTag);
                        this.assetData.tagId.push(this.addTag.addTagId);
                        this.addTag = reactive({
                            addTagId: uuidv4(),
                            addTagName: '',
                            addTagDesc: '',
                        })
                        this.myModal.hide();
                    }
                    return;
                    axios.post('<?= base_url('Asset/addTag'); ?>', {
                        assetId: this.assetData.assetId,
                        tagId: uuidv4(),
                        tagName: this.addTagName,
                        description: this.addTagDesc
                    }).then(res => {
                        if (res.data.status == 'success') {
                            this.myModal.hide();
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
                        } else {
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
                };

                function modalAddLocation() {
                    if (!(this.checkLimitTagLocation())) {
                        return swal.fire({
                            icon: 'info',
                            title: "Your tag has reached the limit"
                        });
                    }
                    this.myModal = new coreui.Modal(document.getElementById('modalAddLocation'));
                    this.myModal.show();
                    // add location map
                    $(document).ready(function() {
                        mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';
                        const map = new mapboxgl.Map({
                            container: 'mapAddLocation', // container ID
                            style: 'mapbox://styles/mapbox/streets-v11', // style URL
                            center: [109.005913, -7.727989], // starting position [lng, lat]
                            zoom: 14, // starting zoom
                        });
                        map.addControl(new mapboxgl.FullscreenControl());
                        map.resize();
                        const marker = new mapboxgl.Marker({
                                draggable: true
                            })
                            .setLngLat([109.005913, -7.727989])
                            .addTo(map);

                        function onDragEnd(params) {
                            const lnglat = marker.getLngLat();
                            let lat = lnglat.lat;
                            let long = lnglat.lng;
                            v.addLocation.addLocationLatitude = lat;
                            v.addLocation.addLocationLongitude = long;
                        }
                        marker.on('dragend', onDragEnd);
                    })
                };

                function addTagLocation() {
                    if (!(this.checkLimitTagLocation())) {
                        return swal.fire({
                            icon: 'info',
                            title: "Your tag has reached the limit"
                        });
                    }
                    if (this.addLocation.addLocationName == '') {
                        const swalWithBootstrapButtons = swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire({
                            title: 'Failed!',
                            text: 'Field location name cannot be empty!.',
                            icon: 'error',
                            allowOutsideClick: false
                        })
                    } else {
                        $('#location').append(`<option class="optLocation` + this.addLocation.addLocationId + `" value="` + this.addLocation.addLocationId + `" selected>` + this.addLocation.addLocationName + `</option>`);
                        this.tagLocation.push($(`.optLocation` + this.addLocation.addLocationId + ``).val());
                        this.locations.push(this.addLocation);
                        this.assetData.tagLocationId.push(this.addLocation.addLocationId);
                        this.addLocation = reactive({
                            addLocationId: uuidv4(),
                            addLocationName: '',
                            addLocationLatitude: '',
                            addLocationLongitude: '',
                            addLocationDesc: '',
                        })
                        this.myModal.hide();
                    }
                };

                function editExistParameter(keyGP, key, parameterId) {
                    this.checkModalExist = true;
                    this.deletePhoto = ref(false);
                    this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                    this.myModal.show();

                    $('#normal').find('option').remove();
                    $('#abnormal').find('option').remove();
                    $('#imgParam').remove();

                    this.paramPhoto = ref("");
                    $('#photoParam').filepond('removeFiles');
                    FilePond.destroy(document.querySelector('#photoParam'));

                    let dt = "";
                    this.allParameter[0].forEach((val, i) => {
                        if (val.parameterId == parameterId) {
                            // dt = _.cloneDeep(v.allParameter[0][i]);
                            dt = {
                                ...toRaw(v.allParameter[0][i])
                            }
                        }
                    });

                    if (dt.photo != '' && dt.photo != undefined) {
                        let url = URL.createObjectURL(dt.photo)
                        const inputElement = document.querySelector('#photoParam');
                        var photoEdit = {
                            acceptedFileTypes: ['image/png', 'image/jpeg'],
                            allowFilePoster: true,
                            allowImagePreview: true,
                            imagePreviewMaxHeight: 200,
                            allowImageCrop: true,
                            allowMultiple: false,
                            credits: false,
                            styleLoadIndicatorPosition: 'center bottom',
                            styleProgressIndicatorPosition: 'right bottom',
                            styleButtonRemoveItemPosition: 'left bottom',
                            styleButtonProcessItemPosition: 'right bottom',
                            files: [{
                                source: url,
                                options: {
                                    type: 'local',
                                    file: dt.photo,
                                    metadata: {
                                        poster: ''
                                    }
                                }
                            }]
                        };
                        let pond = FilePond.create(inputElement, photoEdit);
                        pond.on('addfile', (error, file) => {
                            v.paramPhoto = file.file;
                        })
                        pond.on('removefile', (error, file) => {
                            v.paramPhoto = ref("");
                        })
                    } else {
                        var filepondParam = {
                            acceptedFileTypes: ['image/png', 'image/jpeg'],
                            allowFilePoster: true,
                            allowImagePreview: true,
                            imagePreviewMaxHeight: 200,
                            allowImageCrop: true,
                            allowMultiple: false,
                            credits: false,
                            styleLoadIndicatorPosition: 'center bottom',
                            styleProgressIndicatorPosition: 'right bottom',
                            styleButtonRemoveItemPosition: 'left bottom',
                            styleButtonProcessItemPosition: 'right bottom',
                        };

                        let pond = FilePond.create(document.querySelector('#photoParam'), filepondParam);
                        pond.on('addfile', (error, file) => {
                            v.paramPhoto = file.file;
                        })
                        pond.on('removefile', (error, file) => {
                            v.paramPhoto = ref("");
                        })
                    }
                    this.param.parameterId = dt.parameterId;
                    this.param.sortId = dt.sortId;
                    this.param.parameterName = dt.parameterName;
                    this.param.photo = dt.photo;
                    this.param.photo1 = dt.photo1;
                    this.param.photo2 = dt.photo2;
                    this.param.photo3 = dt.photo3;
                    this.param.description = dt.description;
                    if (dt.uom != "") {
                        this.param.uom = dt.uom;
                    } else {
                        this.param.uom = "";
                    }
                    this.param.max = dt.max;
                    this.param.min = dt.min;
                    this.param.normal = dt.normal;
                    this.param.abnormal = dt.abnormal;
                    this.param.option = dt.option;
                    this.param.inputType = dt.inputType;
                    this.param.showOn = dt.showOn;
                    this.param.deletePhoto = dt.deletePhoto;
                    this.param.keyGP = dt.parameterName.includes("#") ? dt.parameterName.split("#")[0] + "#" : dt.parameterName;

                    if (!this.param.deletePhoto) {
                        $('#deletePhoto').prop('checked', false);
                    }

                    if (this.param.photo1 != "" && !this.param.deletePhoto && this.param.photo1 != null) {
                        $('#previewImg').show();
                        $('#preview').append("<img class='img-thumbnail' id='imgParam' style='height:150px; cursor: pointer' src='" + this.param.photo1 + "' alt=''  onclick='modalPreviewImg()' data-toggle='tooltip' title='click to preview this image'>");
                    } else if (this.param.photo1 == "" || this.param.photo1 == null || this.param.deletePhoto) {
                        $('#previewImg').hide();
                    }

                    if (this.param.inputType != '') {
                        $('.type').val(this.param.inputType).trigger("change");
                    }

                    if (this.param.normal != '' || this.param.abnormal != '') {
                        $lengthNormal = this.param.normal.split(",").length;
                        $lengthAbnormal = this.param.abnormal.split(",").length;
                        if ($lengthNormal > 0) {
                            var dataNormal = this.param.normal.split(",");
                            for (let index = 0; index < dataNormal.length; index++) {
                                $('#normal').append(`<option class="optNormal" value="` + dataNormal[index] + `" selected>` + dataNormal[index] + `</option>`);
                            }
                        }
                        if ($lengthAbnormal > 0) {
                            var dataAbnormal = this.param.abnormal.split(",");
                            for (let index = 0; index < dataAbnormal.length; index++) {
                                $('#abnormal').append(`<option class="optAbnormal" value="` + dataAbnormal[index] + `" selected>` + dataAbnormal[index] + `</option>`);
                            }
                        }
                    }
                    if (this.param.showOn != '') {
                        $('#showOn').val(this.param.showOn.split(",")).trigger('change');
                    }
                }

                function updateExistParameter() {
                    let min = (this.param.min == null) && (this.param.inputType == 'input') ? true : false;
                    let max = (this.param.max == null) && (this.param.inputType == 'input') ? true : false;
                    let uom = ((this.param.uom === "") && ((this.param.inputType == 'input') || (this.param.inputType == 'select'))) ? true : false;
                    let normal = ((this.param.normal == "") && (this.param.inputType == 'select')) ? true : false;
                    let abnormal = ((this.param.abnormal == "") && (this.param.inputType == 'select')) ? true : false;
                    let option = ((this.param.option == "") && ((this.param.inputType == 'select') || this.param.inputType == 'checkbox')) ? true : false;
                    if (this.param.parameterName == '' || this.param.inputType == '' || this.param.showOn == '' || min == true || max == true || uom == true || normal == true || abnormal == true || option == true) {
                        const swalWithBootstrapButtons = swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire({
                            title: 'Failed!',
                            text: "Invalid value!",
                            icon: 'error'
                        })

                        if (this.param.parameterName != '') {
                            $('.parameter').removeClass('is-invalid');
                        }
                        if (this.param.inputType != '') {
                            $('.type').removeClass('is-invalid');
                        }

                        //remove invalid class
                        // input type
                        if (this.param.inputType == 'input') {
                            if (this.param.min != null) {
                                $('.min').removeClass('is-invalid');
                            }
                            if (this.param.max != null) {
                                $('.max').removeClass('is-invalid');
                            }
                            if (this.param.uom != "" || this.param.uom != null) {
                                $('.uom').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal != "") {
                                $('#normal').removeClass('is-invalid');
                            }
                            if (this.param.abnormal != "") {
                                $('#abnormal').removeClass('is-invalid');
                            }
                            if (this.param.uom != "") {
                                $('.uom').removeClass('is-invalid');
                            }
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        }

                        if (this.param.showOn != '') {
                            $('.showOn').removeClass('is-invalid');
                        }

                        //end remove invalid class

                        //add invalid class
                        if (this.param.parameterName == '') {
                            $('.parameter').addClass('is-invalid');
                        }
                        if (this.param.inputType == '') {
                            $('.type').addClass('is-invalid');
                        }
                        if (this.param.inputType == 'input') {
                            if (this.param.min == null) {
                                $('.min').addClass('is-invalid');
                            }
                            if (this.param.max == null) {
                                $('.max').addClass('is-invalid');
                            }
                            if (this.param.uom == "" || this.param.uom == null) {
                                $('.uom').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal == "") {
                                $('#normal').addClass('is-invalid');
                            }
                            if (this.param.abnormal == "") {
                                $('#abnormal').addClass('is-invalid');
                            }
                            if (this.param.uom == "") {
                                $('.uom').addClass('is-invalid');
                            }
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        }

                        if (this.param.showOn == '') {
                            $('.showOn').addClass('is-invalid');
                        }
                    } else {
                        if (this.param.parameterName != '') {
                            $('.parameter').removeClass('is-invalid');
                        }
                        if (this.param.inputType != '') {
                            $('.type').removeClass('is-invalid');
                        }

                        //remove invalid class
                        // input type
                        if (this.param.inputType == 'input') {
                            if (this.param.min != null) {
                                $('.min').removeClass('is-invalid');
                            }
                            if (this.param.max != null) {
                                $('.max').removeClass('is-invalid');
                            }
                            if (this.param.uom != "" || this.param.uom != null) {
                                $('.uom').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal != "") {
                                $('#normal').removeClass('is-invalid');
                            }
                            if (this.param.abnormal != "") {
                                $('#abnormal').removeClass('is-invalid');
                            }
                            if (this.param.uom != "") {
                                $('.uom').removeClass('is-invalid');
                            }
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        }

                        if (this.param.showOn != '') {
                            $('.showOn').removeClass('is-invalid');
                        }

                        //end remove invalid class

                        //add invalid class
                        if (this.param.parameterName == '') {
                            $('.parameter').addClass('is-invalid');
                        }
                        if (this.param.inputType == '') {
                            $('.type').addClass('is-invalid');
                        }
                        if (this.param.inputType == 'input') {
                            if (this.param.min == null) {
                                $('.min').addClass('is-invalid');
                            }
                            if (this.param.max == null) {
                                $('.max').addClass('is-invalid');
                            }
                            if (this.param.uom == "" || this.param.uom == null) {
                                $('.uom').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal == "") {
                                $('#normal').addClass('is-invalid');
                            }
                            if (this.param.abnormal == "") {
                                $('#abnormal').addClass('is-invalid');
                            }
                            if (this.param.uom == "") {
                                $('.uom').addClass('is-invalid');
                            }
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        }

                        if (this.param.showOn == '') {
                            $('.showOn').addClass('is-invalid');
                        }

                        // index = this.param.i;
                        let keyGP = this.param.keyGP;
                        let key = this.param.key;

                        this.param.keyGP = this.param.parameterName.includes("#") ? this.param.parameterName.split("#")[0] + "#" : this.param.parameterName;

                        this.param.photo = this.paramPhoto;
                        this.param.deletePhoto = this.deletePhoto;
                        let compare = "";
                        let edited = _.omit(this.param, ['keyGP', 'key', 'status', 'i']);

                        this.compareParameter.forEach((el, i) => {
                            if (el.parameterId === this.param.parameterId) {
                                compare = _.omit(el, ['assetId', 'createdAt', 'updatedAt', 'deletedAt']);
                            }
                        });

                        let check = _.isEqual(compare, edited);
                        let lengthEdited = this.editedParameter.length;
                        if (check) {
                            for (let i = 0; i < lengthEdited; i++) {
                                let isEditedParam = this.editedParameter[i].parameterId
                                if (isEditedParam == this.param.parameterId) {
                                    this.param.status = 'old';
                                    return this.editedParameter.splice(i, 1);
                                }
                            }
                            this.param.status = 'old';
                        } else {
                            setTimeout(() => {
                                for (let i = 0; i < lengthEdited; i++) {
                                    let isEditedParam = this.editedParameter[i].parameterId
                                    if (isEditedParam == this.param.parameterId) {
                                        return this.editedParameter.splice(i, 1);
                                    }
                                }
                            }, 2000);
                            this.param.status = 'updated';
                            this.editedParameter.push({
                                ...toRaw(this.param)
                            });
                        }

                        this.allParameter[0].forEach((el, i) => {
                            if (el.parameterId === this.param.parameterId) {
                                this.allParameter[0][i] = {
                                    ...toRaw(this.param)
                                }
                            }
                        });
                        this.parameterGroupData = _.groupBy(this.allParameter[0], function(val) {
                            return val.parameterName.includes("#") ? val.parameterName.split("#")[0] + "#" : val.parameterName;
                        });

                        this.myModal.hide();
                        this.deletePhoto = ref(false);

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            iconColor: 'white',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'colored-toast'
                            },
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: 'Successfully Modify Parameter'
                        })
                    }
                }

                function removeExistParameter(keyGP, key, parameterId) {
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
                            var lengthParams = v.parameter.length;
                            v.parameter.forEach((val, i) => {
                                if (val.parameterId == parameterId) {
                                    v.parameter.splice(i, 1);
                                }
                            })
                            v.allParameter[0].forEach((value, k) => {
                                if (value.parameterId == parameterId) {
                                    v.allParameter[0].splice(k, 1);
                                }
                            })
                            v.deletedParameter.push(parameterId);
                            v.param.sortId = this.param.sortId - 1;

                            v.parameterGroupData = _.groupBy(v.allParameter[0], function(v) {
                                return v.parameterName.includes("#") ? v.parameterName.split("#")[0] + "#" : v.parameterName;
                            });
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                iconColor: 'white',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                customClass: {
                                    popup: 'colored-toast'
                                },
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })
                            Toast.fire({
                                icon: 'success',
                                title: 'Successfully Deleted Parameter'
                            })
                        }
                    })

                };

                function addParameter() {
                    if (!(this.checkLimitParameter())) {
                        return swal.fire({
                            icon: 'info',
                            title: "Your parameters has reached the limit"
                        })
                    }
                    this.paramPhoto = ref("");
                    FilePond.destroy(document.querySelector('#photoParam'));
                    FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType, FilePondPluginFilePoster);
                    filepondParam = {
                        acceptedFileTypes: ['image/png', 'image/jpeg'],
                        allowFilePoster: true,
                        allowImagePreview: true,
                        imagePreviewMaxHeight: 200,
                        allowImageCrop: true,
                        allowMultiple: false,
                        credits: false,
                        styleLoadIndicatorPosition: 'center bottom',
                        styleProgressIndicatorPosition: 'right bottom',
                        styleButtonRemoveItemPosition: 'left bottom',
                        styleButtonProcessItemPosition: 'right bottom',
                    };

                    let pond = FilePond.create(document.querySelector('#photoParam'), filepondParam);
                    pond.on('addfile', (error, file) => {
                        v.paramPhoto = file.file
                    })
                    pond.on('removefile', (error, file) => {
                        v.paramPhoto = ref("");
                    })

                    this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                    this.myModal.show();

                    $('#normal').find('option').remove();
                    $('#abnormal').find('option').remove();

                    this.param.parameterId = uuidv4();
                    this.param.sortId = $('#tableParameter tbody tr').length + 1,
                        this.param.parameterName = '';
                    this.param.photo = '';
                    this.param.photo1 = '';
                    this.param.photo2 = '';
                    this.param.photo3 = '';
                    this.param.description = '';
                    this.param.uom = '';
                    this.param.min = null;
                    this.param.max = null;
                    this.param.normal = '';
                    this.param.abnormal = '';
                    this.param.option = '';
                    this.param.inputType = '';
                    this.param.showOn = '';
                    this.param.i = null;
                    this.param.deletePhoto = false;

                    $('#addParameterModal').modal('hide');
                    $('#previewImg').hide();

                    $('.type').val('').trigger("change");
                    $('#showOn').val('').trigger('change');
                    $('#normal').val('').trigger('change');
                    $('#abnormal').val('').trigger('change');

                    $('#imgParam').remove();
                    $('.optNormal').remove();
                    $('.optAbnormal').remove();

                    $('.parameter').removeClass('is-invalid');
                    $('.type').removeClass('is-invalid');
                    $('.showOn').removeClass('is-invalid');
                };

                function addTempParameter() {
                    if (!(this.checkLimitParameter())) {
                        return swal.fire({
                            icon: 'info',
                            title: "Your parameters has reached the limit"
                        })
                    }
                    let min = (this.param.min == null) && (this.param.inputType == 'input') ? true : false;
                    let max = (this.param.max == null) && (this.param.inputType == 'input') ? true : false;
                    let uom = ((this.param.uom == "") && ((this.param.inputType == 'input') || (this.param.inputType == 'select'))) ? true : false;
                    let normal = ((this.param.normal == "") && (this.param.inputType == 'select')) ? true : false;
                    let abnormal = ((this.param.abnormal == "") && (this.param.inputType == 'select')) ? true : false;
                    let option = ((this.param.option == "") && ((this.param.inputType == 'select') || this.param.inputType == 'checkbox')) ? true : false;
                    if (this.param.parameterName == '' || this.param.inputType == '' || this.param.showOn == '' || min == true || max == true || uom == true || normal == true || abnormal == true || option == true) {
                        swal.fire({
                            title: 'Invalid Value!',
                            icon: 'error'
                        })

                        if (this.param.parameterName != '') {
                            $('.parameter').removeClass('is-invalid');
                        }
                        if (this.param.inputType != '') {
                            $('.type').removeClass('is-invalid');
                        }

                        //remove invalid class
                        // input type
                        if (this.param.inputType == 'input') {
                            if (this.param.min != null) {
                                $('.min').removeClass('is-invalid');
                            }
                            if (this.param.max != null) {
                                $('.max').removeClass('is-invalid');
                            }
                            if (this.param.uom != "" || this.param.uom != null) {
                                $('.uom').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal != "") {
                                $('#normal').removeClass('is-invalid');
                            }
                            if (this.param.abnormal != "") {
                                $('#abnormal').removeClass('is-invalid');
                            }
                            if (this.param.uom != "") {
                                $('.uom').removeClass('is-invalid');
                            }
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        }

                        if (this.param.showOn != '') {
                            $('.showOn').removeClass('is-invalid');
                        }

                        //end remove invalid class

                        //add invalid class
                        if (this.param.parameterName == '') {
                            $('.parameter').addClass('is-invalid');
                        }
                        if (this.param.inputType == '') {
                            $('.type').addClass('is-invalid');
                        }
                        if (this.param.inputType == 'input') {
                            if (this.param.min == null) {
                                $('.min').addClass('is-invalid');
                            }
                            if (this.param.max == null) {
                                $('.max').addClass('is-invalid');
                            }
                            if (this.param.uom == "" || this.param.uom == null) {
                                $('.uom').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal == "") {
                                $('#normal').addClass('is-invalid');
                            }
                            if (this.param.abnormal == "") {
                                $('#abnormal').addClass('is-invalid');
                            }
                            if (this.param.uom == "") {
                                $('.uom').addClass('is-invalid');
                            }
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        }

                        if (this.param.showOn == '') {
                            $('.showOn').addClass('is-invalid');
                        }
                    } else {
                        if (this.param.parameterName != '') {
                            $('.parameter').removeClass('is-invalid');
                        }
                        if (this.param.inputType != '') {
                            $('.type').removeClass('is-invalid');
                        }

                        //remove invalid class
                        // input type
                        if (this.param.inputType == 'input') {
                            if (this.param.min != null) {
                                $('.min').removeClass('is-invalid');
                            }
                            if (this.param.max != null) {
                                $('.max').removeClass('is-invalid');
                            }
                            if (this.param.uom != "" || this.param.uom != null) {
                                $('.uom').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal != "") {
                                $('#normal').removeClass('is-invalid');
                            }
                            if (this.param.abnormal != "") {
                                $('#abnormal').removeClass('is-invalid');
                            }
                            if (this.param.uom != "") {
                                $('.uom').removeClass('is-invalid');
                            }
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        }

                        if (this.param.showOn != '') {
                            $('.showOn').removeClass('is-invalid');
                        }

                        //end remove invalid class

                        //add invalid class
                        if (this.param.parameterName == '') {
                            $('.parameter').addClass('is-invalid');
                        }
                        if (this.param.inputType == '') {
                            $('.type').addClass('is-invalid');
                        }
                        if (this.param.inputType == 'input') {
                            if (this.param.min == null) {
                                $('.min').addClass('is-invalid');
                            }
                            if (this.param.max == null) {
                                $('.max').addClass('is-invalid');
                            }
                            if (this.param.uom == "" || this.param.uom == null) {
                                $('.uom').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal == "") {
                                $('#normal').addClass('is-invalid');
                            }
                            if (this.param.abnormal == "") {
                                $('#abnormal').addClass('is-invalid');
                            }
                            if (this.param.uom == "") {
                                $('.uom').addClass('is-invalid');
                            }
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        }

                        if (this.param.showOn == '') {
                            $('.showOn').addClass('is-invalid');
                        }

                        this.param.photo = this.paramPhoto;
                        this.params.push(this.param);
                        this.param.status = 'New'
                        this.allParameter[0].push(this.param);

                        this.param = reactive({
                            parameterId: uuidv4(),
                            sortId: $('#tableParameter tbody tr').length + 2,
                            parameterName: '',
                            photo: '',
                            photo1: '',
                            photo2: '',
                            photo3: '',
                            description: '',
                            uom: '',
                            min: null,
                            max: null,
                            normal: '',
                            abnormal: '',
                            option: '',
                            inputType: '',
                            showOn: '',
                        })
                        this.parameterGroupData = _.groupBy(this.allParameter[0], function(val) {
                            return val.parameterName.includes("#") ? val.parameterName.split("#")[0] + "#" : val.parameterName;
                        });

                        $('#photoParam').filepond('removeFiles');
                        $('.type').val('').trigger("change");
                        $('#showOn').val('').trigger('change');

                        this.paramPhoto = ref("");

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            iconColor: 'white',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'colored-toast'
                            },
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: 'Successfully Added Parameter'
                        })
                        $('#parameterName').focus();
                    }
                };

                function editTempParameter(keyGP, key, parameterId) {
                    this.checkModalExist = false;
                    this.paramPhoto = ref("");
                    this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                    this.myModal.show();
                    let data = "";
                    this.allParameter[0].forEach((val, i) => {
                        if (val.parameterId == parameterId) {
                            data = {
                                ...toRaw(this.allParameter[0][i])
                            };
                        }
                    })

                    this.paramPhoto = ref("");
                    $('#photoParam').filepond('removeFiles');
                    FilePond.destroy(document.querySelector('#photoParam'));

                    if (data.photo != "") {
                        FilePond.destroy(document.querySelector('#photoParam'));
                        let url = URL.createObjectURL(data.photo)
                        const inputElement = document.querySelector('#photoParam');
                        var photoEdit = {
                            acceptedFileTypes: ['image/png', 'image/jpeg'],
                            allowFilePoster: true,
                            allowImagePreview: true,
                            imagePreviewMaxHeight: 200,
                            allowImageCrop: true,
                            allowMultiple: false,
                            credits: false,
                            styleLoadIndicatorPosition: 'center bottom',
                            styleProgressIndicatorPosition: 'right bottom',
                            styleButtonRemoveItemPosition: 'left bottom',
                            styleButtonProcessItemPosition: 'right bottom',
                            files: [{
                                source: url,
                                options: {
                                    type: 'local',
                                    file: data.photo,
                                    metadata: {
                                        poster: ''
                                    }
                                }
                            }]
                        };
                        let pond = FilePond.create(inputElement, photoEdit);
                        pond.on('addfile', (error, file) => {
                            v.paramPhoto = file.file;
                        })
                        pond.on('removefile', (error, file) => {
                            v.paramPhoto = ref("");
                        })
                    } else {
                        var filepondParam = {
                            acceptedFileTypes: ['image/png', 'image/jpeg'],
                            allowFilePoster: true,
                            allowImagePreview: true,
                            imagePreviewMaxHeight: 200,
                            allowImageCrop: true,
                            allowMultiple: false,
                            credits: false,
                            styleLoadIndicatorPosition: 'center bottom',
                            styleProgressIndicatorPosition: 'right bottom',
                            styleButtonRemoveItemPosition: 'left bottom',
                            styleButtonProcessItemPosition: 'right bottom',
                        };

                        let pond = FilePond.create(document.querySelector('#photoParam'), filepondParam);
                        pond.on('addfile', (error, file) => {
                            v.paramPhoto = file.file;
                        })
                        pond.on('removefile', (error, file) => {
                            v.paramPhoto = ref("");
                        })
                    }

                    this.param.parameterId = data.parameterId;
                    this.param.sortId = data.sortId;
                    this.param.parameterName = data.parameterName;
                    this.param.photo = data.photo;
                    this.param.photo1 = data.photo1;
                    this.param.photo2 = data.photo2;
                    this.param.photo3 = data.photo3;
                    this.param.description = data.description;
                    this.param.uom = data.uom;
                    this.param.min = data.min;
                    this.param.max = data.max;
                    this.param.normal = data.normal;
                    this.param.abnormal = data.abnormal;
                    this.param.option = data.option;
                    this.param.inputType = data.inputType;
                    this.param.showOn = data.showOn;
                    // this.param.i = index;

                    if (v.param.inputType != '') {
                        $('.type').val(v.param.inputType).trigger("change");
                    }
                    if (this.param.showOn != '') {
                        $('#showOn').val(this.param.showOn.split(",")).trigger('change');
                    }

                    let normal = v.param.normal.split(",");
                    let abnormal = v.param.abnormal.split(",");

                    $('#normal').find('option').remove();
                    $('#abnormal').find('option').remove();

                    if (normal.length) {
                        // $('#normal').val(normal).trigger("change");
                        for (let i = 0; i < normal.length; i++) {
                            $('#normal').append(`<option class="normal` + normal[i] + `" value="` + normal[i] + `" selected>` + normal[i] + `</option>`);
                        }
                    }
                    if (abnormal.length) {
                        // $('#abnormal').val(abnormal).trigger('change');
                        for (let i = 0; i < abnormal.length; i++) {
                            $('#abnormal').append(`<option class="abnormal` + abnormal[i] + `" value="` + abnormal[i] + `" selected>` + abnormal[i] + `</option>`);
                        }
                    }
                };

                function updateTempParameter() {
                    let min = (this.param.min == null) && (this.param.inputType == 'input') ? true : false;
                    let max = (this.param.max == null) && (this.param.inputType == 'input') ? true : false;
                    let uom = ((this.param.uom == "") && ((this.param.inputType == 'input') || (this.param.inputType == 'select'))) ? true : false;
                    let normal = ((this.param.normal == "") && (this.param.inputType == 'select')) ? true : false;
                    let abnormal = ((this.param.abnormal == "") && (this.param.inputType == 'select')) ? true : false;
                    let option = ((this.param.option == "") && ((this.param.inputType == 'select') || this.param.inputType == 'checkbox')) ? true : false;
                    if (this.param.parameterName == '' || this.param.inputType == '' || this.param.showOn == '' || min == true || max == true || uom == true || normal == true || abnormal == true || option == true) {
                        const swalWithBootstrapButtons = swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire({
                            title: 'Failed!',
                            text: "Invalid value!",
                            icon: 'error'
                        })

                        if (this.param.parameterName != '') {
                            $('.parameter').removeClass('is-invalid');
                        }
                        if (this.param.inputType != '') {
                            $('.type').removeClass('is-invalid');
                        }

                        //remove invalid class
                        // input type
                        if (this.param.inputType == 'input') {
                            if (this.param.min != null) {
                                $('.min').removeClass('is-invalid');
                            }
                            if (this.param.max != null) {
                                $('.max').removeClass('is-invalid');
                            }
                            if (this.param.uom != "" || this.param.uom != null) {
                                $('.uom').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal != "") {
                                $('#normal').removeClass('is-invalid');
                            }
                            if (this.param.abnormal != "") {
                                $('#abnormal').removeClass('is-invalid');
                            }
                            if (this.param.uom != "") {
                                $('.uom').removeClass('is-invalid');
                            }
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        }

                        if (this.param.showOn != '') {
                            $('.showOn').removeClass('is-invalid');
                        }

                        //end remove invalid class

                        //add invalid class
                        if (this.param.parameterName == '') {
                            $('.parameter').addClass('is-invalid');
                        }
                        if (this.param.inputType == '') {
                            $('.type').addClass('is-invalid');
                        }
                        if (this.param.inputType == 'input') {
                            if (this.param.min == null) {
                                $('.min').addClass('is-invalid');
                            }
                            if (this.param.max == null) {
                                $('.max').addClass('is-invalid');
                            }
                            if (this.param.uom == "" || this.param.uom == null) {
                                $('.uom').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal == "") {
                                $('#normal').addClass('is-invalid');
                            }
                            if (this.param.abnormal == "") {
                                $('#abnormal').addClass('is-invalid');
                            }
                            if (this.param.uom == "") {
                                $('.uom').addClass('is-invalid');
                            }
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        }

                        if (this.param.showOn == '') {
                            $('.showOn').addClass('is-invalid');
                        }
                    } else {
                        if (this.param.parameterName != '') {
                            $('.parameter').removeClass('is-invalid');
                        }
                        if (this.param.inputType != '') {
                            $('.type').removeClass('is-invalid');
                        }

                        //remove invalid class
                        // input type
                        if (this.param.inputType == 'input') {
                            if (this.param.min != null) {
                                $('.min').removeClass('is-invalid');
                            }
                            if (this.param.max != null) {
                                $('.max').removeClass('is-invalid');
                            }
                            if (this.param.uom != "" || this.param.uom != null) {
                                $('.uom').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal != "") {
                                $('#normal').removeClass('is-invalid');
                            }
                            if (this.param.abnormal != "") {
                                $('#abnormal').removeClass('is-invalid');
                            }
                            if (this.param.uom != "") {
                                $('.uom').removeClass('is-invalid');
                            }
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option != "") {
                                $('#option').removeClass('is-invalid');
                            }
                        }

                        if (this.param.showOn != '') {
                            $('.showOn').removeClass('is-invalid');
                        }

                        //end remove invalid class

                        //add invalid class
                        if (this.param.parameterName == '') {
                            $('.parameter').addClass('is-invalid');
                        }
                        if (this.param.inputType == '') {
                            $('.type').addClass('is-invalid');
                        }
                        if (this.param.inputType == 'input') {
                            if (this.param.min == null) {
                                $('.min').addClass('is-invalid');
                            }
                            if (this.param.max == null) {
                                $('.max').addClass('is-invalid');
                            }
                            if (this.param.uom == "" || this.param.uom == null) {
                                $('.uom').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'select') {
                            if (this.param.normal == "") {
                                $('#normal').addClass('is-invalid');
                            }
                            if (this.param.abnormal == "") {
                                $('#abnormal').addClass('is-invalid');
                            }
                            if (this.param.uom == "") {
                                $('.uom').addClass('is-invalid');
                            }
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        } else if (this.param.inputType == 'checkbox') {
                            if (this.param.option == "") {
                                $('#option').addClass('is-invalid');
                            }
                        }

                        if (this.param.showOn == '') {
                            $('.showOn').addClass('is-invalid');
                        }

                        this.param.status = 'New';

                        this.params.forEach((val, i) => {
                            if (val.parameterId == this.param.parameterId) {
                                this.params[i] = {
                                    ...toRaw(v.param)
                                }
                            }
                        })

                        this.allParameter[0].forEach((val, i) => {
                            if (val.parameterId == this.param.parameterId) {
                                this.allParameter[0][i] = {
                                    ...toRaw(this.param)
                                }
                            }
                        })

                        this.parameterGroupData = _.groupBy(this.allParameter[0], function(val) {
                            return val.parameterName.includes("#") ? val.parameterName.split("#")[0] + "#" : val.parameterName;
                        });

                        this.myModal.hide();
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            iconColor: 'white',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'colored-toast'
                            },
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: 'Successfully Modify Parameter'
                        })
                    }
                };

                function removeTempParameter(keyGP, key, parameterId) {
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
                            var lengthParams = this.params.length;
                            for (let i = 0; i < lengthParams; i++) {
                                // console.log(v.params[i].sortId);
                                v.params.forEach((val, k) => {
                                    if (v.params[i].sortId > val.sortId) {
                                        v.params[i].sortId = v.params[i].sortId - 1;
                                    } else {
                                        v.params[i].sortId = v.params[i].sortId;
                                    }
                                    v.params.splice(k, 1);
                                    v.param.sortId = this.param.sortId - 1;
                                    v.allParameter[0].forEach((value, b) => {
                                        if (val.parameterId == value.parameterId) {
                                            v.allParameter[0].splice(b, 1);
                                        }
                                    })

                                    v.parameterGroupData = _.groupBy(v.allParameter[0], function(v) {
                                        return v.parameterName.includes("#") ? v.parameterName.split("#")[0] + "#" : v.parameterName;
                                    });
                                })
                            }

                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                iconColor: 'white',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                customClass: {
                                    popup: 'colored-toast'
                                },
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })
                            Toast.fire({
                                icon: 'success',
                                title: 'Successfully Deleted Parameter'
                            })
                        }
                    })

                };

                function btnSaveSetting() {
                    this.submited = ref(true);
                    let checkRfid = this.assetTagging.find(function(key, index) {
                        if (key.assetTaggingtype == 'rfid') return true;
                    })
                    let checkCoordinat = this.assetTagging.find(function(key, index) {
                        if (key.assetTaggingtype == 'coordinat') return true;
                    })
                    let checkValueTagging = (checkRfid.assetTaggingValue == "" && checkCoordinat.assetTaggingValue == "" ? true : false);
                    if (this.assetData.assetName == "" || this.assetData.assetNumber == "" || this.assetData.assetStatusName == "" || this.assetData.tagId == "" || this.assetData.tagLocationId == "" || (this.assetData.schType == "Daily" && this.assetData.schFrequency == '') || this.statusName == '' || checkValueTagging || $('#tableParameter tbody tr').length < 1) {
                        this.submited = ref(false);
                        swal.fire({
                            title: 'Failed!',
                            text: "Invalid value!",
                            icon: 'error'
                        }).then((result) => {
                            let findIsInvalid = $('body').find('.is-invalid');
                            if (result.isConfirmed) {
                                $('html, body').animate({
                                    scrollTop: $(findIsInvalid[0]).offset().top
                                }, 1000);
                            }
                        })

                        if (this.assetData.assetName != '' && $('#assetName').hasClass('is-invalid')) {
                            $('#assetName').removeClass('is-invalid');
                        }
                        if (this.assetData.assetNumber != '' && $('#assetNumber').hasClass('is-invalid')) {
                            $('#assetNumber').removeClass('is-invalid');
                        }
                        if (this.assetData.schType != '' && $('#schType').hasClass('is-invalid') && this.assetData.schManual == '1') {
                            $('#schType').removeClass('is-invalid');
                        }
                        if (this.assetData.schFrequency != '' && $('#schFreq').hasClass('is-invalid')) {
                            $('#schFreq').removeClass('is-invalid');
                            $('#schFreq').removeClass('invalid');
                        }
                        if (this.assetData.schWeekDays != '' && $('#schWeekly').hasClass('is-invalid')) {
                            $('#schWeekly').removeClass('is-invalid');
                            $('#schWeekly').removeClass('invalid');
                        }
                        if (this.assetData.schDays != '' && $('#monthlyDays').hasClass('is-invalid')) {
                            $('#monthlyDays').removeClass('is-invalid');
                        }
                        if (this.assetData.schWeekDays != '' && $('#monthlyOnDays').hasClass('is-invalid')) {
                            $('#monthlyOnDays').removeClass('is-invalid');
                        }
                        if (this.assetData.schWeeks != '' && $('#monthlyOn').hasClass('is-invalid')) {
                            $('#monthlyOn').removeClass('is-invalid');
                        }
                        if (this.assetData.tagId != "" && $('#tag').hasClass('is-invalid')) {
                            $('#tag').removeClass('is-invalid')
                        }
                        if (this.assetData.tagLocationId != "" && $('#location').hasClass('is-invalid')) {
                            $('#location').removeClass('is-invalid')
                        }
                        if (this.assetData.assetStatusName != '' && $('#operation').hasClass('is-invalid')) {
                            $('#operation').removeClass('is-invalid');
                            $('#operation').removeClass('invalid');
                        }

                        //tagging

                        if (checkRfid.assetTaggingValue != '' && $('#valRfid').hasClass('is-invalid') && checkRfid.assetTaggingtype == 'rfid') {
                            $('#valRfid').removeClass('is-invalid');
                        }
                        if (checkCoordinat.assetTaggingValue != '' && $('#valCoordinate').hasClass('is-invalid') && checkCoordinat.assetTaggingtype == 'coordinat') {
                            $('#valCoordinate').removeClass('is-invalid');
                        }

                        if ($('#tableParameter tbody tr').length >= 1) {
                            $('#cardParameter').removeClass('card-border');
                            $('#cardParameter').removeClass('is-invalid');
                        }

                        // add invalid

                        if (this.assetData.assetName == '') {
                            $('#assetName').addClass('is-invalid');
                        }
                        if (this.assetData.assetNumber == '') {
                            $('#assetNumber').addClass('is-invalid');
                        }
                        if (this.assetData.tagId == "") {
                            $('#tag').addClass('is-invalid')
                        }
                        if (this.assetData.tagLocationId == "") {
                            $('#location').addClass('is-invalid')
                        }
                        if (this.assetData.schType == '' && this.assetData.schManual == '0') {
                            $('#schType').addClass('is-invalid');
                        } else if (this.assetData.schType == "Daily") {
                            if (this.assetData.schFrequency == '') {
                                $('#schFreq').addClass('is-invalid');
                                $('#schFreq').addClass('invalid');
                            }
                        } else if (this.assetData.schType == 'Weekly') {
                            if (this.assetData.schWeekDays == '') {
                                $('#schWeekly').addClass('is-invalid');
                                $('#schWeekly').addClass('invalid');
                            }
                        } else if (this.assetData.schType == 'Monthly') {
                            if (v.onDays == 'days') {
                                if (v.assetData.schDays == '') {
                                    $('#monthlyDays').addClass('is-invalid');
                                }
                            } else if (v.onDays == 'on') {
                                if (v.assetData.schWeeks == '' || v.assetData.schMonthlyWeekDays == '') {
                                    if (v.assetData.schWeeks == '') {
                                        $('#monthlyOn').addClass('is-invalid');
                                    } else {
                                        $('#monthlyOn').removeClass('is-invalid');
                                    }
                                    if (v.assetData.schMonthlyWeekDays == '') {
                                        $('#monthlyOnDays').addClass('is-invalid');
                                    } else {
                                        $('#monthlyOnDays').removeClass('is-invalid');
                                    }
                                }
                            }
                        }
                        if (this.assetData.assetStatusName == '') {
                            $('#operation').addClass('is-invalid');
                            $('#operation').addClass('invalid');
                        }

                        // tagging
                        if (checkRfid.assetTaggingValue == '' && checkRfid.assetTaggingtype == 'rfid') {
                            $('#valRfid').addClass('is-invalid');
                        }
                        if (checkCoordinat.assetTaggingValue == '' && checkCoordinat.assetTaggingtype == 'coordinat') {
                            $('#valCoordinate').addClass('is-invalid');
                        }
                        //end tagging
                        if ($('#tableParameter tbody tr').length < 1) {
                            $('#cardParameter').addClass('card-border');
                            $('#cardParameter').addClass('is-invalid');
                        }
                    } else {
                        let checkRfid = this.assetTagging.find(function(key, index) {
                            if (key.assetTaggingtype == 'rfid') return true;
                        })
                        let checkCoordinat = this.assetTagging.find(function(key, index) {
                            if (key.assetTaggingtype == 'coordinat') return true;
                        })
                        if (this.assetData.assetName != '' && $('#assetName').hasClass('is-invalid')) {
                            $('#assetName').removeClass('is-invalid');
                        }
                        if (this.assetData.assetNumber != '' && $('#assetNumber').hasClass('is-invalid')) {
                            $('#assetNumber').removeClass('is-invalid');
                        }
                        if (this.assetData.assetStatusName != '' && $('#operation').hasClass('is-invalid')) {
                            $('#operation').removeClass('is-invalid')
                            $('#operation').removeClass('invalid')
                        }
                        if (this.assetData.tagId != "" && $('#tag').hasClass('is-invalid')) {
                            $('#tag').removeClass('is-invalid')
                        }
                        if (this.assetData.tagLocationId != "" && $('#location').hasClass('is-invalid')) {
                            $('#location').removeClass('is-invalid')
                        }

                        // tagging
                        if (checkRfid.assetTaggingValue != '' && $('#valRfid').hasClass('is-invalid')) {
                            $('#valRfid').removeClass('is-invalid');
                        }
                        if (checkCoordinat.assetTaggingValue != '' && $('#valCoordinate').hasClass('is-invalid')) {
                            $('#valCoordinate').removeClass('is-invalid');
                        }
                        if ($('#tableParameter tbody tr').length >= 1) {
                            $('#cardParameter').removeClass('card-border');
                            $('#cardParameter').removeClass('is-invalid');

                        }
                        if (this.assetData.schType != '' && $('#schType').hasClass('is-invalid') && this.assetData.schManual == '1') {
                            $('#schType').removeClass('is-invalid');
                        }
                        if (this.assetData.schFrequency != '' && $('#schFreq').hasClass('is-invalid')) {
                            $('#schFreq').removeClass('is-invalid');
                            $('#schFreq').removeClass('invalid');
                        }
                        if (this.assetData.schWeekDays != '' && $('#schWeekly').hasClass('is-invalid')) {
                            $('#schWeekly').removeClass('is-invalid');
                            $('#schWeekly').removeClass('invalid');
                        }
                        if (this.assetData.schDays != '' && $('#monthlyDays').hasClass('is-invalid')) {
                            $('#monthlyDays').removeClass('is-invalid');
                        }
                        if (this.assetData.schWeekDays != '' && $('#monthlyOnDays').hasClass('is-invalid')) {
                            $('#monthlyOnDays').removeClass('is-invalid');
                        }
                        if (this.setSch == 'Manual') {
                            this.assetData.schType = '';
                            this.assetData.schWeeks = '';
                            this.assetData.schWeekDays = '';
                            this.assetData.schDays = '';
                            this.assetData.schFrequency = '';
                        } else if (this.setSch == 'Automatic') {
                            if (this.assetData.schType == '') {
                                $('#schType').addClass('is-invalid');
                                const swalWithBootstrapButtons = swal.mixin({
                                    customClass: {
                                        confirmButton: 'btn btn-danger',
                                    },
                                    buttonsStyling: false
                                })
                                swalWithBootstrapButtons.fire({
                                    title: 'Failed!',
                                    text: "Invalid value!",
                                    icon: 'error'
                                })
                                return;
                            } else if (this.assetData.schType == 'Daily') {
                                if (v.assetData.schFrequency == "" && v.assetData.schFrequency == null) {
                                    $('#schFreq').addClass('is-invalid');
                                    $('#schFreq').addClass('invalid');
                                    const swalWithBootstrapButtons = swal.mixin({
                                        customClass: {
                                            confirmButton: 'btn btn-danger',
                                        },
                                        buttonsStyling: false
                                    })
                                    swalWithBootstrapButtons.fire({
                                        title: 'Failed!',
                                        text: "Invalid value!",
                                        icon: 'error'
                                    })
                                    return;
                                }
                            } else if (this.assetData.schType == 'Weekly') {
                                if (this.assetData.schWeekDays == '') {
                                    $('#schWeekly').addClass('is-invalid');
                                    $('#schWeekly').addClass('invalid');
                                    const swalWithBootstrapButtons = swal.mixin({
                                        customClass: {
                                            confirmButton: 'btn btn-danger',
                                        },
                                        buttonsStyling: false
                                    })
                                    swalWithBootstrapButtons.fire({
                                        title: 'Failed!',
                                        text: "Invalid value!",
                                        icon: 'error'
                                    })
                                    return;
                                }
                            } else if (this.assetData.schType == 'Monthly') {
                                if (v.onDays == 'days') {
                                    if (v.assetData.schDays == '') {
                                        $('#monthlyDays').addClass('is-invalid');
                                        const swalWithBootstrapButtons = swal.mixin({
                                            customClass: {
                                                confirmButton: 'btn btn-danger',
                                            },
                                            buttonsStyling: false
                                        })
                                        swalWithBootstrapButtons.fire({
                                            title: 'Failed!',
                                            text: "Invalid value!",
                                            icon: 'error'
                                        })
                                        return;
                                    }
                                } else if (v.onDays == 'on') {
                                    if (v.assetData.schWeeks == '' || v.assetData.schMonthlyWeekDays == '') {
                                        const swalWithBootstrapButtons = swal.mixin({
                                            customClass: {
                                                confirmButton: 'btn btn-danger',
                                            },
                                            buttonsStyling: false
                                        })
                                        swalWithBootstrapButtons.fire({
                                            title: 'Failed!',
                                            text: "Invalid value!",
                                            icon: 'error'
                                        })
                                        if (v.assetData.schWeeks == '') {
                                            $('#monthlyOn').addClass('is-invalid');
                                        } else {
                                            $('#monthlyOn').removeClass('is-invalid');
                                        }
                                        if (v.assetData.schMonthlyWeekDays == '') {
                                            $('#monthlyOnDays').addClass('is-invalid');
                                        } else {
                                            $('#monthlyOnDays').removeClass('is-invalid');
                                        }
                                        return;
                                    }
                                }
                            }
                        }
                        let formdata = new FormData();
                        // asset
                        formdata.append('assetId', this.assetData.assetId);
                        formdata.append('assetName', this.assetData.assetName);
                        formdata.append('assetNumber', this.assetData.assetNumber);
                        formdata.append('deleteAssetPhoto', this.deleteAssetPhoto);
                        formdata.append('assetPhoto', this.assetPhoto);
                        formdata.append('photo', this.assetData.photo);
                        formdata.append('latitude', this.assetData.latitude);
                        formdata.append('longitude', this.assetData.longitude);
                        formdata.append('schManual', this.assetData.schManual);
                        formdata.append('schType', this.assetData.schType);
                        if (v.assetData.schType == 'Daily') {
                            formdata.append('schFrequency', this.assetData.schFrequency);
                        } else {
                            formdata.append('schFrequency', "");
                        }
                        formdata.append('schFrequency', this.assetData.schFrequency);
                        if (v.assetData.schType == "Weekly" && v.assetData.schWeekDays != "") {
                            formdata.append('schWeekDays', this.assetData.schWeekDays);
                        }
                        if (v.assetData.schType == "Monthly" && v.assetData.schMonthlyWeekDays != "" && v.onDays == "on") {
                            formdata.append('schWeekDays', this.assetData.schMonthlyWeekDays);
                        }
                        if (v.assetData.schType == "Monthly" && v.onDays == "days") {
                            formdata.append('schWeekDays', this.assetData.schWeekDays);
                        }
                        if (v.assetData.schType == "Daily") {
                            formdata.append('schWeekDays', this.assetData.schWeekDays);
                        }
                        if (v.assetData.schManual == '1' || v.assetData.schManual == 1) {
                            formdata.append('schWeekDays', this.assetData.schWeekDays);
                        }
                        formdata.append('schWeeks', this.assetData.schWeeks);
                        formdata.append('schDays', this.assetData.schDays);

                        if (this.moreDetailAsset) {
                            formdata.append('assetDesc', JSON.stringify(descJson));
                        } else {
                            formdata.append('assetDesc', v.assetData.description);
                        }

                        // tag location
                        formdata.append('tagId', this.assetData.tagId);
                        formdata.append('locationId', this.assetData.tagLocationId);
                        // status
                        formdata.append('assetStatusId', this.assetData.assetStatusId);
                        formdata.append('assetStatusName', this.assetData.assetStatusName);
                        // tagging
                        if (this.assetTagging.length) {
                            let tagging = this.assetTagging;
                            tagging.forEach((item, i) => {
                                formdata.append('assetTagging[]', JSON.stringify(item));
                            })
                        }

                        // parameter
                        if (this.params.length > 0) {
                            let param = this.params;
                            param.forEach((item, k) => {
                                formdata.append('parameter[]', JSON.stringify(item));
                                // formdata.append('photo[]', item['photo']);
                                formdata.append('photo' + item['parameterId'], this.params[k]['photo']);
                            });
                        } else {
                            formdata.append('parameter[]', this.params);
                        }

                        //deleted parameter
                        if (this.deletedParameter.length) {
                            let deleted = v.deletedParameter.join(",");
                            formdata.append('deletedParameter', deleted);
                        } else {
                            formdata.append('deletedParameter', "");
                        }

                        //edited parameter
                        if (this.editedParameter.length) {
                            let editedParam = this.editedParameter;
                            editedParam.forEach((item, k) => {
                                formdata.append('editedParameter[]', JSON.stringify(item))
                                formdata.append('photo' + item['parameterId'], v.editedParameter[k]['photo']);
                            })
                        } else {
                            formdata.append('editedParameter[]', "");
                        }

                        // new tags and tag locations
                        if (this.tags.length > 0) {
                            let tag = v.tags;
                            tag.forEach((item, i) => {
                                formdata.append('tag[]', JSON.stringify(item))
                            })
                        } else {
                            formdata.append('tag', '');
                        }
                        if (this.locations.length > 0) {
                            let loc = v.locations;
                            loc.forEach((item, i) => {
                                formdata.append('location[]', JSON.stringify(item))
                            })
                        } else {
                            formdata.append('location', '');
                        }

                        axios({
                            url: "<?= base_url('Asset/saveSetting'); ?>",
                            data: formdata,
                            method: "POST"
                        }).then(res => {
                            console.log(res);
                            let rsp = res.data
                            if (rsp.status == 200) {
                                swal.fire({
                                    title: rsp.message,
                                    icon: 'success'
                                }).then(okay => {
                                    if (okay) {
                                        location.reload();
                                    }
                                })
                            } else {
                                swal.fire({
                                    title: rsp.message,
                                    icon: 'error'
                                })
                            }
                        })
                    }
                };

                function deleteAsset() {
                    const swalWithBootstrapButtons = swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success mr-1',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    })
                    swalWithBootstrapButtons.fire({
                        title: 'Area you sure?',
                        text: "You can't restore this data!",
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: '<i class="fa fa-times"></i> Cancel',
                        confirmButtonText: '<i class="fa fa-check"></i> Yes, delete!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.post("<?= base_url('Asset/delete'); ?>", {
                                assetId: this.assetData.assetId
                            }).then(res => {
                                let rsp = res.data;
                                if (rsp.status == 200) {
                                    swal.fire({
                                        title: rsp.message,
                                        icon: 'success',
                                    }).then(okay => {
                                        if (okay) {
                                            window.location.href = "<?= base_url('Asset'); ?>"
                                        }
                                    })
                                } else {
                                    swal.fire({
                                        title: rsp.message,
                                        icon: 'error',
                                    })
                                }
                            })
                        }
                    })
                };

                function photo() {
                    let fileUploaded = this.$refs.file.files[0];
                    this.param.photo3 = fileUploaded;
                    // this.param.photo3 = event.target.files[0];
                };

                function btnCancelModalParam() {
                    this.param.parameterId = uuidv4();
                    this.param.sortId = $('#tableParameter tbody tr').length + 1,
                        this.param.parameterName = '';
                    this.param.photo = '';
                    this.param.photo1 = '';
                    this.param.photo2 = '';
                    this.param.photo3 = '';
                    this.param.description = '';
                    this.param.uom = '';
                    this.param.min = null;
                    this.param.max = null;
                    this.param.normal = '';
                    this.param.abnormal = '';
                    this.param.option = '';
                    this.param.inputType = '';
                    this.param.showOn = '';
                    // this.param.i = null;
                    this.param.keyGP = '';
                    this.param.key = '';

                    $('#addParameterModal').modal('hide');
                    $('#previewImg').hide();

                    $('.type').val('').trigger("change");
                    $('#showOn').val('').trigger('change');
                    $('#normal').val('').trigger('change');
                    $('#abnormal').val('').trigger('change');

                    $('#imgParam').remove();
                    $('.optNormal').remove();
                    $('.optAbnormal').remove();

                    $('.parameter').removeClass('is-invalid');
                    $('.type').removeClass('is-invalid');
                    $('.showOn').removeClass('is-invalid');
                };

                function importParameter() {
                    if (!(this.checkLimitParameter())) {
                        return swal.fire({
                            icon: 'info',
                            title: "Your parameters has reached the limit"
                        })
                    }
                    this.myModal = new coreui.Modal(document.getElementById('importParameterModal'), {
                        backdrop: 'static',
                        keyboard: false
                    });
                    this.myModal.show();
                };

                function insertParam() {
                    if (!(this.checkLimitParameter())) {
                        return swal.fire({
                            icon: 'info',
                            title: "Your parameters has reached the limit"
                        })
                    }
                    var uniqParam = _.uniqBy(v.listNewParam, 'no');
                    if (uniqParam.length) {
                        for (let b = 0; b < uniqParam.length; b++) {
                            uniqParam[b].sortId = $('#tableParameter tbody tr').length + (b + 1);
                            uniqParam[b].parameterId = uuidv4();
                            uniqParam[b].photo = "";
                            this.params.push(uniqParam[b]);
                            this.allParameter[0].push(uniqParam[b]);
                        }
                        this.parameterGroupData = _.groupBy(this.allParameter[0], function(val) {
                            return val.parameterName.includes("#") ? val.parameterName.split("#")[0] + "#" : val.parameterName;
                        });
                        $('#listImport').modal('hide');
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            iconColor: 'white',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'colored-toast'
                            },
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: 'Successfully Added Parameter'
                        })
                        $('#tableImport').DataTable().destroy();
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: "There's no data added!"
                        })
                    }
                };

                function btnSaveSorting() {
                    if (v.sortingParameter.length) {
                        axios.post("<?= base_url('Asset/sortingParameter'); ?>", {
                            assetId: v.assetData.assetId,
                            data: v.sortingParameter
                        }).then(res => {
                            if (res.status == 200) {
                                swal.fire({
                                    icon: 'success',
                                    title: res.data.message
                                }).then((ok) => {
                                    if (ok.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }
                        })
                    }
                }

                function duplicate(assetId) {
                    <?php
                    if (!checkLimitAsset()) { ?>
                        return swal.fire({
                            icon: 'info',
                            title: 'Your assets has reached the limit'
                        })
                    <?php } ?>
                    let formdata = new FormData();
                    formdata.append('assetId', assetId);
                    const swalWithBootstrapButtons = swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-primary mr-1',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    })
                    swalWithBootstrapButtons.fire({
                        title: 'All data will be duplicated including images on assets and parameters. However, the asset name and asset number will be slightly changed from the original.',
                        icon: 'info',
                        showCancelButton: true,
                        cancelButtonText: '<i class="fa fa-times"></i> Cancel',
                        confirmButtonText: '<i class="fa fa-copy"></i> Duplicate',
                    }).then((ok) => {
                        if (ok.isConfirmed) {
                            axios({
                                url: '<?= base_url('Asset/duplicate') ?>',
                                method: 'POST',
                                data: formdata
                            }).then((res) => {
                                let rsp = res.data;
                                console.log(rsp);
                                if (rsp.status == 200) {
                                    let data = rsp.data
                                    swal.fire({
                                        icon: 'success',
                                        title: res.data.message
                                    }).then((okay) => {
                                        window.location.href = '<?= base_url('Asset/detail') ?>' + '/' + data[0]['assetId'];
                                    })
                                } else {
                                    swal.fire({
                                        icon: 'error',
                                        title: res.data.message
                                    })
                                }
                            })
                        }
                    })
                }

                function exportAsset() {
                    let assetId = assetData.assetId;
                    if (!assetId) {
                        return swal.fire({
                            icon: 'error',
                            title: 'Bad request!'
                        })
                    }

                    let formdata = new FormData()
                    formdata.append('assetId', assetId)
                    axios({
                        url: '<?= base_url('Asset/exportPerAsset') ?>',
                        method: 'POST',
                        data: formdata
                    }).then((res) => {
                        let rsp = res.data;
                        // if (rsp.status == 200) {
                        //     swal.fire({
                        //         icon: 'success',
                        //         title: rsp.message
                        //     })
                        // }else{
                        //     swal.fire({
                        //         icon: 'error',
                        //         title: rsp.message
                        //     })
                        // }
                    })
                }

                const addDescJson = async (e) => {
                    let checkKey = _.filter(descJson, {
                        key: e.value
                    });
                    if (checkKey.length < 1) {
                        await descJson.push({
                            key: e.value,
                            value: descJsonValue.value
                        });

                        e.value = '';
                        descJsonValue.value = '';

                        let tableDescJson = document.getElementById("tableDescJson");
                        let trDescJson = tableDescJson.rows[tableDescJson.rows.length - 2];
                        trDescJson.querySelector("input[name='key[]']").focus();
                    }
                }

                function isEqualParam(index, id) {
                    const isEqual = (...objects) => objects.every(obj => JSON.stringify(obj) === JSON.stringify(objects[0]));
                    const {
                        assetId,
                        createdAt,
                        updatedAt,
                        deletedAt,
                        ...newParam
                    } = this.compareParameter[index];
                    delete this.parameter[index].assetId;
                    delete this.parameter[index].createdAt;
                    delete this.parameter[index].updatedAt;
                    delete this.parameter[index].deletedAt;
                    delete this.parameter[index].i;
                    let lengthCompare = this.compareParameter.length;
                    for (let i = 0; i < lengthCompare; i++) {
                        if (this.compareParameter[i].parameterId == id) {
                            console.log(this.compareParameter[i]);
                        }
                    }
                    let checkIsEqual = isEqual(JSON.stringify(newParam), JSON.stringify(this.parameter[index]));
                    return checkIsEqual;
                }

                function IsArray(str) {
                    if (Array.isArray(str)) {
                        return true
                    } else {
                        return false
                    }
                }

                function isUrl(url) {
                    let isValidUrl;
                    try {
                        isValidUrl = new URL(url);
                    } catch (e) {
                        return false;
                    }

                    return true;
                }

                function isFileExist(url) {
                    try {
                        var xhr = new XMLHttpRequest();
                        xhr.open('HEAD', urlToFile, false);
                        xhr.send();

                        if (xhr.status == "404") {
                            return false;
                        } else {
                            return true;
                        }
                    } catch (e) {
                        return false;
                    }
                }

                function checkLimitTag() {
                    let lengthTag = this.masterTag.length;
                    let lengthTempTag = this.tags.length;
                    let TagNow = lengthTag + lengthTempTag;
                    let subscription = "";

                    if (this.subscription.length) {
                        subscription = this.subscription[0];
                        if (TagNow >= subscription.tagMax) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                }

                function checkLimitTagLocation() {
                    let lengthTagLocation = this.masterTagLocation.length;
                    let lengthTempTagLocation = this.locations.length;
                    let tagLocationNow = lengthTagLocation + lengthTempTagLocation;
                    let subscription = "";

                    if (this.subscription.length) {
                        subscription = this.subscription[0];
                        if (tagLocationNow >= subscription.tagMax) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                }

                function checkLimitParameter() {
                    let lengthParameter = this.masterParameter.length;
                    let lengthTempParameter = this.params.length;
                    let lengthDelete = this.deletedParameter.length;
                    let parameterNow = (lengthParameter + lengthTempParameter) - lengthDelete;
                    let subscription = "";

                    if (this.subscription.length) {
                        subscription = this.subscription[0];
                        if (parameterNow >= subscription.parameterMax) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                }

                function checkLimitParameterImport() {
                    let lengthParameter = this.masterParameter.length;
                    let lengthTempParameter = this.params.length;
                    let lengthImport = this.listNewParam.length;
                    let lengthDelete = this.deletedParameter.length;
                    let parameterNow = (lengthParameter + lengthTempParameter + lengthImport) - lengthDelete;
                    let subscription = "";
                    if (this.subscription.length) {
                        subscription = this.subscription[0];
                        if (parameterNow >= subscription.parameterMax) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                }

                const cb = (start, end) => {
                    $('#rangechangelog span').html(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
                    $('#rangechangelog').on('apply.daterangepicker', function(ev, picker) {
                        v.start = picker.startDate.format("YYYY-MM-DD H:mm:ss")
                        v.end = picker.endDate.format("YYYY-MM-DD H:mm:ss")
                        let formdata = new FormData();
                        formdata.append('assetId', v.assetData.assetId);
                        formdata.append('start', v.start);
                        formdata.append('end', v.end);
                        axios({
                            url: '<?= base_url('/Asset/changelog') ?>',
                            method: 'POST',
                            data: formdata
                        }).then((res) => {
                            let rsp = res.data;
                            if (rsp.status == 200) {
                                v.changelog = rsp.data
                                v.tableChangeLog.clear();
                                v.tableChangeLog.rows.add(v.changelog).draw();
                            } else {
                                swal.fire({
                                    icon: 'error',
                                    title: rsp.message
                                })
                            }
                        })
                    })
                }

                onMounted(() => {
                    FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType, FilePondPluginFilePoster);
                    if (assetData.schMonthlyWeekDays != "" && assetData.schMonthlyWeekDays != null && assetData.schType == "Monthly" && assetData.schWeeks != "" && assetData.schWeeks != null) {
                        $('#monthlyOnDays').val(assetData.schMonthlyWeekDays.split(",")).trigger("change");
                    }
                    for (let i = 0; i < parameter.length; i++) {
                        parameter[i].status = 'old';
                        parameter[i].photo = '';
                        parameter[i].deletePhoto = false;
                        compareParameter[i].deletePhoto = false;
                        compareParameter[i].photo = '';
                    }
                    if (parameter.length) {
                        allParameter.push(parameter)
                    }
                    if (allParameter.length) {
                        parameterGroupData.value = _.groupBy(allParameter[0], function(val) {
                            return val.parameterName.includes("#") ? val.parameterName.split("#")[0] + "#" : val.parameterName;
                        });
                    }

                    let assetPhotoPond = {
                        acceptedFileTypes: ['image/png', 'image/jpeg'],
                        allowImagePreview: true,
                        imagePreviewMaxHeight: 200,
                        allowImageCrop: true,
                        allowMultiple: false,
                        credits: false,
                        styleLoadIndicatorPosition: 'center bottom',
                        styleProgressIndicatorPosition: 'right bottom',
                        styleButtonRemoveItemPosition: 'left bottom',
                        styleButtonProcessItemPosition: 'right bottom',
                    };
                    let assetPhoto1 = FilePond.create(document.querySelector('#logo'), assetPhotoPond);
                    assetPhoto1.on('addfile', (error, file) => {
                        v.assetPhoto = file.file
                    })
                    assetPhoto1.on('removefile', (error, file) => {
                        v.assetPhoto = ref("");
                    })

                    let dataAssetName = assetData.assetName;
                    let dataAssetNumber = assetData.assetNumber;
                    let dataAssetDesc = assetData.description;
                    let dataAssetDescJson = JSON.stringify(assetData.descriptionJson);
                    let dataAssetLat = assetData.latitude;
                    let dataAssetLong = assetData.longitude;
                    let dataAssetTag = assetData.tagId;
                    let dataAssetLocation = assetData.tagLocationId;
                    let dataTags = tags.value.length;
                    let dataLocations = locations.value.length;
                    let dataAssetStatusName = assetData.assetStatusName;
                    let dataAssetStatusId = assetData.assetStatusId;

                    let dataSchType = assetData.schType;
                    let dataSchFrequency = assetData.schFrequency;
                    let dataSchDays = assetData.schDays;
                    let dataSchWeekDays = assetData.schWeekDays;
                    let dataSchWeeks = assetData.schWeeks;

                    let dataTaggingId = assetTagging.assetTaggingId;
                    let dataTaggingValue = assetTagging.assetTaggingValue;
                    let dataTaggingType = assetTagging.assetTaggingtype;
                    let dataChecked = checked;
                    let dataFile = file;
                    let dataSetSch = setSch;
                    let dataOnDays = onDays;
                    let dataTempPhoto = tempPhoto;
                    let dataParams = params.value.length;
                    let dataDeletedParameter = deletedParameter.value.length;
                    let dataEditedParameter = editedParameter.value.length;
                    const isEqual = (...objects) => objects.every(obj => JSON.stringify(obj) === JSON.stringify(objects[0]));


                    if (moreDetailAsset.value) document.querySelector("input[name=moreDetailAssetInput]").checked = true;

                    window.addEventListener('beforeunload', function(e) {
                        let checkDescJson = isEqual(dataAssetDescJson, JSON.stringify(assetData.descriptionJson));
                        if (dataAssetName != v.assetData.assetName || dataAssetNumber != v.assetData.assetNumber || dataAssetDesc != v.assetData.description || checkDescJson == false || dataAssetLat != v.assetData.latitude || dataAssetLong != v.assetData.longitude || dataSchType != v.assetData.schType || dataSchDays != v.assetData.schDays || dataSchWeeks != v.assetData.schWeeks || dataSchWeekDays != v.assetData.schWeekDays || dataAssetTag != v.assetData.tagId || dataAssetLocation != v.assetData.tagLocationId || dataParams != v.params.length || dataDeletedParameter != v.deletedParameter.length || dataEditedParameter != v.editedParameter.length || dataAssetStatusId != v.assetData.assetStatusId || dataAssetStatusName != v.assetData.assetStatusName || dataTaggingValue != v.assetTagging.assetTaggingValue || dataTaggingType != v.assetTagging.assetTaggingtype) {
                            if (v.submited == true) {
                                return;
                            } else {
                                e.preventDefault();
                                e.returnValue = '';
                            }
                        }
                    })

                    $('#rangechangelog').daterangepicker({
                        startDate: start,
                        endDate: end,
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        }
                    }, cb);
                    cb(start, end);

                    let formdata = new FormData();
                    formdata.append('assetId', assetData.assetId);
                    formdata.append('start', moment(start).format("YYYY-MM-DD H:mm:ss"));
                    formdata.append('end', moment(end).format("YYYY-MM-DD H:mm:ss"));
                    axios({
                        url: '<?= base_url('/Asset/changelog') ?>',
                        method: 'POST',
                        data: formdata
                    }).then((res) => {
                        let rsp = res.data;
                        if (rsp.status == 200) {
                            v.changelog = rsp.data;
                            $(document).ready(function() {
                                // v.tableChangeLog = v.DTChangeLog(v.changelog);
                                v.tableChangeLog = $('#tableChangeLog').DataTable({
                                    data: v.changelog,
                                    columns: [{
                                            data: "time",
                                            name: "time",
                                            render: function(data, type, row, meta) {
                                                return v.momentchangelog(data);
                                            }
                                        },
                                        {
                                            data: "ip",
                                            name: "ip"
                                        },
                                        {
                                            data: "username",
                                            name: "username"
                                        },
                                        {
                                            data: "activity",
                                            name: "activity",
                                        },
                                        {
                                            data: "assetId",
                                            name: "assetId",
                                            render: function(data, type, row, meta) {
                                                if (row.activity == 'Update asset') {
                                                    return '<button onclick="modalChange(' + meta.row + ')" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye mr-1"></i> Detail</button>'
                                                } else {
                                                    return '<button onclick="modalChangeParam(' + meta.row + ')" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye mr-1"></i> Detail</button>'
                                                }
                                            }
                                        }
                                    ],
                                    order: [0, 'desc'],
                                    columnDefs: [{
                                        targets: 4,
                                        width: "10%"
                                    }]
                                })
                            })
                        }
                    })

                    $("#srcParameter").on("keyup", function() {
                        var value = $(this).val().toLowerCase();
                        $("#tableParameter tbody tr").filter(function() {
                            $(this).toggle($(this).text()
                                .toLowerCase().indexOf(value) > -1)
                        });
                    });
                });

                return {
                    subscription,
                    tableChangeLog,
                    IsArray,
                    isUrl,
                    isFileExist,
                    IsJsonString,
                    changelog,
                    dataChangeLog,
                    dataAB,
                    momentchangelog,
                    parameterChangeLog,
                    parameterGroupChangeLog,

                    checkTabDetail,
                    checkTabParameter,
                    checkTabSetting,

                    myModal,
                    file,
                    submited,

                    modalChange,

                    checked,
                    assetData,
                    statusData,
                    deleteAssetPhoto,
                    assetPhoto,
                    descJson,
                    parameter,
                    compareParameter,
                    deletedParameter,
                    editedParameter,
                    sortingParameter,
                    checkModalAdd,
                    checkModalExist,
                    //tagging
                    assetTagging,
                    valRfid,
                    valCoordinate,
                    valUhf,
                    //end tagging
                    setSch,
                    schFreq,
                    selectedSchWeekly,
                    selectedSchMonthlyDays,
                    onDays,

                    //tag & location
                    masterTag,
                    masterTagLocation,
                    addTag,
                    tag,
                    tags,
                    addLocation,
                    tagLocation,
                    locations,
                    //end tag & location
                    masterParameter,
                    param,
                    paramPhoto,
                    tempPhoto,
                    params,
                    importList,
                    listNewParam,
                    tableImportParam,
                    pathParamPhoto,
                    deletePhoto,
                    // new tag & location
                    modalAddTag,
                    addNewTag,
                    modalAddLocation,
                    addTagLocation,

                    addTempParameter,
                    editTempParameter,
                    updateTempParameter,
                    removeTempParameter,
                    btnSaveSetting,
                    deleteAsset,
                    photo,
                    addParameter,
                    editExistParameter,
                    updateExistParameter,
                    removeExistParameter,
                    importParameter,
                    insertParam,
                    addDescJson,
                    descJsonValue,
                    moreDetailAsset,
                    btnCancelModalParam,
                    btnSaveSorting,
                    isEqualParam,
                    parameterGroupData,
                    tempParameterGroupData,
                    allParameter,
                    cb,
                    start,
                    end,
                    duplicate,
                    exportAsset,
                    // DTChangeLog

                    checkLimitTag,
                    checkLimitTagLocation,
                    checkLimitParameter,
                    checkLimitParameterImport
                }
            },
            computed: {
                rfidValue: {
                    get() {
                        let lengthTagging = this.assetTagging.length;
                        if (lengthTagging) {
                            let checkRfid = this.assetTagging.find(function(key, index) {
                                if (key.assetTaggingtype == 'rfid') return true;
                            })
                            if (checkRfid) {
                                return checkRfid.assetTaggingValue;
                            } else {
                                this.assetTagging.push({
                                    'assetId': this.assetData.assetId,
                                    'assetTaggingId': uuidv4(),
                                    'assetTaggingtype': 'rfid',
                                    'assetTaggingValue': ''
                                })
                            }
                        }
                    },
                    set(newValue) {
                        let lengthTagging = this.assetTagging.length;
                        if (lengthTagging) {
                            if (this.assetTagging[0].assetTaggingtype == 'rfid') {
                                this.assetTagging[0].assetTaggingValue = newValue;
                            } else {
                                for (let i = 0; i < this.assetTagging.length; i++) {
                                    if (this.assetTagging[i].assetTaggingtype == 'rfid') {
                                        this.assetTagging[i].assetTaggingValue = newValue;
                                    }
                                }
                            }
                        }
                    }
                },
                coordinatValue: {
                    get() {
                        let lengthTagging = this.assetTagging.length;
                        if (lengthTagging) {
                            for (let i = 0; i < lengthTagging; i++) {
                                if (this.assetTagging[i].assetTaggingtype == 'coordinat') {
                                    return this.assetTagging[i].assetTaggingValue
                                }
                            }
                        }
                    },
                    set(newValue) {
                        let lengthTagging = this.assetTagging.length;
                        if (lengthTagging) {
                            for (let i = 0; i < lengthTagging; i++) {
                                if (this.assetTagging[i].assetTaggingtype == 'coordinat') {
                                    this.assetTagging[i].assetTaggingValue = newValue;
                                }
                            }
                        }
                    }
                }
            }
        }).mount('#app');

        function modalChange(i) {
            v.myModal = new coreui.Modal(document.getElementById('modalChange'), {});
            v.myModal.show();

            v.dataChangeLog = {
                ...v.changelog[i]
            };

            let before = _.omit((JSON.parse(v.changelog[i].data)).data_before, ['assetId', 'userId', 'assetStatusId', 'createdAt', 'updatedAt', 'deletedAt', 'tagId', 'tagLocationId', 'latitude', 'longitude']);
            let after = _.omit((JSON.parse(v.changelog[i].data)).data_after, ['assetId', 'userId', 'assetStatusId', 'createdAt', 'updatedAt', 'deletedAt', 'tagId', 'tagLocationId', 'latitude', 'longitude']);

            let data = [];
            data['data_before'] = before;
            data['data_after'] = after;
            v.dataAB = data
        }

        function modalChangeParam(i) {
            v.myModal = new coreui.Modal(document.getElementById('modalChangeParam'), {});
            v.myModal.show();

            v.dataChangeLog = {
                ...v.changelog[i]
            };

            let before = _.omit((JSON.parse(v.changelog[i].data)).data_before, ['assetId', 'userId', 'assetStatusId', 'createdAt', 'updatedAt', 'deletedAt', 'tagId', 'tagLocationId', 'latitude', 'longitude']);
            let after = _.omit((JSON.parse(v.changelog[i].data)).data_after, ['assetId', 'userId', 'assetStatusId', 'createdAt', 'updatedAt', 'deletedAt', 'tagId', 'tagLocationId', 'latitude', 'longitude']);

            let data = [];
            data['data_before'] = Object.values(before);
            data['data_after'] = Object.values(after);
            v.dataAB = data;
            v.parameterChangeLog = [];
            v.dataAB.data_before.forEach((val, i) => {
                v.parameterChangeLog.push(val)
            });
            v.dataAB.data_after.forEach((val, i) => {
                v.parameterChangeLog.push(val)
            });

            v.parameterGroupChangeLog = _.groupBy(v.parameterChangeLog, function(val) {
                return val.parameterId;
            });
        }

        function modalPreviewImg() {
            $('#fullImg').remove();
            this.myModal = new coreui.Modal(document.getElementById('modalPreviewImg'), {});
            this.myModal.show();
            $('#fullPreview').append("<img id='fullImg' src='" + v.param.photo1 + "' alt='img' style='height: 500px'>");
        }

        function modalImgAsset() {
            $('#fullImg').remove();
            this.myModal = new coreui.Modal(document.getElementById('modalPreviewImg'), {});
            this.myModal.show();
            $('#fullPreview').append("<img id='fullImg' src='" + v.assetData.photo + "' alt='img' style='height: 500px'>");
        }


        $(function() {
            $('tbody').sortable({
                cursor: "move",
                handle: '.handle',
                stop: function(event, ui) {
                    $(this).find('tr').each(function(i) {
                        $(this).find('td:first').text(i + 1);
                    })

                    var getParameter = [];
                    $('#tableParam tr').each(function() {
                        var rowDataArray = [];
                        var actualData = $(this).find('td');
                        if (actualData.length > 0) {
                            actualData.each(function() {
                                rowDataArray.push($(this).text());
                            })
                            getParameter.push(rowDataArray);
                        }
                    })
                    v.sortingParameter = getParameter;
                }
            });
        })

        $(document).ready(function() {
            let lengthTableParam = $('#tableParameter tbody tr').length;
            v.param.sortId = lengthTableParam + 1;
        })

        // Get value selected location, tag, operation mode
        $(document).ready(function() {
            let selectedTag = $('#tag').val();
            v.assetData.tagId = selectedTag;

            let selectedTagLocation = $('#location').val();
            v.assetData.tagLocationId = selectedTagLocation;

            if (v.assetData.assetStatusId != '') {
                let id = '#' + (v.assetData.assetStatusId);
                $(id).parent().addClass('active');
            }

            if (v.assetData.schFrequency != '' && v.assetData.schFrequency != 0) {
                let id = '#schFreq' + (v.assetData.schFrequency);
                $(id).parent().addClass('active');
            }

            if (v.assetData.schType == 'Weekly' && v.assetData.schWeekDays != '' && v.assetData.schWeekDays != null) {
                let schWeekDays = v.assetData.schWeekDays.split(',');
                for (let i = 0; i < schWeekDays.length; i++) {
                    let id = '#schWeekly' + (schWeekDays[i]);
                    $(id).parent().addClass('active');
                    $(id).prop('checked', true);
                }
            }
        })

        // On change tag, location, operation mode
        $('#location').on('change', function() {
            let data = $(this).val();
            v.assetData.tagLocationId = data;
            for (let i = 0; i < v.tagLocation.length; i++) {
                let includes = _.includes(data, v.tagLocation[i]);
                if (!includes) {
                    for (let b = 0; b < v.locations.length; b++) {
                        if (v.tagLocation[i] == v.locations[b].addLocationId) {
                            v.locations.splice(b, 1);
                        }
                    }
                    v.tagLocation.splice(i, 1);
                }
            }
        })

        $('#tag').on('change', function() {
            let data = $(this).val();
            v.assetData.tagId = data;
            for (let i = 0; i < v.tag.length; i++) {
                let includes = _.includes(data, v.tag[i]);
                if (!includes) {
                    for (let b = 0; b < v.tags.length; b++) {
                        if (v.tag[i] == v.tags[b].addTagId) {
                            v.tags.splice(b, 1);
                        }
                    }
                    v.tag.splice(i, 1);
                }
            }
        })

        $('#taggingType').on('change', function() {
            let data = $(this).val();
            v.assetTagging.assetTaggingtype = data;
        })

        // map tagging
        $(document).ready(function() {
            mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';
            for (let i = 0; i < v.assetTagging.length; i++) {
                let checkCoordinat = v.assetTagging.find(function(key, index) {
                    if (key.assetTaggingtype == 'coordinat') return true;
                })
                if (checkCoordinat) {
                    var latlong = (checkCoordinat.assetTaggingValue.split(",")).reverse();
                    break;
                } else {
                    v.assetTagging.push({
                        'assetId': v.assetData.assetId,
                        'assetTaggingId': uuidv4(),
                        'assetTaggingtype': 'coordinat',
                        'assetTaggingValue': ''
                    })
                    var latlong = [109.01134179104685, -7.730072747845469];
                    break;
                }
            }
            var map = new mapboxgl.Map({
                container: 'mapTagging', // container ID
                style: 'mapbox://styles/mapbox/streets-v11', // style URL
                center: [latlong[0], latlong[1]], // starting position [lng, lat]
                zoom: 14, // starting zoom
            });
            var marker = new mapboxgl.Marker({
                    draggable: true,
                })
                .setLngLat([latlong[0], latlong[1]])
                .addTo(map);

            function onDragEnd(params) {
                const lnglat = marker.getLngLat();
                // coordinates.style.display = 'block';
                let lat = lnglat.lat;
                let long = lnglat.lng;
                v.valCoordinate = lat + "," + long;
                for (let i = 0; i < v.assetTagging.length; i++) {
                    if (v.assetTagging[i].assetTaggingtype == 'coordinat') {
                        v.assetTagging[i].assetTaggingValue = v.valCoordinate;
                    }
                }
            }
            marker.on('dragend', onDragEnd);
        })

        // import parameter
        $(document).ready(function() {
            FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType);
            let pond = $('#fileImportParam').filepond({
                acceptedFileTypes: 'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xlsx',
                allowMultiple: false,
                instantUpload: true,
                credits: false,
                server: {
                    process: {
                        url: "<?= base_url('Asset/getDataImportParameter'); ?>",
                        method: 'post',
                        onload: (res) => {
                            var rsp = JSON.parse(res);
                            if (rsp.status == "success") {
                                v.importList = rsp.data;
                                if (v.importList.length > 0) {
                                    $('#tableImport').DataTable().destroy();
                                    loadListImport((v.importList));

                                    $('#importParameterModal').modal('hide');

                                    this.myModal = new coreui.Modal(document.getElementById('listImport'), {});
                                    this.myModal.show();
                                    $('#fileImportParam').filepond('removeFiles');
                                }
                            } else if (rsp.status == "failed") {
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
                            }
                        }
                    }
                }
            });
        })

        var loadListImport = (importList) => {
            v.tableImportParam = $('#tableImport').DataTable({
                drawCallback: function(settings) {
                    $('#all').removeClass('sorting_asc');
                    if ($('#select-all').prop('checked', true)) {
                        $('input[name="parameterId"]').prop('checked', true);
                        v.listNewParam = v.importList;
                        if (!(v.checkLimitParameterImport())) {
                            v.listNewParam = ref([]);
                            $('#select-all').prop('checked', false);
                            $('input[name="parameterId"]').prop('checked', false);
                        }
                    }
                    let arr = [];
                    $('#select-all').change(function() {
                        if (this.checked) {
                            let table = $('#tableImport').DataTable();
                            $('input[name="parameterId"]').prop('checked', this.checked);
                            let elm = table.rows().data();
                            $.each(elm, function(key, val) {
                                arr.push(val);
                            })
                            v.listNewParam = arr;
                            if (!(v.checkLimitParameterImport())) {
                                v.listNewParam = ref([]);
                                $('#select-all').prop('checked', false);
                                $('input[name="parameterId"]').prop('checked', false);
                                let lengthParameter = v.masterParameter.length;
                                let lengthParams = v.params.length;
                                let lengthImport = v.listNewParam.length;
                                let lengthDeleted = v.deletedParameter.length;
                                let available = (v.subscription[0].parameterMax) - (lengthParams + lengthImport + lengthParameter) + lengthDeleted;
                                return swal.fire({
                                    icon: 'info',
                                    title: 'Your parameters still available : ' + available + ' parameter'
                                })
                            }
                        } else {
                            $('input[name="parameterId"]').prop('checked', this.checked);
                            v.listNewParam = ref([]);
                        }
                    })

                    $('#tableImport tbody').on('change', 'input[name="parameterId"]', function() {
                        let elm = $('#select-all').get(0);
                        if (elm && elm.checked && ('indeterminate' in elm)) {
                            elm.indeterminate = true;
                        }
                    })

                    $('#tableImport tbody').on('change', 'tr', function() {
                        let table = $('#tableImport').DataTable();
                        let data = table.row(this).data();
                        let id = '#id' + data.no;
                        let checkParam = ($(id).prop('checked')) == true ? true : false;
                        if (checkParam) {
                            let lengthParam = v.importList.length;
                            for (let i = 0; i < lengthParam; i++) {
                                if (data.no == v.importList[i].no) {
                                    if (!(v.checkLimitParameterImport())) {
                                        $(id).prop('checked', false);
                                        return swal.fire({
                                            icon: 'info',
                                            title: "Your parameters has reached the limit"
                                        })
                                    }
                                    v.listNewParam.push(v.importList[i])
                                }
                            }
                        } else {
                            let lengthListNewParam = v.listNewParam.length;
                            for (let i = 0; i < lengthListNewParam; i++) {
                                if (data.no == (v.listNewParam[i]).no) {
                                    v.listNewParam.splice(i, 1)
                                }
                            }
                        }
                    })
                },
                processing: true,
                serverSide: false,
                scrollX: false,
                paging: false,
                ordering: false,
                dom: `<"d-flex justify-content-between align-items-center"<i><f>>t`,
                data: importList,
                columns: [{
                        data: "no"
                    },
                    {
                        data: "parameterName"
                    },
                    {
                        data: "description"
                    },
                    {
                        data: "max",
                        render: function(type, data, row, meta) {
                            if (row.max != null && row.normal == '' && row.inputType == 'input') {
                                if (row.flipMax && row.flipMin) {
                                    return '<div>' + row.min + ' - ' + row.max + '<br><span class="text-success">Reversed value</span></div>'
                                }
                                return row.min + ' - ' + row.max
                            } else if (row.normal != '') {
                                return row.normal
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        data: "min",
                        render: function(type, data, row, meta) {
                            if (row.min != null && row.abnormal == '' && row.inputType == 'input') {
                                if (row.flipMax && row.flipMin) {
                                    return '<div>x < ' + row.min + '; x > ' + row.max + '<br><span class="text-success">Reversed value</span></div>'
                                }
                                return 'x < ' + row.min + '; x > ' + row.max
                            } else if (row.abnormal != '') {
                                return row.abnormal
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        data: "uom"
                    },
                    {
                        data: "option"
                    },
                    {
                        data: "showOn"
                    }
                ],
                columnDefs: [{
                    targets: 0,
                    searchable: false,
                    orderable: false,
                    className: 'dt-body-center',
                    render: function(data) {
                        return `<input type="checkbox" name="parameterId" class="checkbox" id="id${data}" value="${data}">`;
                    }
                }],
                order: [0, 'asc'],
            });
        }

        // select2 setting asset
        $(document).ready(function() {
            // tag, location
            $('#tag').select2({
                theme: 'coreui',
                placeholder: "Select Tag",
                escapeMarkup: function(markup) {
                    return markup;
                },
                language: {
                    noResults: function() {
                        if (!(v.checkLimitTag())) {
                            return `<button class="btn btn-sm btn-primary disabled d-flex align-items-center" disabled onclick="v.modalAddTag()" style="cursor: not-allowed"><i class="fa fa-plus mr-1"></i> Add <i class="cil-lock-locked"></i></button>`;
                        } else {
                            return `<button class="btn btn-sm btn-primary" onclick="v.modalAddTag()"><i class="fa fa-plus"></i> Add</button>`;
                        }
                    }
                }
            });
            $('#location').select2({
                theme: 'coreui',
                placeholder: "Select Tag Location",
                escapeMarkup: function(markup) {
                    return markup;
                },
                language: {
                    noResults: function() {
                        if (!(v.checkLimitTagLocation())) {
                            return `<button class="btn btn-sm btn-primary disabled d-flex align-items-center" disabled onclick="v.modalAddLocation()" style="cursor: not-allowed"><i class="fa fa-plus mr-1"></i> Add <i class="cil-lock-locked"></i></button>`;
                        } else {
                            return `<button class="btn btn-sm btn-primary" onclick="v.modalAddLocation()"><i class="fa fa-plus"></i> Add</button>`;
                        }
                    }
                }
            });

            // schedule
            $('#setSch').select2({
                theme: 'coreui',
                placeholder: "Select Item",
            });

            $('#schType').select2({
                theme: 'coreui',
                placeholder: "Select Schedule Type",
            });

            $('.monthly').select2({
                theme: 'coreui',
                placeholder: "Select Month",
            });

            $('.days').select2({
                theme: 'coreui',
                placeholder: "Select Days",
            });

            $('#monthlyOn').select2({
                theme: 'coreui',
                placeholder: "Select Item"
            });

            $('#monthlyOnDays').select2({
                theme: 'coreui',
                placeholder: "Select All Days",
            });

            // tagging
            $('#taggingType').select2({
                theme: 'coreui',
                placeholder: 'Select Tagging Type'
            });

            // parameter
            $('.type').select2({
                theme: 'coreui',
                placeholder: "Select Type",
                dropdownParent: $('#addParameterModal'),
            });

            $('.normal').select2({
                theme: 'coreui',
                placeholder: "Select Item",
                dropdownParent: $('#addParameterModal'),
                tags: true,
                createTag: function(params) {
                    var term = $.trim(params.term);

                    if (term === '') {
                        return null;
                    }
                    return {
                        id: term,
                        text: term,
                        newTag: true // add additional parameters
                    }
                }
            });

            $('.abnormal').select2({
                theme: 'coreui',
                placeholder: "Select Item",
                dropdownParent: $('#addParameterModal'),
                tags: true,
                createTag: function(params) {
                    var term = $.trim(params.term);

                    if (term === '') {
                        return null;
                    }
                    return {
                        id: term,
                        text: term,
                        newTag: true // add additional parameters
                    }
                }
            });

            $('.showOn').select2({
                theme: 'coreui',
                placeholder: "Parameter Status",
                dropdownParent: $('#addParameterModal'),
            });
        });

        function uuidv4() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0,
                    v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

        // select2 parameter on change

        $('.normal').on('change', function() {
            let data = $('.normal').val();
            v.param.normal = data.toString();
        })

        $('.abnormal').on('change', function() {
            let data = $('.abnormal').val();
            v.param.abnormal = data.toString();
        })

        $('.normalAbnormal').on('change', function() {
            if (v.param.normal != '' || v.param.abnormal != '') {
                let normal = v.param.normal.toString();
                let abnormal = v.param.abnormal.toString();
                v.param.option = normal + ',' + abnormal;
            } else if (v.param.normal.length < 1 || v.param.abnormal.length < 1) {
                v.param.option = '';
            }
        })

        $('.showOn').on('change', function() {
            let data = $('.showOn').val();
            v.param.showOn = data.toString();
        })

        $('.type').on('change', function() {
            let data = $('.type option:selected').val();
            v.param.inputType = data;
            if ($(this).val() == 'select') {
                v.param.min = null;
                v.param.max = null;
            } else if ($(this).val() == 'checkbox') {
                v.param.min = null;
                v.param.max = null;
                v.param.uom = '';
                v.param.normal = '';
                v.param.abnormal = '';
                $('#normal').find('option').remove();
                $('#abnormal').find('option').remove();
            } else if ($(this).val() == 'input') {
                v.param.option = '';
                v.param.normal = '';
                v.param.abnormal = '';
                $('#normal').find('option').remove();
                $('#abnormal').find('option').remove();
            } else {
                v.param.min = null;
                v.param.max = null;
                v.param.uom = '';
                v.param.normal = '';
                v.param.abnormal = '';
                v.param.option = '';
                $('#normal').find('option').remove();
                $('#abnormal').find('option').remove();
            }
        })

        $(document).ready(function() {
            if (v.assetData.schManual == '0') {
                $('#setSch').val('Automatic').trigger("change");
            } else {
                $('#setSch').val('Manual').trigger("change");
            }
        })
        // select2 schedule on change
        $('#setSch').on('change', function() {
            v.setSch = $(this).val();
            if ($(this).val() == 'Manual') {
                v.assetData.schManual = '1';
            } else if ($(this).val() == 'Automatic') {
                v.assetData.schManual = '0';
            }
        })

        $('#schType').on('change', function() {
            if ($(this).val() == 'Daily') {
                v.assetData.schType = $(this).val();

                //set weekly
                let schWeekDays = v.assetData.schWeekDays.split(",");
                for (let i = 0; i < schWeekDays.length; i++) {
                    let id = '#schWeekly' + schWeekDays[i];
                    $(id).prop('checked', false);
                    $(id).parent().removeClass('active');
                }
                v.assetData.schWeekDays = ref('');
                v.selectedSchWeekly = ref([]);

                //set monthly days
                let schMonthlyDays = v.assetData.schDays.split(",");
                for (let i = 0; i < schMonthlyDays.length; i++) {
                    let id = '#schMonthlyDays' + schMonthlyDays[i];
                    $(id).prop('checked', false);
                    $(id).parent().removeClass('active');
                }
                v.assetData.schDays = ref('');

                $('#monthlyOn').val("").trigger("change");

                $('#monthlyOnDays').val("").trigger("change");

            } else if ($(this).val() == 'Weekly') {
                // set daily
                let schFreq = v.assetData.schFrequency;
                let id = '#schFreq' + schFreq;
                $(id).parent().removeClass('active');

                v.assetData.schType = $(this).val();
                v.assetData.schFrequency = "";

                //set monthly days
                let schMonthlyDays = v.assetData.schDays.split(",");
                for (let i = 0; i < schMonthlyDays.length; i++) {
                    let id = '#schMonthlyDays' + schMonthlyDays[i];
                    $(id).prop('checked', false);
                    $(id).parent().removeClass('active');
                }
                v.assetData.schDays = ref('');

                $('#monthlyOn').val("").trigger("change");
                $('#monthlyOnDays').val("").trigger("change");

            } else {
                // set daily
                let schFreq = v.assetData.schFrequency;
                let id = '#schFreq' + schFreq;
                $(id).parent().removeClass('active');

                //set weekly
                let schWeekDays = v.assetData.schWeekDays.split(",");
                for (let i = 0; i < schWeekDays.length; i++) {
                    let id = '#schWeekly' + schWeekDays[i];
                    $(id).prop('checked', false);
                    $(id).parent().removeClass('active');
                }
                v.selectedSchWeekly = ref([]);
                v.assetData.schWeekDays = ref("");

                v.assetData.schType = $(this).val();
                v.assetData.schFrequency = "";
            }
        })

        // set value schedule
        $(document).ready(function() {
            if (v.assetData.schType != '') {
                $('#schType').val(v.assetData.schType).trigger("change");
            }

            if (v.assetData.schWeeks != '') {
                $('#monthlyOn').val(v.assetData.schWeeks.split(",")).trigger("change");
                $('#gridRadios2').click();
            }

            if (v.assetData.schDays != '') {
                // $('#monthlyDays').val(v.assetData.schDays.split(",")).trigger("change");
                let selectedSchMonthlyDays = v.assetData.schDays.split(",");
                for (let i = 0; i < selectedSchMonthlyDays.length; i++) {
                    let id = "#schMonthlyDays" + selectedSchMonthlyDays[i];
                    $(id).prop('checked', true);
                    $(id).parent().addClass('active');
                }
                $('#gridRadios1').click();
            }
        })

        $('input[type="radio"][name="schFreq"]').on('change', function() {
            let val = $(this)[0].dataset.content;
            v.assetData.schFrequency = val;
        })

        // var selectedSchWeekly = [];
        $(document).ready(function() {
            if (v.assetData.schWeekDays != "" && v.assetData.schType == 'Weekly') {
                let data = v.assetData.schWeekDays.split(",");
                for (let i = 0; i < data.length; i++) {
                    v.selectedSchWeekly.push(data[i]);
                }
            }
        })

        //sch weekly
        $('input[type="checkbox"][name="schWeekly"]').on('change', function() {
            let el = $(this)[0];
            let checked = ($(el).prop('checked') == true ? true : false);
            if (checked == true) {
                let data = el.value;
                $(el).parent().addClass('active');
                v.selectedSchWeekly.push(data);
            } else {
                let data = el.value;
                $(el).parent().removeClass('active');
                for (let i = 0; i < v.selectedSchWeekly.length; i++) {
                    if (v.selectedSchWeekly[i] === data) {
                        v.selectedSchWeekly.splice(i, 1);
                    }
                }
            }
            v.assetData.schWeekDays = v.selectedSchWeekly.toString();
        })

        //sch monthly days
        $('input[type="checkbox"][name="schMonthlyDays"]').on('change', function() {
            let checked = $('input[name="schMonthlyDays"]:checked');
            let schDays = [];
            for (let i = 0; i < checked.length; i++) {
                let val = checked[i].value;
                schDays.push(val);
            }
            v.assetData.schDays = schDays.toString();
            let el = $(this)[0];
            let elChecked = ($(el).prop('checked') == true ? true : false);
            if (elChecked) {
                let data = el.value;
                $(el).parent().addClass('active');
            } else {
                let data = el.value;
                $(el).parent().removeClass('active');
            }
        })

        $('#monthlyOn').on('change', function() {
            v.assetData.schWeeks = $(this).val().toString();
        })

        $('#monthlyOnDays').on('change', function() {
            v.assetData.schMonthlyWeekDays = $(this).val().toString();
        })

        //radio monthly
        $('input[type="radio"][name="gridRadios"]').on('change', function() {
            if ($(this).val() == "days") {
                $('#monthlyOn').val("").trigger("change");
                $('#monthlyOnDays').val("").trigger("change");
                // $('#days').show();
                // $('#on').hide();
                v.assetData.schWeeks = '';
                v.onDays = "days";
            } else if ($(this).val() == "on") {
                // $('#monthlyDays').val("").trigger("change");
                // $('#days').hide();
                // $('#on').show();

                //set monthly days
                let schMonthlyDays = v.assetData.schDays.split(",");
                for (let i = 0; i < schMonthlyDays.length; i++) {
                    let id = '#schMonthlyDays' + schMonthlyDays[i];
                    $(id).prop('checked', false);
                    $(id).parent().removeClass('active');
                }
                v.assetData.schDays = ref('');
                v.onDays = "on";
            }
        })

        //asset status
        $('input[type="radio"][name="options"]').on('change', function() {
            let id = $(this)[0].id;
            let text = $(this)[0].dataset.content;

            v.assetData.assetStatusId = id;
            v.assetData.assetStatusName = text;
        })

        // fab button
        $(function() {
            $('.btn-group-fab').on('click', '.btn', function() {
                $('.btn-group-fab').toggleClass('active');
            });
            $('has-tooltip').tooltip();
        });
    </script>
    <?= $this->endSection(); ?>;