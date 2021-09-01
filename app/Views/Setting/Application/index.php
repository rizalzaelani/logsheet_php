<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-main card-border-top fixed-height">
            <div class="container-fluid">
                <!-- <div class="card-header d-flex flex-row justify-content-between">
                    <h4 class="title"><?= (isset($subtitle) ? $subtitle : ''); ?></h4>
                    <div>
                        <button class="btn btn-sm btn-primary">button</button>
                    </div>
                </div> -->
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between align-items-center w-100">
                        <ul class="nav nav-tabs w-100 d-flex flex-row align-items-center" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#detail" role="tab" aria-controls="detail">
                                    <svg class="c-icon">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-list-rich"></use>
                                    </svg> Setting <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>On this tab, you can read equipment data, edit, and delete the data. And also you can read the log of changes that have occurred to the equipment data.</div>"></i></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#parameter" role="tab" aria-controls="parameter">
                                    <svg class="c-icon">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-timeline"></use>
                                    </svg> Numbering Code <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>On this tab, you can read the parameter data of an equipment</div>"></i></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting" role="tab" aria-controls="setting">
                                    <svg class="c-icon">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-cog"></use>
                                    </svg> .. <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>In this tab, you can change the settings on an equipment</div>"></i></a></li>
                        </ul>
                        <ul class="nav justify-content-end">
                            <li class="nav-item float-right">
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
                                            <th>1</th>
                                            <td>:</td>
                                        </tr>
                                        <tr class="mt-1">
                                            <th>2</th>
                                            <td>:</td>
                                        </tr>
                                        <tr class="mt-1">
                                            <th>3</th>
                                            <td>:</td>
                                        </tr>
                                        <tr class="mt-1">
                                            <th>4</th>
                                            <td>:</td>
                                        </tr>
                                        <tr class="mt-1">
                                            <th>5</th>
                                            <td>:</td>
                                        </tr>
                                        <tr class="mt-1">
                                            <th>6</th>
                                            <td>: <button class="btn btn-sm btn-primary mr-1" type="button" @click="getDataUpdate()"><i class="fa fa-edit"></i> Edit</button>
                                                <button class="btn btn-sm btn-danger mr-1" type="button" @click="handleDelete()"><i class="fa fa-trash"></i> Delete</button>
                                            </td>
                                            <!-- <td><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="action"></i></td> -->
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
                                <table class="table table-hover">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th style="border-top-left-radius: 5px;">#</th>
                                            <th>Column 1</th>
                                            <th>Column 2</th>
                                            <th>Column 3</th>
                                            <th style="border-top-right-radius: 5px;">Column 4</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 1; $i < 6; $i++) { ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td>Data 1</td>
                                                <td>Data 2</td>
                                                <td>Data 3</td>
                                                <td>Data 4</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- tab setting -->
                        <div class="tab-pane" id="setting" role="tabpanel">
                            <div class="row mt-2">
                                <div class="col">
                                    <h5>Setting</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal Edit-->
                    <div class="modal fade" tabindex="-1" role="dialog" id="modalEdit">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Data</h5>
                                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button> -->
                                </div>
                                <div class="modal-body">
                                    <div class="container form-group">
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="row mt-1" style="display: none;">
                                                <div class="col-4">
                                                    <label for="adminequip_id">ID <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="id"></i></label>
                                                </div>
                                                <div class="col-8">
                                                    <input id="adminequip_id" readonly type="text" class="form-control" name="adminequip_id" v-model="adminequip_idEdit">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="company">Company <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="company"></i></label>
                                                <input id="company" type="text" class="form-control" name="company" v-model="companyEdit">
                                            </div>
                                            <div class="mb-3">
                                                <label for="area">Area <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="area"></i></label>
                                                <input id="area" type="text" class="form-control" name="area" v-model="areaEdit">
                                            </div>
                                            <div class="mb-3">
                                                <label for="unit">Unit <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="unit"></i></label>
                                                <input id="unit" type="text" class="form-control" name="unit" v-model="unitEdit">
                                            </div>
                                            <div class="mb-3">
                                                <label for="equipment">Equipment <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="equipment"></i></label>
                                                <input id="equipment" type="text" class="form-control" name="equipment" v-model="equipmentEdit">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancel" @click="btnCancel()"><i class="fa fa-times"></i> Cancel</button>
                                    <button type="button" class="btn btn-primary" @click="update()"><i class="fa fa-check"></i> Update</button>
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
<?= $this->endSection(); ?>