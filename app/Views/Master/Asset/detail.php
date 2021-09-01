<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>

<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main card-border-top">
            <div class="container-fluid">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between align-items-center w-100">
                        <ul class="nav nav-tabs w-100 d-flex flex-row align-items-center" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#detail" role="tab" aria-controls="detail">
                                    <svg class="c-icon">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-list-rich"></use>
                                    </svg> Detail <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>On this tab, you can read equipment data, edit, and delete the data. And also you can read the log of changes that have occurred to the equipment data.</div>"></i></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#parameter" role="tab" aria-controls="parameter">
                                    <svg class="c-icon">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-timeline"></use>
                                    </svg> Parameter <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>On this tab, you can read the parameter data of an equipment</div>"></i></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting" role="tab" aria-controls="setting">
                                    <svg class="c-icon">
                                        <use xlink:href="/icons/coreui/svg/linear.svg#cil-cog"></use>
                                    </svg> Setting <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='tooltipClass'>In this tab, you can change the settings on an equipment</div>"></i></a></li>
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
                                            <th>Asset</th>
                                            <td>: IPC</td>
                                        </tr>
                                        <tr class="mt-1">
                                            <th>Number</th>
                                            <td>: 001</td>
                                        </tr>
                                        <tr class="mt-1">
                                            <th>Tag</th>
                                            <td>: ROUTER</td>
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
                                        <tr class="mt-1">
                                            <th>Action</th>
                                            <td>: <button class="btn btn-sm btn-primary mr-1" type="button" @click="getDataUpdate()"><i class="fa fa-edit"></i> Edit</button>
                                                <button class="btn btn-sm btn-danger mr-1" type="button" @click="handleDelete()"><i class="fa fa-trash"></i> Delete</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-6 d-flex flex-row align-items-center" style="border: 1px solid #d8dbe0;">
                                    <img src="/img/logo-act.png" alt="Image" class="img-thumbnail m-0">
                                </div>
                            </div>
                            <div class="row">
                                <div class="mt-2 col-12">
                                    <h5>Change Log</h5>
                                </div>
                                <div class="table-responsive w-100 mt-2 col-12">
                                    <table class="table table-hover">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th style="border-top-left-radius: 5px;">#</th>
                                                <th>Asset</th>
                                                <th>Number</th>
                                                <th>Tag</th>
                                                <th>Location</th>
                                                <th>Frequency</th>
                                                <th>Description</th>
                                                <!-- <th style="border-top-right-radius: 5px;">Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 1; $i < 6; $i++) { ?>
                                                <tr>
                                                    <td><?= $i; ?></td>
                                                    <td>log asset</td>
                                                    <td>log number</td>
                                                    <td>log tag</td>
                                                    <td>log location</td>
                                                    <td>log frequency</td>
                                                    <td>log description</td>
                                                    <!-- <td class="d-flex justify-content-between align-items-center">
                                                            <a href="" class="btn btn-sm btn-success mr-1"><i class="fa fa-edit"></i></a>
                                                            <a href="" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                                        </td> -->
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- tab parameter -->
                        <div class="tab-pane" id="parameter" role="tabpanel">
                            <button class="btn btn-sm btn-success mt-2" @click="addParameter()"><i class="fa fa-plus"></i> Add Parameter</button>
                            <div class="table-responsive mt-2">
                                <table class="table table-hover">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Photo</th>
                                            <th>Description</th>
                                            <th>UoM</th>
                                            <th>Min</th>
                                            <th>Max</th>

                                            <th>Show On</th>
                                            <th style="border-top-right-radius: 5px;">Action</th>
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
                                                    <input id="adminequip_id" readonly type="text" class="form-control" name="adminequip_id">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="company">Company <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="company"></i></label>
                                                <input id="company" type="text" class="form-control" name="company">
                                            </div>
                                            <div class="mb-3">
                                                <label for="area">Area <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="area"></i></label>
                                                <input id="area" type="text" class="form-control" name="area">
                                            </div>
                                            <div class="mb-3">
                                                <label for="unit">Unit <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="unit"></i></label>
                                                <input id="unit" type="text" class="form-control" name="unit">
                                            </div>
                                            <div class="mb-3">
                                                <label for="equipment">Equipment <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="equipment"></i></label>
                                                <input id="equipment" type="text" class="form-control" name="equipment">
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

                    <!-- modal add parameter-->
                    <div class="modal fade" role="dialog" id="addParameterModal">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                                                <input type="text" class="form-control col-9" name="photo" placeholder="Photo">
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
                                                <input type="text" class="form-control col-9 description" name="description" placeholder="Description of parameter">
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

                    <!-- modal edit parameter-->
                    <div class="modal fade" role="dialog" id="editParameterModal">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                                                <input type="text" class="form-control col-9" name="photo" placeholder="Photo">
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
                                                <input type="text" class="form-control col-9 description" name="description" placeholder="Description of parameter">
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
<script>
    let v = new Vue({
        el: '#app',
        data: {
            myModal: ''
        },
        methods: {
            addParameter() {
                this.myModal = new coreui.Modal(document.getElementById('addParameterModal'), {});
                this.myModal.show();
            },
            editParameter() {
                this.myModal = new coreui.Modal(document.getElementById('editParameterModal'), {});
                this.myModal.show();
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
                    title: 'Delete this data?',
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
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        const swalWithBootstrapButtons = swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'You cancel deleting this data.',
                            'error'
                        )
                    }
                })
            }
        }
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
        } else {
            $('.typeSelect').hide();
            $('.typeInput').show();
        }
    })


    // select2 edit parameter
    $(document).ready(function() {
        $('.type').select2({
            theme: 'coreui',
            placeholder: "Select Type",
            dropdownParent: $('#editParameterModal'),
        });
    });

    $(document).ready(function() {
        $('.normal').select2({
            theme: 'coreui',
            placeholder: "Select Type",
            dropdownParent: $('#editParameterModal'),
        });
    });
    $(document).ready(function() {
        $('.abnormal').select2({
            theme: 'coreui',
            placeholder: "Select Type",
            dropdownParent: $('#editParameterModal'),
        });
    });
    $(document).ready(function() {
        $('.option').select2({
            theme: 'coreui',
            placeholder: "Select Type",
            dropdownParent: $('#editParameterModal'),
        });
    });
    $(document).ready(function() {
        $('.showOn').select2({
            theme: 'coreui',
            placeholder: "Parameter Status",
            dropdownParent: $('#editParameterModal'),
        });
    });
</script>


<?= $this->endSection(); ?>