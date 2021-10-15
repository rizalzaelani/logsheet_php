<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<?php
$assetTagId = (array_values(array_unique(explode(",", $assetData['tagId']))));
$assetLocationId = (array_values(array_unique(explode(",", $assetData['tagLocationId']))));
$assetStatus = array($assetData['assetStatusId']);
$assetTaggingType = array('rfid', 'coordinate', 'uhf');
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
                                </svg> Detail <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>On this tab, you can read equipment data, edit, and delete the data. And also you can read the log of changes that have occurred to the equipment data.</div>"></i></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#parameter" role="tab" aria-controls="parameter" id="parameter_tab" @click="parameterTab()">
                                <svg class="c-icon">
                                    <use xlink:href="/icons/coreui/svg/linear.svg#cil-timeline"></use>
                                </svg> Parameter <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>On this tab, you can read the parameter data of an equipment</div>"></i></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting" role="tab" aria-controls="setting" id="setting_tab" @click="settingTab()">
                                <svg class="c-icon">
                                    <use xlink:href="/icons/coreui/svg/linear.svg#cil-cog"></use>
                                </svg> Setting <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>In this tab, you can change the settings on an equipment</div>"></i></a></li>
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
                                            <td class="text-center"><i class="fa fa-bars"></i></td>
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
                                            <label for="asset">Asset</label>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" class="form-control" id="asset" name="asset" v-model="assetName" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row d-flex align-items-center">
                                        <div class="col-3">
                                            <label for="asset">Number</label>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="asset" v-model="assetNumber" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row d-flex">
                                        <div class="col-3">
                                            <label for="asset">Description</label>
                                        </div>
                                        <div class="col-9">
                                            <textarea type="text" class="form-control" name="asset" v-model="assetDesc" rows="8" readonly></textarea>
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-end">
                                    <div class="form-group row mb-0">
                                        <div class="col-md d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm mr-1 btn-outline-primary" type="button" @click="editDetail()" id="btnEdit"><i class="fa fa-edit mr-1"></i>Edit</button>
                                            <div style="display: none;" id="btnCancelEdit">
                                                <button class="btn btn-sm mr-1 d-flex align-items-center justify-content-between btn-outline-primary" type="button" @click="cancelEdit()"><i class="fa fa-times mr-1"></i> Cancel</button>
                                            </div>
                                            <div id="btnDelete">
                                                <button class="btn btn-sm mr-1 d-flex align-items-center justify-content-between btn-outline-primary" type="button" @click="deleteAsset()"><i class="fa fa-trash mr-1"></i>Delete</button>
                                            </div>
                                            <div style="display: none;" id="btnUpdate">
                                                <button class="btn btn-sm mr-1 d-flex align-items-center justify-content-between btn-outline-primary" type="button" @click="updateAsset()"><i class="fa fa-check mr-1"></i> Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 h-100" style="border: 1px solid #d8dbe0;">
                                <div class="valueDefault mt-2 w-100">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="/img/logo-act.png" alt="Image" class="img-thumbnail">
                                    </div>
                                </div>
                                <div style="display: none;" class="input">
                                    <div class="d-flex align-items-center">
                                        <input type="file" class="filepond mt-2 mb-2 w-100" name="filepond" id="logo" accept="image/png, image/jpeg, image/gif" />
                                    </div>
                                </div>
                                <div class="mt-1" id="map" style="min-width: 100% !important; height: 200px;"></div>
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
                                                <label class="col-3" for="parameter">Parameter <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="parameter"></i></label>
                                                <input type="text" class="form-control col-9 parameter" name="parameter" placeholder="Parameter Name" v-model="param.parameterName" :required="true">
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-3" for="photo">Photo <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="photo"></i></label>
                                                <input type="file" class="p-0 col-9 photo" name="photo" @change="photo" accept="image/png, image/jpeg, image/gif">
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-3" for="type">Type <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="type"></i></label>
                                                <div class="col-9 p-0">
                                                    <select class="form-control type" name="type" placeholder="Select Type">
                                                        <option value="" selected disabled>Select Type</option>
                                                        <option value="input">Input</option>
                                                        <option value="select">Select</option>
                                                        <option value="checkbox">Checkbox</option>
                                                        <option value="textarea">Free Text</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3 typeInput">
                                                <label class="col-3" for="min">Min <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="min"></i></label>
                                                <input type="text" class="form-control col-9 min" name="min" placeholder="Min Value" v-model="param.min">
                                            </div>
                                            <div class="row mb-3 typeInput">
                                                <label class="col-3" for="max">Max <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="max"></i></label>
                                                <input type="text" class="form-control col-9 max" name="max" placeholder="Max Value" v-model="param.max">
                                            </div>
                                            <div class="row mb-3 typeInput">
                                                <label class="col-3" for="uom">Unit Of Measure <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="uom"></i></label>
                                                <input type="text" class="form-control col-9 uom" name="uom" placeholder="Unit Of Measure" v-model="param.uom">
                                            </div>
                                            <div class="row mb-3 typeSelect" style="display: none;">
                                                <label class="col-3" for="normal">Normal <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="normal"></i></label>
                                                <div class="col-9 p-0">
                                                    <select class="form-control normalAbnormal normal" name="normal" id="normal" multiple>
                                                        <option value="item 1">item 1</option>
                                                        <option value="item 2">item 2</option>
                                                        <option value="item 3">item 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3 typeSelect" style="display: none;">
                                                <label class="col-3" for="abnormal">Abnormal <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="abnormal"></i></label>
                                                <div class="col-9 p-0">
                                                    <select class="form-control normalAbnormal abnormal" name="abnormal" id="abnormal" multiple>
                                                        <option value="item 1">item 1</option>
                                                        <option value="item 2">item 2</option>
                                                        <option value="item 3">item 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3 typeCheckbox" style="display: none;">
                                                <label class="col-3">Option <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="option"></i></label>
                                                <div class="col-9 p-0">
                                                    <input class="form-control" type="text" name="option" id="option" v-model="param.option" placeholder="Option Value">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-3" for="showOn">Parameter Status <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="showOn"></i></label>
                                                <div class="col-9 p-0">
                                                    <select class="form-control showOn" name="showOn" id="showOn" multiple>
                                                        <option value="Running">Running</option>
                                                        <option value="Standby">Standby</option>
                                                        <option value="Repair">Repair</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-3" for="description">Description <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="description"></i></label>
                                                <textarea class="form-control col-9 description" rows="9" name="description" placeholder="Description of parameter" v-model="param.description"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancel" @click="btnCancel()"><i class=" fa fa-times"></i> Cancel</button>
                                <button type="submit" class="btn btn-success" @click="addParam()" id="btnAddParam"><i class="fa fa-plus"></i> Add Parameter</button>
                                <button type="button" class="btn btn-success" @click="updateParameter()" style="display: none;" id="btnUpdateParam"><i class="fa fa-check"></i> Save Changes</button>
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
                                            <label for="addTagName">Tag Name <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Name for tag"></i></label>
                                            <input id="addTagName" type="text" class="form-control" required v-model="addTagName" placeholder="Tag Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="addTagDesc">Description <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Description for tag"></i></label>
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
                                                <label for="addTagName">Tag Location Name <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Name for tag"></i></label>
                                                <input id="addTagName" type="text" class="form-control" required v-model="addLocationName" placeholder="Tag Location Name">
                                            </div>
                                            <div class="mb-3">
                                                <label for="latitude">Latitude <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Latitude"></i></label>
                                                <input id="latitude" type="text" class="form-control" required v-model="addLocationLatitude" placeholder="Location Latitude">
                                            </div>
                                            <div class="mb-3">
                                                <label for="longitude">Longitude <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Longitude"></i></label>
                                                <input id="longitude" type="text" class="form-control" required v-model="addLocationLongitude" placeholder="Location Longitude">
                                            </div>
                                            <div class="mb-3">
                                                <label for="addTagDesc">Description <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Description for tag"></i></label>
                                                <textarea id="addTagDesc" class="form-control" required v-model="addLocationDesc" rows="8" placeholder="Description of tag location"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <div id="mapAddLocation" style="min-width: 100% !important; height: 350px;"></div>
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
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="p-0 m-0">
                                <b>Asset Location</b>
                            </h5>
                            <div class="d-flex align-items-center justify-content-between">
                                <button class="btn btn-sm btn-outline-primary" id="btnLocation" @click="btnLocation()">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary mr-1" id="btnLocationCancel" @click="btnLocationCancel()">
                                    <i class="fa fa-times"></i>
                                    Cancel
                                </button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary" id="btnLocationSave" @click="btnLocationSave()">
                                    <i class="fa fa-save"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="location">Location</label>
                            <div class="col-md-9">
                                <select class="form-control" name="location" id="location" multiple="multiple" readonly="readonly">
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
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="p-0 m-0">
                                <b>Asset Tag</b>
                            </h5>
                            <div class="d-flex align-items-center justify-content-between">
                                <button class="btn btn-sm btn-outline-primary" id="btnTag" @click="btnTag()">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary mr-1" id="btnTagCancel" @click="btnTagCancel()">
                                    <i class="fa fa-times"></i>
                                    Cancel
                                </button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary" id="btnTagSave" @click="btnTagSave()">
                                    <i class="fa fa-save"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <?php
                            ?>
                            <label class="col-md-3 col-form-label" for="tag">Tag</label>
                            <div class="col-md-9">
                                <select class="form-control" name="tag" id="tag" multiple readonly>
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
                                <b>Schedule</b>
                            </h5>
                            <div>
                                <button class="btn btn-sm btn-outline-primary" id="btnSchedule" @click="btnSchedule()">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary" id="btnScheduleCancel" @click="btnScheduleCancel()">
                                    <i class="fa fa-times"></i>
                                    Cancel
                                </button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary" id="btnScheduleSave" @click="btnScheduleSaave()">
                                    <i class="fa fa-save"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group row d-flex align-items-center">
                                    <div class="col-3">
                                        <label for="schType">Frequency</label>
                                    </div>
                                    <div class="col-9">
                                        <select class="form-control" name="schType" id="schType" readonly="readonly">
                                            <option value="" selected disabled>Select Frequency</option>
                                            <option value="Daily">Daily</option>
                                            <option value="Weekly">Weekly</option>
                                            <option value="Monthly">Monthly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3" id="daily" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <label for="frequency">Recur every</label>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" id="frequency" placeholder="1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">/ Day</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2" id="weekly" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <label for="frequency">Recur every</label>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" id="frequency" placeholder="1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">/ Weeks</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-check-inline mr-1">
                                                <input class="form-check-input" id="sunday" type="checkbox" value="Sunday">
                                                <label class="form-check-label" for="sunday">Sunday</label>
                                            </div>
                                            <div class="form-check form-check-inline mr-1">
                                                <input class="form-check-input" id="monday" type="checkbox" value="Monday">
                                                <label class="form-check-label" for="monday">Monday</label>
                                            </div>
                                            <div class="form-check form-check-inline mr-1">
                                                <input class="form-check-input" id="tuesday" type="checkbox" value="Tuesday">
                                                <label class="form-check-label" for="tuesday">Tuesday</label>
                                            </div>
                                            <div class="form-check form-check-inline mr-1">
                                                <input class="form-check-input" id="wednesday" type="checkbox" value="Wednesday">
                                                <label class="form-check-label" for="wednesday">Wednesday</label>
                                            </div>
                                            <div class="form-check form-check-inline mr-1">
                                                <input class="form-check-input" id="thursday" type="checkbox" value="Thursday">
                                                <label class="form-check-label" for="thursday">Thursday</label>
                                            </div>
                                            <div class="form-check form-check-inline mr-1">
                                                <input class="form-check-input" id="friday" type="checkbox" value="Friday">
                                                <label class="form-check-label" for="friday">Friday</label>
                                            </div>
                                            <div class="form-check form-check-inline mr-1">
                                                <input class="form-check-input" id="saturday" type="checkbox" value="Saturday">
                                                <label class="form-check-label" for="saturday">Saturday</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2" id="monthly" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <label for="monthly">Months</label>
                                            <select name="monthly" class="form-control monthly">
                                                <option value="" selected disabled>Select Month</option>
                                                <option value="January">January</option>
                                                <option value="February">February</option>
                                                <option value="March">March</option>
                                                <option value="April">April</option>
                                                <option value="May">May</option>
                                                <option value="June">June</option>
                                                <option value="July">July</option>
                                                <option value="August">August</option>
                                                <option value="September">September</option>
                                                <option value="October">October</option>
                                                <option value="November">November</option>
                                                <option value="December">December</option>
                                            </select>
                                        </div>
                                    </div>
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
                                                        <select name="monthly" class="form-control days" multiple>
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
                                                    <div class="col-1 d-flex align-items-center">
                                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="on">
                                                        <label class="form-check-label mr-1" for="gridRadios2">
                                                            On:
                                                        </label>
                                                    </div>
                                                    <div class="col-11 d-flex justify-content-between align-iems-center">
                                                        <select name="onMonth" class="form-control on onMonth mr-1" id="onMonth">
                                                            <option value="First">First</option>
                                                            <option value="Second">Second</option>
                                                            <option value="Third">Third</option>
                                                            <option value="Fourth">Fourth</option>
                                                            <option value="Last">Last</option>
                                                        </select>
                                                        <select name="onDays" class="form-control on onDays mr-1" multiple>
                                                            <option value="all">Select all days</option>
                                                            <option value="Sunday">Sunday</option>
                                                            <option value="Monday">Monday</option>
                                                            <option value="Tuesday">Tuesday</option>
                                                            <option value="Wednesday">Wednesday</option>
                                                            <option value="Thursday">Thursday</option>
                                                            <option value="Friday">Friday</option>
                                                            <option value="Saturday">Saturday</option>
                                                        </select>
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
                                <b>Change Operation Mode</b>
                            </h5>
                            <div>
                                <button class="btn btn-sm btn-outline-primary" id="btnOperation" @click="btnOperation()">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary" id="btnOperationCancel" @click="btnOperationCancel">
                                    <i class="fa fa-times"></i>
                                    Cancel
                                </button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary" id="btnOperationSave" @click="btnOperationSave()">
                                    <i class="fa fa-save"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-3">
                                <label for="operation">Operation</label>
                            </div>
                            <div class="col-9">
                                <select name="operation" id="operation" readonly="readonly">
                                    <?php foreach ($statusData as $key) : ?>
                                        <option value="<?= $key->assetStatusId; ?>" <?= in_array($key->assetStatusId, $assetStatus) ? 'selected' : ''; ?>><?= $key->assetStatusName ?></option>
                                    <?php endforeach; ?>
                                </select>
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
                            <div>
                                <button class="btn btn-sm btn-outline-primary" id="btnTagging" @click="btnTagging()">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary" id="btnCancelTagging" @click="btnCancelTagging()">
                                    <i class="fa fa-times"></i>
                                    Cancel
                                </button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary" id="btnSaveTagging" @click="btnSaveTagging()">
                                    <i class="fa fa-save"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                        <hr>
                        <form enctype="multipart/form-data" method="post">
                            <div class="form-group row d-flex align-items-center">
                                <div class="col-3">
                                    <label for="asset">Value</label>
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="tagging" name="tagging" placeholder="Tagging Value" v-model="assetTaggingValue" readonly>
                                </div>
                            </div>
                            <div class="form-group row d-flex align-items-center">

                                <div class="col-3">
                                    <label for="asset">Type</label>
                                </div>
                                <div class="col-9">
                                    <select name="tagging" id="taggingType" readonly="readonly">
                                        <?php foreach ($tagging as $key) : ?>
                                            <option value="<?= $key['assetTaggingtype']; ?>" selected><?= $key['assetTaggingtype']; ?></option>
                                        <?php endforeach; ?>
                                        <?php foreach ($assetTaggingType as $key) : ?>
                                            <option value="<?= $key; ?>"><?= $key; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3">
                                    <label for="asset">Description</label>
                                </div>
                                <div class="col-9">
                                    <textarea class="form-control" id="descTagging" placeholder="Description" v-model="assetTaggingDescription" name="tagging" readonly rows="8"></textarea>
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
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- parameter -->
        <div class="card card-main" id="cardParameter" style="display: none;">
            <div class="mt-2 d-flex justify-content-between align-items-center">
                <h5><b>Parameter</b></h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary" @click="importParameter()"><i class="fa fa-upload"></i> Import Parameter</button>
                    <button class="btn btn-sm btn-outline-primary" @click="addParameter()"><i class="fa fa-plus"></i> Add Parameter</button>
                </div>
            </div>
            <div class="table-responsive mt-2">
                <table class="table dt-responsive table-hover w-100 display" id="tableParameter">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Parameter</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">UoM</th>
                            <th class="text-center">Min</th>
                            <th class="text-center">Max</th>
                            <th class="text-center">Show On</th>
                            <th width="10%" class="text-center" style="border-top-right-radius: 5px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($parameter as $key) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
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
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success mr-1" @click="editParameter('<?= $key['parameterId']; ?>')"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" @click="deleteParameter('<?= $key['parameterId']; ?>')"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?= $this->endSection(); ?>

    <?= $this->section('customScripts'); ?>
    <!-- Custom Script Js -->
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
    <script>
        let v = new Vue({
            el: '#app',
            data: {
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
                assetTaggingId: `<?php foreach ($tagging as $key) {
                                        echo $key['assetTaggingId'];
                                    } ?>`,
                assetTaggingValue: `<?php foreach ($tagging as $key) {
                                        echo $key['assetTaggingValue'];
                                    } ?>`,
                assetTaggingType: '',
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
                    assetId: `<?= $assetData['assetId']; ?>`,
                    sortId: null,
                    parameterName: '',
                    photo: '',
                    description: '',
                    uom: '',
                    min: null,
                    max: null,
                    normal: [],
                    abnormal: [],
                    option: '',
                    inputType: '',
                    showOn: []
                }
            },
            mounted() {
                this.getDataParameter();
            },
            methods: {
                detailTab() {
                    $('#cardChangeLog').show();
                    $('#cardParameter').hide();
                    $('#cardLocationTag').hide();
                    $('#cardScheduleOpt').hide();
                    $('#cardAssetTagging').hide();
                },
                parameterTab() {
                    $('#cardChangeLog').hide();
                    $('#cardParameter').hide();
                    $('#cardLocationTag').hide();
                    $('#cardScheduleOpt').hide();
                    $('#cardAssetTagging').hide();
                },
                settingTab() {
                    $('#cardParameter').show();
                    $('#cardLocationTag').show();
                    $('#cardScheduleOpt').show();
                    $('#cardAssetTagging').show();
                    $('#cardChangeLog').hide();
                },
                getDataParameter() {
                    $('#tableParam').DataTable({
                        dom: 't',
                        paging: false,
                        rowReorder: {
                            selector: 'td:last-child'
                        },
                    });
                },
                updateAsset() {
                    axios.post("<?= base_url('Asset/update') ?>", {
                        assetId: this.param.assetId,
                        assetName: this.assetName,
                        assetNumber: this.assetNumber,
                        description: this.assetDesc
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
                    $('#btnUpdateParam').hide();
                    $('#titleModalEdit').hide();
                    this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                    this.myModal.show();
                },
                photo(event){
                    this.photo = event.target.files[0];
                },
                addParam() {
                    let photo = document.querySelector('#photo');
                    let formdata = new FormData();
                    formdata.append('parameterId', this.param.parameterId);
                    formdata.append('assetId', this.param.assetId);
                    formdata.append('sortId', this.param.sortId);
                    formdata.append('parameterName', this.param.parameterName);
                    formdata.append('photo', this.file);
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
                        url: '<?= base_url('Asset/addParameter'); ?>',
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
                        }else if(res.data.status == 'failed'){
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
                editParameter($parameterId) {
                    $('#btnAddParam').hide();
                    $('#titleModalAdd').hide();
                    $('#btnUpdateParam').show();
                    $('#titleModalEdit').show();
                    this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
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
                            this.param.abnormal = dt.normal;
                            this.param.option = dt.option;
                            this.param.inputType = dt.inputType;
                            this.param.showOn = dt.showOn;
                        }
                    })
                    this.myModal.show();
                },
                updateParameter() {
                    let photo = document.querySelector('#photo');
                    let formdata = new FormData();
                    formdata.append('parameterId', this.param.parameterId);
                    formdata.append('assetId', this.param.assetId);
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
                        }else if(res.data.status == 'failed'){
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
                    this.param.sortId = '',
                        this.param.parameterName = '';
                    this.param.photo = '';
                    this.param.description = '';
                    this.param.uom = '';
                    this.param.min = '';
                    this.param.max = '';
                    this.param.normal = [];
                    this.param.abnormal = [];
                    this.param.option = '';
                    this.param.inputType = '';
                    this.param.showOn = '';
                },
                btnLocation() {
                    $('#location').removeAttr("readonly");
                    $('#btnLocationCancel').show();
                    $('#btnLocationSave').show();
                    $('#btnLocation').hide();
                },
                btnLocationCancel() {
                    $('#location').attr("readonly", "readonly");
                    $('#btnLocationCancel').hide();
                    $('#btnLocationSave').hide();
                    $('#btnLocation').show();
                },
                btnLocationSave() {
                    axios.post('<?= base_url('Asset/updateTagLocation'); ?>', {
                        assetId: this.param.assetId,
                        tagLocationId: this.assetLocation
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
                        } else {
                            const swalWithBootstrapButtons = swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-danger',
                                },
                                buttonsStyling: false
                            })
                            swalWithBootstrapButtons.fire({
                                title: 'Failed!',
                                text: 'Bad request!',
                                icon: 'error'
                            })
                        }
                    })
                },
                btnTag() {
                    $('#tag').removeAttr("readonly");
                    $('#btnTagCancel').show();
                    $('#btnTagSave').show();
                    $('#btnTag').hide();
                },
                btnTagCancel() {
                    $('#tag').attr("readonly", "readonly");
                    $('#btnTagCancel').hide();
                    $('#btnTagSave').hide();
                    $('#btnTag').show();
                },
                btnTagSave() {
                    axios.post('<?= base_url('Asset/updateTag'); ?>', {
                        assetId: this.param.assetId,
                        tagId: this.assetTag,
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
                        } else if (res.data.status == 'failed') {
                            const swalWithBootstrapButtons = swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-success mr-1',
                                },
                                buttonsStyling: false
                            })
                            swalWithBootstrapButtons.fire({
                                title: 'Failed!',
                                text: 'Bad Request!',
                                icon: 'error'
                            })
                        }
                    })
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
                btnOperation() {
                    $('#operation').removeAttr("readonly");
                    $('#btnOperationCancel').show();
                    $('#btnOperationSave').show();
                    $('#btnOperation').hide();
                },
                btnOperationCancel() {
                    $('#operation').attr("readonly", "readonly");
                    $('#btnOperationCancel').hide();
                    $('#btnOperationSave').hide();
                    $('#btnOperation').show();
                },
                btnOperationSave() {
                    axios.post("<?= base_url('Asset/updateOperation'); ?>", {
                        assetId: this.param.assetId,
                        assetStatusId: this.statusId,
                        assetStatusName: this.statusName
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
                        }
                    })
                },
                btnTagging() {
                    let input = $('input[name=tagging]');
                    input.removeAttr("readonly");
                    $('#descTagging').removeAttr("readonly");
                    $('#taggingType').removeAttr("readonly");
                    $('#btnCancelTagging').show();
                    $('#btnSaveTagging').show();
                    $('#btnTagging').hide();
                },
                btnCancelTagging() {
                    this.assetTaggingValue = `<?php foreach ($tagging as $key) {
                                                    echo $key['assetTaggingValue'];
                                                } ?>`;
                    this.assetTaggingType = `<?php foreach ($tagging as $key) {
                                                    echo $key['description'];
                                                } ?>`;
                    let input = $('input[name=tagging]');
                    input.attr("readonly", "readonly");
                    $('#descTagging').attr("readonly", "readonly");
                    $('#taggingType').attr("readonly", "readonly");
                    $('#btnCancelTagging').hide();
                    $('#btnSaveTagging').hide();
                    $('#btnTagging').show();
                },
                btnSaveTagging() {
                    axios.post("<?= base_url('Asset/updateTagging'); ?>", {
                        assetTaggingId: this.assetTaggingId,
                        assetId: this.param.assetId,
                        assetTaggingValue: this.assetTaggingValue,
                        assetTaggingType: this.assetTaggingType,
                        description: this.assetTaggingDescription
                    }).then(res => {
                        if (res.data.status == 'success') {
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
                                        location.reload();
                                    }
                                })
                            }
                        }
                    })
                },
                editDetail() {
                    let input = $('input[name=asset]');
                    let textarea = $('textarea[name=asset]');
                    input.removeAttr("readonly");
                    textarea.removeAttr("readonly");
                    $('#btnCancelEdit').show();
                    $('#btnUpdate').show();
                    $('#btnEdit').hide();
                    $('.input').show();
                    $('.valueDefault').hide();
                },
                cancelEdit() {
                    let input = $('input[name=asset]');
                    let textarea = $('textarea[name=asset]');
                    input.attr("readonly", "readonly");
                    textarea.attr("readonly", "readonly");
                    this.assetName = `<?= $assetData['assetName']; ?>`;
                    this.assetNumber = `<?= $assetData['assetNumber']; ?>`;
                    this.assetDesc = `<?= $assetData['description']; ?>`;
                    $('#btnCancelEdit').hide();
                    $('#btnUpdate').hide();
                    $('#btnEdit').show();
                    $('.input').hide();
                    $('.valueDefault').show();
                },
                modalAddTag() {
                    this.myModal = new coreui.Modal(document.getElementById('modalAddTag'));
                    this.myModal.show();
                },
                addTag() {
                    axios.post('<?= base_url('Asset/addTag'); ?>', {
                        assetId: this.param.assetId,
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
                        const marker = new mapboxgl.Marker()
                            .setLngLat([109.005913, -7.727989])
                            .addTo(map);
                    })
                },
                addTagLocation() {
                    axios.post("<?= base_url('Asset/addTagLocation'); ?>", {
                        assetId: this.param.assetId,
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
                    coordinates.style.display = 'block';
                    coordinates.innerHTML = `Longitude: ${lnglat.lng}<br />Latitude: ${lnglat.lat}`;
                }
                marker.on('dragend', onDragEnd);
                $('#map').show();
                $('#map').addClass('w-100');
            } else if (!($(this).is(':checked'))) {
                $('#map').hide();
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
                mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';
                const map = new mapboxgl.Map({
                    container: 'mapSetting', // container ID
                    style: 'mapbox://styles/mapbox/streets-v11', // style URL
                    center: [109.005913, -7.727989], // starting position [lng, lat]
                    zoom: 14, // starting zoom
                });
                map.addControl(new mapboxgl.FullscreenControl());
                map.resize();
                const marker = new mapboxgl.Marker()
                    .setLngLat([109.005913, -7.727989])
                    .addTo(map);

                $('#mapSetting').show();
                $('#mapSetting').addClass('w-100');
            } else if (!($(this).is(':checked'))) {
                $('#mapSetting').hide();
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


        $('#tableParam tbody tr td:last-child').addClass('cursor-move');
        // select2 edit asset
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

        $(document).ready(function() {
            $('#schType').select2({
                theme: 'coreui',
                placeholder: "Select Frequency",
            });
        });

        $(document).ready(function() {
            $('.monthly').select2({
                theme: 'coreui',
                placeholder: "Select Month",
            });
        });

        $(document).ready(function() {
            $('.days').select2({
                theme: 'coreui',
                placeholder: "Select Days",
            });
        });

        $(document).ready(function() {
            $('.onMonth').select2({
                theme: 'coreui',
            });
        });

        $(document).ready(function() {
            $('.onDays').select2({
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
        });

        $(document).ready(function() {
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
        });

        $(document).ready(function() {
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
        });

        $(document).ready(function() {
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
            v.param.normal = data;
        })

        $('.abnormal').on('change', function() {
            let data = $('.abnormal').val();
            v.param.abnormal = data;
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
            v.param.showOn = data;
        })

        $('.type').on('change', function() {
            if ($(this).val() == 'select') {
                $('.typeSelect').show();
                $('.typeCheckbox').show();
                $('.typeInput').hide();
            } else if ($(this).val() == 'checkbox') {
                $('.typeCheckbox').show();
                $('.typeSelect').hide();
                $('.typeInput').hide();
            } else if ($(this).val() == 'input') {
                $('.typeInput').show();
                $('.typeSelect').hide();
                $('.typeCheckbox').hide();
            } else {
                $('.typeInput').hide();
                $('.typeSelect').hide();
                $('.typeCheckbox').hide();
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

        $('#schType').on('change', function() {
            if ($(this).val() == 'Daily') {
                $('#daily').show();
                $('#weekly').hide();
                $('#monthly').hide();
            } else if ($(this).val() == 'Weekly') {
                $('#weekly').show();
                $('#daily').hide();
                $('#monthly').hide();
            } else if ($(this).val() == 'Monthly') {
                $('#monthly').show();
                $('#daily').hide();
                $('#weekly').hide();
            }
        })

        //radio monthly
        $('input[type="radio"][name="gridRadios"]').on('change', function() {
            if ($(this).val() == "days") {
                $('.on').attr('disabled', true);
                $('.days').attr('disabled', false);
            } else if ($(this).val() == "on") {
                $('.days').attr('disabled', true);
                $('.on').attr('disabled', false);
            }
        })
    </script>
    <?= $this->endSection(); ?>