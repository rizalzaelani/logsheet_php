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
                        <div class="row mt-2">
                            <div class="col-6">
                                <table class="table mt-2">
                                    <tr class="mt-1">
                                        <th>Asset</th>
                                        <td>:
                                            <?php foreach ($data as $key) {
                                                echo $key['assetName'];
                                            } ?>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Number</th>
                                        <td>: <?php foreach ($data as $key) {
                                                    echo $key['assetNumber'];
                                                } ?></td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Description</th>
                                        <td>: <?php foreach ($data as $key) {
                                                    echo $key['description'];
                                                } ?></td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Frequency</th>
                                        <td>: <?php foreach ($data as $key) {
                                                    echo $key['frequencyType'];
                                                } ?></td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Status</th>
                                        <td>: <?php foreach ($data as $key) {
                                                    echo $key['assetStatusName'];
                                                } ?></td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Tag</th>
                                        <td>:
                                            <?php foreach ($data as $key) : ?>
                                                <span class="badge badge-secondary" style="font-size: 13px;"><?= $key['tagName']; ?></span>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Location</th>
                                        <td>:
                                            <?php foreach ($data as $key) : ?>
                                                <span class="badge badge-secondary" style="font-size: 13px;"><?= $key['tagLocationName']; ?></span>
                                            <?php endforeach; ?>
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
                                            <td class="text-center"><?= $key['uom']; ?></td>
                                            <td class="text-center"><?= $key['min']; ?></td>
                                            <td class="text-center"><?= $key['max']; ?></td>
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
                        <div class="row mt-2">
                            <div class="col-6">
                                <table class="table mt-2">
                                    <tr class="mt-1">
                                        <th>Asset</th>
                                        <td>:</td>
                                        <td class="valueDefault">
                                            <?php foreach ($data as $key) {
                                                echo $key['assetName'];
                                            } ?>
                                        </td>
                                        <td class="input" style="display: none;"><input type="text" class="form-control" name="assetName" id="assetName" value="<?= $asset['assetName'] ?>"></td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Number</th>
                                        <td>:</td>
                                        <td class="valueDefault">
                                            <?php foreach ($data as $key) {
                                                echo $key['assetNumber'];
                                            } ?>
                                        </td>
                                        <td class="input" style="display: none;"><input type="text" class="form-control" name="assetNumber" id="assetNumber" value="<?= $asset['assetNumber'] ?>"></td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Description</th>
                                        <td>:</td>
                                        <td class="valueDefault">
                                            <?php foreach ($data as $key) {
                                                echo $key['description'];
                                            } ?>
                                        </td>
                                        <td class="input" style="display: none;"><input type="text" class="form-control" name="description" id="description" value="<?= $asset['description'] ?>"></td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Status</th>
                                        <td>:</td>
                                        <td>
                                            <?php foreach ($data as $key) {
                                                echo $key['assetStatusName'];
                                            } ?>
                                        </td>
                                        <!-- <td class="d-flex align-items-center">
                                            <div class="ml-1 btn-group btn-group-toggle d-flex align-items-center" data-toggle="buttons" disabled>
                                                <label class="btn btn-sm btn-outline-success">
                                                    <input type="radio" name="running" autocomplete="off"> Running
                                                </label>
                                                <label class="btn btn-sm btn-outline-info">
                                                    <input type="radio" name="standby" autocomplete="off"> Standby
                                                </label>
                                                <label class="btn btn-sm btn-outline-danger">
                                                    <input type="radio" name="repair" autocomplete="off"> Repair
                                                </label>
                                            </div>
                                        </td> -->
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Show Last Value</th>
                                        <td>:</td>
                                        <td class="valueDefault">
                                            <div class="d-flex align-items-center"></div>
                                            <label class="ml-1 c-switch c-switch-pill c-switch-label c-switch-opposite-success m-0">
                                                <input type="checkbox" class="c-switch-input" disabled checked>
                                                <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                            </label>
                                        </td>
                                        <td style="display: none;" class="input">
                                            <div class="d-flex align-items-center"></div>
                                            <label class="ml-1 c-switch c-switch-pill c-switch-label c-switch-opposite-success m-0">
                                                <input type="checkbox" class="c-switch-input" checked>
                                                <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Latitude/Longitude</th>
                                        <td>:</td>
                                        <td>
                                            <div class="d-flex align-items-center"></div>
                                            <label class="ml-1 c-switch c-switch-pill c-switch-label c-switch-opposite-success m-0">
                                                <input type="checkbox" class="c-switch-input latlong" id="latlong" v-model="checked" checked disabled>
                                                <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                            </label>
                                        </td>
                                        <!-- <td style="display: none;" class="input">
                                            <div class="d-flex align-items-center">
                                                <label class="ml-1 c-switch c-switch-pill c-switch-label c-switch-opposite-success m-0">
                                                    <input type="checkbox" class="c-switch-input latlong" id="latlong">
                                                    <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </div>
                                        </td> -->
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Action</th>
                                        <td>:</td>
                                        <th class="d-flex justify-content-start align-items-center">
                                            <button class="btn btn-sm mr-1" type="button" @click="editDetail()" id="btnEdit"><i class="fa fa-edit mr-1"></i>Edit</button>
                                            <div style="display: none;" id="btnCancelEdit">
                                                <button class="btn btn-sm mr-1 d-flex align-items-center justify-content-between" type="button" @click="cancelEdit()"><i class="fa fa-times mr-1"></i> Cancel</button>
                                            </div>
                                            <div id="btnDelete">
                                                <button class="btn btn-sm mr-1 d-flex align-items-center justify-content-between" type="button" @click="deleteAsset()"><i class="fa fa-trash mr-1"></i>Delete</button>
                                            </div>
                                            <div style="display: none;" id="btnUpdate">
                                                <button class="btn btn-sm mr-1 d-flex align-items-center justify-content-between" type="button" @click="updateDetail()"><i class="fa fa-check mr-1"></i> Update</button>
                                            </div>
                                        </th>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-6" style="border: 1px solid #d8dbe0;">
                                <div class="valueDefault mt-2 w-100">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="/img/logo-act.png" alt="Image" class="img-thumbnail">
                                    </div>
                                    <div class="mt-1" id="mapSetting" style="min-width: 100% !important; height: 200px;"></div>
                                </div>
                                <div style="display: none;" class="input">
                                    <div class="d-flex align-items-center">
                                        <input type="file" class="filepond mt-2 mb-2 w-100" name="filepond" accept="image/png, image/jpeg, image/gif" />
                                    </div>
                                    <div id="map-container">
                                        <div id="map" style="min-width: 100% !important; height: 200px;"></div>
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
                                                <label class="col-3" for="parameter">Parameter <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="parameter"></i></label>
                                                <input type="text" class="form-control col-9 parameter" name="parameter" placeholder="Parameter Name" v-model="param.parameterName">
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-3" for="photo">Photo <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="photo"></i></label>
                                                <input type="file" class="form-control col-9 photo" name="photo" placeholder="Photo">
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-3" for="type">Type <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="type"></i></label>
                                                <div class="col-9 p-0">
                                                    <select class="form-control type" name="type" placeholder="Select Type">
                                                        <option value="" selected disabled>Select Type</option>
                                                        <option value="input">Input</option>
                                                        <option value="select">Select</option>
                                                        <option value="checkbox">Checkbox</option>
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
                                                    <select class="form-control normal" name="normal">
                                                        <option value="" selected disabled>Select Item</option>
                                                        <option value="item 1">item 1</option>
                                                        <option value="item 2">item 2</option>
                                                        <option value="item 3">item 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3 typeSelect" style="display: none;">
                                                <label class="col-3" for="abnormal">Abnormal <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="abnormal"></i></label>
                                                <div class="col-9 p-0">
                                                    <select class="form-control abnormal" name="abnormal">
                                                        <option value="" selected disabled>Select Item</option>
                                                        <option value="item 1">item 1</option>
                                                        <option value="item 2">item 2</option>
                                                        <option value="item 3">item 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3 typeCheckbox" style="display: none;">
                                                <label class="col-3">Option <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="option"></i></label>
                                                <div class="col-9 p-0">
                                                    <div class="form-check form-check-inline mr-1">
                                                        <input class="form-check-input" id="items1Add" type="checkbox" value="Item 1">
                                                        <label class="form-check-label" for="items1Add">Item 1</label>
                                                    </div>
                                                    <div class="form-check form-check-inline mr-1">
                                                        <input class="form-check-input" id="items2Add" type="checkbox" value="Item 2">
                                                        <label class="form-check-label" for="items2Add">Item 2</label>
                                                    </div>
                                                    <div class="form-check form-check-inline mr-1">
                                                        <input class="form-check-input" id="items3Add" type="checkbox" value="Item 3">
                                                        <label class="form-check-label" for="items3Add">Item 3</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-3" for="showOn">Parameter Status <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="showOn"></i></label>
                                                <div class="col-9 p-0">
                                                    <select class="form-control showOn" name="showOn" id="paramStatus">
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
                                <button type="button" class="btn btn-success" @click="addParam()" id="btnAddParam"><i class="fa fa-check"></i> Add Parameter</button>
                                <button type="button" class="btn btn-success" @click="updateParameter()" style="display: none;" id="btnUpdateParam"><i class="fa fa-check"></i> Save Changes</button>
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
                    <h5>Change Log</h5>
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
                        <h5 class="p-0 m-0">
                            Location Name
                        </h5>
                        <hr>
                        <form method="post" enctype="multipart/form-data">
                            <div class="valueDefault">
                                <?php foreach ($data as $key) : ?>
                                    <span class="badge badge-secondary" style="font-size: 13px;"><?= $key['tagLocationName']; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="input" style="display: none;">
                                <select class="form-control" name="location" id="location" multiple="multiple">
                                    <?php foreach ($data as $key) : ?>
                                        <option value="<?= $key['tagLocationName']; ?>" selected><?= $key['tagLocationName']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardLocationTag">
                        <h5 class="p-0 m-0">
                            Tag
                        </h5>
                        <hr>
                        <form method="post" enctype="multipart/form-data">
                            <div class="valueDefault">
                                <?php foreach ($data as $key) : ?>
                                    <span class="badge badge-secondary" style="font-size: 13px;"><?= $key['tagName']; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="input" style="display: none;">
                                <select class="form-control" name="tag" id="tag" multiple>
                                    <option value="Tag 1" selected>Tag 1</option>
                                    <option value="Tag 2" selected>Tag 2</option>
                                    <option value="Tag 3" selected>Tag 3</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule and Operation -->
        <div id="cardScheduleOpt" style="display: none;">
            <div class="row">
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardLocationTag">
                        <h5 class="p-0 m-0">Schedule</h5>
                        <hr>
                        <div class="valueDefault">
                            <p>Daily</p>
                        </div>
                        <div class="input" style="display: none;">
                            <form method="post" enctype="multipart/form-data">
                                <label for="frequencyType">Set Schedule</label>
                                <select class="form-control" name="frequencyType" id="frequencyType">
                                    <option value="" selected disabled>Select Schedule</option>
                                    <option value="Daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
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
                        <h5 class="p-0 m-0">Operation</h5>
                        <hr>
                        <div class="ml-1 btn-group btn-group-toggle d-flex align-items-center" data-toggle="buttons" disabled>
                            <label class="btn btn-sm btn-outline-success">
                                <input type="radio" name="running" autocomplete="off"> Running
                            </label>
                            <label class="btn btn-sm btn-outline-info">
                                <input type="radio" name="standby" autocomplete="off"> Standby
                            </label>
                            <label class="btn btn-sm btn-outline-danger">
                                <input type="radio" name="repair" autocomplete="off"> Repair
                            </label>
                        </div>
                        <?php foreach ($status as $key) : ?>
                            <p><?= $key['assetStatusName'] ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Asset Tagging and Config -->
            <div id="cardAssetTagging" style="display: none;">
                <div class="row">
                    <div class="col-6">
                        <div class="card card-main" id="cardLocationTag">
                            <h5>Asset Tagging</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card card-main" id="cardLocationTag">
                            <h5>Other Config</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- parameter -->
        <div class="card card-main" id="cardParameter" style="display: none;">
            <div class="mt-2 d-flex justify-content-between align-items-center">
                <h5>Parameter</h5>
                <div>
                    <button class="btn btn-sm" @click="addParameter()"><i class="fa fa-upload"></i> Import Parameter</button>
                    <button class="btn btn-sm" @click="addParameter()"><i class="fa fa-plus"></i> Add Parameter</button>
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
                            <th class="text-center" style="border-top-right-radius: 5px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($parameter as $key) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td class="text-center"><?= $key['parameterName']; ?></td>
                                <td class="text-center"><?= $key['description']; ?></td>
                                <td class="text-center"><?= $key['uom']; ?></td>
                                <td class="text-center"><?= $key['min']; ?></td>
                                <td class="text-center"><?= $key['max']; ?></td>
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
                myModal: '',
                checked: '',
                param: {
                    parameterId: null,
                    assetId: `<?php foreach ($data as $key) {
                                    echo $key['assetId'];
                                } ?>`,
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
                        rowReorder: {
                            selector: 'td:last-child'
                        },
                    });
                },
                updateDetail() {
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
                            swalWithBootstrapButtons.fire({
                                title: 'Success!',
                                text: 'You have successfully deleted this data.',
                                icon: 'success',
                                allowOutsideClick: false
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
                addParam() {
                    for (var option of document.getElementById('paramStatus').options) {
                        if (option.selected) {
                            v.param.showOn = option.value;
                        }
                    }
                    axios.post("<?= base_url('Asset/addParameter'); ?>", {
                        parameterId: this.param.parameterId,
                        assetId: this.param.assetId,
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
                        showOn: this.param.showOn
                    }).then(res => {
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
                            text: 'You have successfully add data.',
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
                    axios.post("<?= base_url('Asset/updateParameter'); ?>", {
                        parameterId: this.param.parameterId,
                        assetId: this.param.assetId,
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
                        showOn: this.param.showOn
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
                    this.param.normal = '';
                    this.param.abnormal = '';
                    this.param.option = '';
                    this.param.inputType = '';
                    this.param.showOn = '';
                },
                editDetail() {
                    $('.input').show();
                    $('#btnCancelEdit').show();
                    $('#btnUpdate').show();
                    $('.valueDefault').hide();
                    $('#btnEdit').hide();
                    $('.latlong').removeAttr('disabled');
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
                },
                cancelEdit() {
                    $('.input').hide();
                    $('#btnCancelEdit').hide();
                    $('#btnUpdate').hide();
                    $('.valueDefault').show();
                    $('#btnEdit').show();
                    $('.latlong').prop('disabled', true);
                },
            }
        });

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

        FilePond.registerPlugin(FilePondPluginImageCrop, FilePondPluginImagePreview, FilePondPluginImageEdit, FilePondPluginFileValidateType);
        const pond = $('.filepond').filepond({
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

        $('#tableParam tbody tr td:last-child').addClass('cursor-move');

        // select2 edit asset
        $(document).ready(function() {
            $('#tag').select2({
                theme: 'coreui',
                placeholder: "Tag Name",
                tags: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
                language: {
                    noResults: function() {
                        return `<button class="btn btn-sm btn-primary">Add</button>`;
                    }
                }
            });
        });

        $(document).ready(function() {
            $('#location').select2({
                theme: 'coreui',
                placeholder: "Select Location",
                escapeMarkup: function(markup) {
                    return markup;
                },
                language: {
                    noResults: function() {
                        return `<button class="btn btn-sm btn-primary">Add</button>`;
                    }
                }
            });
        });

        $(document).ready(function() {
            $('#frequencyType').select2({
                theme: 'coreui',
                placeholder: "Select Schedule",
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
                placeholder: "Select Type",
                dropdownParent: $('#addParameterModal'),
            });
        });

        $(document).ready(function() {
            $('.abnormal').select2({
                theme: 'coreui',
                placeholder: "Select Type",
                dropdownParent: $('#addParameterModal'),
            });
        });

        $(document).ready(function() {
            $('.option').select2({
                theme: 'coreui',
                placeholder: "Select Type",
                dropdownParent: $('#addParameterModal'),
            });
        });

        $(document).ready(function() {
            $('.showOn').select2({
                theme: 'coreui',
                placeholder: "Parameter Status",
                dropdownParent: $('#addParameterModal'),
            });
        });

        $('.type').on('change', function() {
            let data = $('.type option:selected').val();
            v.param.inputType = data;
        })
        $('.normal').on('change', function() {
            let data = $('.normal option:selected').val();
            v.param.normal = data;
        })
        $('.abnormal').on('change', function() {
            let data = $('.abnormal option:selected').val();
            v.param.abnormal = data;
        })

        $('.type').on('change', function() {
            if ($(this).val() == 'select') {
                $('.typeSelect').show();
                $('.typeInput').hide();
                $('.typeCheckbox').hide();
            } else if ($(this).val() == 'checkbox') {
                $('.typeCheckbox').show();
                $('.typeSelect').hide();
                $('.typeInput').hide();
            } else {
                $('.typeInput').show();
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

        $('#frequencyType').on('change', function() {
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

        // select2 edit parameter
        $(document).ready(function() {
            $('.typeEdit').select2({
                theme: 'coreui',
                placeholder: "Select Type",
                dropdownParent: $('#editParameterModal'),
            });
        });

        $(document).ready(function() {
            $('.normalEdit').select2({
                theme: 'coreui',
                placeholder: "Select Type",
                dropdownParent: $('#editParameterModal'),
            });
        });
        $(document).ready(function() {
            $('.abnormalEdit').select2({
                theme: 'coreui',
                placeholder: "Select Type",
                dropdownParent: $('#editParameterModal'),
            });
        });
        $(document).ready(function() {
            $('.optionEdit').select2({
                theme: 'coreui',
                placeholder: "Select Type",
                dropdownParent: $('#editParameterModal'),
            });
        });
        $(document).ready(function() {
            $('.showOnEdit').select2({
                theme: 'coreui',
                placeholder: "Parameter Status",
                dropdownParent: $('#editParameterModal'),
            });
        });
    </script>
    <script>

    </script>


    <?= $this->endSection(); ?>