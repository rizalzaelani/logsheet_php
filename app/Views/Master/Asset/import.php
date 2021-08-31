<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main card-border-top">
            <div class="container-fluid">
                <div class="card-header d-flex flex-row justify-content-between align-items-center">
                    <h4 class="title"><?= (isset($subtitle) ? $subtitle : '')  ?></h4>
                    <div>
                        <a data-toggle="tooltip" data-placement="top" title="Back" href="<?= base_url('/Asset'); ?>" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-12">
                            <h5>Download Template Import Data</h5>
                            <a data-toggle="tooltip" data-placement="top" title="Download Template" href="<?= base_url('/Asset/download'); ?>" class="btn btn-sm btn-primary text-white"><i class="fa fa-download"></i> Template Excel</a>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col">
                            <h5>Import Data</h5>
                            <!-- id="uploadexcel" -->
                            <i>*Ketentuan Import</i>
                            <ol>
                                <li>Format file harus .xlsx, .xls</li>
                                <!-- <li>File harus berisi min 1 baris data</li> -->
                            </ol>
                            <div class="mt-2">
                                <input type="file" class="my-pond" name="filepond" />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-4" id="tbl" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">Preview Data</h4>
                            <div>
                                <button class="btn btn-sm btn-danger" id="btnCancel" @click="btnCancel()"><i class="fa fa-times"></i> Cancel</button>
                                <button class="btn btn-sm btn-success" id="btnUpload" @click="btnUpload()"><i class="fa fa-upload"></i> Upload</button>
                            </div>
                        </div>
                        <table class="table table-hover w-100" id="tableImport">
                            <thead class="bg-primary">
                                <tr>
                                    <th style="border-top-left-radius: 5px;"><input type="checkbox" name="select_all" id="example-select-all" value="1"></th>
                                    <th>Company</th>
                                    <th>Area</th>
                                    <th>Unit</th>
                                    <th style="border-top-right-radius: 5px;">Equipment</th>
                                </tr>
                            </thead>
                        </table>
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
    var adminequip_id = [];
    var company = [];
    var area = [];
    var unit = [];
    var equipment = [];
    var selectedEquipmentData = [];
    let v = new Vue({
        el: '#app',
        data: () => ({
            dataImport: '',
            table: '',
            adminequip_id: null,
            company: null,
            area: null,
            unit: null,
            equipment: null,
            myModal: null,
            data: [],
            myModal: null,
            file: '',
            currentRoute: window.location.pathname,
        }),
        methods: {
            GetData() {
                this.table = $('#tableImport').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    responsive: true,
                    order: [
                        [3, 'desc']
                    ],
                    data: v.dataImport,
                    columns: [{
                            data: "company",
                            name: "company",
                        },
                        {
                            data: "area",
                            name: "area"
                        },
                        {
                            data: "unit",
                            name: "unit"
                        },
                        {
                            data: "equipment",
                            name: "equipment",
                        },
                        // {
                        // 	data: "adminequip_id",
                        // 	name: "adminequip_id"
                        // },
                    ],
                });
            },
            async uploadFile() {
                this.file = this.$refs.file.files[0];
                let formData = new FormData();
                FormData.append = ('importexcel', this.file);

                await fetch('<?= base_url('Asset/getDataImport'); ?>', {
                    method: 'POST',
                    body: data,
                }).then(y => {
                    console.log(success);
                })
            },
            btnCancel() {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger ml-1'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You will not upload this file!.",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: "<i class='fa fa-times'></i> Back",
                    confirmButtonText: "<i class='fa fa-check'></i> Yes, cancel upload!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire(
                            'Cancelled!',
                            'You cancelled uploading this file.',
                            'error'
                        ).then(okay => {
                            if (okay) {
                                // loading('show');
                                swal.fire({
                                    title: 'Please Wait!',
                                    text: 'Reload page..',
                                    onOpen: function() {
                                        swal.showLoading()
                                    }
                                })
                                window.location.href = "<?= base_url('Asset/import'); ?>";
                            }
                        })
                    }
                })
            },
            btnUpload() {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger ml-1'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Upload this file?',
                    text: "You will upload this file!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: "<i class='fa fa-times'></i> Cancel",
                    confirmButtonText: "<i class='fa fa-check'></i> Yes, upload!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        let data = selectedEquipmentData;
                        if (data.length > 0) {
                            axios.post("<?= base_url('Asset/insertExcel'); ?>", {
                                    data
                                })
                                .then(res => {
                                    console.log(res);
                                    swalWithBootstrapButtons.fire(
                                        'Success!',
                                        'You have successfully uploaded this file.',
                                        'success'
                                    ).then(okay => {
                                        if (okay) {
                                            // loading('show');
                                            swal.fire({
                                                title: 'Please Wait!',
                                                text: 'Redirecting..',
                                                onOpen: function() {
                                                    swal.showLoading()
                                                }
                                            })
                                            window.location.href = "<?= base_url('Asset'); ?>";
                                        }
                                    })
                                })
                        } else {
                            const swalWithBootstrapButtons = swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            })
                            swalWithBootstrapButtons.fire(
                                'Failed!',
                                "You didn't add any data.",
                                'error'
                            )
                        }
                    } else if (
                        /* Read more about handling dismissals below */
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
                            'You cancelled uploading this file.',
                            'error'
                        )
                    }
                })
            },
        }
    });

    $(document).ready(function() {
        $('#uploadexcel').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '<?= base_url('Asset/getDataImport'); ?>',
                type: "post",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                async: true,
                success: function(data) {
                    alert("Upload File Successful.");
                }
            });
        });
    });
    const pond = $('.my-pond').filepond({
        instantUpload: true,
        allowMultiple: false,
        credits: false,
        acceptedFileTypes: 'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xlsx',
        server: {
            process: {
                url: '<?= base_url('/Asset/getDataImport') ?>',
                onload: (res) => {
                    var rsp = JSON.parse(res);
                    if (rsp.status == "success") {
                        importList = rsp.data;
                        if (importList.length > 0) {
                            v.dataImport = importList;
                            loadListImport(importList);
                            $('#tbl').show();
                            $('.my-pond').filepond('removeFiles');

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
            },
            fetch: null,
            revert: null,
        }
    });

    var foo = function(settings, val) {
        table = $('#tableImport').DataTable();
        $('#tableImport tbody').on('change', 'tr td input[type="checkbox"]', function() {
            var row = $(this).parents('tr');
            var dataChecked = table.row(row).data();
            if (this.checked) {
                $(row).addClass("selected-row");
                selectedEquipmentData.push({
                    adminequip_id: dataChecked.adminequip_id,
                    company: dataChecked.company,
                    area: dataChecked.area,
                    unit: dataChecked.unit,
                    equipment: dataChecked.equipment
                });
                // if (!_.find(selectedEquipmentData, function(val) {
                //         return val.adminequip_id = dataChecked.adminequip_id;
                //     })) {
                //     $(row).addClass("selected-row");
                //     selectedEquipmentData.push({
                //         adminequip_id: dataChecked.adminequip_id,
                //         company: dataChecked.company,
                //         area: dataChecked.area,
                //         unit: dataChecked.unit,
                //         equipment: dataChecked.equipment
                //     });
                // }
            } else {
                // _.find(selectedEquipmentData, function(val) {
                //     return val.adminequip_id != dataChecked.adminequip_id;
                //     // console.log(selectedEquipmentData);
                // });
                $(row).removeClass("selected-row");
                selectedEquipmentData = _.without(selectedEquipmentData, dataChecked.adminequip_id);
                // console.log(selectedEquipmentData);
            }
        });

        $('#tableImport thead tr input[type="checkbox"]').on('click', function() {
            if (this.checked) {
                selectedEquipmentData = importList;
                $('#tableImport tbody tr td input[type="checkbox"]').prop('checked', true);
                $('#tableImport tbody tr td input[type="checkbox"]').parents('tr').addClass('selected-row');
            } else {
                selectedEquipmentData = [];
                $('#tableImport tbody tr td input[type="checkbox"]').prop('checked', false);
                $('#tableImport tbody tr td input[type="checkbox"]').parents('tr').removeClass('selected-row');
            }
        })
    }
    var loadListImport = (importList) => {
        var table = $('#tableImport').DataTable({
            "processing": false,
            "serverSide": false,
            "scrollX": false,
            "paging": false,
            "dom": `<"d-flex justify-content-between align-items-center"<i><f>>t`,
            "data": importList,
            "columns": [{
                    "data": "adminequip_id"
                },
                {
                    "data": "company"
                }, {
                    "data": "area"
                }, {
                    "data": "unit"
                }, {
                    "data": "equipment"
                }
            ],
            "columnDefs": [{
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                "render": function(data, type, row, meta) {
                    let checked = "";
                    if (selectedEquipmentData.includes(row.adminequip_id)) {
                        checked = "checked";
                    }
                    return `
                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="checkbox_${data}" data-id="${data}" ${checked}>
                        <label class="custom-control-label" for="checkbox_${data}"></label>
                        </div>
                        `;
                },
            }],
            "rowCallback": function(row, data) {
                if (_.find(selectedEquipmentData, function(val) {
                        return val.adminequip_id == data.adminequip_id;
                    })) {
                    $(row).addClass('selected-row');
                    $(row).children().first().children().children().first().prop("checked", true);
                } else {
                    $(row).removeClass('selected-row');
                    $(row).children().first().children().children().first().prop("checked", false);
                }
            },
            "initComplete": foo,
            "order": [1, 'asc'],
        });
    }
</script>
<?= $this->endSection(); ?>