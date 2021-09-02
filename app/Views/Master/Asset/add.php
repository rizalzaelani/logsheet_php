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
                    <div class="form-group mt-3">
                        <form action="">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5><b>Add Asset</b></h5>
                                <a class="btn btn-sm btn-success" href="<?= base_url('Asset'); ?>"><i class="fa fa-arrow-left"></i> Back</a>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <label class="col-2" for="assetName">Asset Name</label>
                                <input class="col-10 form-control" type="text" placeholder="Asset Name">
                            </div>
                            <div class="row mb-3">
                                <label class="col-2" for="assetNumber">Asset Number</label>
                                <input class="col-10 form-control" type="text" placeholder="Asset Number">
                            </div>
                            <div class="row mb-3">
                                <label class="col-2" for="description">Description</label>
                                <input class="col-10 form-control" type="text" placeholder="Description">
                            </div>
                            <div class="row mb-3">
                                <label class="col-2" for="frequencyType">Frequency Type</label>
                                <div class="col-10 p-0">
                                    <select name="frequencyType" id="frequencyType">
                                        <option value="Daily">Daily</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-2" for="tag">Tag</label>
                                <div class="col-10 p-0">
                                    <select name="tag" id="tag" multiple>
                                        <option value="CCTV">CCTV</option>
                                        <option value="ROUTER">ROUTER</option>
                                        <option value="IT">IT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-2" for="location">Location</label>
                                <div class="col-10 p-0">
                                    <select name="location" id="location">
                                        <option value="GEDUNG PARKIR">GEDUNG PARKIR</option>
                                        <option value="GEDUNG MESIN">GEDUNG MESIN</option>
                                        <option value="GEDUNG FINANCE">GEDUNG FINANCE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center" id="param">
                                <h5 class="mt-3"><b>Parameter</b></h5>
                                <button class="btn btn-sm btn-primary" @click="addParam()" type="button"><i class="fa fa-plus"></i> Add Parameter</button>
                            </div>
                            <hr>
                            <div>
                                <h5 class="mb-3">Parameter</h5>
                                <div class="row mb-3">
                                    <label class="col-2" for="parameter">Parameter</label>
                                    <input class="col-10 form-control" type="text" placeholder="Parameter Name">
                                </div>
                                <div class="row mb-3">
                                    <label class="col-2" for="photo">Photo</label>
                                    <input class="col-10 form-control" type="text" placeholder="Photo">
                                </div>
                                <div class="row mb-3">
                                    <label class="col-2" for="description">Description</label>
                                    <input class="col-10 form-control" type="text" placeholder="Description">
                                </div>
                                <div class="row mb-3">
                                    <label class="col-2" for="type">Type</label>
                                    <div class="col-10 p-0">
                                        <select name="type" id="type">
                                            <option value="Input">Input</option>
                                            <option value="Select">Select</option>
                                            <option value="Checkbox">Checkbox</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3 typeInput">
                                    <label class="col-2" for="min">Min</label>
                                    <input class="col-10 form-control" type="text" name="min" id="min" placeholder="Min Value">
                                </div>
                                <div class="row mb-3 typeInput">
                                    <label class="col-2" for="max">Max</label>
                                    <input class="col-10 form-control" type="text" name="max" id="max" placeholder="Max Value">
                                </div>
                                <div class="row mb-3 typeInput">
                                    <label class="col-2" for="uom">Unit Of Measure</label>
                                    <input class="col-10 form-control" type="text" name="uom" id="uom" placeholder="Unit Of Measure">
                                </div>
                                <div class="row mb-3 typeSelect" style="display: none;">
                                    <label class="col-2" for="normal">Normal</label>
                                    <div class="col-10 p-0">
                                        <select name="normal" id="normal" multiple>
                                            <option value="Item 1">Item 1</option>
                                            <option value="Item 2">Item 2</option>
                                            <option value="Item 3">Item 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3 typeSelect" style="display: none;">
                                    <label class="col-2" for="abnormal">Abnormal</label>
                                    <div class="col-10 p-0">
                                        <select name="abnormal" id="abnormal" multiple>
                                            <option value="Item 1">Item 1</option>
                                            <option value="Item 2">Item 2</option>
                                            <option value="Item 3">Item 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3 typeSelect" style="display: none;">
                                    <label class="col-2" for="option">Option</label>
                                    <div class="col-10 p-0">
                                        <select name="option" id="option" multiple>
                                            <option value="Item 1">Item 1</option>
                                            <option value="Item 2">Item 2</option>
                                            <option value="Item 3">Item 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center">
                                <button class="btn btn-success">Submit</button>
                            </div>
                        </form>
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
            tag: this.addParam,
        },
        methods: {
            addParam() {
                // create a new div element
                const newDiv = document.createElement("div");

                // and give it some content
                const newContent = document.createTextNode("Hi there and greetings!");

                // add the text node to the newly created div
                newDiv.appendChild(newContent);
            }
        }
    })

    $('#type').on('change', function() {
        if ($(this).val() == 'Select') {
            $('.typeSelect').show();
            $('.typeInput').hide();
        } else {
            $('.typeSelect').hide();
            $('.typeInput').show();
        }
    })

    $('#frequencyType').select2({
        theme: 'coreui'
    })
    $('#tag').select2({
        theme: 'coreui',
        placeholder: "Select Tag",
        allowClear: true,
        escapeMarkup: function(markup) {
            return markup;
        },
        language: {
            noResults: function() {
                return `<button class="btn btn-sm btn-primary">Add</button>`;
            }
        }
    })
    $('#location').select2({
        theme: 'coreui'
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
    $('#option').select2({
        theme: 'coreui',
        placeholder: "Select Item"
    })
</script>
<?= $this->endSection(); ?>