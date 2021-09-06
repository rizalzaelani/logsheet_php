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
                                        <td>: IPC</td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Number</th>
                                        <td>: 001</td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Tag</th>
                                        <td>: ROUTER, CCTV</td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Location</th>
                                        <td>: GEDUNG FINANCE</td>
                                    </tr>

                                    <tr class="mt-1">
                                        <th>Frequency</th>
                                        <td>: WEEKLY</td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Description</th>
                                        <td>: DESC</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-6 d-flex flex-row align-items-center" style="border: 1px solid #d8dbe0;">
                                <img src="/img/logo-act.png" alt="Image" class="img-thumbnail m-0">
                            </div>
                        </div>
                    </div>
                    <!-- tab parameter -->
                    <div class="tab-pane" id="parameter" role="tabpanel">
                        <div class="table-responsive mt-2">
                            <table class="table dt-responsive table-hover w-100 display" id="tableParam">
                                <thead>
                                    <tr class="bg-primary text-center">
                                        <th colspan="8">SORTING PARAMETER</th>
                                    </tr>
                                    <tr style="display: none;">
                                        <th>#</th>
                                        <th width="12,5%">Parameter</th>
                                        <th width="12,5%">Photo</th>
                                        <th width="12,5%">Description</th>
                                        <th width="12,5%">UoM</th>
                                        <th width="12,5%">Min</th>
                                        <th width="12,5%">Max</th>
                                        <th width="15%">Show On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="display: none;">1</td>
                                        <td>PING</td>
                                        <td><a href="/img/logo-act.png">ping.jpg</a></td>
                                        <td>desc ping</td>
                                        <td>ms</td>
                                        <td>35</td>
                                        <td>67</td>
                                        <td>Running, Standby</td>
                                    </tr>
                                    <tr>
                                        <td style="display: none;">2</td>
                                        <td>UPLOAD</td>
                                        <td><a href="/img/logo-act.png">upload.jpg</a></td>
                                        <td>desc upload</td>
                                        <td>MBPS</td>
                                        <td>35</td>
                                        <td>67</td>
                                        <td>Running, Standby</td>
                                    </tr>
                                    <tr>
                                        <td style="display: none;">3</td>
                                        <td>CABLE</td>
                                        <td><a href="/img/logo-act.png">cable.jpg</a></td>
                                        <td>desc cable</td>
                                        <td>good, bad</td>
                                        <td>bad</td>
                                        <td>good</td>
                                        <td>Running, Standby</td>
                                    </tr>
                                    <tr>
                                        <td style="display: none;">4</td>
                                        <td>AMPERE</td>
                                        <td><a href="/img/logo-act.png">ampere.jpg</a></td>
                                        <td>desc ampere</td>
                                        <td>A</td>
                                        <td>5</td>
                                        <td>23</td>
                                        <td>Running, Standby</td>
                                    </tr>
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
                                        <td class="valueDefault">Asset Name</td>
                                        <td class="input" style="display: none;"><input type="text" class="form-control" name="assetName" id="assetName" placeholder="Asset Name"></td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Number</th>
                                        <td>:</td>
                                        <td class="valueDefault">Asset Number</td>
                                        <td class="input" style="display: none;"><input type="text" class="form-control" name="assetNumber" id="assetNumber" placeholder="001"></td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Tag</th>
                                        <td>:</td>
                                        <td class="valueDefault">Tag Name</td>
                                        <td class="input" style="display: none">
                                            <select class="form-control" name="tag" id="tag">
                                                <option value="CCTV">CCTV</option>
                                                <option value="ROUTER">ROUTER</option>
                                                <option value="IT">IT</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Location</th>
                                        <td>:</td>
                                        <td class="valueDefault">Location Name</td>
                                        <td class="input" style="display: none;">
                                            <select class="form-control" name="location" id="location">
                                                <option value="GEDUNG PARKIR">GEDUNG PARKIR</option>
                                                <option value="GEDUNG MESIN">GEDUNG MESIN</option>
                                                <option value="GEDUNG FINANCE">GEDUNG FINANCE</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr class="mt-1">
                                        <th>Frequency</th>
                                        <td>:</td>
                                        <td class="valueDefault">Frequency</td>
                                        <td class="input" style="display: none;">
                                            <select class="form-control" name="frequency" id="frequency">
                                                <option value="Daily">Daily</option>
                                                <option value="Weekly">Weekly</option>
                                                <option value="Monthly">Monthly</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Description</th>
                                        <td>:</td>
                                        <td class="valueDefault">Description</td>
                                        <td class="input" style="display: none;"><input type="text" class="form-control" name="description" id="description" placeholder="Description"></td>
                                    </tr>
                                    <tr class="mt-1">
                                        <th>Change Operation Mode</th>
                                        <td>:</td>
                                        <td class="d-flex align-items-center">
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
                                        </td>
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
                                                <input type="checkbox" class="c-switch-input latlong" id="latlong" v-model="checked" disabled>
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
                                </div>
                                <div style="display: none;" class="input">
                                    <div class="d-flex align-items-center">
                                        <input type="file" class="filepond mt-2 mb-2 w-100" name="filepond" accept="image/png, image/jpeg, image/gif" />
                                    </div>
                                    <div id="map-container">
                                        <div id="map" style="min-width: 100% !important; height: 350px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal Edit
                <div class="modal fade" tabindex="-1" role="dialog" id="editDetailModal">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Data</h5>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="row mb-3">
                                            <label class="col-3" for="asset">Asset <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="asset"></i></label>
                                            <input class="col-9 form-control asset" type="text" name="asset" placeholder="Asset Name">
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="number">Number <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="number"></i></label>
                                            <input class="col-9 form-control number" type="text" name="number" placeholder="Asset Number">
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="tag">Tag <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="tag"></i></label>
                                            <div class="col-9 p-0">
                                                <select class="form-control" name="tag" id="tag">
                                                    <option value="CCTV">CCTV</option>
                                                    <option value="ROUTER">ROUTER</option>
                                                    <option value="IT">IT</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="location">Location <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="location"></i></label>
                                            <div class="col-9 p-0">
                                                <select class="form-control" name="location" id="location">
                                                    <option value="GEDUNG PARKIR">GEDUNG PARKIR</option>
                                                    <option value="GEDUNG MESIN">GEDUNG MESIN</option>
                                                    <option value="GEDUNG FINANCE">GEDUNG FINANCE</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="frequency">Frequency Type <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="frequency"></i></label>
                                            <div class="col-9 p-0">
                                                <select class="form-control" name="frequency" id="frequency">
                                                    <option value="Daily">Daily</option>
                                                    <option value="Weekly">Weekly</option>
                                                    <option value="Monthly">Monthly</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="description">Description <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="description"></i></label>
                                            <textarea class="col-9 form-control description" rows="9" name="description" placeholder="Description"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancel"><i class="fa fa-times"></i> Cancel</button>
                                <button type="button" class="btn btn-success" @click="updateAsset()"><i class="fa fa-check"></i> Update</button>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- modal add parameter-->
                <div class="modal fade" role="dialog" id="addParameterModal">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Parameter</h5>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="row mb-3">
                                            <label class="col-3" for="parameter">Parameter <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="parameter"></i></label>
                                            <input type="text" class="form-control col-9 parameter" name="parameter" placeholder="Parameter Name">
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="photo">Photo <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="photo"></i></label>
                                            <input type="file" class="form-control col-9 photo" name="photo" placeholder="Photo">
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="type">Type <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="type"></i></label>
                                            <div class="col-9 p-0">
                                                <select class="form-control type" name="type" placeholder="Select Type">
                                                    <option value="input">Input</option>
                                                    <option value="select">Select</option>
                                                    <option value="checkbox">Checkbox</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 typeInput">
                                            <label class="col-3" for="min">Min <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="min"></i></label>
                                            <input type="text" class="form-control col-9 min" name="min" placeholder="Min Value">
                                        </div>
                                        <div class="row mb-3 typeInput">
                                            <label class="col-3" for="max">Max <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="max"></i></label>
                                            <input type="text" class="form-control col-9 max" name="max" placeholder="Max Value">
                                        </div>
                                        <div class="row mb-3 typeInput">
                                            <label class="col-3" for="uom">Unit Of Measure <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="uom"></i></label>
                                            <input type="text" class="form-control col-9 uom" name="uom" placeholder="Unit Of Measure">
                                        </div>
                                        <div class="row mb-3 typeSelect" style="display: none;">
                                            <label class="col-3" for="normal">Normal <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="normal"></i></label>
                                            <div class="col-9 p-0">
                                                <select class="form-control normal" name="normal">
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
                                                    <option value="item 1">item 1</option>
                                                    <option value="item 2">item 2</option>
                                                    <option value="item 3">item 3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 typeSelect" style="display: none;">
                                            <label class="col-3" for="option">Option <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="option"></i></label>
                                            <div class="col-9 p-0">
                                                <select class="form-control option" name="option">
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
                                                <select class="form-control showOn" name="showOn" multiple="multiple">
                                                    <option value="Running">Running</option>
                                                    <option value="Standby">Standby</option>
                                                    <option value="Repair">Repair</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="description">Description <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="description"></i></label>
                                            <textarea class="form-control col-9 description" rows="9" name="description" placeholder="Description of parameter"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancel"><i class=" fa fa-times"></i> Cancel</button>
                                <button type="button" class="btn btn-success" @click="addParam()"><i class="fa fa-check"></i> Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal edit parameter-->
                <div class="modal fade" role="dialog" id="editParameterModal">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Parameter</h5>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="row mb-3">
                                            <label class="col-3" for="parameter">Parameter <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="parameter"></i></label>
                                            <input type="text" class="form-control col-9 parameter" name="parameter" placeholder="Parameter Name">
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="photo">Photo <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="photo"></i></label>
                                            <input type="file" class="form-control col-9" name="photo" placeholder="Photo">
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="type">Type <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="type"></i></label>
                                            <div class="col-9 p-0">
                                                <select class="form-control typeEdit" name="type" placeholder="Select Type">
                                                    <option value="input">Input</option>
                                                    <option value="select">Select</option>
                                                    <option value="checkbox">Checkbox</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 typeInputEdit">
                                            <label class="col-3" for="min">Min <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="min"></i></label>
                                            <input type="text" class="form-control col-9 min" name="min" placeholder="Min Value">
                                        </div>
                                        <div class="row mb-3 typeInputEdit">
                                            <label class="col-3" for="max">Max <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="max"></i></label>
                                            <input type="text" class="form-control col-9 max" name="max" placeholder="Max Value">
                                        </div>
                                        <div class="row mb-3 typeInputEdit">
                                            <label class="col-3" for="uom">Unit Of Measure <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="uom"></i></label>
                                            <input type="text" class="form-control col-9 uom" name="uom" placeholder="Unit Of Measure">
                                        </div>
                                        <div class="row mb-3 typeSelectEdit" style="display: none;">
                                            <label class="col-3" for="normal">Normal <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="normal"></i></label>
                                            <div class="col-9 p-0">
                                                <select class="form-control normalEdit" name="normal">
                                                    <option value="item 1">item 1</option>
                                                    <option value="item 2">item 2</option>
                                                    <option value="item 3">item 3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 typeSelectEdit" style="display: none;">
                                            <label class="col-3" for="abnormal">Abnormal <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="abnormal"></i></label>
                                            <div class="col-9 p-0">
                                                <select class="form-control abnormalEdit" name="abnormal">
                                                    <option value="item 1">item 1</option>
                                                    <option value="item 2">item 2</option>
                                                    <option value="item 3">item 3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 typeSelectEdit" style="display: none;">
                                            <label class="col-3" for="option">Option <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="option"></i></label>
                                            <div class="col-9 p-0">
                                                <select class="form-control optionEdit" name="option">
                                                    <option value="item 1">item 1</option>
                                                    <option value="item 2">item 2</option>
                                                    <option value="item 3">item 3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 typeCheckboxEdit" style="display: none;">
                                            <label class="col-3">Option <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="option"></i></label>
                                            <div class="col-9 p-0">
                                                <div class="form-check form-check-inline mr-1">
                                                    <input class="form-check-input" id="items1Edit" type="checkbox" value="Item 1">
                                                    <label class="form-check-label" for="items1Edit">Item 1</label>
                                                </div>
                                                <div class="form-check form-check-inline mr-1">
                                                    <input class="form-check-input" id="items2Edit" type="checkbox" value="Item 2">
                                                    <label class="form-check-label" for="items2Edit">Item 2</label>
                                                </div>
                                                <div class="form-check form-check-inline mr-1">
                                                    <input class="form-check-input" id="items3Edit" type="checkbox" value="Item 3">
                                                    <label class="form-check-label" for="items3Edit">Item 3</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="showOn">Parameter Status <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="showOn"></i></label>
                                            <div class="col-9 p-0">
                                                <select class="form-control showOnEdit" name="showOn" multiple="multiple">
                                                    <option value="Running">Running</option>
                                                    <option value="Standby">Standby</option>
                                                    <option value="Repair">Repair</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-3" for="description">Description <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="description"></i></label>
                                            <textarea class="form-control col-9 description" rows="9" name="description" placeholder="Description of parameter"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancel"><i class="fa fa-times"></i> Cancel</button>
                                <button type="button" class="btn btn-success" @click="updateParameter()"><i class="fa fa-check"></i> Update</button>
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

        <!-- parameter -->
        <div class="card card-main" id="cardParameter" style="display: none;">
            <div class="mt-2 d-flex justify-content-between align-items-center">
                <h5>Parameter</h5>
                <button class="btn btn-sm" @click="addParameter()"><i class="fa fa-plus"></i> Add Parameter</button>
            </div>
            <div class="table-responsive mt-2">
                <table class="table dt-responsive table-hover w-100 display" id="tableParameter">
                    <thead class="bg-primary">
                        <tr>
                            <th width="12,5%">Parameter</th>
                            <th width="12,5%">Photo</th>
                            <th width="12,5%">Description</th>
                            <th width="12,5%">UoM</th>
                            <th width="12,5%">Min</th>
                            <th width="12,5%">Max</th>
                            <th width="15%">Show On</th>
                            <th width="10%" style="border-top-right-radius: 5px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>PING</td>
                            <td><a href="/img/logo-act.png">ping.jpg</a></td>
                            <td>desc ping</td>
                            <td>ms</td>
                            <td>35</td>
                            <td>67</td>
                            <td>Running, Standby</td>
                            <td>
                                <button class="btn btn-sm btn-success mr-1" @click="editParameter()"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" @click="deleteParameter()"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>UPLOAD</td>
                            <td><a href="/img/logo-act.png">upload.jpg</a></td>
                            <td>desc upload</td>
                            <td>MBPS</td>
                            <td>35</td>
                            <td>67</td>
                            <td>Running, Standby</td>
                            <td>
                                <button class="btn btn-sm btn-success mr-1" @click="editParameter()"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" @click="deleteParameter()"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>CABLE</td>
                            <td><a href="/img/logo-act.png">cable.jpg</a></td>
                            <td>desc cable</td>
                            <td>good, bad</td>
                            <td>bad</td>
                            <td>good</td>
                            <td>Running, Standby</td>
                            <td>
                                <button class="btn btn-sm btn-success mr-1" @click="editParameter()"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" @click="deleteParameter()"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>AMPERE</td>
                            <td><a href="/img/logo-act.png">ampere.jpg</a></td>
                            <td>desc ampere</td>
                            <td>A</td>
                            <td>5</td>
                            <td>23</td>
                            <td>Running, Standby</td>
                            <td>
                                <button class="btn btn-sm btn-success mr-1" @click="editParameter()"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" @click="deleteParameter()"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
            checked: false
        },
        mounted() {
            this.getDataParameter();
        },
        methods: {
            detailTab() {
                $('#cardChangeLog').show();
                $('#cardParameter').hide();
            },
            parameterTab() {
                $('#cardChangeLog').hide();
                $('#cardParameter').hide();
            },
            settingTab() {
                $('#cardParameter').show();
                $('#cardChangeLog').hide();
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
                                color: 'green',
                                draggable: true,
                            })
                            .setLngLat([109.005913, -7.727989])
                            .addTo(map);

                        const marker2 = new mapboxgl.Marker({
                                color: 'red',
                                draggable: true,
                            })
                            .setLngLat([109.000000, -7.726000])
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
            },
            getDataParameter() {
                $('#tableParam').DataTable({
                    dom: 't',
                    rowReorder: {
                        selector: 'tr'
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
                this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                this.myModal.show();
            },
            addParam() {
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
                })
            },
            editParameter() {
                this.myModal = new coreui.Modal(document.getElementById('editParameterModal'), {});
                this.myModal.show();
            },
            updateParameter() {
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
                })
            },
            deleteParameter() {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger ml-1'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Are yu sure?',
                    text: "You will delete this data!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: "<i class='fa fa-times'></i> Cancel",
                    confirmButtonText: "<i class='fa fa-check'></i> Yes, delete!",
                    reverseButtons: false
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
            editDetail() {
                $('.input').show();
                $('#btnCancelEdit').show();
                $('#btnUpdate').show();
                $('.valueDefault').hide();
                $('#btnEdit').hide();
                $('.latlong').removeAttr('disabled');
            },
            cancelEdit() {
                $('.input').hide();
                $('#btnCancelEdit').hide();
                $('#btnUpdate').hide();
                $('.valueDefault').show();
                $('#btnEdit').show();
                $('.latlong').prop('disabled', true);
            },
            btnCancel() {
                const swalWithBootstrapButtons = swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'You cancel editing this data.',
                    'error'
                )
            }
        }
    });

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

    $('#tableParam tbody tr').addClass('cursor-move');

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
            placeholder: "Location Name",
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
        $('#frequency').select2({
            theme: 'coreui',
            placeholder: "Frequency Type",
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