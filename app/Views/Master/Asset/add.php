<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main card-border-top">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5><?= $title; ?></h5>
                    <a class="btn btn-sm btn-success" href="<?= base_url('Asset'); ?>"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
                <div class="form-group mt-3">
                    <form method="post" enctype="multipart/form-data">
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
            <div class="modal fade" role="dialog" id="addParameterModal">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="titleModalAdd">Add Parameter</h5>
                            <h5 class="modal-title" id="titleModalEdit" style="display: none;">Edit Parameter</h5>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row mb-3">
                                        <label class="col-3" for="parameterName">Parameter <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>Parameter name for asset that you have.</div>"></i></label>
                                        <div class="col-9 p-0">
                                            <input type="text" class="form-control parameterName" name="parameterName" placeholder="Parameter Name" v-model="param.parameterName">
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
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3 typeSelect" style="display: none;">
                                        <label class="col-3" for="abnormal">Abnormal <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="abnormal"></i></label>
                                        <div class="col-9 p-0">
                                            <select class="form-control normalAbnormal abnormal" name="abnormal" id="abnormal" multiple>
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
                                        <textarea class="form-control col-9 description" rows="9" name="description" placeholder="Description of parameter" v-model="param.paramDesc"></textarea>
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
                            <button type="submit" class="btn btn-success" @click="addTempParameter()" id="btnAddParam"><i class="fa fa-plus"></i> Add Parameter</button>
                            <button type="button" class="btn btn-success" @click="updateParameter()" style="display: none;" id="btnUpdateParameter"><i class="fa fa-check"></i> Save Changes</button>
                            <button type="button" class="btn btn-success" @click="updateTempParameter()" style="display: none;" id="btnUpdateParam"><i class="fa fa-check"></i> Save Changes</button>
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
                        <div class="d-flex align-items-center">
                            <h5 class="p-0 m-0">
                                <b class="d-flex justify-content-start align-item-center">
                                    <svg class="c-icon">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-location-pin"></use>
                                    </svg>
                                    <p class="m-0"> Asset Tag Location</p>
                                </b>
                            </h5>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="tagLocation">Location</label>
                            <div class="col-md-9">
                                <select class="form-control" name="tagLocation" id="tagLocation" multiple="multiple">
                                    <?php foreach ($locationData as $val) : ?>
                                        <option class="optionLocation" value="<?= $val->tagLocationId; ?>"><?= $val->tagLocationName; ?></option>
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
                                <b class="d-flex justify-content-start align-item-center">
                                    <svg class="c-icon mr-1">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-tags"></use>
                                    </svg>
                                    <p class="m-0"> Asset Tag</p>
                                </b>
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
                                        <option value="<?= $val->tagId; ?>"><?= $val->tagName; ?></option>
                                    <?php endforeach; ?>
                                </select>
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
                    <div class="card card-main h-100" id="cardLocationTag">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="p-0 m-0">
                                <b class="d-flex justify-content-start align-item-center">
                                    <svg class="c-icon mr-1">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-calendar"></use>
                                    </svg>
                                    <p class="m-0"> Schedule</p>
                                </b>
                            </h5>
                        </div>
                        <hr>
                        <div>
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group row d-flex align-items-center">
                                    <div class="col-3">
                                        <label for="setSch">Set As<span class="required">*</span></label>
                                    </div>
                                    <div class="col-9">
                                        <select class="form-control" name="setSch" id="setSch">
                                            <option value="Manual" selected>Manual</option>
                                            <option value="Automatic">Automatic</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Field cannot be empty.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row d-flex align-items-center schType hide">
                                    <div class="col-3">
                                        <label for="schType">Schedule<span class="required">*</span></label>
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
                                                <input type="text" class="form-control" id="schFrequency" v-model="schFrequency" placeholder="Factor of 24">
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
                                                    <div class="invalid-feedback">
                                                        Field cannot be empty.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="" id="monthly" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-check">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="days">
                                                        <label class="form-check-label mr-1" for="gridRadios1">
                                                            Days
                                                        </label>
                                                    </div>
                                                    <div class="col-9 pl-0" id="days" style="display: none;">
                                                        <select name="monthly" class="form-control days" id="monthlyDays" multiple>
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
                                                    <div class="col-3">
                                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="on">
                                                        <label class="form-check-label mr-1" for="gridRadios2">
                                                            On
                                                        </label>
                                                    </div>
                                                    <div class="col-9" id="on" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-6 pr-1 pl-0">
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
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-6 pb-4">
                    <div class="card card-main h-100" id="cardLocationTag">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="p-0 m-0">
                                <b class="d-flex justify-content-start align-item-center">
                                    <svg class="c-icon mr-1">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-cog"></use>
                                    </svg>
                                    <p class="m-0"> Change Operation Mode</p>
                                </b>
                            </h5>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-3">
                                <label for="operation">Operation<span class="required">*</span></label>
                            </div>
                            <div class="col-9">
                                <select name="operation" id="operation">
                                    <option value="" selected disabled>Select Operation</option>
                                    <?php foreach ($statusData as $key) : ?>
                                        <option value="<?= $key->assetStatusId; ?>"><?= $key->assetStatusName ?></option>
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
        <div id="cardAssetTagging">
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
                                    <select name="tagging" id="taggingType" class="form-control">
                                        <option value="" selected disabled>Select Type</option>
                                        <option value="rfid">rfid</option>
                                        <option value="coordinat">coordinat</option>
                                        <option value="uhf">uhf</option>
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
                                    <input type="text" class="form-control" placeholder="Latitude value" v-model="assetLatitude">
                                </td>
                            </tr>
                            <tr class="mt-1" id="assetLong" style="display: none;">
                                <td>Longitude</td>
                                <td>:</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Longitude value" v-model="assetLongitude">
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
        <div class="card card-main" id="cardParameter">
            <div class="mt-2 d-flex justify-content-between align-items-center">
                <h5>
                    <b class="d-flex justify-content-start align-item-center">
                        <svg class="c-icon mr-1">
                            <use xlink:href="/icons/coreui/svg/linear.svg#cil-timeline"></use>
                        </svg>
                        <p class="m-0"> Parameter<span class="required">*</span></p>
                    </b>
                </h5>
                <div>
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
                            <td class="text-center">{{ items.paramDesc}}</td>
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
                                <button class="btn btn-sm btn-success mr-1" @click="editTempParameter(i)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" @click="removeTempParameter(i)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="cardSave">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center w-100">
                        <button class="btn btn-outline-primary w-100" type="button" @click="save()"><i class="fa fa-plus"></i> Add Asset</button>
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
        onMounted,
        ref,
        reactive
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var assetId = ref(uuidv4());
            var userId = ref(uuidv4());
            var assetName = ref('');
            var assetNumber = ref('');
            var assetDesc = ref('');
            var statusId = ref('');
            var statusName = ref('');
            var setSch = ref('');
            var schType = ref('');
            var schWeeks = ref('');
            var schWeekDays = ref('');
            var schMonthlyWeekDays = ref('');
            var schDays = ref('');
            var schFrequency = ref('');
            var onDays = ref('');
            var assetLatitude = ref('');
            var assetLongitude = ref('');
            var assetTaggingId = ref(uuidv4());
            var assetTaggingValue = ref('');
            var assetTaggingType = ref('');
            var assetTaggingDescription = ref('');
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
                    paramDesc: '',
                    uom: '',
                    min: null,
                    max: null,
                    normal: '',
                    abnormal: '',
                    option: '',
                    inputType: '',
                    showOn: '',
                }),
                params = ref([]);

            onMounted(() => {
                let dataAssetName   = assetName.value;
                let dataAssetNumber = assetNumber.value;
                let dataAssetDesc   = assetDesc.value;
                let dataStatusName  = statusName.value;
                let dataSetSch      = setSch.value;
                let dataSchWeeks    = schWeeks.value;
                let dataSchWeekDays = schWeekDays.value;
                let dataSchMonthlyWeekDays = schMonthlyWeekDays.value;
                let dataSchDays     = schDays.value;
                let dataSchFrequency= schFrequency.value;
                let dataOnDays      = onDays.value;
                let dataAssetLat    = assetLatitude.value;
                let dataAssetLong   = assetLongitude.value;
                let dataTaggingType = assetTaggingType.value;
                let dataTaggingValue= assetTaggingValue.value;
                let dataTaggingDesc = assetTaggingDescription.value;
                let dataTag         = tag.value.length;
                let dataLocation    = tagLocation.value.length;
                let dataTags        = tags.value.length;
                let dataLocations   = locations.value.length;
                let dataParams      = params.value.length;
                window.addEventListener('beforeunload', function(e) {
                    if (dataAssetName != v.assetName || dataAssetNumber != v.assetNumber || dataAssetDesc != v.assetDesc || dataStatusName != v.statusName || dataSchWeeks != v.schWeeks || dataSchWeeks != v.schWeeks || dataSchWeekDays != v.schWeekDays || dataSchDays != v.schDays || dataSchMonthlyWeekDays != v.schMonthlyWeekDays || dataSchFrequency != v.schFrequency || dataAssetLat != v.assetLatitude || dataAssetLong != v.assetLongitude || dataTaggingType != v.assetTaggingType || dataTaggingValue != v.assetTaggingValue || dataTaggingDesc != v.assetTaggingDescription || dataTags != v.tags.length || dataLocations != v.locations.length || dataParams != v.params.length || dataTag != v.tag.length || dataLocation != v.tagLocation.length ) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                })
                
            });
            return {
                assetId,
                userId,
                assetName,
                assetNumber,
                assetDesc,
                statusId,
                statusName,
                setSch,
                schType,
                schWeeks,
                schWeekDays,
                schMonthlyWeekDays,
                schDays,
                schFrequency,
                onDays,
                assetLatitude,
                assetLongitude,
                assetTaggingId,
                assetTaggingType,
                assetTaggingValue,
                assetTaggingDescription,
                tag,
                tagLocation,
                addTag,
                addLocation,
                tags,
                locations,
                myModal,
                param,
                params
            };
        },
        methods: {
            photo(event) {
                this.param.photo = event.target.files[0];
                let photo = URL.createObjectURL(event.target.files[0])
                console.log(photo);
            },
            addParameter() {
                $('#btnAddParam').show();
                $('#titleModalAdd').show();
                $('#btnUpdateParameter').hide();
                $('#btnUpdateParam').hide();
                $('#titleModalEdit').hide();
                this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                this.myModal.show();
                //addparam
                // this.params.push(this.param);
                // this.param = {
                //         parameterName: '',
                //         photo: '',
                //         inputType: '',
                //         min: '',
                //         max: '',
                //         uom: '',
                //         normal: '',
                //         abnormal: '',
                //         option: [],
                //         showOn: [],
                //         paramDesc: '',
                //     },
                //     $('#paramStatus option').prop('selected', function() {
                //         return this.defaultSelected;
                //     })
                // const swalWithBootstrapButtons = swal.mixin({
                //     customClass: {
                //         confirmButton: 'btn btn-success mr-1',
                //         cancelButton: 'btn btn-danger'
                //     },
                //     buttonsStyling: false
                // })
                // swalWithBootstrapButtons.fire({
                //     title: 'Success!',
                //     text: 'You have successfully add parameter.',
                //     icon: 'success'
                // })
            },
            addTempParameter() {
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
                        $('.parameterName').removeClass('is-invalid');
                    }
                    if (this.param.inputType != '') {
                        $('.type').removeClass('is-invalid');
                    }
                    if (this.param.showOn != '') {
                        $('.showOn').removeClass('is-invalid');
                    }

                    if (this.param.parameterName == '') {
                        $('.parameterName').addClass('is-invalid');
                    }
                    if (this.param.inputType == '') {
                        $('.type').addClass('is-invalid');
                    }
                    if (this.param.showOn == '') {
                        $('.showOn').addClass('is-invalid');
                    }
                } else {
                    if (this.param.parameterName != '') {
                        $('.parameterName').removeClass('is-invalid');
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
                        paramDesc: '',
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
                    $('#normal').val('').trigger("change");
                    $('#abnormal').val('').trigger('change');
                }
            },
            editTempParameter(index) {
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
                    paramDesc: this.params[index].paramDesc,
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
            updateTempParameter() {
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
                        paramDesc: this.param.paramDesc,
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
            },
            removeTempParameter(index) {
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
            btnCancelModalParam() {
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

            },
            modalAddTag() {
                this.myModal = new coreui.Modal(document.getElementById('modalAddTag'));
                this.myModal.show();
            },
            addAssetTag() {
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
                    this.addTag = {
                        addTagId: '',
                        addTagName: '',
                        addTagDesc: '',
                    }
                    this.myModal.hide();
                    this.addTag.addTagId = uuidv4();
                }
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
                        v.addLocation.addLocationLatitude = lat;
                        v.addLocation.addLocationLongitude = long;
                    }
                    marker.on('dragend', onDragEnd);
                })
            },
            addTagLocation() {
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
                    this.addLocation = {
                        addLocationId: '',
                        addLocationName: '',
                        addLocationLatitude: '',
                        addLocationLongitude: '',
                        addLocationDesc: '',
                    }
                    this.myModal.hide();
                    this.addLocation.addLocationId = uuidv4();
                }
            },
            save() {
                let factorFrom = 24;
                let schFreq = [];
                for (let index = 1; index <= factorFrom; index++) {
                    if (factorFrom % index == 0) {
                        schFreq.push(index)
                    }
                }
                let isFactorOf = schFreq.includes(parseInt(this.schFrequency));

                if (this.assetName == "" || this.assetNumber == "" || this.statusName == '' || this.assetTaggingValue == '' || this.assetTaggingType == '' || this.assetTaggingDescription == '' || $('#tableParameter tbody tr').length < 1) {
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
                    if (this.assetNumber != '' && $('#assetNumber').hasClass('is-invalid')) {
                        $('#assetNumber').removeClass('is-invalid');
                    }
                    if (this.schType != '' && $('#schType').hasClass('is-invalid')) {
                        $('#schType').removeClass('is-invalid');
                    }
                    if (isFactorOf != false && $('#schFrequency').hasClass('is-invalid')) {
                        $('#schFrequency').removeClass('is-invalid');
                    }
                    if (this.schWeekDays != '' && $('#schWeekDays').hasClass('is-invalid')) {
                        $('#schWeekDays').removeClass('is-invalid');
                    }
                    if (this.schDays != '' && $('#monthlyDays').hasClass('is-invalid')) {
                        $('#monthlyDays').removeClass('is-invalid');
                    }
                    if (this.schMonthlyWeekDays != '' && $('#monthlyOnDays').hasClass('is-invalid')) {
                        $('#monthlyOnDays').removeClass('is-invalid');
                    }
                    if (this.schWeeks != '' && $('#monthlyOn').hasClass('is-invalid')) {
                        $('#monthlyOn').removeClass('is-invalid');
                    }
                    if (this.statusName != '' && $('#operation').hasClass('is-invalid')) {
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
                    if (this.assetNumber == '') {
                        $('#assetNumber').addClass('is-invalid');
                    }
                    if (this.schType == '') {
                        $('#schType').addClass('is-invalid');
                    } else if (this.schType == "Daily") {
                        if (isFactorOf == false) {
                            $('#schFrequency').addClass('is-invalid');
                        }
                    } else if (this.schType == 'Weekly') {
                        if (this.schWeekDays == '') {
                            $('#schWeekDays').addClass('is-invalid');
                        }
                    } else if (this.schType == 'Monthly') {
                        if (v.onDays == 'days') {
                            if (v.schDays == '') {
                                $('#monthlyDays').addClass('is-invalid');
                            }
                        } else if (v.onDays == 'on') {
                            if (v.schWeeks == '' || v.schMonthlyWeekDays == '') {
                                if (v.schWeeks == '') {
                                    $('#monthlyOn').addClass('is-invalid');
                                } else {
                                    $('#monthlyOn').removeClass('is-invalid');
                                }
                                if (v.schMonthlyWeekDays == '') {
                                    $('#monthlyOnDays').addClass('is-invalid');
                                } else {
                                    $('#monthlyOnDays').removeClass('is-invalid');
                                }
                            }
                        }
                    }
                    if (this.statusName == '') {
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
                } else {
                    const factorFrom = 24;
                    const schFreq = [];
                    for (let index = 1; index <= factorFrom; index++) {
                        if (factorFrom % index == 0) {
                            schFreq.push(index)
                        }
                    }
                    let isFactorOf = schFreq.includes(parseInt(this.schFrequency));
                    if (this.assetName != '' && $('#assetName').hasClass('is-invalid')) {
                        $('#assetName').removeClass('is-invalid');
                    }
                    if (this.assetNumber != '' && $('#assetNumber').hasClass('is-invalid')) {
                        $('#assetNumber').removeClass('is-invalid');
                    }
                    if (this.statusName != '' && $('#operation').hasClass('is-invalid')) {
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
                    if (this.schType != '' && $('#schType').hasClass('is-invalid')) {
                        $('#schType').removeClass('is-invalid');
                    }
                    if (this.schWeekDays != '' && $('#schWeekDays').hasClass('is-invalid')) {
                        $('#schWeekDays').removeClass('is-invalid');
                    }
                    if (this.schDays != '' && $('#monthlyDays').hasClass('is-invalid')) {
                        $('#monthlyDays').removeClass('is-invalid');
                    }
                    if (this.schMonthlyWeekDays != '' && $('#monthlyOnDays').hasClass('is-invalid')) {
                        $('#monthlyOnDays').removeClass('is-invalid');
                    }
                    if (this.setSch == 'Manual') {
                        this.schType = '';
                        this.schWeeks = '';
                        this.schWeekDays = '';
                        this.schDays = '';
                        this.schFrequency = '';
                    } else if (this.setSch == 'Automatic') {
                        if (this.schType == '') {
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
                        } else if (this.schType == 'Daily') {
                            if (isFactorOf == false) {
                                $('#schFrequency').addClass('is-invalid');
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
                        } else if (this.schType == 'Weekly') {
                            if (this.schWeekDays == '') {
                                $('#schWeekDays').addClass('is-invalid');
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
                        } else if (this.schType == 'Monthly') {
                            if (v.onDays == 'days') {
                                if (v.schDays == '') {
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
                                if (v.schWeeks == '' || v.schMonthlyWeekDays == '') {
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
                                    if (v.schWeeks == '') {
                                        $('#monthlyOn').addClass('is-invalid');
                                    } else {
                                        $('#monthlyOn').removeClass('is-invalid');
                                    }
                                    if (v.schMonthlyWeekDays == '') {
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
                    formdata.append('assetId', this.assetId);
                    formdata.append('assetName', this.assetName);
                    formdata.append('assetNumber', this.assetNumber);
                    formdata.append('assetDesc', this.assetDesc);
                    formdata.append('latitude', this.assetLatitude);
                    formdata.append('longitude', this.assetLongitude);
                    formdata.append('schType', this.schType);
                    formdata.append('schFrequency', this.schFrequency);
                    formdata.append('schWeekDays', this.schWeekDays == '' ? this.schMonthlyWeekDays : schWeekDays);
                    formdata.append('schWeeks', this.schWeeks);
                    formdata.append('schDays', this.schDays);

                    // tag location
                    formdata.append('tagId', this.tag);
                    formdata.append('locationId', this.tagLocation);
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
            },
        }
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
            v.tag = $(this).val();
        })
        $('#tagLocation').on('change', function() {
            v.tagLocation = $(this).val();
        })
        $('#showOn').on('change', function() {
            v.param.showOn = $(this).val().toString();
        })
    })

    // select2 schedule on change
    $(document).ready(function() {
        let selected = $('#setSch').val();
        v.setSch = selected;
    })

    $('#setSch').on('change', function() {
        if ($(this).val() == 'Manual') {
            $('.schType').addClass('hide');
            $('#daily').hide();
            $('#weekly').hide();
            $('#monthly').hide();
        } else if ($(this).val() == 'Automatic') {
            $('.schType').removeClass('hide');
        }
    })

    $('#setSch').on('change', function() {
        v.setSch = $(this).val();
    })

    $('#schType').on('change', function() {
        if ($(this).val() == 'Daily') {
            $('#daily').show();
            $('#weekly').hide();
            $('#monthly').hide();
            v.schType = $(this).val();

            $(document).ready(function() {
                $('#schWeekDays').val("").trigger("change");

                $('#monthlyDays').val("").trigger("change");

                $('#monthlyOn').val("").trigger("change");

                $('#monthlyOnDays').val("").trigger("change");
            })

        } else if ($(this).val() == 'Weekly') {
            $('#weekly').show();
            $('#daily').hide();
            $('#monthly').hide();
            v.schType = $(this).val();
            v.schFrequency = null;
            $(document).ready(function() {
                $('#monthlyDays').val("").trigger("change");
                $('#monthlyOn').val("").trigger("change");
                $('#monthlyOnDays').val("").trigger("change");
            })

        } else if ($(this).val() == 'Monthly') {
            $(document).ready(function() {
                $('#schWeekDays').val("").trigger("change");
            })
            $('#monthly').show();
            $('#daily').hide();
            $('#weekly').hide();
            v.schType = $(this).val();
            v.schFrequency = null;

        }
    })

    $('#schWeekDays').on('change', function() {
        v.schWeekDays = $(this).val().toString();
    })

    $('#monthlyDays').on('change', function() {
        v.schDays = $(this).val().toString();
    })

    $('#monthlyOn').on('change', function() {
        v.schWeeks = $(this).val().toString();
    })

    $('#monthlyOnDays').on('change', function() {
        v.schMonthlyWeekDays = $(this).val().toString();
    })

    $('#schType').on('change', function() {
        let data = $('#schType option:selected').val();
        v.schType = data;
    })
    $('#operation').on('change', function() {
        let data = $('#operation option:selected').val();
        let text = $('#operation option:selected').text();
        v.statusId = data;
        v.statusName = text;
    })
    $('#taggingType').on('change', function() {
        let data = $('#taggingType option:selected').val();
        v.assetTaggingType = data;
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

    $('#setSch').select2({
        theme: 'coreui'
    })

    $('#schType').select2({
        theme: 'coreui'
    })

    $('#schWeekDays').select2({
        theme: 'coreui',
        placeholder: 'Select Days'
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

    $('#operation').select2({
        theme: 'coreui'
    })
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

    // map
    $(document).ready(function() {
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
            v.assetLatitude = lat;
            v.assetLongitude = long;
        }
        marker.on('dragend', onDragEnd);
    })
    $('.latlong').on('change', function() {
        if ($(this).is(':checked')) {
            v.checked = true;
            $('#assetLat').show();
            $('#assetLong').show();

            $('#divMap').show();
            $('#map').addClass('w-100');

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

    // filepond
    $(document).ready(function() {
        FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginImageEdit, FilePondPluginFileValidateType);
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

    //radio monthly
    $('input[type="radio"][name="gridRadios"]').on('change', function() {
        if ($(this).val() == "days") {
            $('#monthlyOn').val("").trigger("change");
            $('#monthlyOnDays').val("").trigger("change");
            $('#on').hide();
            $('#days').show();
            v.schWeeks = '';
            v.onDays = "days";

        } else if ($(this).val() == "on") {
            $('#monthlyDays').val("").trigger("change");
            $('#days').hide();
            $('#on').show();
            v.schDays = '';
            v.onDays = "on";
        }
    })
</script>
<?= $this->endSection(); ?>