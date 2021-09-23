<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
<link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet" />
<link href='https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
<script src='https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js'></script>
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<?php
$assetTagId = (array_values(array_unique(explode(",", $assetData['tagId']))));
$assetLocationId = (array_values(array_unique(explode(",", $assetData['tagLocationId']))));
$assetStatus = array($assetData['assetStatusId']);
$assetTaggingType = array('rfid', 'coordinat', 'uhf');
?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main card-border-top">
            <div class="card-body">
                <div class="d-flex flex-row justify-content-between align-items-center w-100">
                    <ul class="nav nav-tabs w-100 d-flex flex-row align-items-center" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" id="detail_tab" @click="detailTab()">
                                <svg class="c-icon">
                                    <use xlink:href="/icons/coreui/svg/linear.svg#cil-list-rich"></use>
                                </svg> Detail <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>On this tab, you can read equipment data, edit, and delete the data. And also you can read the log of changes that have occurred to the equipment data.</div>"></i></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#parameter" role="tab" aria-controls="parameter" id="parameter_tab" @click="parameterTab()">
                                <svg class="c-icon">
                                    <use xlink:href="/icons/coreui/svg/linear.svg#cil-timeline"></use>
                                </svg> Parameter <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>On this tab, you can read the parameter data of an equipment</div>"></i></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting" role="tab" aria-controls="setting" id="setting_tab" @click="settingTab()">
                                <svg class="c-icon">
                                    <use xlink:href="/icons/coreui/svg/linear.svg#cil-cog"></use>
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
                            <div class="col-6">
                                <table class="table mt-2">
                                    <tr class="mt-1">
                                        <th width="30%">Asset</th>
                                        <td width="5%">:</td>
                                        <td class="pl-0">
                                            <?= $assetData['assetName']; ?>
                                        </td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th width="30%">Number</th>
                                        <td width="5%">:</td>
                                        <td class="pl-0">
                                            <?= $assetData['assetNumber']; ?>
                                        </td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th width="30%">Description</th>
                                        <td width="5%">:</td>
                                        <td class="pl-0">
                                            <?= $assetData['description']; ?>
                                        </td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th width="30%">Frequency</th>
                                        <td width="5%">:</td>
                                        <td class="pl-0">
                                            <?= $assetData['schType']; ?>
                                        </td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th width="30%">Status</th>
                                        <td width="5%">:</td>
                                        <td class="pl-0">
                                            <?= $assetData['assetStatusName']; ?>
                                        </td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th width="30%">Tag</th>
                                        <td width="5%">:</td>
                                        <td class="pl-0">
                                            <?php
                                            $assetTagValue = (array_values(array_unique(explode(",", $assetData['tagName']))));
                                            $length = count($assetTagValue);
                                            for ($i = 0; $i < $length; $i++) { ?>
                                                <span class="badge badge-dark p-1 mt-1" style="font-size: 13px;">
                                                    <?= $assetTagValue[$i]; ?>
                                                </span>
                                            <?php }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th width="30%">Tag Location</th>
                                        <td width="5%">:</td>
                                        <td class="pl-0">
                                            <?php $assetLocationValue = (array_values(array_unique(explode(",", $assetData['tagLocationName']))));
                                            $length = count($assetLocationValue);
                                            for ($i = 0; $i < $length; $i++) { ?>
                                                <span class="badge badge-dark p-1 mt-1" style="font-size: 13px;">
                                                    <?= $assetLocationValue[$i]; ?>
                                                </span>
                                            <?php }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-6 imgMap" style="border: 1px solid #d8dbe0;">
                                <img src="/img/logo-act.png" alt="Image" class="img-thumbnail mt-1 m-0">
                                <div class="mt-1" id="mapDetail" style="width: 100% !important; height: 170px"></div>
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
                        <div class="row mt-3">
                            <div class="col-6 h-100">
                                <form enctype="multipart/form-data" method="post">
                                    <div class="form-group row d-flex align-items-center">
                                        <div class="col-3">
                                            <label for="assetName">Asset<span class="required">*</span></label>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" class="form-control" id="assetName" name="assetName" v-model="assetName" placeholder="Asset Name" required>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row d-flex align-items-center">
                                        <div class="col-3">
                                            <label for="assetNumber">Number<span class="required">*</span></label>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" class="form-control" id="assetNumber" name="assetNumber" v-model="assetNumber" placeholder="Asset Number" required>
                                            <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row d-flex">
                                        <div class="col-3">
                                            <label for="asssetDesc">Description</label>
                                        </div>
                                        <div class="col-9">
                                            <textarea type="text" class="form-control" id="assetDesc" name="asssetDesc" v-model="assetDesc" rows="8" placeholder="Asset Description"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-6 h-100">
                                <div class="valueDefault w-100">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="/img/logo-act.png" alt="Image" class="img-thumbnail">
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
                                                <label class="col-3" for="parameter">Parameter <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="parameter"></i></label>
                                                <div class="col-9 p-0">
                                                    <input type="text" class="form-control parameter" name="parameter" placeholder="Parameter Name" v-model="param.parameterName" :required="true">
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-3" for="photo">Photo <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="photo"></i></label>
                                                <input type="file" class="p-0 col-9 photo" name="photo" @change="photo" accept="image/png, image/jpeg, image/gif">
                                            </div>
                                            <div style="display: none !important;" class="row mb-3" id="previewImg">
                                                <div class="col-3"></div>
                                                <div class="col-9 p-0" id="preview"></div>
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
                                            <div class="row mb-3 typeInput">
                                                <label class="col-3" for="min">Min <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="min"></i></label>
                                                <input type="number" class="form-control col-9 min" name="min" placeholder="Min Value" v-model="param.min">
                                            </div>
                                            <div class="row mb-3 typeInput">
                                                <label class="col-3" for="max">Max <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="max"></i></label>
                                                <input type="number" class="form-control col-9 max" name="max" placeholder="Max Value" v-model="param.max">
                                            </div>
                                            <div class="row mb-3 typeInput">
                                                <label class="col-3" for="uom">Unit Of Measure <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="uom"></i></label>
                                                <input type="text" class="form-control col-9 uom" name="uom" placeholder="Unit Of Measure" v-model="param.uom">
                                            </div>
                                            <div class="row mb-3 typeSelect" style="display: none;">
                                                <label class="col-3" for="normal">Normal <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="normal"></i></label>
                                                <div class="col-9 p-0">
                                                    <select class="form-control normalAbnormal normal" name="normal" id="normal" multiple>
                                                        <?php foreach ($normal as $key) : ?>
                                                            <option value="<?= $key; ?>"><?= $key; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3 typeSelect" style="display: none;">
                                                <label class="col-3" for="abnormal">Abnormal <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="abnormal"></i></label>
                                                <div class="col-9 p-0">
                                                    <select class="form-control normalAbnormal abnormal" name="abnormal" id="abnormal" multiple>
                                                    <?php foreach ($abnormal as $key) : ?>
                                                            <option value="<?= $key; ?>"><?= $key; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3 typeCheckbox" style="display: none;">
                                                <label class="col-3">Option <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="option"></i></label>
                                                <div class="col-9 p-0">
                                                    <input class="form-control" type="text" name="option" id="option" v-model="param.option" placeholder="Option Value">
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
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" id="cancel" @click="btnCancel()"><i class=" fa fa-times"></i> Cancel</button>
                                <button type="submit" class="btn btn-success" @click="addParam()" id="btnAddParam"><i class="fa fa-plus"></i> Add Parameter</button>
                                <button type="button" class="btn btn-success" @click="updateParameter()" style="display: none;" id="btnUpdateParameter"><i class="fa fa-check"></i> Save Changes</button>
                                <button type="button" class="btn btn-success" @click="updateParam()" style="display: none;" id="btnUpdateParam"><i class="fa fa-check"></i> Save Changes</button>
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
                <div class="modal fade" id="modalAddTag" tabindex="-1" role="dialog" aria-labelledby="modalTagTitle" aria-hidden="true">
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
                <div class="modal fade" id="modalAddLocation" tabindex="-1" role="dialog" aria-labelledby="modalTagTitle" aria-hidden="true">
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
                                                <label for="addTagName">Tag Location Name <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Name for tag"></i></label>
                                                <input id="addTagName" type="text" class="form-control" required v-model="addLocationName" placeholder="Tag Location Name">
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
                                                <label for="addTagDesc">Description <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Description for tag"></i></label>
                                                <textarea id="addTagDesc" class="form-control" required v-model="addLocationDesc" rows="8" placeholder="Description of tag location"></textarea>
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
                                <th>#</th>
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
                                    <td><?= $i; ?></td>
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
                        <div class="d-flex align-items-center">
                            <h5 class="p-0 m-0">
                                <b>
                                    <svg class="c-icon">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-location-pin"></use>
                                    </svg> Asset Location</b>
                            </h5>
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardLocationTag">
                        <div class="d-flex align-items-center">
                            <h5 class="p-0 m-0">
                                <b>
                                    <svg class="c-icon">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-tags"></use>
                                    </svg> Asset Tag</b>
                            </h5>
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
                    <div class="card card-main h-100" id="cardLocationTag">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="p-0 m-0">
                                <b><svg class="c-icon">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-calendar"></use>
                                    </svg> Schedule</b>
                            </h5>
                        </div>
                        <hr>
                        <div>
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group row d-flex align-items-center">
                                    <div class="col-3">
                                        <label for="schType">Frequency<span class="required">*</span></label>
                                    </div>
                                    <div class="col-9">
                                        <select class="form-control" name="schType" id="schType">
                                            <option value="" selected disabled>Select Frequency</option>
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
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" id="schFrequency" v-model="schFrequency" placeholder="1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">/ Day</div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Please choose a factor of 24.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2" id="weekly" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group mb-2">
                                                <div class="w-100">
                                                    <select name="schWeekDays" id="schWeekDays" multiple>
                                                        <option value="Su">Sunday</option>
                                                        <option value="Mo">Monday</option>
                                                        <option value="Tu">Tuesday</option>
                                                        <option value="We">Wednesday</option>
                                                        <option value="Th">Thursday</option>
                                                        <option value="Fr">Friday</option>
                                                        <option value="Sa">Saturday</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2" id="monthly" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-check">
                                                <div class="row mt-2">
                                                    <div class="col-1 d-flex align-items-center">
                                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="days">
                                                        <label class="form-check-label mr-1" for="gridRadios1">
                                                            Days:
                                                        </label>
                                                    </div>
                                                    <div class="col-11">
                                                        <select name="monthly" class="form-control days" id="monthlyDays" multiple readonly>
                                                            <?php for ($i = 1; $i <= 31; $i++) {  ?>
                                                                <option value="<?= $i; ?>"><?= $i; ?></option>
                                                            <?php } ?>
                                                            <option value="Last">Last</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check">
                                                <div class="row mt-2">
                                                    <div class="col-1">
                                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="on">
                                                        <label class="form-check-label mr-1" for="gridRadios2">
                                                            On:
                                                        </label>
                                                    </div>
                                                    <div class="col-11">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <select name="onMonth" class="form-control on mr-1" id="monthlyOn" multiple readonly>
                                                                    <option value="First">First</option>
                                                                    <option value="Second">Second</option>
                                                                    <option value="Third">Third</option>
                                                                    <option value="Fourth">Fourth</option>
                                                                    <option value="Last">Last</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <select name="onDays" class="form-control on mr-1" id="monthlyOnDays" multiple readonly>
                                                                    <option value="Su">Sunday</option>
                                                                    <option value="Mo">Monday</option>
                                                                    <option value="Tu">Tuesday</option>
                                                                    <option value="We">Wednesday</option>
                                                                    <option value="Th">Thursday</option>
                                                                    <option value="Fr">Friday</option>
                                                                    <option value="Sa">Saturday</option>
                                                                </select>
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
                    <div class="card card-main h-100" id="cardLocationTag">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="p-0 m-0">
                                <b>
                                    <svg class="c-icon">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-cog"></use>
                                    </svg> Change Operation Mode</b>
                            </h5>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-3">
                                <label for="operation">Operation<span class="required">*</span></label>
                            </div>
                            <div class="col-9">
                                <select name="operation" id="operation">
                                    <?php foreach ($statusData as $key) : ?>
                                        <option value="<?= $key->assetStatusId; ?>" <?= in_array($key->assetStatusId, $assetStatus) ? 'selected' : ''; ?>><?= $key->assetStatusName ?></option>
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

        <!-- Asset Tagging and Config -->
        <div id="cardAssetTagging" style="display: none;">
            <div class="row">
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardLocationTag">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="p-0 m-0">
                                <b>Asset Tagging</b>
                            </h5>
                        </div>
                        <hr>
                        <form enctype="multipart/form-data" method="post">
                            <div class="form-group row d-flex align-items-center">
                                <div class="col-3">
                                    <label for="asset">Value<span class="required">*</span></label>
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="tagging" name="tagging" placeholder="Tagging Value" v-model="assetTaggingValue" required>
                                    <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                </div>
                            </div>
                            <div class="form-group row d-flex align-items-center">

                                <div class="col-3">
                                    <label for="asset">Type<span class="required">*</span></label>
                                </div>
                                <div class="col-9">
                                    <select name="tagging" id="taggingType">
                                        <?php foreach ($assetTaggingType as $key) : ?>
                                            <option value="<?= $key; ?>"><?= $key; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3">
                                    <label for="asset">Description<span class="required">*</span></label>
                                </div>
                                <div class="col-9">
                                    <textarea class="form-control" id="descTagging" placeholder="Description" v-model="assetTaggingDescription" name="tagging" rows="8"></textarea>
                                    <div class="invalid-feedback">
                                                Field cannot be empty.
                                            </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardLocationTag">
                        <h5><b>Other Config</b></h5>
                        <hr>
                        <table>
                            <tr class="mt-1">
                                <td width="40%">Show Last Value</td>
                                <td>:</td>
                                <td>
                                    <label class="ml-1 c-switch c-switch-pill c-switch-label c-switch-opposite-success m-0">
                                        <input type="checkbox" class="c-switch-input" checked>
                                        <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr class="mt-1">
                                <td>Coordinate</td>
                                <td>:</td>
                                <td>
                                    <label class="ml-1 c-switch c-switch-pill c-switch-label c-switch-opposite-success m-0">
                                        <input type="checkbox" class="c-switch-input latlong" id="latlong">
                                        <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr class="mt-1" id="assetLat" style="display: none;">
                                <td>Latitude</td>
                                <td>:</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Latitude value" v-model="latitude">
                                </td>
                            </tr>
                            <tr class="mt-1" id="assetLong" style="display: none;">
                                <td>Longitude</td>
                                <td>:</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Longitude value" v-model="longitude">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" id="divMap" style="display: none;">
                                    <div class="mt-1" id="map" style="min-width: 100% !important; height: 200px;"></div>
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
                <h5><b>Parameter<span class="required">*</span></b></h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary" @click="importParameter()"><i class="fa fa-upload"></i> Import Parameter</button>
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
                            <td class="text-center"><i class="text-success">(New)</i></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-success mr-1" @click="editParam(i)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" @click="removeParam(i)"><i class="fa fa-trash"></i></button>
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
                                <td class="text-center"><i>(Added)</i></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success mr-1" @click="editParameter('<?= $key['parameterId']; ?>')"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" @click="deleteParameter('<?= $key['parameterId']; ?>')"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card card-main" id="btnSaveSetting" style="display: none;">
            <button class="btn btn-outline-primary text-center w-100" @click="btnSaveSetting()"><i class="fa fa-save"></i> Save Changes</button>
        </div>
    </div>
    <?= $this->endSection(); ?>

    <?= $this->section('customScripts'); ?>
    <!-- Custom Script Js -->
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
    <!-- <script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script> -->
    <script>
        let v = new Vue({
            el: '#app',
            data: {
                assetId: `<?= $assetData['assetId']; ?>`,
                assetName: `<?= $assetData['assetName']; ?>`,
                assetNumber: `<?= $assetData['assetNumber']; ?>`,
                assetDesc: `<?= $assetData['description']; ?>`,
                statusId: '',
                statusName: '',
                assetLocation: [],
                assetTag: [],
                myModal: '',
                checked: '',
                file: '',
                latitude: <?= $assetData['latitude']; ?>,
                longitude: <?= $assetData['longitude']; ?>,
                schType: "<?= $assetData['schType']; ?>",
                schWeeks: "<?= $assetData['schWeeks']; ?>",
                schWeekDays: "<?= $assetData['schWeekDays']; ?>",
                schDays: "<?= $assetData['schDays']; ?>",
                schFrequency: "<?= $assetData['schFrequency']; ?>",
                assetTaggingId: `<?php foreach ($tagging as $key) {
                                        echo $key['assetTaggingId'];
                                    } ?>`,
                assetTaggingValue: `<?php foreach ($tagging as $key) {
                                        echo $key['assetTaggingValue'];
                                    } ?>`,
                assetTaggingType: `<?php foreach ($tagging as $key) {
                                        echo $key['assetTaggingtype'];
                                    } ?>`,
                assetTaggingDescription: `<?php foreach ($tagging as $key) {
                                                echo $key['description'];
                                            } ?>`,
                addTagName: '',
                addTagDesc: '',
                addLocationName: '',
                addLocationLatitude: '',
                addLocationLongitude: '',
                addLocationDesc: '',
                param: {
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
                },
                params: [],
                tableParam: '',
            },
            methods: {
                editParam(index) {
                    this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                    $('#btnAddParam').hide();
                    $('#titleModalAdd').hide();
                    $('#btnUpdateParameter').hide();
                    $('#btnUpdateParam').show();
                    $('#titleModalEdit').show();
                    this.myModal.show();
                    let data = this.params[index];
                    this.param = {
                        parameterId: this.params[index].parameterId,
                        sortId: null,
                        parameterName: this.params[index].parameterName,
                        photo: this.params[index].photo,
                        description: this.params[index].description,
                        uom: this.params[index].uom,
                        min: this.params[index].min,
                        max: this.params[index].max,
                        normal: this.params[index].normal,
                        abnormal: this.params[index].abnormal,
                        option: this.params[index].option,
                        inputType: this.params[index].inputType,
                        showOn: this.params[index].showOn,
                        i: index,
                    }
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
                },
                updateParam() {
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
                    // $('#addParameterModal').modal('hide');
                },
                removeParam(index) {
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
                                .then(res => {
                                    if (res.data.status == 'success') {
                                        swalWithBootstrapButtons.fire({
                                            title: 'Success!',
                                            text: 'You have successfully deleted this data.',
                                            icon: 'success',
                                            allowOutsideClick: false
                                        })
                                    }
                                })
                        }
                    })

                },
                detailTab() {
                    $('#cardChangeLog').show();
                    $('#cardParameter').hide();
                    $('#cardLocationTag').hide();
                    $('#cardScheduleOpt').hide();
                    $('#cardAssetTagging').hide();
                    $('#btnSaveSetting').hide();
                },
                parameterTab() {
                    $('#cardChangeLog').hide();
                    $('#cardParameter').hide();
                    $('#cardLocationTag').hide();
                    $('#cardScheduleOpt').hide();
                    $('#cardAssetTagging').hide();
                    $('#btnSaveSetting').hide();
                },
                settingTab() {
                    $('#cardParameter').show();
                    $('#cardLocationTag').show();
                    $('#cardScheduleOpt').show();
                    $('#cardAssetTagging').show();
                    $('#cardChangeLog').hide();
                    $('#btnSaveSetting').show();
                },
                btnSaveSetting() {
                    let factorFrom = 24;
                    let schFreq = [];
                    for (let index = 1; index <= factorFrom; index++) {
                        if (factorFrom % index == 0) {
                            schFreq.push(index)
                        }
                    }
                    let isFactorOf = schFreq.includes(parseInt(this.schFrequency));

                    if (this.assetName == "" || this.assetNumber == "" || this.schType == '' || this.statusName == '' || this.assetTaggingValue == '' || this.assetTaggingType == '' || this.assetTaggingDescription == '' || $('#tableParameter tbody tr').length < 1) {
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

                            if (this.assetName != '' && $('#assetName').hasClass('is-invalid')) {
                                $('#assetName').removeClass('is-invalid');
                            }
                            if(this.assetNumber != '' && $('#assetNumber').hasClass('is-invalid')){
                                $('#assetNumber').removeClass('is-invalid');
                            }
                            if(this.schType != '' && $('#schType').hasClass('is-invalid')){
                                $('#schType').removeClass('is-invalid');
                            }
                            if(isFactorOf != false && $('#schFrequency').hasClass('is-invalid')){
                                $('#schFrequency').removeClass('is-invalid');
                            }
                            if(this.statusName != '' && $('#operation').hasClass('is-invalid')){
                                $('#operation').removeClass('is-invalid')
                            }
                            if (this.assetTaggingValue != '' && $('#tagging').hasClass('is-invalid')) {
                                $('#tagging').removeClass('is-invalid');
                            }
                            if (this.assetTaggingType != '' && $('#taggingType').hasClass('is-invalid')) {
                                $('#taggingType').removeClass('is-invalid');
                            }
                            if (this.assetTaggingDescription != '' && $('#descTagging').hasClass('is-invalid')) {
                                $('#descTagging').removeClass('is-invalid');
                            }
                            if ($('#tableParameter tbody tr').length >= 1) {
                                $('#cardParameter').removeClass('card-border');
                            }

                            if (this.assetName == '') {
                                $('#assetName').addClass('is-invalid');
                            }
                            if(this.assetNumber == ''){
                                $('#assetNumber').addClass('is-invalid');
                            }
                            if(this.schType == ''){
                                $('#schType').addClass('is-invalid');
                            }
                            if (this.schType == "Daily") {
                                if(isFactorOf == false){
                                    $('#schFrequency').addClass('is-invalid');
                                }
                            }
                            if(this.statusName == ''){
                                $('#operation').addClass('is-invalid')
                            }
                            if (this.assetTaggingValue == '') {
                                $('#tagging').addClass('is-invalid');
                            }
                            if (this.assetTaggingType == '') {
                                $('#taggingType').addClass('is-invalid');
                            }
                            if (this.assetTaggingDescription == '') {
                                $('#descTagging').addClass('is-invalid');
                            }
                            if ($('#tableParameter tbody tr').length < 1) {
                                $('#cardParameter').addClass('card-border');
                            }
                    }else{
                    let formdata = new FormData();
                    // asset
                    formdata.append('assetId', this.assetId);
                    formdata.append('assetName', this.assetName);
                    formdata.append('assetNumber', this.assetNumber);
                    formdata.append('assetDesc', this.assetDesc);
                    formdata.append('latitude', this.latitude);
                    formdata.append('longitude', this.longitude);
                    formdata.append('schType', this.schType);
                    formdata.append('schFrequency', this.schFrequency);
                    formdata.append('schWeekDays', this.schWeekDays);
                    formdata.append('schWeeks', this.schWeeks);
                    formdata.append('schDays', this.schDays);

                    // tag location
                    formdata.append('tagId', this.assetTag);
                    formdata.append('locationId', this.assetLocation);
                    // status
                    formdata.append('assetStatusId', this.statusId);
                    formdata.append('assetStatusName', this.statusName);
                    // tagging
                    formdata.append('assetTaggingId', this.assetTaggingId);
                    formdata.append('assetTaggingValue', this.assetTaggingValue);
                    formdata.append('assetTaggingType', this.assetTaggingType);
                    formdata.append('assetTaggingDescription', this.assetTaggingDescription);
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
                },
                deleteAsset() {
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
                                assetId: this.param.assetId
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
                },
                importParameter() {
                    this.myModal = new coreui.Modal(document.getElementById('importParameterModal'), {
                        backdrop: 'static',
                        keyboard: false
                    });
                    this.myModal.show();
                },
                insertParam() {
                    axios.post("<?= base_url('Asset/insertParameter'); ?>", {
                        dataParam: importList,
                        assetId: this.param.assetId
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
                },
                addParameter() {
                    $('#btnAddParam').show();
                    $('#titleModalAdd').show();
                    $('#btnUpdateParameter').hide();
                    $('#btnUpdateParam').hide();
                    $('#titleModalEdit').hide();
                    this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                    this.myModal.show();
                },
                photo(event) {
                    this.param.photo = event.target.files[0];
                },
                addParam() {
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
                    }else{
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
                        this.param = {
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
                        }
                        $('.type').val('').trigger("change");
                        $('#showOn').val('').trigger('change');
                    }
                },
                editParameter($parameterId) {
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
                        console.log(res.data.data);
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
                            $('#normal').val(v.param.normal.split(",")).trigger('change');
                            $('#abnormal').val(v.param.abnormal.split(",")).trigger('change');
                        }
                        if (this.param.showOn != '') {
                            $('#showOn').val(this.param.showOn.split(",")).trigger('change');
                        }
                    })
                },
                updateParameter() {
                    let photo = document.querySelector('#photo');
                    let formdata = new FormData();
                    console.log(this.file)
                    formdata.append('parameterId', this.param.parameterId);
                    formdata.append('assetId', this.assetId);
                    formdata.append('sortId', this.param.sortId);
                    formdata.append('parameterName', this.param.parameterName);
                    formdata.append('photo', (this.file == '' ? this.param.photo : this.file))
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
                },
                updateParameter2() {
                    axios.post("<?= base_url('Asset/updateParameter'); ?>", {
                        parameterId: this.param.parameterId,
                        assetId: this.param.assetId,
                        sortId: this.param.sortId,
                        parameterName: this.param.parameterName,
                        photo: '',
                        description: this.param.description,
                        uom: this.param.uom,
                        min: this.param.min,
                        max: this.param.max,
                        normal: this.param.normal.toString(),
                        abnormal: this.param.abnormal.toString(),
                        option: this.param.option,
                        inputType: this.param.inputType,
                        showOn: this.param.showOn.toString()
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
                                text: 'You have successfully update this data.',
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
                },
                deleteParameter($parameterId) {
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
                },
                btnCancel() {
                    this.param = {
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
                    }
                    $('#addParameterModal').modal('hide');
                    $('#imgParam').remove();
                    $('.type').val('').trigger("change");
                    $('#showOn').val('').trigger('change');
                    $('#normal').val('').trigger('change');
                    $('#abnormal').val('').trigger('change');
                },
                btnSchedule() {
                    $('#schType').removeAttr("readonly");
                    $('#btnScheduleCancel').show();
                    $('#btnScheduleSave').show();
                    $('#btnSchedule').hide();
                },
                btnScheduleCancel() {
                    $('#schType').attr("readonly", "readonly");
                    $('#btnScheduleCancel').hide();
                    $('#btnScheduleSave').hide();
                    $('#btnSchedule').show();
                },
                modalAddTag() {
                    this.myModal = new coreui.Modal(document.getElementById('modalAddTag'));
                    this.myModal.show();
                },
                addTag() {
                    axios.post('<?= base_url('Asset/addTag'); ?>', {
                        assetId: this.assetId,
                        tagId: uuidv4(),
                        tagName: this.addTagName,
                        description: this.addTagDesc
                    }).then(res => {
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
                    })
                },
                modalAddLocation() {
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
                },
                addTagLocation() {
                    axios.post("<?= base_url('Asset/addTagLocation'); ?>", {
                        assetId: this.assetId,
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
            }
        });

        $(function(){
            $('tbody').sortable({
                cursor: "move",
                handle: '.handle',
                stop: function(event, ui){
                    $(this).find('tr').each(function(i){
                        $(this).find('td:first').text(i+1);
                    })

                    var getParameter = [];
                    $('#tableParam tr').each(function(){
                        var rowDataArray = [];
                        var actualData = $(this).find('td');
                        if (actualData.length > 0) {
                            actualData.each(function(){
                                rowDataArray.push($(this).text());
                            })
                            getParameter.push(rowDataArray);
                        }
                    })
                    axios.post("<?= base_url('Asset/sortingParameter'); ?>", {
                        assetId: v.assetId,
                        data: getParameter
                    })
                }
            });
        })
        // if (window.history && history.pushState) {
        //     addEventListener('load', function() {
        //         history.pushState(null, null, null); // creates new history entry with same URL
        //         addEventListener('popstate', function() {
        //             var stayOnPage = confirm("Would you like to save this changes?");
        //             if (!stayOnPage) {
        //                 history.back()
        //             } else {
        //                 v.btnSaveSetting();
        //             }
        //         });
        //     });
        // }

        window.onbeforeunload = function() {
            return true;
        }
        // Get value selected location, tag, operation mode

        $(document).ready(function() {
            let selected = $('#tag').val();
            v.assetTag = selected;
        })

        $(document).ready(function() {
            let selected = $('#location').val();
            v.assetLocation = selected;
        })

        $(document).ready(function() {
            let selected = $('#operation').val();
            let text = $('#operation :selected').text();
            v.statusId = selected;
            v.statusName = text;
        })

        $(document).ready(function() {
            if (v.assetTaggingType != '') {
                $('#taggingType').val(v.assetTaggingType).trigger("change");
            }else{
                let selected = $('#taggingType').val();
                v.assetTaggingType = selected;
            }
        })

        // On change tag, location, operation mode
        $('#location').on('change', function() {
            let data = $(this).val();
            v.assetLocation = data;
        })

        $('#tag').on('change', function() {
            let data = $(this).val();
            v.assetTag = data;
        })

        $('#operation').on('change', function() {
            let data = $(this).val();
            let text = $('#operation :selected').text();
            v.statusId = data;
            v.statusName = text;
        })

        $('#taggingType').on('change', function() {
            let data = $(this).val();
            v.assetTaggingType = data;
        })

        $('.latlong').on('change', function() {
            if ($(this).is(':checked')) {
                mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';
                const map = new mapboxgl.Map({
                    container: 'mapDetail', // container ID
                    style: 'mapbox://styles/mapbox/streets-v11', // style URL
                    center: [109.005913, -7.727989], // starting position [lng, lat]
                    zoom: 14, // starting zoom
                });
                map.addControl(new mapboxgl.FullscreenControl());
                map.resize();
                const marker = new mapboxgl.Marker()
                    .setLngLat([109.005913, -7.727989])
                    .addTo(map);

                $('#mapDetail').show();
                $('#mapDetail').addClass('w-100');
                $('.imgMap').removeClass('d-flex align-items-center');
            } else if (!($(this).is(':checked'))) {
                $('.imgMap').addClass('d-flex align-items-center');
                $('#mapDetail').hide();
            } else {
                swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Failed Load Map'
                })
            }
        })

        $('.latlong').on('change', function() {
            if ($(this).is(':checked')) {
                $('#assetLat').show();
                $('#assetLong').show();
                
                if (v.latitude == '' && v.longitude == '') {
                    mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';
                    const map = new mapboxgl.Map({
                        container: 'map', // container ID
                        style: 'mapbox://styles/mapbox/streets-v11', // style URL
                        center: [109.005913, -7.727989], // starting position [lng, lat]
                        zoom: 14, // starting zoom
                    });
                    map.addControl(new mapboxgl.FullscreenControl());
                    map.resize();
                    const marker = new mapboxgl.Marker({
                            draggable: true,
                        })
                        .setLngLat([109.005913, -7.727989])
                        .addTo(map);

                    function onDragEnd(params) {
                        const lnglat = marker.getLngLat();
                        // coordinates.style.display = 'block';
                        let lat = lnglat.lat;
                        let long = lnglat.lng;
                        v.latitude = lat;
                        v.longitude = long;
                    }
                    marker.on('dragend', onDragEnd);
                } else {
                    mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';
                    const map = new mapboxgl.Map({
                        container: 'map', // container ID
                        style: 'mapbox://styles/mapbox/streets-v11', // style URL
                        center: [v.longitude, v.latitude], // starting position [lng, lat]
                        zoom: 14, // starting zoom
                    });
                    map.addControl(new mapboxgl.FullscreenControl());
                    map.resize();
                    const marker = new mapboxgl.Marker({
                            draggable: true,
                        })
                        .setLngLat([v.longitude, v.latitude])
                        .addTo(map);
                        function onDragEnd(params) {
                            const lnglat = marker.getLngLat();
                            // coordinates.style.display = 'block';
                            let lat = lnglat.lat;
                            let long = lnglat.lng;
                            v.latitude = lat;
                            v.longitude = long;
                        }
                marker.on('dragend', onDragEnd);
                }

                $('#divMap').show();
                $('#map').addClass('w-100');
            } else if (!($(this).is(':checked'))) {
                $('#divMap').hide();
                $('#assetLat').hide();
                $('#assetLong').hide();
            } else {
                swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Failed Load Map'
                })
            }
        })

       

        $(document).ready(function() {
            FilePond.registerPlugin(FilePondPluginImageCrop, FilePondPluginImagePreview, FilePondPluginImageEdit, FilePondPluginFileValidateType);
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

        $(document).ready(function() {
            FilePond.registerPlugin(FilePondPluginImageCrop, FilePondPluginImagePreview, FilePondPluginImageEdit, FilePondPluginFileValidateType);
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


        // $('#tableParam tbody tr td:last-child').addClass('cursor-move');

        // // select2 edit asset
        $(document).ready(function() {
            $('#tag').select2({
                theme: 'coreui',
                placeholder: "Select Tag",
                escapeMarkup: function(markup) {
                    return markup;
                },
                language: {
                    noResults: function() {
                        return `<button class="btn btn-sm btn-primary" onclick="v.modalAddTag()">Add</button>`;
                    }
                }
            });
        });

        $(document).ready(function() {
            $('#location').select2({
                theme: 'coreui',
                placeholder: "Select Tag Location",
                escapeMarkup: function(markup) {
                    return markup;
                },
                language: {
                    noResults: function() {
                        return `<button class="btn btn-sm btn-primary" onclick="v.modalAddLocation()">Add</button>`;
                    }
                }
            });
        });
        
        // select2 schedule
        $(document).ready(function() {
            $('#schType').select2({
                theme: 'coreui',
                placeholder: "Select Frequency",
            });

            $('#schWeekDays').select2({
                theme: 'coreui',
                placeholder: 'Select Days'
            })

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
        });

        // select2 add parameter
        $(document).ready(function() {
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

        $(document).ready(function() {
            $('#operation').select2({
                theme: 'coreui',
                tags: true,
                createTag: function(params) {
                    var term = $.trim(params.term);

                    if (term === '') {
                        return null;
                    }
                    return {
                        id: uuidv4(),
                        text: term,
                        newTag: true // add additional parameters
                    }
                }
            })
        })


        $(document).ready(function() {
            $('#taggingType').select2({
                theme: 'coreui',
                placeholder: 'Select Tagging Type'
            })
        })

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

        $('.typeEdit').on('change', function() {
            if ($(this).val() == 'select') {
                $('.typeSelectEdit').show();
                $('.typeInputEdit').hide();
                $('.typeCheckboxEdit').hide();
            } else if ($(this).val() == 'checkbox') {
                $('.typeCheckboxEdit').show();
                $('.typeSelectEdit').hide();
                $('.typeInputEdit').hide();
            } else {
                $('.typeInputEdit').show();
                $('.typeSelectEdit').hide();
                $('.typeCheckboxEdit').hide();
            }
        })

        $(document).ready(function(){
            if (v.schType != '') {
                $('#schType').val(v.schType).trigger("change");
            }

            if (v.schFrequency != null || v.schFrequency != '') {
                $('#schFrequency').val(v.schFrequency);
            }

            if (v.schWeeks != '') {
                $('#monthlyOn').val(v.schWeeks.split(",")).trigger("change");
                $('#gridRadios2').click();
            }

            if (v.schDays != '') {
                $('#monthlyDays').val(v.schDays.split(",")).trigger("change");
                $('#gridRadios1').click();
            }

            if (v.schWeekDays != '') {
                $('#monthlyOnDays').val(v.schWeekDays.split(",")).trigger("change");
                $('#schWeekDays').val(v.schWeekDays.split(",")).trigger("change");
            }
        })

        $('#schType').on('change', function() {
            if ($(this).val() == 'Daily') {
                $('#daily').show();
                $('#weekly').hide();
                $('#monthly').hide();
                v.schType = $(this).val();

                $('#schWeekDays').each(function(){
                    $(this).select2('destroy').val("").select2({
                        theme: 'coreui',
                        placeholder: 'Select Days'
                    });
                });

                $('#monthlyDays').each(function(){
                    $(this).select2('destroy').val("").select2({
                        theme: 'coreui',
                        placeholder: 'Select Days'
                    });
                });

                $('#monthlyOn').each(function(){
                    $(this).select2('destroy').val("").select2({
                        theme: 'coreui',
                        placeholder: 'Select Item'
                    });
                });

                $('#monthlyOnDays').each(function(){
                    $(this).select2('destroy').val("").select2({
                        theme: 'coreui',
                        placeholder: 'Select All Days'
                    });
                });

            } else if ($(this).val() == 'Weekly') {
                $('#weekly').show();
                $('#daily').hide();
                $('#monthly').hide();
                v.schType = $(this).val();
                v.schFrequency = null;
                $('#monthlyDays').each(function(){
                    $(this).select2('destroy').val("").select2({
                        theme: 'coreui',
                        placeholder: 'Select Days'
                    });
                });

                $('#monthlyOn').each(function(){
                    $(this).select2('destroy').val("").select2({
                        theme: 'coreui',
                        placeholder: 'Select Item'
                    });
                });

                $('#monthlyOnDays').each(function(){
                    $(this).select2('destroy').val("").select2({
                        theme: 'coreui',
                        placeholder: 'Select All Days'
                    });
                });


            } else if ($(this).val() == 'Monthly') {
                $('#schWeekDays').each(function(){
                    $(this).select2('destroy').val("").select2({
                        theme: 'coreui',
                        placeholder: 'Select Days'
                    });
                });

                $('#monthly').show();
                $('#daily').hide();
                $('#weekly').hide();
                v.schType = $(this).val();
                v.schFrequency = null;
            }
        })

        $('#schWeekDays').on('change', function(){
            v.schWeekDays = $(this).val().toString();
        })

        $('#monthlyDays').on('change', function(){
            v.schDays = $(this).val().toString();
        })

        $('#monthlyOn').on('change', function(){
            v.schWeeks = $(this).val().toString();
        })

        $('#monthlyOnDays').on('change', function(){
            v.schWeekDays = $(this).val().toString();
        })

        //radio monthly
        $('input[type="radio"][name="gridRadios"]').on('change', function() {
            if ($(this).val() == "days") {
                $('#monthlyOn').each(function(){
                    $(this).select2('destroy').val("").select2({
                        theme: 'coreui',
                        placeholder: 'Select Item'
                    });
                });
                $('#monthlyOnDays').each(function(){
                    $(this).select2('destroy').val("").select2({
                        theme: 'coreui',
                        placeholder: 'Select All Days'
                    });
                });
                $('.on').attr('readonly', true);
                $('.days').attr('readonly', false);
                v.schWeeks = '';
                
            } else if ($(this).val() == "on") {
                $('#monthlyDays').each(function(){
                    $(this).select2('destroy').val("").select2({
                        theme: 'coreui',
                        placeholder: 'Select Days'
                    });
                });
                $('.days').attr('readonly', true);
                $('.on').attr('readonly', false);
                v.schDays = '';
            }
        })
    </script>
    <?= $this->endSection(); ?>