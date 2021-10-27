<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<style>
    .invalid {
        border: 1px solid #e55353;
        border-radius: 0.25rem;
        padding: 0.25rem;
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
?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main card-border-top">
            <div class="card-body">
                <div class="d-flex flex-row justify-content-between align-items-center w-100">
                    <ul class="nav nav-tabs w-100 d-flex flex-row align-items-center" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" id="detail_tab" @click="detailTab()">
                                <svg class="c-icon">
                                    <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-list-rich"></use>
                                </svg> Detail <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>On this tab, you can read equipment data, edit, and delete the data. And also you can read the log of changes that have occurred to the equipment data.</div>"></i></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#parameter" role="tab" aria-controls="parameter" id="parameter_tab" @click="parameterTab()">
                                <svg class="c-icon">
                                    <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-timeline"></use>
                                </svg> Parameter <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>On this tab, you can read the parameter data of an equipment</div>"></i></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting" role="tab" aria-controls="setting" id="setting_tab" @click="settingTab()">
                                <svg class="c-icon">
                                    <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-cog"></use>
                                </svg> Setting <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>In this tab, you can change the settings on an equipment</div>"></i></a></li>
                        <li class="nav-item ml-auto">
                            <a href="<?= base_url('/Asset'); ?>" class="btn btn-sm btn-success" style="display: flex;"><i class="fa fa-arrow-left"></i> Back</a>
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
                                            <template v-if="assetData.tagName" v-for="(val, key) in _.uniq(assetData.tagName.split(','))">
                                                <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                            </template>
                                            <template v-else>-</template>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td>
                                            <template v-if="assetData.tagLocationName" v-for="(val, key) in _.uniq(assetData.tagLocationName.split(','))">
                                                <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                            </template>
                                            <template v-else>-</template>
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
                            <div class="col-md-6 imgMap">
                                <img src="<?= base_url() ?>/img/logo-act.png" alt="Image" class="img-thumbnail mt-1 m-0">
                                <div class="mt-1" id="mapDetail" style="width: 100% !important; height: 170px; display: none"></div>
                            </div>
                        </div>
                    </div>
                    <!-- tab parameter -->
                    <div class=" tab-pane" id="parameter" role="tabpanel">
                        <div class="table-responsive mt-2">
                            <table class="table dt-responsive table-hover w-100" id="tableParam">
                                <thead>
                                    <tr class="bg-primary text-center">
                                        <th>Parameter</th>
                                        <th>Description</th>
                                        <th>UoM</th>
                                        <th>Min</th>
                                        <th>Max</th>
                                        <th>Show On</th>
                                        <th>Sorting</th>
                                    </tr>
                                    <tr style="display: none;">
                                        <th>#</th>
                                        <th width="12,5%">Parameter</th>
                                        <th width="12,5%">Description</th>
                                        <th width="12,5%">UoM</th>
                                        <th width="12,5%">Min</th>
                                        <th width="12,5%">Max</th>
                                        <th width="15%">Show On</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($parameter as $key) : ?>
                                        <tr>
                                            <td style="display: none;"><?= $i++; ?></td>
                                            <td class="text-center" style="display: none;"><?= $key['parameterId']; ?></td>
                                            <td class="text-center"><?= $key['parameterName']; ?></td>
                                            <td class="text-center"><?= $key['description']; ?></td>
                                            <td class="text-center">
                                                <?php
                                                if ($key['uom'] != '') {
                                                    echo $key['uom'];
                                                } else if ($key['uom'] == '' && $key['option'] == '') {
                                                    echo '<i>(Empty)</i>';
                                                } else {
                                                    echo $key['option'];
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($key['min'] != '') {
                                                    echo $key['min'];
                                                } else if ($key['min'] == '' && $key['abnormal'] == '') {
                                                    echo '<i>(Empty)</i>';
                                                } else {
                                                    echo $key['abnormal'];
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($key['max'] != '') {
                                                    echo $key['max'];
                                                } else if ($key['max'] == '' && $key['normal'] == '') {
                                                    echo '<i>(Empty)</i>';
                                                } else {
                                                    echo $key['normal'];
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center"><?= $key['showOn']; ?></td>
                                            <td class="text-center handle" style="cursor: move;"><i class="fa fa-bars"></i></td>
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
                                    <div class="form-group row d-flex align-items-center">
                                        <div class="col-sm-3">
                                            <label for="assetName">Asset<span class="required">*</span></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="assetName" name="assetName" v-model="assetData.assetName" placeholder="Asset Name" required>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row d-flex align-items-center">
                                        <div class="col-sm-3">
                                            <label for="assetNumber">Number<span class="required">*</span></label>
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
                                        <img src="<?= base_url() ?>/img/logo-act.png" alt="Image" class="img-thumbnail">
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center">
                                        <input type="file" class="filepond mt-2 mb-2 w-100" name="filepond" id="logo" accept="image/png, image/jpeg, image/gif" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal add parameter-->
                <div class="modal fade" role="dialog" id="addParameterModal">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="titleModalAdd">Add Parameter</h5>
                                <h5 class="modal-title" id="titleModalEdit" style="display: none;">Edit Parameter</h5>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="form-group">
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="row mb-3">
                                                <label class="col-sm-3" for="parameter">Parameter <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="parameter"></i></label>
                                                <div class="col-sm-9 p-0">
                                                    <input type="text" class="form-control parameter" name="parameter" placeholder="Parameter Name" v-model="param.parameterName" :required="true">
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-sm-3" for="type">Type <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="type"></i></label>
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
                                            <div class="row mb-3 typeInput">
                                                <label class="col-sm-3" for="min">Min <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="min"></i></label>
                                                <input type="number" class="form-control col-sm-9 min" name="min" placeholder="Min Value" v-model="param.min">
                                            </div>
                                            <div class="row mb-3 typeInput">
                                                <label class="col-sm-3" for="max">Max <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="max"></i></label>
                                                <input type="number" class="form-control col-sm-9 max" name="max" placeholder="Max Value" v-model="param.max">
                                            </div>
                                            <div class="row mb-3 typeInput">
                                                <label class="col-sm-3" for="uom">Unit Of Measure <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="uom"></i></label>
                                                <input type="text" class="form-control col-sm-9 uom" name="uom" placeholder="Unit Of Measure" v-model="param.uom">
                                            </div>
                                            <div class="row mb-3 typeSelect" style="display: none;">
                                                <label class="col-sm-3" for="normal">Normal <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="normal"></i></label>
                                                <div class="col-sm-9 p-0">
                                                    <select class="form-control normalAbnormal normal" name="normal" id="normal" multiple>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3 typeSelect" style="display: none;">
                                                <label class="col-sm-3" for="abnormal">Abnormal <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="abnormal"></i></label>
                                                <div class="col-sm-9 p-0">
                                                    <select class="form-control normalAbnormal abnormal" name="abnormal" id="abnormal" multiple>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3 typeCheckbox" style="display: none;">
                                                <label class="col-sm-3">Option <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="option"></i></label>
                                                <div class="col-sm-9 p-0">
                                                    <input class="form-control" type="text" name="option" id="option" v-model="param.option" placeholder="Option Value">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-sm-3" for="showOn">Parameter Status <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="showOn"></i></label>
                                                <div class="col-sm-9 p-0">
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
                                                <label class="col-sm-3" for="description">Description <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="description"></i></label>
                                                <textarea class="form-control col-sm-9 description" rows="9" name="description" placeholder="Description of parameter" v-model="param.description"></textarea>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-3" for="photo">Photo <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="photo"></i></label>
                                                <div class="col-9 p-0">
                                                    <input type="file" ref="file" class="photo w-100" name="photo" @change="photo()" accept="image/png, image/jpeg, image/gif">
                                                </div>
                                            </div>
                                            <div style="display: none !important;" class="row mb-3" id="previewImg">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-9 p-0" id="preview"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" id="cancel" @click="btnCancelModalParam()"><i class=" fa fa-times"></i> Cancel</button>
                                <button type="submit" class="btn btn-success" @click="addTempParameter()" id="btnAddParam"><i class="fa fa-plus"></i> Add Parameter</button>
                                <button type="button" class="btn btn-success" @click="updateParameter()" style="display: none;" id="btnUpdateParameter"><i class="fa fa-check"></i> Save Changes</button>
                                <button type="button" class="btn btn-success" @click="updateTempParameter()" style="display: none;" id="btnUpdateParam"><i class="fa fa-check"></i> Save Changes</button>
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
                                                <th>Parameter</th>
                                                <th>Description</th>
                                                <th>UoM</th>
                                                <th>min</th>
                                                <th>max</th>
                                                <th>input type</th>
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
                                            <input id="addTagName" type="text" class="form-control" required v-model="addTagName" placeholder="Tag Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="addTagDesc">Description <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Description for tag"></i></label>
                                            <textarea id="addTagDesc" class="form-control" required v-model="addTagDesc" rows="8" placeholder="Description of tag"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
                                <button type="button" class="btn btn-success" @click="addTag()"><i class="fa fa-plus"></i> Add Tag</button>
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
                                                <input id="addLocName" type="text" class="form-control" required v-model="addLocationName" placeholder="Tag Location Name">
                                            </div>
                                            <div class="mb-3">
                                                <label for="latitude">Latitude <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Latitude"></i></label>
                                                <input id="latitude" type="text" class="form-control" required v-model="addLocationLatitude" placeholder="Location Latitude">
                                            </div>
                                            <div class="mb-3">
                                                <label for="longitude">Longitude <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Longitude"></i></label>
                                                <input id="longitude" type="text" class="form-control" required v-model="addLocationLongitude" placeholder="Location Longitude">
                                            </div>
                                            <div class="mb-3">
                                                <label for="addLocDesc">Description <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Description for tag"></i></label>
                                                <textarea id="addLocDesc" class="form-control" required v-model="addLocationDesc" rows="8" placeholder="Description of tag location"></textarea>
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
        </div>

        <!-- change log -->
        <div class="card card-main" id="cardChangeLog">
            <div class="row">
                <div class="mt-2 col-12">
                    <h5><b>Change Log</b></h5>
                </div>
                <div class="table-responsive w-100 mt-2 col-12">
                    <table class="table table-hover">
                        <thead class="bg-primary">
                            <tr>
                                <th>Date</th>
                                <th>Asset</th>
                                <th>Number</th>
                                <th>Tag</th>
                                <th>Location</th>
                                <th>Frequency</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 1; $i < 6; $i++) { ?>
                                <tr>
                                    <td>13-02-2021 12.30.00</td>
                                    <td>log asset</td>
                                    <td>log number</td>
                                    <td>log tag</td>
                                    <td>log location</td>
                                    <td>log frequency</td>
                                    <td>log description</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Location and Tag -->
        <div id="cardLocationTag" style="display: none;">
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
                                    Asset Tag Location
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
                                    Asset Tag
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

        <!-- Schedule and Operation -->
        <div id="cardScheduleOpt" style="display: none;">
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
                                <div class="form-group row d-flex align-items-center">
                                    <div class="col-sm-3">
                                        <label for="setSch">Set As<span class="required">*</span></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="setSch" id="setSch">
                                            <option value="Manual" selected>Manual</option>
                                            <option value="Automatic">Automatic</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Field cannot be empty.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row d-flex align-items-center schType">
                                    <div class="col-sm-3">
                                        <label for="schType">Schedule<span class="required">*</span></label>
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
                                <div class="mt-3" id="daily" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <div class="btn-group-toggle w-100 d-flex justify-content-between align-items-center w-100" id="schFreq" data-toggle="buttons">
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
                                <div class="mt-2" id="weekly" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <div class="btn-group-toggle d-flex justify-content-between align-items-center" id="schWeekly">
                                                <?php foreach ($schDay as $key => $val) : ?>
                                                    <label class="btn btn-sm btn-outline-primary" for="schWeekly<?= $key ?>">
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
                                <div id="monthly" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-check">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="days">
                                                        <label class="form-check-label mr-1" for="gridRadios1">
                                                            Days
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-9 pl-0" id="days" style="display: none;">
                                                        <select name="monthly" class="form-control days" id="monthlyDays" multiple readonly>
                                                            <?php for ($i = 1; $i <= 31; $i++) {  ?>
                                                                <option value="<?= $i; ?>"><?= $i; ?></option>
                                                            <?php } ?>
                                                            <option value="Last">Last</option>
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Field cannot be empty.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check">
                                                <div class="row mt-2">
                                                    <div class="col-sm-3">
                                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="on">
                                                        <label class="form-check-label mr-1" for="gridRadios2">
                                                            On
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-9 pl-0" id="on" style="display: none">
                                                        <div class="row">
                                                            <div class="col-6 pr-1">
                                                                <select name="onMonth" class="form-control on mr-1" id="monthlyOn" multiple readonly>
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
                                                                <select name="onDays" class="form-control on mr-1" id="monthlyOnDays" multiple readonly>
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
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                                <div class="btn-group-toggle" data-toggle="buttons" style="max-height: 100px !important; overflow-y: auto;" id="operation">
                                    <?php foreach ($statusData as $key) : ?>
                                        <label class="btn btn-sm btn-outline-primary mr-1 mb-1">
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
            </div>
        </div>

        <!-- Asset Tagging and Config -->
        <div id="cardAssetTagging" style="display: none;">
            <div class="row">
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
                                                    <label for="asset">Value<span class="required">*</span></label>
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
                                <td width="40%">Bypass tagging rfid</td>
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
        <div class="card card-main" id="cardParameter" style="display: none;">
            <div class="mt-2 d-flex justify-content-between align-items-center">
                <h5>
                    <b class="d-flex justify-content-start align-item-center">
                        <svg class="c-icon mr-1">
                            <use xlink:href="<?= base_url() ?>/icons/coreui/svg/linear.svg#cil-timeline"></use>
                        </svg>
                        <p class="m-0"> Parameter<span class="required">*</span></p>
                    </b>
                </h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary mr-1" @click="importParameter()"><i class="fa fa-upload"></i> Import Parameter</button>
                    <button class="btn btn-sm btn-outline-primary" @click="addParameter()"><i class="fa fa-plus"></i> Add Parameter</button>
                </div>
            </div>
            <div class="table-responsive mt-2">
                <table class="table dt-responsive table-hover w-100 display" id="tableParameter">
                    <thead class="bg-primary">

                        <tr>
                            <th class="text-center">Parameter</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">UoM</th>
                            <th class="text-center">Min</th>
                            <th class="text-center">Max</th>
                            <th class="text-center">Show On</th>
                            <th class="text-center"><i>Status</i></th>
                            <th width="10%" class="text-center" style="border-top-right-radius: 5px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(items, i) in params" :key="i">
                            <td class="text-center">{{ items.parameterName}}</td>
                            <td class="text-center">{{ items.description}}</td>
                            <td class="text-center" v-if="items.uom != ''">
                                {{ items.uom }}
                            </td>
                            <td class="text-center" v-else-if="items.option != ''">
                                {{ items.option }}
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
                            <td class="text-center" v-if="items.max != null">
                                {{ items.max }}
                            </td>
                            <td class="text-center" v-else-if="items.normal != ''">
                                {{ items.normal }}
                            </td>
                            <td class="text-center" v-else>
                                <i>(Empty)</i>
                            </td>
                            <td class="text-center">{{ items.showOn}}</td>
                            <td class="text-center"><i class="text-success"><span class="badge badge-success text-white">New!</span></i></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-success mr-1" @click="editTempParameter(i)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger" @click="removeTempParameter(i)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php $i = 1;
                        foreach ($parameter as $key) : ?>
                            <tr>
                                <td class="text-center"><?= $key['parameterName']; ?></td>
                                <td class="text-center"><?= $key['description']; ?></td>
                                <td class="text-center">
                                    <?php
                                    if ($key['uom'] != '') {
                                        echo $key['uom'];
                                    } else if ($key['uom'] == '' && $key['option'] == '') {
                                        echo '<i>(Empty)</i>';
                                    } else {
                                        echo $key['option'];
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    if ($key['min'] != '') {
                                        echo $key['min'];
                                    } else if ($key['min'] == null && $key['abnormal'] == '') {
                                        echo '<i>(Empty)</i>';
                                    } else {
                                        echo $key['abnormal'];
                                    }
                                    ?>

                                </td>
                                <td class="text-center">
                                    <?php
                                    if ($key['max'] != '') {
                                        echo $key['max'];
                                    } else if ($key['max'] == null && $key['normal'] == '') {
                                        echo '<i>(Empty)</i>';
                                    } else {
                                        echo $key['normal'];
                                    }
                                    ?>
                                </td>
                                <td class="text-center"><?= $key['showOn']; ?></td>
                                <td class="text-center"><?= $key['updatedAt'] != $key['createdAt'] ? '<span class="badge badge-warning text-white">Updated</span>' : '<span class="badge badge-primary text-white">Added</span>' ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-success mr-1" @click="editParameter('<?= $key['parameterId']; ?>')"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger" @click="deleteParameter('<?= $key['parameterId']; ?>')"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card card-main" id="btnSaveSetting" style="display: none;">
            <button class="btn btn-outline-primary text-center w-100" id="btnSaveSetting" @click="btnSaveSetting()"><i class="fa fa-save"></i> Save Changes</button>
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
            computed
        } = Vue;

        const v = Vue.createApp({
            el: '#app',
            setup() {
                var assetData = reactive(<?= json_encode($assetData); ?>);
                var myModal = ref('');
                var checked = ref('');
                var file = ref('');
                var setSch = ref('');
                var schFreq = ref([]);
                var selectedSchWeekly = ref([]);
                var onDays = ref('');
                var assetTagging = <?= count($tagging) > 0 ? "reactive(" . json_encode($tagging[0]) . ")" : "reactive({
                    assetTaggingId: uuidv4(),
                    assetTaggingValue: '',
                    assetTaggingtype: '',
                    description: '',
                })" ?>;
                var valRfid = ref('');
                var valCoordinate = ref('');
                var valUhf = ref('');
                var addTagName = ref('');
                var addTagDesc = ref('');
                var addLocationName = ref('');
                var addLocationLatitude = ref('');
                var addLocationLongitude = ref('');
                var addLocationDesc = ref('');
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
                    showOn: ''
                });
                var tempPhoto = ref('');
                var params = ref([]);
                var submited = ref(false);
                var descJsonValue = ref('');
                var moreDetailAsset = ref(IsJsonString(assetData?.description))

                if (IsJsonString(assetData?.description)) {
                    assetData.descriptionJson = JSON.parse(assetData.description);
                    assetData.description = '';
                } else {
                    assetData.descriptionJson = [];
                }

                var descJson = reactive(assetData.descriptionJson);

                function detailTab() {
                    $('#cardChangeLog').show();
                    $('#cardParameter').hide();
                    $('#cardLocationTag').hide();
                    $('#cardScheduleOpt').hide();
                    $('#cardAssetTagging').hide();
                    $('#btnSaveSetting').hide();
                };

                function parameterTab() {
                    $('#cardChangeLog').hide();
                    $('#cardParameter').hide();
                    $('#cardLocationTag').hide();
                    $('#cardScheduleOpt').hide();
                    $('#cardAssetTagging').hide();
                    $('#btnSaveSetting').hide();
                };

                function settingTab() {
                    $('#cardParameter').show();
                    $('#cardLocationTag').show();
                    $('#cardScheduleOpt').show();
                    $('#cardAssetTagging').show();
                    $('#cardChangeLog').hide();
                    $('#btnSaveSetting').show();
                };

                function modalAddTag() {
                    this.myModal = new coreui.Modal(document.getElementById('modalAddTag'));
                    this.myModal.show();
                };

                function addTag() {
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
                            v.addLocationLatitude = lat;
                            v.addLocationLongitude = long;
                        }
                        marker.on('dragend', onDragEnd);
                    })
                };

                function addTagLocation() {
                    if (this.addLocationName == '') {
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
                        axios.post("<?= base_url('Asset/addTagLocation'); ?>", {
                            assetId: this.assetData.assetId,
                            tagLocationId: uuidv4(),
                            tagLocationName: this.addLocationName,
                            latitude: this.addLocationLatitude,
                            longitude: this.addLocationLongitude,
                            description: this.addLocationDesc
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
                            }
                        })
                    }
                };

                function addTempParameter() {
                    if (this.param.parameterName == '' || this.param.inputType == '' || this.param.showOn == '') {
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
                        if (this.param.showOn != '') {
                            $('.showOn').removeClass('is-invalid');
                        }

                        if (this.param.parameterName == '') {
                            $('.parameter').addClass('is-invalid');
                        }
                        if (this.param.inputType == '') {
                            $('.type').addClass('is-invalid');
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
                        if (this.param.showOn != '') {
                            $('.showOn').removeClass('is-invalid');
                        }

                        this.params.push(this.param);
                        this.param = reactive({
                            parameterId: uuidv4(),
                            sortId: $('#tableParameter tbody tr').length + 2,
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
                    }
                };

                function editTempParameter(index) {
                    this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                    $('#btnAddParam').hide();
                    $('#titleModalAdd').hide();
                    $('#btnUpdateParameter').hide();
                    $('#btnUpdateParam').show();
                    $('#titleModalEdit').show();
                    this.myModal.show();
                    let data = this.params[index];
                    this.param.parameterId = this.params[index].parameterId;
                    this.param.sortId = this.params[index].sortId;
                    this.param.parameterName = this.params[index].parameterName;
                    this.param.photo = this.params[index].photo;
                    this.param.description = this.params[index].description;
                    this.param.uom = this.params[index].uom;
                    this.param.min = this.params[index].min;
                    this.param.max = this.params[index].max;
                    this.param.normal = this.params[index].normal;
                    this.param.abnormal = this.params[index].abnormal;
                    this.param.option = this.params[index].option;
                    this.param.inputType = this.params[index].inputType;
                    this.param.showOn = this.params[index].showOn;
                    this.param.i = index;
                    if (this.param.photo != "") {
                        $('#previewImg').show();
                        $('#preview').append("<img id='imgParam' src='/assets/uploads/img/" + this.param.photo + "' alt='' width='40%' onclick='window.open(this.src)' style='cursor: pointer' data-toggle='tooltip' title='click to preview this image'>");
                    } else if (this.param.photo == "" || this.param.photo == null) {
                        $('#previewImg').hide();
                    }
                    if (v.param.inputType != '') {
                        $('.type').val(v.param.inputType).trigger("change");
                    }
                    if (v.param.normal != '' || v.param.abnormal != '') {
                        $('#normal').val(v.param.normal.split(",")).trigger('change');
                        $('#abnormal').val(v.param.abnormal.split(",")).trigger('change');
                    }
                    if (this.param.showOn != '') {
                        $('#showOn').val(this.param.showOn.split(",")).trigger('change');
                    }
                };

                function updateTempParameter() {
                    if (this.param.parameterName == '' || this.param.inputType == '' || this.param.showOn == '') {
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
                        if (this.param.showOn != '') {
                            $('.showOn').removeClass('is-invalid');
                        }

                        if (this.param.parameterName == '') {
                            $('.parameter').addClass('is-invalid');
                        }
                        if (this.param.inputType == '') {
                            $('.type').addClass('is-invalid');
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
                        if (this.param.showOn != '') {
                            $('.showOn').removeClass('is-invalid');
                        }

                        index = this.param.i;
                        this.params[index] = {
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
                        }
                    }
                };

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
                            var lengthParams = this.params.length;
                            for (let i = 0; i < lengthParams; i++) {
                                // console.log(v.params[i].sortId);
                                if (v.params[i].sortId > v.params[index].sortId) {
                                    v.params[i].sortId = v.params[i].sortId - 1;
                                } else {
                                    v.params[i].sortId = v.params[i].sortId;
                                }
                            }
                            this.params.splice(index, 1);
                            this.param.sortId = this.param.sortId - 1;
                        }
                    })

                };

                function btnSaveSetting() {
                    this.submited = ref(true);

                    if (this.assetData.assetName == "" || this.assetData.assetNumber == "" || this.assetData.assetStatusName == "" || this.assetData.tagId == "" || this.assetData.tagLocationId == "" || (this.assetData.schType == "Daily" && this.assetData.schFrequency == '') || this.statusName == '' || this.assetTagging.assetTaggingValue == '' || this.assetTagging.assetTaggingtype == '' || $('#tableParameter tbody tr').length < 1) {
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

                        if (this.assetData.assetName != '' && $('#assetName').hasClass('is-invalid')) {
                            $('#assetName').removeClass('is-invalid');
                        }
                        if (this.assetData.assetNumber != '' && $('#assetNumber').hasClass('is-invalid')) {
                            $('#assetNumber').removeClass('is-invalid');
                        }
                        if (this.assetData.schType != '' && $('#schType').hasClass('is-invalid')) {
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
                        if (this.assetData.schType == '') {
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

                        // tagging
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
                        if (this.assetData.tagId != "" && $('#tag').hasClass('is-invalid')) {
                            $('#tag').removeClass('is-invalid')
                        }
                        if (this.assetData.tagLocationId != "" && $('#location').hasClass('is-invalid')) {
                            $('#location').removeClass('is-invalid')
                        }

                        // tagging
                        if (this.assetTagging.assetTaggingValue != '' && $('#valRfid').hasClass('is-invalid')) {
                            $('#valRfid').removeClass('is-invalid');
                        }
                        if (this.assetTagging.assetTaggingValue != '' && $('#valCoordinate').hasClass('is-invalid')) {
                            $('#valCoordinate').removeClass('is-invalid');
                        }
                        if (this.assetTagging.assetTaggingValue != '' && $('#valUhf').hasClass('is-invalid')) {
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
                        formdata.append('latitude', this.assetData.latitude);
                        formdata.append('longitude', this.assetData.longitude);
                        formdata.append('schType', this.assetData.schType);
                        formdata.append('schFrequency', this.assetData.schFrequency);
                        formdata.append('schWeekDays', this.assetData.schWeekDays);
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
                        formdata.append('assetTaggingId', this.assetTagging.assetTaggingId);
                        if (v.assetTagging.assetTaggingtype == 'rfid') {
                            formdata.append('assetTaggingValue', v.assetTagging.assetTaggingValue);
                        } else if (v.assetTagging.assetTaggingtype == 'coordinat') {
                            formdata.append('assetTaggingValue', v.assetTagging.assetTaggingValue);
                        } else {
                            formdata.append('assetTaggingValue', v.assetTagging.assetTaggingValue);
                        }
                        formdata.append('assetTaggingType', this.assetTagging.assetTaggingtype);
                        formdata.append('assetTaggingDescription', this.assetTagging.description);
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
                        axios({
                            url: "<?= base_url('Asset/saveSetting'); ?>",
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
                                    text: 'You have successfully updated data.',
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
                            } else if (res.data.status == 'error') {
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
                        text: 'You will delete this data!.',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: '<i class="fa fa-times"></i> Cancel',
                        confirmButtonText: '<i class="fa fa-check"></i> Yes, delete!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.post("<?= base_url('Asset/delete'); ?>", {
                                assetId: this.assetData.assetId
                            }).then(res => {
                                if (res.data.status == 'success') {
                                    swalWithBootstrapButtons.fire({
                                        title: 'Success!',
                                        text: 'You have successfully deleted this data.',
                                        icon: 'success',
                                        allowOutsideClick: false
                                    }).then(okay => {
                                        if (okay) {
                                            swal.fire({
                                                title: 'Please Wait!',
                                                text: 'Redirect..',
                                                onOpen: function() {
                                                    swal.showLoading()
                                                }
                                            })
                                            window.location.href = "<?= base_url('Asset'); ?>"
                                        }
                                    })
                                } else if (res.code == 500) {
                                    const swalWithBootstrapButtons = swal.mixin({
                                        customClass: {
                                            confirmButton: 'btn btn-success mr-1',
                                            cancelButton: 'btn btn-danger'
                                        },
                                        buttonsStyling: false
                                    })
                                    swalWithBootstrapButtons.fire({
                                        title: 'Failed!',
                                        text: 'Bad Request!',
                                        icon: 'error',
                                        allowOutsideClick: false
                                    })
                                }
                            })
                        }
                    })
                };

                function photo() {
                    let fileUploaded = this.$refs.file.files[0];
                    this.param.photo = fileUploaded;
                    // this.param.photo = event.target.files[0];
                };

                function addParameter() {
                    $('#btnAddParam').show();
                    $('#titleModalAdd').show();
                    $('#btnUpdateParameter').hide();
                    $('#btnUpdateParam').hide();
                    $('#titleModalEdit').hide();
                    this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                    this.myModal.show();
                };

                function editParameter($parameterId) {
                    $('#btnAddParam').hide();
                    $('#titleModalAdd').hide();
                    $('#btnUpdateParam').hide();
                    $('#btnUpdateParameter').show();
                    $('#titleModalEdit').show();
                    this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                    this.myModal.show();
                    axios.post("<?= base_url('Asset/editParameter'); ?>", {
                        parameterId: $parameterId
                    }).then(res => {
                        if (res.data.data != '') {
                            let dt = res.data.data[0];
                            this.param.parameterId = $parameterId,
                                this.param.sortId = dt.sortId,
                                this.param.parameterName = dt.parameterName;
                            this.param.photo = dt.photo;
                            this.param.description = dt.description;
                            this.param.uom = dt.uom;
                            this.param.min = dt.min;
                            this.param.max = dt.max;
                            this.param.normal = dt.normal;
                            this.param.abnormal = dt.abnormal;
                            this.param.option = dt.option;
                            this.param.inputType = dt.inputType;
                            this.param.showOn = dt.showOn;
                        }
                    }).then(y => {
                        if (this.param.photo != "") {
                            $('#previewImg').show();
                            $('#preview').append("<img id='imgParam' src='/assets/uploads/img/" + this.param.photo + "' alt='' width='40%' onclick='window.open(this.src)' style='cursor: pointer' data-toggle='tooltip' title='click to preview this image'>");
                        } else if (this.param.photo == "" || this.param.photo == null) {
                            $('#previewImg').hide();
                        }
                        if (v.param.inputType != '') {
                            $('.type').val(v.param.inputType).trigger("change");
                        }
                        if (v.param.normal != '' || v.param.abnormal != '') {
                            $lengthNormal = v.param.normal.split(",").length;
                            $lengthAbnormal = v.param.abnormal.split(",").length;
                            if ($lengthNormal > 0) {
                                var dataNormal = v.param.normal.split(",");
                                for (let index = 0; index < dataNormal.length; index++) {
                                    $('#normal').append(`<option class="optNormal" value="` + dataNormal[index] + `" selected>` + dataNormal[index] + `</option>`);
                                }
                            }
                            if ($lengthAbnormal > 0) {
                                var dataAbnormal = v.param.abnormal.split(",");
                                for (let index = 0; index < dataAbnormal.length; index++) {
                                    $('#abnormal').append(`<option class="optAbnormal" value="` + dataAbnormal[index] + `" selected>` + dataAbnormal[index] + `</option>`);
                                }
                            }
                        }
                        if (this.param.showOn != '') {
                            $('#showOn').val(this.param.showOn.split(",")).trigger('change');
                        }
                    })
                };

                function updateParameter() {
                    if (this.param.parameterName == '' || this.param.inputType == '' || this.param.showOn == '') {
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
                        if (this.param.showOn != '') {
                            $('.showOn').removeClass('is-invalid');
                        }

                        if (this.param.parameterName == '') {
                            $('.parameter').addClass('is-invalid');
                        }
                        if (this.param.inputType == '') {
                            $('.type').addClass('is-invalid');
                        }
                        if (this.param.showOn == '') {
                            $('.showOn').addClass('is-invalid');
                        }
                    } else {
                        let photo = document.querySelector('#photo');
                        let formdata = new FormData();
                        formdata.append('parameterId', this.param.parameterId);
                        formdata.append('assetId', this.assetData.assetId);
                        formdata.append('sortId', this.param.sortId);
                        formdata.append('parameterName', this.param.parameterName);
                        formdata.append('photo', this.param.photo);
                        formdata.append('description', this.param.description);
                        formdata.append('uom', this.param.uom);
                        formdata.append('min', this.param.min);
                        formdata.append('max', this.param.max);
                        formdata.append('normal', this.param.normal.toString());
                        formdata.append('abnormal', this.param.abnormal.toString());
                        formdata.append('option', this.param.option);
                        formdata.append('inputType', this.param.inputType);
                        formdata.append('showOn', this.param.showOn.toString());
                        axios({
                            url: '<?= base_url('Asset/updateParameter'); ?>',
                            method: 'POST',
                            data: formdata,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'multipart/form-data'
                            }
                        }).then(res => {
                            if (res.data.status == 'success') {
                                this.myModal.hide();
                                const swalWithBootstrapButtons = swal.mixin({
                                    customClass: {
                                        confirmButton: 'btn btn-success mr-1',
                                        cancelButton: 'btn btn-danger'
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
                                            text: 'Reoading page..',
                                            onOpen: function() {
                                                swal.showLoading()
                                            }
                                        })
                                        location.reload();
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
                };

                function deleteParameter($parameterId) {
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
                            axios.post("<?= base_url('Asset/deleteParameter'); ?>", {
                                parameterId: $parameterId
                            }).then(res => {
                                if (res.data.status == 'success') {
                                    swalWithBootstrapButtons.fire({
                                        title: 'Success!',
                                        text: 'You have successfully deleted this data.',
                                        icon: 'success',
                                        allowOutsideClick: false
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
                        }
                    })
                };

                function btnCancelModalParam() {
                    this.param.parameterId = uuidv4();
                    this.param.sortId = $('#tableParameter tbody tr').length + 1,
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
                    this.myModal = new coreui.Modal(document.getElementById('importParameterModal'), {
                        backdrop: 'static',
                        keyboard: false
                    });
                    this.myModal.show();
                };

                function insertParam() {
                    axios.post("<?= base_url('Asset/insertParameter'); ?>", {
                        dataParam: importList,
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

                function coordinate() {
                    v.assetTagging.assetTaggingValue = ref('')
                    v.assetTagging.assetTaggingtype = 'coordinat';
                    v.valRfid = ref('');
                    v.valUhf = ref('');
                }

                function rfid() {
                    v.assetTagging.assetTaggingValue = ref('');
                    v.assetTagging.assetTaggingtype = 'rfid';
                    v.valCoordinate = ref('');
                    v.valUhf = ref('');
                }

                function uhf() {
                    v.assetTagging.assetTaggingValue = ref('');
                    v.assetTagging.assetTaggingtype = 'uhf';
                    v.valRfid = ref('');
                    v.valCoordinate = ref('');
                }

                onMounted(() => {
                    let dataAssetName = assetData.assetName;
                    let dataAssetNumber = assetData.assetNumber;
                    let dataAssetDesc = assetData.description;
                    let dataAssetDescJson = JSON.stringify(assetData.descriptionJson);
                    let dataAssetLat = assetData.latitude;
                    let dataAssetLong = assetData.longitude;
                    let dataAssetTag = assetData.tagId;
                    let dataAssetLocation = assetData.tagLocationId;
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
                    let dataAddTagName = addTagName;
                    let dataAddTagDesc = addTagDesc;
                    let dataLocationName = addLocationName;
                    let dataLocationLatitude = addLocationLatitude;
                    let dataLocationLongitude = addLocationLongitude;
                    let dataLocationDesc = addLocationDesc;
                    let dataTempPhoto = tempPhoto;
                    let dataParams = params.value.length;
                    const isEqual = (...objects) => objects.every(obj => JSON.stringify(obj) === JSON.stringify(objects[0]));


                    if (moreDetailAsset.value) document.querySelector("input[name=moreDetailAssetInput]").checked = true;

                    window.addEventListener('beforeunload', function(e) {
                        let checkDescJson = isEqual(dataAssetDescJson, JSON.stringify(assetData.descriptionJson));
                        if (dataAssetName != v.assetData.assetName || dataAssetNumber != v.assetData.assetNumber || dataAssetDesc != v.assetData.description || checkDescJson == false || dataAssetLat != v.assetData.latitude || dataAssetLong != v.assetData.longitude || dataSchType != v.assetData.schType || dataSchDays != v.assetData.schDays || dataSchWeeks != v.assetData.schWeeks || dataSchWeekDays != v.assetData.schWeekDays || dataAssetTag != v.assetData.tagId || dataAssetLocation != v.assetData.tagLocationId || dataParams != v.params.length || dataAssetStatusId != v.assetData.assetStatusId || dataAssetStatusName != v.assetData.assetStatusName || dataTaggingValue != v.assetTagging.assetTaggingValue || dataTaggingType != v.assetTagging.assetTaggingtype) {
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
                    detailTab,
                    parameterTab,
                    settingTab,

                    myModal,
                    checked,
                    assetData,
                    valRfid,
                    valCoordinate,
                    valUhf,
                    rfid,
                    coordinate,
                    uhf,
                    checked,
                    file,
                    setSch,
                    schFreq,
                    selectedSchWeekly,
                    onDays,
                    assetTagging,

                    addTagName,
                    addTagDesc,
                    addLocationName,
                    addLocationLatitude,
                    addLocationLongitude,
                    addLocationDesc,
                    descJson,
                    param,
                    tempPhoto,
                    params,
                    submited,

                    modalAddTag,
                    addTag,
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
                    editParameter,
                    updateParameter,
                    deleteParameter,
                    importParameter,
                    insertParam,
                    addDescJson,
                    descJsonValue,
                    moreDetailAsset,
                    btnCancelModalParam,
                }
            },
        }).mount('#app');

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
                    axios.post("<?= base_url('Asset/sortingParameter'); ?>", {
                        assetId: v.assetData.assetId,
                        data: getParameter
                    })
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

        $(document).ready(function() {
            if (v.assetTagging.assetTaggingtype != '') {
                if (v.assetTagging.assetTaggingtype == 'rfid') {
                    $('#tabRfid').addClass('active');
                    $('#tabCoordinate').removeClass('active');
                    $('#tabUhf').removeClass('active');

                    $('#rfid_tab').addClass('active');
                    $('#coordinate_tab').removeClass('active');
                    $('#uhf_tab').removeClass('active');
                } else if (v.assetTagging.assetTaggingtype == 'coordinat') {
                    $('#tabCoordinate').addClass('active');
                    $('#tabRfid').removeClass('active');
                    $('#tabUhf').removeClass('active');

                    $('#coordinate_tab').addClass('active');
                    $('#rfid_tab').removeClass('active');
                    $('#uhf_tab').removeClass('active');

                    v.valCoordinate = v.assetTagging.assetTaggingValue;
                } else {
                    $('#tabUhf').addClass('active');
                    $('#tabRfid').removeClass('active');
                    $('#tabCoordinate').removeClass('active');

                    $('#uhf_tab').addClass('active');
                    $('#coordinate_tab').removeClass('active');
                    $('#rfid_tab').removeClass('active');
                }
            } else {
                v.assetTagging.assetTaggingtype = '';
            }
        })

        // On change tag, location, operation mode
        $('#location').on('change', function() {
            let data = $(this).val();
            v.assetData.tagLocationId = data;
        })

        $('#tag').on('change', function() {
            let data = $(this).val();
            v.assetData.tagId = data;
        })

        $('#taggingType').on('change', function() {
            let data = $(this).val();
            v.assetTagging.assetTaggingtype = data;
        })

        $(document).ready(function() {
            mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';
            const map = new mapboxgl.Map({
                container: 'mapDetail', // container ID
                style: 'mapbox://styles/mapbox/streets-v11', // style URL
                center: [v.assetData.longitude, v.assetData.latitude], // starting position [lng, lat]
                zoom: 14, // starting zoom
            });
            map.addControl(new mapboxgl.FullscreenControl());
            map.resize();
            const marker = new mapboxgl.Marker()
                .setLngLat([v.assetData.longitude, v.assetData.latitude])
                .addTo(map);
        })

        // map tagging
        $(document).ready(function() {
            mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';

            if (v.assetTagging.assetTaggingtype == 'coordinat' && v.assetTagging.assetTaggingValue != '') {
                var latlong = v.assetTagging.assetTaggingValue.split(",");
                var map = new mapboxgl.Map({
                    container: 'mapTagging', // container ID
                    style: 'mapbox://styles/mapbox/streets-v11', // style URL
                    center: [latlong[1], latlong[0]], // starting position [lng, lat]
                    zoom: 14, // starting zoom
                });
                var marker = new mapboxgl.Marker({
                        draggable: true,
                    })
                    .setLngLat([latlong[1], latlong[0]])
                    .addTo(map);
            } else {
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
            }
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

        $('.latlong').on('change', function() {
            if ($(this).is(':checked')) {
                v.checked = true;
                $('#assetLat').show();
                $('#assetLong').show();

                //map detail
                $('#mapDetail').show();
                $('#mapDetail').addClass('w-100');
                $('.imgMap').removeClass('d-flex align-items-center');
            } else if (!($(this).is(':checked'))) {
                v.checked = false;
                $('#divMap').hide();
                $('#assetLat').hide();
                $('#assetLong').hide();

                //map detail
                $('.imgMap').addClass('d-flex align-items-center');
                $('#mapDetail').hide();
            }
        })

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
        // $(document).ready(function() {
        //     FilePond.registerPlugin(FilePondPluginImageCrop, FilePondPluginImagePreview, FilePondPluginImageEdit, FilePondPluginFileValidateType);
        //     let pond = $('.photo').filepond({
        //         acceptedFileTypes: ['image/png', 'image/jpeg'],
        //         allowImagePreview: true,
        //         imagePreviewMaxHeight: 200,
        //         allowImageCrop: true,
        //         allowMultiple: false,
        //         credits: false,
        //         styleLoadIndicatorPosition: 'center bottom',
        //         styleProgressIndicatorPosition: 'right bottom',
        //         styleButtonRemoveItemPosition: 'left bottom',
        //         styleButtonProcessItemPosition: 'right bottom',
        //     });
        // })

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
                                importList = rsp.data;
                                if (importList.length > 0) {
                                    loadListImport(importList);
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
            var table = $('#tableImport').DataTable({
                "processing": false,
                "serverSide": false,
                "scrollX": false,
                "paging": false,
                "dom": `<"d-flex justify-content-between align-items-center"<i><f>>t`,
                "data": importList,
                "columns": [{
                        "data": "parameterName"
                    },
                    {
                        "data": "description"
                    },
                    {
                        "data": "uom"
                    },
                    {
                        "data": "min"
                    },
                    {
                        "data": "max"
                    },
                    {
                        "data": "inputType"
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
                }],
                "order": [1, 'asc'],
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
                        return `<button class="btn btn-sm btn-primary" onclick="v.modalAddTag()"><i class="fa fa-plus"></i> Add</button>`;
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
                        return `<button class="btn btn-sm btn-primary" onclick="v.modalAddLocation()"><i class="fa fa-plus"></i> Add</button>`;
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
                placeholder: "Select Frequency",
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
        $('.type').on('change', function() {
            let data = $('.type option:selected').val();
            v.param.inputType = data;
        })

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
            if ($(this).val() == 'select') {
                $('.typeSelect').show();
                $('.typeCheckbox').show();
                $('.typeInput').hide();
                v.param.min = null;
                v.param.max = null;
                v.param.uom = '';
            } else if ($(this).val() == 'checkbox') {
                $('.typeCheckbox').show();
                $('.typeSelect').hide();
                $('.typeInput').hide();
                v.param.min = null;
                v.param.max = null;
                v.param.uom = '';
                v.param.normal = '';
                v.param.abnormal = '';
            } else if ($(this).val() == 'input') {
                $('.typeInput').show();
                $('.typeSelect').hide();
                $('.typeCheckbox').hide();
                v.param.option = '';
                v.param.normal = '';
                v.param.abnormal = '';
            } else {
                $('.typeInput').hide();
                $('.typeSelect').hide();
                $('.typeCheckbox').hide();
                v.param.min = null;
                v.param.max = null;
                v.param.uom = '';
                v.param.normal = '';
                v.param.abnormal = '';
                v.param.option = '';
            }
        })

        $(document).ready(function() {
            if (v.assetData.schType != '') {
                $('#setSch').val('Automatic').trigger("change");
            } else {
                $('#setSch').val('Manual').trigger("change");
            }
        })
        // select2 schedule on change
        $('#setSch').on('change', function() {
            v.setSch = $(this).val();
            if ($(this).val() == 'Manual') {
                $('.schType').addClass('hide');
                $('#daily').hide();
                $('#weekly').hide();
                $('#monthly').hide();
            } else if ($(this).val() == 'Automatic') {
                $('.schType').removeClass('hide');
            }
        })

        $('#schType').on('change', function() {
            if ($(this).val() == 'Daily') {
                $('#daily').show();
                $('#weekly').hide();
                $('#monthly').hide();
                v.assetData.schType = $(this).val();

                let schWeekDays = v.assetData.schWeekDays.split(",");
                for (let i = 0; i < schWeekDays.length; i++) {
                    let id = '#schWeekly' + schWeekDays[i];
                    $(id).prop('checked', false);
                    $(id).parent().removeClass('active');
                }
                v.assetData.schWeekDays = ref('');
                v.selectedSchWeekly = ref([]);

                $('#monthlyDays').val("").trigger("change");

                $('#monthlyOn').val("").trigger("change");

                $('#monthlyOnDays').val("").trigger("change");

            } else if ($(this).val() == 'Weekly') {
                $('#weekly').show();
                $('#daily').hide();
                $('#monthly').hide();

                let schFreq = v.assetData.schFrequency;
                let id = '#schFreq' + schFreq;
                $(id).parent().removeClass('active');

                v.assetData.schType = $(this).val();
                v.assetData.schFrequency = "";

                $('#monthlyDays').val("").trigger("change");
                $('#monthlyOn').val("").trigger("change");
                $('#monthlyOnDays').val(v.assetData.schWeekDays.split(",")).trigger("change");


            } else if ($(this).val() == 'Monthly') {
                $('#monthly').show();
                $('#daily').hide();
                $('#weekly').hide();
                $('#monthlyOnDays').val(v.assetData.schWeekDays.split(",")).trigger("change");
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
                $('#monthlyDays').val(v.assetData.schDays.split(",")).trigger("change");
                $('#gridRadios1').click();
            }

            if (v.assetData.schWeekDays != '') {
                $('#monthlyOnDays').val(v.assetData.schWeekDays.split(",")).trigger("change");
            }
        })

        $('input[type="radio"][name="schFreq"]').on('change', function() {
            let val = $(this)[0].dataset.content;
            v.assetData.schFrequency = val;
        })

        // var selectedSchWeekly = [];
        $(document).ready(function() {
            if (v.assetData.schWeekDays != "") {
                let data = v.assetData.schWeekDays.split(",");
                for (let i = 0; i < data.length; i++) {
                    v.selectedSchWeekly.push(data[i]);
                }
            }
        })
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

        $('#monthlyDays').on('change', function() {
            v.assetData.schDays = $(this).val().toString();
        })

        $('#monthlyOn').on('change', function() {
            v.assetData.schWeeks = $(this).val().toString();
        })

        $('#monthlyOnDays').on('change', function() {
            v.assetData.schWeekDays = $(this).val().toString();
        })

        //radio monthly
        $('input[type="radio"][name="gridRadios"]').on('change', function() {
            if ($(this).val() == "days") {
                $('#monthlyOn').val("").trigger("change");
                $('#monthlyOnDays').val("").trigger("change");
                $('.on').attr('readonly', true);
                $('.days').attr('readonly', false);
                $('#days').show();
                $('#on').hide();
                v.assetData.schWeeks = '';
                v.onDays = "days";
            } else if ($(this).val() == "on") {
                $('#monthlyDays').val("").trigger("change");
                $('.days').attr('readonly', true);
                $('.on').attr('readonly', false);
                $('#days').hide();
                $('#on').show();
                v.assetData.schDays = '';
                v.onDays = "on";
            }
        })

        $('input[type="radio"][name="options"]').on('change', function() {
            let id = $(this)[0].id;
            let text = $(this)[0].dataset.content;

            v.assetData.assetStatusId = id;
            v.assetData.assetStatusName = text;
        })
    </script>
    <?= $this->endSection(); ?>