<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<?php
$schFreq = array('1', '2', '3', '4', '6', '8', '12', '24');
$schDay = array('Su' => 'Sunday', 'Mo' => 'Monday', 'Tu' => 'Tuesday', 'We' => 'Wednesday', 'Th' => 'Thursday', 'Fr' => 'Friday', 'Sa' => 'Saturday');
$schDays = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 'Last');
?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main card-border-top">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>
                        <?= $title; ?>
                    </h5>
                    <a class="btn btn-sm btn-success" href="<?= base_url('Asset'); ?>"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
                <div class="form-group mt-3">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row mt-3">
                            <div class="col-6 h-100">
                                <form enctype="multipart/form-data" method="post">
                                    <div class="form-group row">
                                        <div class="col-3">
                                            <label for="assetName">Asset <span class="required">*</span></label>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" class="form-control" id="assetName" name="assetName" v-model="assetData.assetName" placeholder="Asset Name" required>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-3">
                                            <label for="assetNumber">Number <span class="required">*</span></label>
                                        </div>
                                        <div class="col-9">
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
                                    <div :class="moreDetailAsset == true ? 'd-none' : 'row d-flex'">
                                        <div class="col-3">
                                            <label for="asssetDesc">Description</label>
                                        </div>
                                        <div class="col-9">
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
                                                        <th class="text-center"><button class="btn btnlink text-danger" @click="descJson.splice(key, 1)"><i class="fa fa-times"></i></button></th>
                                                    </tr>
                                                </template>
                                                <tr>
                                                    <th><input type="text" name="descJsonKey" class="form-control input-transparent" @keyup="addDescJson($event.target)" placeholder="Key"></th>
                                                    <th><input type="text" name="descJsonValue" class="form-control input-transparent" v-model="descJsonValue" placeholder="Value"></th>
                                                    <th class="text-center"></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                            <div class="col-6 h-100">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <input type="file" class="filepond mt-2 mb-2 w-100" name="filepond" id="logo" accept="image/png, image/jpeg, image/gif" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- Modal Tambah-->
            <div class="modal fade" id="modalTag" tabindex="-1" role="dialog" aria-labelledby="modalTagTitle" aria-hidden="true" style="z-index: 9999;">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
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
                                        <label for="tagname">Tag Name <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="frequency"></i></label>
                                        <input id="tagname" type="text" class="form-control" required>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- modal add parameter-->
            <div class="modal fade" role="dialog" id="addParameterModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 :class="checkModalAdd == true ? 'modal-title' : 'd-none'" id="titleModalAdd">Add Parameter</h5>
                            <h5 :class="checkModalAdd == true ?  'd-none' : 'modal-title'" id="titleModalEdit">Edit Parameter</h5>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row mb-3">
                                        <label class="col-3" for="parameterName">Parameter <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Parameter name for asset that you have.</div>"></i></label>
                                        <div class="col-9 p-0">
                                            <input type="text" class="form-control parameterName" name="parameterName" id="parameterName" placeholder="Parameter Name" v-model="param.parameterName">
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3" for="type">Type <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="type"></i></label>
                                        <div class="col-9 p-0">
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
                                        <label class="col-3" for="min">Min <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="min"></i></label>
                                        <div class="col-9 p-0">
                                            <input type="number" class="form-control min" name="min" placeholder="Min Value" v-model="param.min">
                                            <div class="invalid-feedback">
                                                    Field cannot be empty.
                                                </div>
                                        </div>
                                    </div>
                                    <div :class="param.inputType == 'input' ? 'row mb-3' : 'd-none'">
                                        <label class="col-3" for="max">Max <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="max"></i></label>
                                        <div class="col-9 p-0">
                                            <input type="number" class="form-control max" name="max" placeholder="Max Value" v-model="param.max">
                                            <div class="invalid-feedback">
                                                    Field cannot be empty.
                                                </div>
                                        </div>
                                    </div>
                                    <div :class="param.inputType == 'select' ? 'row mb-3' : 'd-none'">
                                        <label class="col-3" for="normal">Normal <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="normal"></i></label>
                                        <div class="col-9 p-0">
                                            <select class="form-control normalAbnormal normal" name="normal" id="normal" multiple></select>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                    <div :class="param.inputType == 'select' ? 'row mb-3' : 'd-none'">
                                        <label class="col-3" for="abnormal">Abnormal <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="abnormal"></i></label>
                                        <div class="col-9 p-0">
                                            <select class="form-control normalAbnormal abnormal" name="abnormal" id="abnormal" multiple></select>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                    <div :class="((param.inputType == 'input') || (param.inputType == 'select') ? 'row mb-3' : 'd-none')">
                                        <label class="col-3" for="uom">Unit Of Measure <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="uom"></i></label>
                                        <div class="col-9 p-0">
                                            <input type="text" class="form-control uom" name="uom" placeholder="Unit Of Measure" v-model="param.uom">
                                            <div class="invalid-feedback">
                                                    Field cannot be empty.
                                                </div>
                                        </div>
                                    </div>
                                    <div :class="((param.inputType == 'select') || (param.inputType == 'checkbox') ? 'row mb-3' : 'd-none')">
                                        <label class="col-3">Option <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="option"></i></label>
                                        <div class="col-9 p-0">
                                            <input class="form-control" type="text" name="option" id="option" v-model="param.option" placeholder="Option Value">
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3" for="showOn">Parameter Status <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="showOn"></i></label>
                                        <div class="col-9 p-0">
                                            <select class="form-control showOn" name="showOn" id="showOn" multiple>
                                                <option value="Running">Running</option>
                                                <option value="Standby">Standby</option>
                                                <option value="Repair">Repair</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3" for="description">Description <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="description"></i></label>
                                        <textarea class="form-control col-9 description" rows="9" name="description" placeholder="Description of parameter" v-model="param.description"></textarea>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-3" for="photo">Photo <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="photo"></i></label>
                                        <input type="file" class="p-0 col-9 photo" name="photo" @change="photo" accept="image/png, image/jpeg, image/gif">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="cancel" @click="btnCancelModalParam()"><i class=" fa fa-times"></i> Cancel</button>
                            <button type="submit" :class="checkModalAdd == true ? 'btn btn-success' : 'd-none'" @click="addTempParameter()" id="btnAddParam"><i class="fa fa-plus"></i> Add Parameter</button>
                            <!-- <button type="button" class="btn btn-success" @click="updateParameter()" style="display: none;" id="btnUpdateParameter"><i class="fa fa-check"></i> Save Changes</button> -->
                            <button type="button" :class="checkModalAdd == true ? 'd-none' : 'btn btn-success'" @click="updateTempParameter()" id="btnUpdateParam"><i class="fa fa-check"></i> Save Changes</button>
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
                                            <a href="<?= base_url('/Asset/download'); ?>" class="btn btn-success w-100"><i class="fa fa-file-excel"></i> Download Template</a>
                                        </div>
                                        <div>
                                            <b><i>Ketentuan Upload File</i></b>
                                            <ol>
                                                <li>File harus ber ekstensi .xls, .xlsx</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <form action="post" enctype="multipart/form-data">
                                        <input type="file" class="filepond mt-2 mb-2 w-100" name="importParam" id="fileImportParam" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- modal table import parameter-->
            <div class="modal fade" role="dialog" id="listImport">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="titleModalAdd">List Parameter</h5>
                        </div>
                        <div class="modal-body">
                            <div class="container">
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
                            <button type="button" class="btn btn-success" @click="addAssetTag()"><i class="fa fa-plus"></i> Add Tag</button>
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
        </div>
        <!-- Location and Tag -->
        <div id="cardLocationTag">
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
                            <label class="col-md-3" for="tagLocation">Location</label>
                            <div class="col-md-9">
                                <select class="form-control" name="tagLocation" id="tagLocation" multiple="multiple">
                                    <?php foreach ($locationData as $val) : ?>
                                        <option class="optionLocation" value="<?= $val->tagLocationId; ?>"><?= $val->tagLocationName; ?></option>
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
                                    <svg class="c-icon">
                                        <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-tags"></use>
                                    </svg>
                                </h5>
                            </div>
                            <div>
                                <h5 class="ml-1 mb-0">
                                    Asset Tag <span class="required">*</span>
                                </h5>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <?php
                            ?>
                            <label class="col-md-3" for="tag">Tag</label>
                            <div class="col-md-9">
                                <select class="form-control" name="tag" id="tag" multiple>
                                    <?php foreach ($tagData as $val) : ?>
                                        <option value="<?= $val->tagId; ?>"><?= $val->tagName; ?></option>
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

        <!-- Schedule and Operation -->
        <div id="cardScheduleOpt">
            <div class="row">
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardSchedule">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                                <h5>
                                    <svg class="c-icon">
                                        <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-calendar"></use>
                                    </svg>
                                </h5>
                            </div>
                            <div>
                                <h5 class="ml-1 mb-0">
                                    Schedule <span class="required">*</span>
                                </h5>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-3">
                                        <label for="setSch">Set As <span class="required">*</span></label>
                                    </div>
                                    <div class="col-9">
                                        <select class="form-control" name="setSch" id="setSch">
                                            <option value="" selected disabled>Schedule Set As</option>
                                            <option value="Manual">Manual</option>
                                            <option value="Automatic">Automatic</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Field cannot be empty.
                                        </div>
                                        <div :class="setSch == 'Manual' ? 'mt-1' : 'd-none'" style="font-size: 80%; width: 100%; color: #e55353">
                                            Please set schedule on schedule page
                                        </div>
                                    </div>
                                </div>
                                <div :class="setSch == 'Automatic' ? 'form-group row schType' : 'd-none'">
                                    <div class="col-3">
                                        <label for="schType">Schedule <span class="required">*</span></label>
                                    </div>
                                    <div class="col-9">
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
                                <div :class="((assetData.schType == 'Daily') && (setSch == 'Automatic') ? 'mt-3' : 'd-none')" id="daily">
                                    <div class="row">
                                        <div class="col">
                                            <div class="btn-group-toggle w-100 d-flex justify-content-between align-items-q w-100" id="schFreq" data-toggle="buttons">
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
                                                            <label class="btn btn-sm btn-outline-primary mr-1 mb-1" for="schWeekly<?= $key ?>" style="width: 10% !important; display: inline-table">
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
                                                    <label for="">Set Montly As <span class="required">*</span></label>
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
                                                            <label class="btn btn-sm btn-outline-primary mr-1 mb-1" for="schMonthlyDays<?= $val ?>" style="width: 12% !important; display: inline-table">
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
                            <ul class="nav nav-tabs w-100 d-flex align-items-center" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tabRfid" role="tab" aria-controls="tabRfid" id="rfid_tab" @click="rfid()">
                                        <svg class="c-icon">
                                            <use xlink:href="<?= base_url() ?>/icons/coreui/svg/solid.svg#cis-qr-code"></use>
                                        </svg> rfid </a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabCoordinate" role="tab" aria-controls="tabCoordinate" id="coordinate_tab" @click="coordinate()">
                                        <svg class="c-icon">
                                            <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-map"></use>
                                        </svg> coordinate </a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabUhf" role="tab" aria-controls="tabUhf" id="uhf_tab" @click="uhf()">
                                        <svg class="c-icon">
                                            <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-waves"></use>
                                        </svg> uhf </a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabRfid" role="tabpanel">
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <div class="form-group row d-flex align-items-center">
                                                <div class="col-3">
                                                    <label for="asset">Value <span class="required">*</span></label>
                                                </div>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" id="valRfid" name="valRfid" placeholder="Tagging Value" v-model="assetTagging.assetTaggingValue" required>
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabCoordinate" role="tabpanel">
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <div class="form-group row d-flex align-items-center">
                                                <div class="col-3">
                                                    <label for="asset">Value<span class="required">*</span></label>
                                                </div>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" id="valCoordinate" name="valCoordinate" placeholder="Latitude, Longitude" v-model="valCoordinate" required>
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1" id="mapTagging" style="min-width: 100% !important; height: 200px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabUhf" role="tabpanel">
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

        <!-- Asset Tagging and Config -->
        <div id="cardAssetTagging">
            <div class="row">
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardOperation">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                                <h5>
                                    <svg class="c-icon">
                                        <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-cog"></use>
                                    </svg>
                                </h5>
                            </div>
                            <div>
                                <h5 class="ml-1 mb-0">
                                    Change Operation Mode <span class="required">*</span>
                                </h5>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="btn-group-toggle" data-toggle="buttons" style="max-height: 100px !important; overflow-y: auto;" id="operation">
                                    <?php foreach ($statusData as $key) : ?>
                                        <label class="btn btn-outline-primary mr-1 mb-1">
                                            <input type="radio" name="options" data-content="<?= $key->assetStatusName ?>" id="<?= $key->assetStatusId ?>" autocomplete="off"><?= $key->assetStatusName ?>
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
        <div class="card card-main" id="cardParameter">
            <div class="mt-2 d-flex justify-content-between align-items-center">
                <h5>
                    <b class="d-flex justify-content-start align-item-center">
                        <svg class="c-icon mr-1">
                            <use xlink:href="/icons/coreui/svg/linear.svg#cil-timeline"></use>
                        </svg>
                        <p class="m-0"> Parameter <span class="required">*</span></p>
                    </b>
                </h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary mr-1" @click="importParameter()"><i class="fa fa-upload"></i> Import Parameter</button>
                    <button class="btn btn-sm btn-outline-primary" @click="addParameter(); checkModalAdd = true"><i class="fa fa-plus"></i> Add Parameter</button>
                </div>
            </div>
            <div class="table-responsive mt-2">
                <table class="table dt-responsive table-hover w-100 display" id="tableParameter">
                    <thead class="bg-primary">

                        <tr>
                            <th class="text-center">Parameter</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Normal</th>
                            <th class="text-center">Abnormal</th>
                            <th class="text-center">UoM</th>
                            <th class="text-center">Show On</th>
                            <th class="text-center"><i>Status</i></th>
                            <th width="10%" class="text-center" style="border-top-right-radius: 5px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(items, i) in params" :key="i">
                            <td class="text-center">{{ items.parameterName}}</td>
                            <td class="text-center">{{ items.description}}</td>
                            <td class="text-center" v-if="items.max != null">
                                {{ items.max }}
                            </td>
                            <td class="text-center" v-else-if="items.normal != ''">
                                {{ items.normal }}
                            </td>
                            <td class="text-center" v-else>
                                <i>(Empty)</i>
                            </td>
                            <td class="text-center" v-if="items.min != null">
                                {{ items.min }}
                            </td>
                            <td class="text-center" v-else-if="items.abnormal != ''">
                                {{ items.abnormal }}
                            </td>
                            <td class="text-center" v-else>
                                <i>(Empty)</i>
                            </td>
                            <td class="text-center" v-if="items.uom != ''">
                                {{ items.uom }}
                            </td>
                            <td class="text-center" v-else-if="items.option != ''">
                                {{ items.option }}
                            </td>
                            <td class="text-center" v-else>
                                <i>(Empty)</i>
                            </td>
                            <td class="text-center">{{ items.showOn}}</td>
                            <td class="text-center"><i class="text-success"><span class="badge badge-success text-white">New!</span></i></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-success mr-1" @click="editTempParameter(i); checkModalAdd = false"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger" @click="removeTempParameter(i)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="cardSave">
            <!-- <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center w-100">
                        <button class="btn btn-outline-primary w-100" type="button" @click="save()"><i class="fa fa-plus"></i> Publish Asset</button>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <div class="btn-fab" aria-label="fab">
        <div>
            <button @click="save()" type="button" class="btn btn-main btn-success has-tooltip" data-toggle="tooltip" data-placement="top" title="Publish Asset"><i class="fa fa-save"></i></button>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<script>
    const {
        onMounted,
        ref,
        reactive
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var checkModalAdd = ref(false);
            var assetData = reactive({
                assetId: uuidv4(),
                assetName: '',
                assetNumber: '',
                description: '',
                descriptionJson: [],
                assetStatusId: '',
                assetStatusName: '',
                latitude: '',
                longitude: '',
                schManual: '1',
                schType: '',
                schFrequency: '',
                schDays: '',
                schWeekDays: '',
                schWeeks: '',
                tagId: [],
                tagLocationId: [],
            });
            var assetTagging = reactive({
                assetId: assetData.assetId,
                assetTaggingId: uuidv4(),
                assetTaggingtype: 'rfid',
                assetTaggingValue: '',
            });
            var setSch = ref('');
            var selectedSchWeekly = ref([]);
            var selectedSchMonthlyDays = ref([]);
            var valCoordinate = ref('');
            var userId = ref(uuidv4());
            var onDays = ref('');
            var assetLatitude = ref('');
            var assetLongitude = ref('');
            var tag = ref([]);
            var tagLocation = ref([]);
            var addTag = reactive({
                addTagId: uuidv4(),
                addTagName: '',
                addTagDesc: '',
            });
            var addLocation = reactive({
                addLocationId: uuidv4(),
                addLocationName: '',
                addLocationLatitude: '',
                addLocationLongitude: '',
                addLocationDesc: '',
            });
            var tags = ref([]);
            var locations = ref([]);
            var myModal = ref('');
            var param = reactive({
                parameterId: uuidv4(),
                sortId: null,
                parameterName: '',
                photo: '',
                description: '',
                uom: '',
                min: null,
                max: null,
                normal: '',
                abnormal: '',
                option: '',
                inputType: '',
                showOn: '',
            });
            var params = ref([]);
            var importList = reactive({});
            var listNewParam = ref([]);
            var moreDetailAsset = ref(false);
            var descJson = reactive(assetData.descriptionJson);
            var descJsonValue = ref('');
            var submited = ref(false);

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

                    let tableDescJson = document.getElementById('tableDescJson');
                    let trDescJson = tableDescJson.rows[tableDescJson.rows.length - 2];
                    trDescJson.querySelector("input[name='key[]']").focus();
                }
            }

            function photo(event) {
                this.param.photo = event.target.files[0];
                let photo = URL.createObjectURL(event.target.files[0])
                console.log(photo);
            }

            function importParameter() {
                this.myModal = new coreui.Modal(document.getElementById('importParameterModal'), {
                    backdrop: 'static',
                    keyboard: false
                })
                this.myModal.show();
            }

            function insertParam() {
                let lengthParam = v.listNewParam.length;
                var uniqParam = _.uniqBy(v.listNewParam, 'no');
                if (uniqParam.length) {
                    for (let i = 0; i < uniqParam.length; i++) {
                        uniqParam[i].sortId = $('#tableParameter tbody tr').length + (i + 1);
                        uniqParam[i].parameterId = uuidv4();
                        uniqParam[i].photo = "";
                        this.params.push(uniqParam[i]);
                    }
                    $('#listImport').modal('hide');
                    $('#tableImport').DataTable().destroy();
                } else {
                    swal.fire({
                        icon: 'error',
                        title: "There's no data added!"
                    })
                }
                return
                axios.post("<?= base_url('Asset/insertParameter'); ?>", {
                    dataParam: v.listNewParam,
                    assetId: this.assetData.assetId
                }).then(res => {
                    console.log(res);
                    if (res.data.status == 'success') {
                        const swalWithBootstrapButtons = swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire(
                            'Success!',
                            'You have successfully add parameter.',
                            'success'
                        ).then(okay => {
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
            };

            function addParameter() {
                this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                this.myModal.show();
                $('#normal').find('option').remove();
                $('#abnormal').find('option').remove();
            }

            function addTempParameter() {
                let min = ((this.param.min == "") || (this.param.min == null)) && (this.param.inputType == 'input') ? true : false;
                let max = ((this.param.max == "") || (this.param.max == null)) && (this.param.inputType == 'input') ? true : false;
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
                        if (this.param.min != "" || this.param.min != null) {
                            $('.min').removeClass('is-invalid');
                        }
                        if (this.param.max != "" || this.param.max != null) {
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
                        if (this.param.min == "" || this.param.min == null) {
                            $('.min').addClass('is-invalid');
                        }
                        if (this.param.max == "" || this.param.max == null) {
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
                        if (this.param.min != "" || this.param.min != null) {
                            $('.min').removeClass('is-invalid');
                        }
                        if (this.param.max != "" || this.param.max != null) {
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
                        if (this.param.min == "" || this.param.min == null) {
                            $('.min').addClass('is-invalid');
                        }
                        if (this.param.max == "" || this.param.max == null) {
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

                    this.params.push(this.param);
                    this.param = reactive({
                        parameterId: uuidv4(),
                        sortId: null,
                        parameterName: '',
                        photo: '',
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
                    $('.type').val('').trigger("change");
                    $('#showOn').val('').trigger('change');
                    $('#normal').val('').trigger("change");
                    $('#abnormal').val('').trigger('change');
                    $('#parameterName').focus();
                }
            }

            function editTempParameter(index) {
                this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                this.myModal.show();
                let data = this.params[index];
                this.param.parameterId = data.parameterId;
                this.param.sortId = null;
                this.param.parameterName = data.parameterName;
                this.param.photo = data.photo;
                this.param.description = data.description;
                this.param.uom = data.uom;
                this.param.min = data.min;
                this.param.max = data.max;
                this.param.normal = data.normal;
                this.param.abnormal = data.abnormal;
                this.param.option = data.option;
                this.param.inputType = data.inputType;
                this.param.showOn = data.showOn;
                this.param.i = index;

                index = this.param.i;
                this.params[index] = reactive({
                    parameterId: this.param.parameterId,
                    sortId: this.param.sortId,
                    parameterName: this.param.parameterName,
                    photo: this.param.photo,
                    description: this.param.description,
                    uom: this.param.uom,
                    min: this.param.min,
                    max: this.param.max,
                    normal: this.param.normal,
                    abnormal: this.param.abnormal,
                    option: this.param.option,
                    inputType: this.param.inputType,
                    showOn: this.param.showOn,
                    i: index,
                })
                if (this.param.photo != "") {
                    $('#previewImg').show();
                    $('#preview').append("<img id='imgParam' src='/assets/uploads/img/" + this.param.photo + "' alt='' width='40%' onclick='window.open(this.src)' style='cursor: pointer' data-toggle='tooltip' title='click to preview this image'>");
                } else if (this.param.photo == "" || this.param.photo == null) {
                    $('#previewImg').hide();
                }
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
            }

            function updateTempParameter() {
                let min = ((this.param.min == "") || (this.param.min == null)) && (this.param.inputType == 'input') ? true : false;
                let max = ((this.param.max == "") || (this.param.max == null)) && (this.param.inputType == 'input') ? true : false;
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
                        if (this.param.min != "" || this.param.min != null) {
                            $('.min').removeClass('is-invalid');
                        }
                        if (this.param.max != "" || this.param.max != null) {
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
                        if (this.param.min == "" || this.param.min == null) {
                            $('.min').addClass('is-invalid');
                        }
                        if (this.param.max == "" || this.param.max == null) {
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
                        if (this.param.min != "" || this.param.min != null) {
                            $('.min').removeClass('is-invalid');
                        }
                        if (this.param.max != "" || this.param.max != null) {
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
                        if (this.param.min == "" || this.param.min == null) {
                            $('.min').addClass('is-invalid');
                        }
                        if (this.param.max == "" || this.param.max == null) {
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

                    index = this.param.i;
                    this.params[index] = reactive({
                        parameterId: this.param.parameterId,
                        sortId: this.param.sortId,
                        parameterName: this.param.parameterName,
                        photo: this.param.photo,
                        description: this.param.description,
                        uom: this.param.uom,
                        min: this.param.min,
                        max: this.param.max,
                        normal: this.param.normal,
                        abnormal: this.param.abnormal,
                        option: this.param.option,
                        inputType: this.param.inputType,
                        showOn: this.param.showOn,
                        i: index,
                    })
                    this.myModal.hide();
                    this.param.parameterId = uuidv4();
                    this.param.sortId = null;
                    this.param.parameterName = '';
                    this.param.photo = '';
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

                    $('#previewImg').hide();
                    $('#imgParam').remove();
                    $('.type').val('').trigger("change");
                    $('#showOn').val('').trigger('change');
                    $('#normal').val('').trigger('change');
                    $('#abnormal').val('').trigger('change');
                    $('.optNormal').remove();
                    $('.optAbnormal').remove();
                    $('.parameter').removeClass('is-invalid');
                    $('.type').removeClass('is-invalid');
                    $('.showOn').removeClass('is-invalid');
                }
            }

            function removeTempParameter(index) {
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
                        this.params.splice(index, 1)
                    }
                })

            }

            function btnCancelModalParam() {
                this.param.parameterId = uuidv4();
                this.param.sortId = null;
                this.param.parameterName = '';
                this.param.photo = '';
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
                $('#addParameterModal').modal('hide');
                $('#previewImg').hide();
                $('#imgParam').remove();
                $('.type').val('').trigger("change");
                $('#showOn').val('').trigger('change');
                $('#normal').val('').trigger('change');
                $('#abnormal').val('').trigger('change');
                $('.optNormal').remove();
                $('.optAbnormal').remove();
                $('.parameter').removeClass('is-invalid');
                $('.type').removeClass('is-invalid');
                $('.showOn').removeClass('is-invalid');

            }

            function modalAddTag() {
                this.myModal = new coreui.Modal(document.getElementById('modalAddTag'));
                this.myModal.show();
            }

            function addAssetTag() {
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
                    this.addTag = {
                        addTagId: '',
                        addTagName: '',
                        addTagDesc: '',
                    }
                    this.myModal.hide();
                    this.addTag.addTagId = uuidv4();
                }
            }

            function modalAddLocation() {
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
            }

            function addTagLocation() {
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
                    $('#tagLocation').append(`<option class="optLocation` + this.addLocation.addLocationId + `" value="` + this.addLocation.addLocationId + `" selected>` + this.addLocation.addLocationName + `</option>`);
                    this.tagLocation.push($(`.optLocation` + this.addLocation.addLocationId + ``).val());
                    this.locations.push(this.addLocation);
                    this.assetData.tagLocationId.push(this.addLocation.addLocationId);
                    this.addLocation = {
                        addLocationId: uuidv4(),
                        addLocationName: '',
                        addLocationLatitude: '',
                        addLocationLongitude: '',
                        addLocationDesc: '',
                    }
                    this.myModal.hide();
                }
            }

            function save() {
                this.submited = true;
                let checkSchType = ((this.assetData.schType == "") && (this.setSch == 'Automatic')) ? true : false;
                if (this.assetData.assetName == "" || this.assetData.assetNumber == "" || this.assetData.tagId == "" || this.assetData.tagLocationId == "" || this.setSch == "" || checkSchType == true || (v.assetData.schType == 'Daily' && v.assetData.schFrequency == "") || (v.assetData.schType == 'Weekly' && v.assetData.schWeekDays == "") || (v.assetData.schType == 'Monthly' && v.onDays == 'days' && v.assetData.schDays == "") || (v.assetData.schType == 'Monthly' && v.onDays == "on" && v.assetData.schWeeks == "") || this.assetData.assetStatusName == '' || this.assetTagging.assetTaggingValue == '' || this.assetTagging.assetTaggingtype == '' || $('#tableParameter tbody tr').length < 1) {
                    this.submited = false;
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
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('html, body').animate({
                                scrollTop: $(".is-invalid").offset().top
                            }, 1000);
                        }
                    })

                    if (this.assetData.assetName != '' && $('#assetName').hasClass('is-invalid')) {
                        $('#assetName').removeClass('is-invalid');
                    }
                    if (this.assetData.assetNumber != '' && $('#assetNumber').hasClass('is-invalid')) {
                        $('#assetNumber').removeClass('is-invalid');
                    }
                    if (this.setSch != '' && $('#setSch').hasClass('is-invalid')) {
                        $('#setSch').removeClass('is-invalid');
                    }
                    if (this.assetData.schType != '' && $('#schType').hasClass('is-invalid')) {
                        $('#schType').removeClass('is-invalid');
                    }
                    if (this.assetData.schFrequency != '' && $('#schFreq').hasClass('is-invalid') || this.assetData.schType != 'Daily') {
                        $('#schFreq').removeClass('is-invalid');
                        $('#schFreq').removeClass('invalid');
                    }
                    if (this.assetData.schWeekDays != '' && $('#schWeekly').hasClass('is-invalid') || this.assetData.schType != 'Weekly') {
                        $('#schWeekly').removeClass('is-invalid');
                        $('#schWeekly').removeClass('invalid');
                    }
                    if (this.assetData.schDays != '' && $('#monthlyDays').hasClass('is-invalid') || this.assetData.schType != 'Monthly') {
                        $('#monthlyDays').removeClass('is-invalid');
                        $('#monthlyDays').removeClass('invalid');
                    }
                    if (this.assetData.schWeekDays != '' && $('#monthlyOnDays').hasClass('is-invalid') || this.assetData.schType != 'Monthly') {
                        $('#monthlyOnDays').removeClass('is-invalid');
                    }
                    if (this.assetData.schWeeks != '' && $('#monthlyOn').hasClass('is-invalid') || this.assetData.schType != 'Monthly') {
                        $('#monthlyOn').removeClass('is-invalid');
                    }
                    if (this.assetData.tagId != '' && $('#tag').hasClass('is-invalid')) {
                        $('#tag').removeClass('is-invalid');
                    }
                    if (this.assetData.tagLocationId != '' && $('#tagLocation').hasClass('is-invalid')) {
                        $('#tagLocation').removeClass('is-invalid');
                    }
                    if (this.assetData.assetStatusName != '' && $('#operation').hasClass('is-invalid')) {
                        $('#operation').removeClass('is-invalid');
                        $('#operation').removeClass('invalid');
                    }

                    //tagging
                    if (this.assetTagging.assetTaggingValue != '' && $('#valRfid').hasClass('is-invalid') && this.assetTagging.assetTaggingtype == 'rfid') {
                        $('#valRfid').removeClass('is-invalid');
                    }
                    if (this.assetTagging.assetTaggingValue != '' && $('#valCoordinate').hasClass('is-invalid') && this.assetTagging.assetTaggingtype == 'coordinat') {
                        $('#valCoordiinate').removeClass('is-invalid');
                    }
                    if (this.assetTagging.assetTaggingValue != '' && $('#valUhf').hasClass('is-invalid') && this.assetTagging.assetTaggingtype == 'uhf') {
                        $('#valUhf').removeClass('is-invalid');
                    }
                    if (this.assetTagging.assetTaggingtype != '' || this.assetTagging.assetTaggingtype != null && $('#taggingType').hasClass('is-invalid')) {
                        $('#taggingType').removeClass('is-invalid');
                    }

                    if ($('#tableParameter tbody tr').length >= 1) {
                        $('#cardParameter').removeClass('card-border');
                    }

                    //add invalid

                    if (this.assetData.assetName == '') {
                        $('#assetName').addClass('is-invalid');
                    }
                    if (this.assetData.assetNumber == '') {
                        $('#assetNumber').addClass('is-invalid');
                    }
                    if (this.assetData.tagId == '') {
                        $('#tag').addClass('is-invalid');
                    }
                    if (this.assetData.tagLocationId == '') {
                        $('#tagLocation').addClass('is-invalid');
                    }
                    if (this.setSch == "") {
                        $('#setSch').addClass('is-invalid');
                    }
                    if (this.assetData.schType == '' && this.assetData.schManual == '0') {
                        $('#schType').addClass('is-invalid');
                    } else if (this.assetData.schType == "Daily" && this.assetData.schManual == '0') {
                        if (this.assetData.schFrequency == '') {
                            $('#schFreq').addClass('is-invalid');
                            $('#schFreq').addClass('invalid');
                        }
                    } else if (this.assetData.schType == 'Weekly' && this.assetData.schManual == '0') {
                        if (this.assetData.schWeekDays == '') {
                            $('#schWeekly').addClass('is-invalid');
                            $('#schWeekly').addClass('invalid');
                        }
                    } else if (this.assetData.schType == 'Monthly' && this.assetData.schManual == '0') {
                        if (v.onDays == 'days') {
                            if (v.assetData.schDays == '') {
                                $('#monthlyDays').addClass('is-invalid');
                                $('#monthlyDays').addClass('invalid');
                            }
                        } else if (v.onDays == 'on') {
                            if (v.assetData.schWeeks == '' || v.assetData.schWeekDays == '') {
                                if (v.assetData.schWeeks == '') {
                                    $('#monthlyOn').addClass('is-invalid');
                                } else {
                                    $('#monthlyOn').removeClass('is-invalid');
                                }
                                if (v.assetData.schWeekDays == '') {
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
                    //tagging
                    if (this.assetTagging.assetTaggingValue == '' && this.assetTagging.assetTaggingtype == 'rfid') {
                        $('#valRfid').addClass('is-invalid');
                    }
                    if (this.assetTagging.assetTaggingValue == '' && this.assetTagging.assetTaggingtype == 'coordinat') {
                        $('#valCoordinate').addClass('is-invalid');
                    }
                    if (this.assetTagging.assetTaggingValue == '' && this.assetTagging.assetTaggingtype == 'uhf') {
                        $('#valUhf').addClass('is-invalid');
                    }
                    if (this.assetTagging.assetTaggingtype == '' || this.assetTagging.assetTaggingtype == null) {
                        $('#taggingType').addClass('is-invalid');
                    }
                    //end tagging

                    if ($('#tableParameter tbody tr').length < 1) {
                        $('#cardParameter').addClass('card-border');
                    }
                } else {
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
                    if (this.assetData.tagId != '' && $('#tag').hasClass('is-invalid')) {
                        $('#tag').removeClass('is-invalid');
                    }
                    if (this.assetData.tagLocationId != '' && $('#tagLocation').hasClass('is-invalid')) {
                        $('#tagLocation').removeClass('is-invalid');
                    }

                    //tagging
                    if (this.assetTagging.assetTaggingValue != '' && $('#valRfid').hasClass('is-invalid') && this.assetTagging.assetTaggingtype == 'rfid') {
                        $('#valRfid').removeClass('is-invalid');
                    }
                    if (this.assetTagging.assetTaggingValue != '' && $('#valCoordinate').hasClass('is-invalid') && this.assetTagging.assetTaggingtype == 'coordinat') {
                        $('#valCoordinate').removeClass('is-invalid');
                    }
                    if (this.assetTagging.assetTaggingValue != '' && $('#valUhf').hasClass('is-invalid') && this.assetTagging.assetTaggingtype == 'uhf') {
                        $('#valUhf').removeClass('is-invalid');
                    }
                    if (this.assetTagging.assetTaggingtype != '' || this.assetTagging.assetTaggingtype != null && $('#taggingType').hasClass('is-invalid')) {
                        $('#taggingType').removeClass('is-invalid');
                    }

                    if ($('#tableParameter tbody tr').length >= 1) {
                        $('#cardParameter').removeClass('card-border');
                    }
                    if (this.assetData.schType != '' && $('#schType').hasClass('is-invalid')) {
                        $('#schType').removeClass('is-invalid');
                    }
                    if (this.setSch != '' && $('#setSch').hasClass('is-invalid')) {
                        $('#setSch').removeClass('is-invalid');
                    }
                    if (this.assetData.schFrequency != '' && $('#schFreq').hasClass('is-invalid') || this.assetData.schType != 'Daily') {
                        $('#schFreq').removeClass('is-invalid');
                        $('#schFreq').removeClass('invalid');
                    }
                    if (this.assetData.schWeekDays != '' && $('#schWeekly').hasClass('is-invalid') || this.assetData.schType != 'Weekly') {
                        $('#schWeekly').removeClass('is-invalid');
                        $('#schWeekly').removeClass('invalid');
                    }
                    if (this.assetData.schDays != '' && $('#monthlyDays').hasClass('is-invalid') || this.assetData.schType != 'Monthly') {
                        $('#monthlyDays').removeClass('is-invalid');
                        $('#monthlyDays').removeClass('invalid');
                    }
                    if (this.assetData.schWeekDays != '' && $('#monthLyOnDays').hasClass('is-invalid' || this.assetData.schType != 'Monthly')) {
                        $('#monthLyOnDays').removeClass('is-invalid');
                    }
                    if (this.assetData.schWeeks != '' && $('#monthlyOn').hasClass('is-invalid') || this.assetData.schType != 'Monthly') {
                        $('#monthlyOn').removeClass('is-invalid');
                    }

                    if (this.setSch == "") {
                        $('#setSch').addClass('is-invalid');
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
                                    $('#monthlyDays').addClass('invalid');
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
                                if (v.assetData.schWeeks == '' || v.assetData.schWeekDays == '') {
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
                                    if (v.assetData.schWeekDays == '') {
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
                    formdata.append('latitude', this.assetLatitude);
                    formdata.append('longitude', this.assetLongitude);
                    formdata.append('schManual', this.assetData.schManual);
                    formdata.append('schType', this.assetData.schType);
                    formdata.append('schFrequency', this.assetData.schFrequency);
                    formdata.append('schWeekDays', this.assetData.schWeekDays);
                    formdata.append('schWeeks', this.assetData.schWeeks);
                    formdata.append('schDays', this.assetData.schDays);

                    if (this.moreDetailAsset) {
                        formdata.append('assetDesc', JSON.stringify(v.descJson));
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
                    formdata.append('assetTaggingId', this.assetTagging.assetTaggingId);
                    formdata.append('assetTaggingValue', this.assetTagging.assetTaggingValue);
                    formdata.append('assetTaggingtype', this.assetTagging.assetTaggingtype);
                    formdata.append('assetTaggingDescription', "");

                    // parameter
                    if (this.params.length > 0) {
                        let param = this.params;
                        param.forEach((item, k) => {
                            formdata.append('parameter[]', JSON.stringify(item));
                            // formdata.append('photo[]', item['photo']);
                            formdata.append('photo' + item['parameterId'], this.params[k]['photo']);
                        });
                        for (let index = 0; index < this.params.length; index++) {
                            console.log(this.params[index]['photo']);
                        }
                    } else {
                        formdata.append('parameter[]', this.params);
                    }

                    if (this.tags.length > 0) {
                        let tag = this.tags;
                        tag.forEach((item, i) => {
                            formdata.append('tag[]', JSON.stringify(item));
                        })
                    } else {
                        formdata.append('tag', '');
                    }
                    if (this.locations.length > 0) {
                        let location = this.locations;
                        location.forEach((item, i) => {
                            formdata.append('location[]', JSON.stringify(item));
                        })
                    } else {
                        formdata.append('location', '');
                    }
                    axios({
                        url: "<?= base_url('Asset/addAsset'); ?>",
                        data: formdata,
                        method: "POST"
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
                                    text: 'You have successfully added data.',
                                    icon: 'success'
                                })
                                .then(okay => {
                                    if (okay) {
                                        swal.fire({
                                            title: 'Please Wait!',
                                            text: 'Reloading page..',
                                            onOpen: function() {
                                                swal.showLoading()
                                            }
                                        })
                                        window.location.href = "<?= base_url('Asset'); ?>";
                                    }
                                })
                        } else if (res.data.status == 'failed') {
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
            }

            function coordinate() {
                v.assetTagging.assetTaggingValue = ref('')
                v.assetTagging.assetTaggingtype = 'coordinat';
            };

            function rfid() {
                v.assetTagging.assetTaggingValue = ref('');
                v.assetTagging.assetTaggingtype = 'rfid';
                v.valCoordinate = ref('');
            };

            function uhf() {
                v.assetTagging.assetTaggingValue = ref('');
                v.assetTagging.assetTaggingtype = 'uhf';
                v.valCoordinate = ref('');
            };

            onMounted(() => {
                $('#assetName').focus();
                let dataAssetName = assetData.assetName;
                let dataAssetNumber = assetData.assetNumber;
                let dataAssetDesc = assetData.description;
                let dataAssetDescJson = assetData.descriptionJson.length;
                let dataAssetStatus = assetData.assetStatusName;

                let dataAssetTag = assetData.tagId.length;
                let dataAssetLocation = assetData.tagLocationId.length;
                let dataTags = tags.value.length;
                let dataLocations = locations.value.length;

                let dataSchManual = assetData.schManual;
                let dataTaggingValue = assetTagging.assetTaggingValue;
                let dataParams = params.value.length;

                window.addEventListener('beforeunload', function(e) {
                    if (dataAssetName != v.assetData.assetName || dataAssetNumber != v.assetData.assetNumber || dataAssetDesc != v.assetData.description || dataAssetDescJson != assetData.descriptionJson.length || dataAssetTag != assetData.tagId.length || dataAssetLocation != assetData.tagLocationId.length || dataTags != tags.value.length || dataLocations != locations.value.length || dataSchManual != assetData.schManual || dataTaggingValue != assetTagging.assetTaggingValue || dataParams != params.value.length) {
                        if (v.submited == true) {
                            return;
                        } else {
                            e.preventDefault();
                            e.returnValue = '';
                        }
                    }
                })

            });
            return {
                checkModalAdd,
                assetData,
                setSch,
                selectedSchWeekly,
                selectedSchMonthlyDays,
                valCoordinate,
                rfid,
                coordinate,
                uhf,
                assetTagging,
                userId,
                onDays,
                assetLatitude,
                assetLongitude,
                tag,
                tagLocation,
                addTag,
                addLocation,
                tags,
                locations,
                myModal,
                param,
                params,
                importList,
                insertParam,
                listNewParam,
                moreDetailAsset,
                descJson,
                descJsonValue,
                addDescJson,

                photo,
                importParameter,
                addParameter,
                addTempParameter,
                editTempParameter,
                updateTempParameter,
                removeTempParameter,
                btnCancelModalParam,
                modalAddTag,
                addAssetTag,
                modalAddLocation,
                addTagLocation,
                save,
                submited,
            };
        },
    }).mount('#app');

    function uuidv4() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0,
                v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    $(document).ready(function() {
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
        $('#tagLocation').on('change', function() {
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
        $('#showOn').on('change', function() {
            v.param.showOn = $(this).val().toString();
        })
    })

    $('#setSch').on('change', function() {
        if ($(this).val() == 'Manual') {
            v.setSch = $(this).val();
            v.assetData.schManual = '1';
        } else if ($(this).val() == 'Automatic') {
            v.setSch = $(this).val();
            v.assetData.schManual = '0';
        }
    })

    $('#schType').on('change', function() {
        if ($(this).val() == 'Daily') {
            v.assetData.schType = $(this).val();

            // set weekly
            let schWeekDays = v.assetData.schWeekDays.split(",");
            for (let i = 0; i < schWeekDays.length; i++) {
                let id = '#schWeekly' + schWeekDays[i];
                $(id).prop('checked', false);
                $(id).parent().removeClass('active');
            }
            v.assetData.schWeekDays = ref('');
            v.selectedSchWeekly = ref([]);

            //set monthlyDays
            let selectedSchMonthlyDays = v.assetData.schDays.split(",");
            for (let i = 0; i < selectedSchMonthlyDays.length; i++) {
                let id = "#schMonthlyDays" + selectedSchMonthlyDays[i];
                $(id).prop('checked', false);
                $(id).parent().removeClass('active');
            }
            v.assetData.schDays = ref('');
            v.selectedSchMonthlyDays = ref([]);

            $('#monthlyOn').val("").trigger("change");
            $('#monthlyOnDays').val("").trigger("change");

        } else if ($(this).val() == 'Weekly') {

            //set daily
            let schFreq = v.assetData.schFrequency;
            let id = '#schFreq' + schFreq;
            $(id).parent().removeClass('active');
            v.assetData.schFrequency = "";

            //set monthlyDays
            let selectedSchMonthlyDays = v.assetData.schDays.split(",");
            for (let i = 0; i < selectedSchMonthlyDays.length; i++) {
                let id = "#schMonthlyDays" + selectedSchMonthlyDays[i];
                $(id).prop('checked', false);
                $(id).parent().removeClass('active');
            }
            v.assetData.schDays = ref('');
            v.selectedSchMonthlyDays = ref([]);

            v.assetData.schType = $(this).val();
            $('#monthlyOn').val("").trigger("change");
            $('#monthlyOnDays').val("").trigger("change");

        } else {
            $('#gridRadios1').prop('checked', true);
            v.onDays = 'days';
            //set daily
            let schFreq = v.assetData.schFrequency;
            let id = '#schFreq' + schFreq;
            $(id).parent().removeClass('active');

            // set weekly
            let schWeekDays = v.assetData.schWeekDays.split(",");
            for (let i = 0; i < schWeekDays.length; i++) {
                let id = "#schWeekly" + schWeekDays[i];
                $(id).prop('checked', false);
                $(id).parent().removeClass('active');
            }

            v.assetData.schType = $(this).val();
            v.assetData.schFrequency = "";
            v.assetData.schWeekDays = "";
        }
    })

    $('#monthlyOn').on('change', function() {
        v.assetData.schWeeks = $(this).val().toString();
    })

    $('#monthlyOnDays').on('change', function() {
        v.assetData.schWeekDays = $(this).val().toString();
    })

    $('#schType').on('change', function() {
        let data = $('#schType option:selected').val();
        v.assetData.schType = data;
    })

    $('#taggingType').on('change', function() {
        let data = $('#taggingType option:selected').val();
        v.assetTaggingtype = data;
    })

    // select2 parameter on change
    $('.type').on('change', function() {
        let data = $('.type option:selected').val();
        v.param.inputType = data;
    })
    $('#normal').on('change', function() {
        let data = $('#normal').val();
        v.param.normal = data.toString();
    })
    $('#abnormal').on('change', function() {
        let data = $('#abnormal').val();
        v.param.abnormal = data.toString();
    })

    $('.normalAbnormal').on('change', function() {
        if (v.param.normal != '' || v.param.abnormal != '') {
            let normal = v.param.normal.toString();
            let abnormal = v.param.abnormal.toString();
            v.param.option = normal + ',' + abnormal;
            $('#option').val(normal + "," + abnormal);
        } else if (v.param.normal.length < 1 || v.param.abnormal.length < 1) {
            v.param.option = '';
        }
    })

    $('.type').on('change', function() {
        if ($(this).val() == 'select') {
            v.param.min = null;
            v.param.max = null;
        } else if ($(this).val() == 'checkbox') {
            v.param.min = null;
            v.param.max = null;
            v.param.uom = '';
            v.param.normal = '';
            v.param.abnormal = '';
        } else if ($(this).val() == 'input') {
            v.param.option = '';
            v.param.normal = '';
            v.param.abnormal = '';
        } else {
            v.param.min = null;
            v.param.max = null;
            v.param.uom = '';
            v.param.normal = '';
            v.param.abnormal = '';
            v.param.option = '';
        }
    })

    $('#setSch').select2({
        theme: 'coreui',
        placeholder: 'Schedule Set As'
    })

    $('#schType').select2({
        theme: 'coreui',
        placeholder: 'Select Schedule Type',
    })

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
    $('#taggingType').select2({
        theme: 'coreui'
    })

    $('#tag').select2({
        theme: 'coreui',
        placeholder: "Select Tag",
        escapeMarkup: function(markup) {
            return markup;
        },
        language: {
            noResults: function() {
                return `<button class="btn btn-sm btn-primary" onclick="v.modalAddTag()"><i class="fa fa-plus"></i> Add</button>`;
            }
        }
    })

    $('#tagLocation').select2({
        theme: 'coreui',
        placeholder: 'Select Location',
        escapeMarkup: function(markup) {
            return markup;
        },
        language: {
            noResults: function() {
                return `<button class="btn btn-sm btn-primary" onclick="v.modalAddLocation()"><i class="fa fa-plus"></i> Add</button>`;
            }
        }
    })

    // select2 for add modal
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

    // filepond
    $(document).ready(function() {
        FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType);
        let pond = $('#logo').filepond({
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
        });
    })

    var loadListImport = (importList) => {
        var table = $('#tableImport').DataTable({
            drawCallback: function(settings) {
                $('#all').removeClass('sorting_asc');
                if ($('#select-all').prop('checked', true)) {
                    $('input[name="parameterId"]').prop('checked', true);
                    v.listNewParam = v.importList;
                }
                let arr = [];
                $('#select-all').change(function() {
                    if (this.checked) {
                        $('input[name="parameterId"]').prop('checked', this.checked);
                        let elm = table.rows().data();
                        $.each(elm, function(key, val) {
                            arr.push(val);
                        })
                        v.listNewParam = arr;
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
            dom: `<"d-flex justify-content-between align-items-center"<i><f>>t`,
            data: importList,
            columns: [{
                    "data": "no"
                },
                {
                    "data": "parameterName"
                },
                {
                    "data": "description"
                },
                {
                    "data": "maxNormal"
                },
                {
                    "data": "minAbnormal"
                },
                {
                    "data": "uomOption"
                },
                {
                    "data": "showOn"
                }
            ],
            "columnDefs": [{
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                render: function(data) {
                    return `<input type="checkbox" name="parameterId" class="checkbox" id="id${data}" value="${data}">`;
                }
            }],
            "order": [0, 'asc'],
        });
    }

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
                    url: "<?= base_url('Asset/uploadFile'); ?>",
                    method: 'post',
                    onload: (res) => {
                        var rsp = JSON.parse(res);
                        if (rsp.status == "success") {
                            v.importList = rsp.data;
                            if (v.importList.length > 0) {
                                loadListImport(v.importList);
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

    //radio monthly
    $('input[type="radio"][name="gridRadios"]').on('change', function() {
        if ($(this).val() == "days") {
            $('#monthlyOn').val("").trigger("change");
            $('#monthlyOnDays').val("").trigger("change");
            v.assetData.schWeeks = '';
            v.onDays = "days";
        } else if ($(this).val() == "on") {
            //set monthlyDays
            let selectedSchMonthlyDays = v.assetData.schDays.split(",");
            for (let i = 0; i < selectedSchMonthlyDays.length; i++) {
                let id = "#schMonthlyDays" + selectedSchMonthlyDays[i];
                $(id).prop('checked', false);
                $(id).parent().removeClass('active');
            }
            v.assetData.schDays = ref('');
            v.selectedSchMonthlyDays = ref([]);
            v.onDays = "on";
        }
    })

    // sch Frequency
    $('input[type="radio"][name="schFreq"]').on('change', function() {
        let val = $(this)[0].dataset.content;
        v.assetData.schFrequency = val;
    })

    //sch Weekly / weekdays
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

    $('input[type="checkbox"][name="schMonthlyDays"]').on('change', function() {
        let el = $(this)[0];
        let checked = ($(el).prop('checked') == true ? true : false);
        if (checked) {
            let data = el.value;
            $(el).parent().addClass('active');
            v.selectedSchMonthlyDays.push(data);
        } else {
            let data = el.value;
            $(el).parent().removeClass('active');
            for (let i = 0; i < v.selectedSchMonthlyDays.length; i++) {
                if (v.selectedSchMonthlyDays[i] == data) {
                    v.selectedSchMonthlyDays.splice(i, 1);
                }
            }
        }
        v.assetData.schDays = v.selectedSchMonthlyDays.toString();
    })

    // operation / asset status
    $('input[type="radio"][name="options"]').on('change', function() {
        let id = $(this)[0].id;
        let text = $(this)[0].dataset.content;
        v.assetData.assetStatusId = id;
        v.assetData.assetStatusName = text;
    })

    // mapbox
    // map tagging
    $(document).ready(function() {
        mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';

        var map = new mapboxgl.Map({
            container: 'mapTagging', // container ID
            style: 'mapbox://styles/mapbox/streets-v11', // style URL
            center: [109.005913, -7.727989], // starting position [lng, lat]
            zoom: 14, // starting zoom
        });
        var marker = new mapboxgl.Marker({
                draggable: true,
            })
            .setLngLat([109.005913, -7.727989])
            .addTo(map);
        map.addControl(new mapboxgl.FullscreenControl());
        map.resize();

        function onDragEnd(params) {
            const lnglat = marker.getLngLat();
            // coordinates.style.display = 'block';
            let lat = lnglat.lat;
            let long = lnglat.lng;
            v.valCoordinate = lat + "," + long;
            v.assetTagging.assetTaggingValue = v.valCoordinate;
        }
        marker.on('dragend', onDragEnd);
    })
</script>
<?= $this->endSection(); ?>