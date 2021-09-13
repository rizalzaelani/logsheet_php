<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main card-border-top">
            <div class="card-body">
                <div class="d-flex justify-content-end align-items-center">
                    <a class="btn btn-sm btn-success" href="<?= base_url('Asset'); ?>"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
                <div class="form-group mt-3">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <label class="col-2" for="assetName">Asset Name <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>The name of an asset that you have.</div>"></i></label>
                            <input class="col-10 form-control" type="text" placeholder="Asset Name" v-model="assetName">
                        </div>
                        <div class="row mb-3">
                            <label class="col-2" for="assetNumber">Asset Number <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Number for each asset.</div>"></i></label>
                            <input class="col-10 form-control" type="text" placeholder="Asset Number" v-model="assetNumber">
                        </div>
                        <div class="row mb-3">
                            <label class="col-2" for="status">Status <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Status</div>"></i></label>
                            <div class="col-10 p-0">
                                <select name="status" id="status">
                                    <option value="" selected disabled>Select Status</option>
                                    <?php foreach ($status as $key) : ?>
                                        <option value="<?= $key['assetStatusName']; ?>"><?= $key['assetStatusName']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-2" for="frequencyType">Frequency Type <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Determining the frequency for your asset. Such as <b>Daily, Weekly, or Monthly</b></div>"></i></label>
                            <div class="col-10 p-0">
                                <select name="frequencyType" id="frequencyType">
                                    <option value="" selected disabled>Select Frequency Type</option>
                                    <option value="Daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-2" for="assetLatitude">Latitude <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Latitude for each asset.</div>"></i></label>
                            <input class="col-10 form-control" type="text" placeholder="Latitude" v-model="latitude">
                        </div>
                        <div class="row mb-3">
                            <label class="col-2" for="assetLongitude">Longitude <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Longitude for each asset.</div>"></i></label>
                            <input class="col-10 form-control" type="text" placeholder="Longitude" v-model="longitude">
                        </div>
                        <div class="row mb-3">
                            <label class="col-2" for="tag">Tag <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Tag for your asset</div>"></i></label>
                            <div class="col-10 p-0">
                                <select name="tag" id="tag" multiple required>
                                    <?php foreach ($tag as $key) : ?>
                                        <option value="<?= $key['tagName']; ?>"><?= $key['tagName']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-2" for="location">Location <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Location of an asset that you have</div>"></i></label>
                            <div class="col-10 p-0">
                                <select name="location" id="location" multiple>
                                    <?php foreach ($location as $key) : ?>
                                        <option value="<?= $key['tagLocationName']; ?>"><?= $key['tagLocationName']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-2" for="description">Description <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Description of an asset that you have</div>"></i></label>
                            <textarea class="col-10 form-control" rows="9" placeholder="Description" v-model="descAsset"></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center" id="param">
                            <h5 class="mt-3"><b>Parameter</b></h5>
                            <button class="btn btn-primary" @click="handleModalParameter()" type="button"><i class="fa fa-plus"></i> Add Parameter</button>
                        </div>
                        <hr>

                        <table class="table">
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
                                <tr v-for="(items, i) in params" :key="i">
                                    <td>{{ items.parameter }}</td>
                                    <td>{{ items.photo }}</td>
                                    <td>{{ items.descParam }}</td>
                                    <td>{{ items.uom }}</td>
                                    <td>{{ items.min }}</td>
                                    <td>{{ items.max }}</td>
                                    <td>{{ items.parameterStatus }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success mr-1" @click="handleEditParameter()"><i class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger" @click="deleteParameter()"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- table parameter -->
                        <!-- <div class="table-responsive mt-2">
                            <table class="table table-hover">
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
                                            <button type="button" class="btn btn-sm btn-success mr-1" @click="handleEditParameter()"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" @click="deleteParameter()"><i class="fa fa-trash"></i></button>
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
                                            <button type="button" class="btn btn-sm btn-success mr-1" @click="handleEditParameter()"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" @click="deleteParameter()"><i class="fa fa-trash"></i></button>
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
                                            <button type="button" class="btn btn-sm btn-success mr-1" @click="handleEditParameter()"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" @click="deleteParameter()"><i class="fa fa-trash"></i></button>
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
                                            <button type="button" class="btn btn-sm btn-success mr-1" @click="handleEditParameter()"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" @click="deleteParameter()"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> -->

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
                                                    <label for="tagname">Tag Name <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="frequency"></i></label>
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
                                                    <label class="col-3" for="parameter">Parameter <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Parameter name for asset that you have.</div>"></i></label>
                                                    <input type="text" class="form-control col-9 parameter" name="parameter" placeholder="Parameter Name" v-model="param.parameter">
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-3" for="photo">Photo <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="photo"></i></label>
                                                    <input type="file" class="form-control col-9" name="photo" placeholder="Photo" v-model="param.photo">
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-3" for="type">Type <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="type"></i></label>
                                                    <div class="col-9 p-0">
                                                        <select class="form-control type" name="type" placeholder="Select Type" id="type">
                                                            <option value="" selected disabled>Select Type</option>
                                                            <option value="input">Input</option>
                                                            <option value="select">Select</option>
                                                            <option value="checkbox">Checkbox</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3 typeInput">
                                                    <label class="col-3" for="min">Min <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="min"></i></label>
                                                    <input type="text" class="form-control col-9 min" name="min" placeholder="Min Value" v-model="param.min">
                                                </div>
                                                <div class="row mb-3 typeInput">
                                                    <label class="col-3" for="max">Max <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="max"></i></label>
                                                    <input type="text" class="form-control col-9 max" name="max" placeholder="Max Value" v-model="param.max">
                                                </div>
                                                <div class="row mb-3 typeInput">
                                                    <label class="col-3" for="uom">Unit Of Measure <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="uom"></i></label>
                                                    <input type="text" class="form-control col-9 uom" name="uom" placeholder="Unit Of Measure" v-model="param.uom">
                                                </div>
                                                <div class="row mb-3 typeSelect" style="display: none;">
                                                    <label class="col-3" for="normal">Normal <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="normal"></i></label>
                                                    <div class="col-9 p-0">
                                                        <select class="form-control normal" name="normal" id="normal">
                                                            <option value="" selected disabled>Select Item</option>
                                                            <option value="item 1">item 1</option>
                                                            <option value="item 2">item 2</option>
                                                            <option value="item 3">item 3</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3 typeSelect" style="display: none;">
                                                    <label class="col-3" for="abnormal">Abnormal <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="abnormal"></i></label>
                                                    <div class="col-9 p-0">
                                                        <select class="form-control abnormal" name="abnormal" id="abnormal">
                                                            <option value="" selected disabled>Select Item</option>
                                                            <option value="item 1">item 1</option>
                                                            <option value="item 2">item 2</option>
                                                            <option value="item 3">item 3</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3 typeCheckbox" style="display: none;">
                                                    <label class="col-3">Option <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="option"></i></label>
                                                    <div class="col-9 p-0">
                                                        <div class="form-check form-check-inline mr-1">
                                                            <input class="form-check-input option" id="option1" type="checkbox" value="Item 1">
                                                            <label class="form-check-label" for="option1">Item 1</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mr-1">
                                                            <input class="form-check-input option" id="option2" type="checkbox" value="Item 2">
                                                            <label class="form-check-label" for="option2">Item 2</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mr-1">
                                                            <input class="form-check-input option" id="option3" type="checkbox" value="Item 3">
                                                            <label class="form-check-label" for="option3">Item 3</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-3" for="showOn">Parameter Status <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="showOn"></i></label>
                                                    <div class="col-9 p-0">
                                                        <select class="form-control showOn" name="showOn" multiple="multiple" id="paramStatus">
                                                            <option value="Running">Running</option>
                                                            <option value="Standby">Standby</option>
                                                            <option value="Repair">Repair</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-3" for="description">Description <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="description"></i></label>
                                                    <textarea class="form-control col-9 description" name="description" rows="9" placeholder="Description of Parameter" v-model="param.descParam"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancel"><i class=" fa fa-times"></i> Cancel</button>
                                        <button type="button" class="btn btn-success" @click="addParameter()"><i class="fa fa-check"></i> Add</button>
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
                                                            <input class="form-check-input" id="option1" type="checkbox" value="Item 1">
                                                            <label class="form-check-label" for="option1">Item 1</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mr-1">
                                                            <input class="form-check-input" id="option2" type="checkbox" value="Item 2">
                                                            <label class="form-check-label" for="option2">Item 2</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mr-1">
                                                            <input class="form-check-input" id="option3" type="checkbox" value="Item 3">
                                                            <label class="form-check-label" for="option3">Item 3</label>
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

                        <div class="d-flex align-items-center w-100">
                            <button class="btn btn-success w-100" type="button" @click="save()"><i class="fa fa-plus"></i> Add Asset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<script>
    let v = new Vue({
        el: '#app',
        data: {
            assetName: '',
            assetNumber: '',
            status: '',
            frequencyType: '',
            latitude: '',
            longitude: '',
            descAsset: '',
            tag: [],
            location: '',
            myModal: '',
            param: {
                parameter: '',
                photo: '',
                type: '',
                min: '',
                max: '',
                uom: '',
                normal: '',
                abnormal: '',
                option: [],
                parameterStatus: [],
                descParam: '',
            },
            params: [],
        },
        methods: {
            handleModalParameter() {
                this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                this.myModal.show();
            },
            addTag() {
                this.modalTag = new coreui.Modal(document.getElementById('modalTag'), {});
                this.modalTag.show();
            },
            addParameter() {
                //status parameter
                // var paramStatus = [];
                for (var option of document.getElementById('paramStatus').options) {
                    if (option.selected) {
                        v.param.parameterStatus.push(option.value);
                    }
                }
                // v.param.parameterStatus.push(paramStatus);

                //option param
                var opt = [];
                if ($('#option1').is(':checked')) {
                    let a = $('#option1').val();
                    opt.push(a);
                }
                if ($('#option2').is(':checked')) {
                    let a = $('#option2').val();
                    opt.push(a);
                }
                if ($('#option3').is(':checked')) {
                    let a = $('#option3').val();
                    opt.push(a);
                }
                v.param.option = opt;

                //addparam
                this.params.push(this.param);
                this.param = {
                        parameter: '',
                        photo: '',
                        type: '',
                        min: '',
                        max: '',
                        uom: '',
                        normal: '',
                        abnormal: '',
                        option: [],
                        parameterStatus: [],
                        descParam: '',
                    },
                    $('#paramStatus option').prop('selected', function() {
                        return this.defaultSelected;
                    })
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
                    text: 'You have successfully add parameter.',
                    icon: 'success'
                })
            },
            handleEditParameter() {
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
                    text: 'You have successfully update parameter.',
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
            save() {
                var selectedTag = [];
                for (var option of document.getElementById('tag').options) {
                    if (option.selected) {
                        selectedTag.push(option.value);
                    }
                }
                v.tag = selectedTag;

                var selectedLocation = [];
                for (var option of document.getElementById('location').options) {
                    if (option.selected) {
                        selectedLocation.push(option.value);
                    }
                }
                v.location = selectedLocation;

                //post data
                axios.post("<?= base_url('Asset/save'); ?>", {
                    assetId: '',
                    assetName: this.assetName,
                    assetNumber: this.assetNumber,
                    description: this.descAsset,
                    frequencyType: this.frequencyType,
                    latitude: this.latitude,
                    longitude: this.longitude,
                    tag: this.tag,
                    location: this.location
                }).then(res => {
                    console.log(res);
                    if (res.data.status == 'success') {
                        const swalWithBootstrapButtons = swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success mr-1',
                                cancelButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire({
                            title: 'Success!',
                            text: 'You have successfully add new asset.',
                            icon: 'success'
                        })
                    }
                })
            }
        }
    })

    $('#frequencyType').on('change', function() {
        let data = $('#frequencyType option:selected').val();
        v.frequencyType = data;
    })
    $('#status').on('change', function() {
        let data = $('#status option:selected').val();
        v.status = data;
    })
    $('#type').on('change', function() {
        let data = $('#type option:selected').val();
        v.param.type = data;
    })
    $('#normal').on('change', function() {
        let data = $('#normal option:selected').val();
        v.param.normal = data;
    })
    $('#abnormal').on('change', function() {
        let data = $('#abnormal option:selected').val();
        v.param.abnormal = data;
    })

    $('#type').on('change', function() {
        if ($(this).val() == 'Select') {
            $('.typeSelect').show();
            $('.typeInput').hide();
            $('.typeCheckbox').hide();
        } else if ($(this).val() == 'Checkbox') {
            $('.typeCheckbox').show();
            $('.typeSelect').hide();
            $('.typeInput').hide();
        } else {
            $('.typeInput').show();
            $('.typeSelect').hide();
            $('.typeCheckbox').hide();
        }
    })

    $('#frequencyType').select2({
        theme: 'coreui'
    })

    $('#status').select2({
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
                return `<button class="btn btn-sm btn-primary" onclick="v.addTag()"><i class="fa fa-plus"></i> Add</button>`;
            }
        }
    })

    $('#location').select2({
        theme: 'coreui',
        placeholder: 'Select Location'
    })
    $('#type').select2({
        theme: 'coreui'
    })

    $('#normal').select2({
        theme: 'coreui',
        placeholder: "Select Item"
    })
    $('#abnormal').select2({
        theme: 'coreui',
        placeholder: "Select Item"
    })

    // type input, select, checkbox for add modal
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

    // select2 for add modal
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
        $('.showOn').select2({
            theme: 'coreui',
            placeholder: "Parameter Status",
            dropdownParent: $('#addParameterModal'),
        });
    });

    // type input, select, checkbox for edit modal
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

    // select2 for add modal
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
<?= $this->endSection(); ?>