<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main card-border-top">
            <div class="container-fluid">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <h4 class="title">Preview Data</h4>
                        <div class="btn-group">
                            <button data-toggle="tooltip" data-placement="top" title="Cancel Upload" class="btn btn-sm btn-danger mr-1" id="cancel" @click="btnCancel()"><i class="fa fa-times"></i> Cancel</button>
                            <button data-toggle="tooltip" data-placement="top" title="Upload" class="btn btn-sm btn-success" @click="insertData()"><i class="fa fa-upload"></i> Upload</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table w-100 table-bordered">
                        <thead style="text-align: center;" class="bg-success">
                            <tr>
                                <th>#</th>
                                <th>Company</th>
                                <th>Area</th>
                                <th>Unit</th>
                                <th>Equipment</th>
                            </tr>
                        </thead>
                        <tbody style="text-align: center;">
                            <?php $i = 1;
                            foreach ($get as $item) : ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td class="company"><?= $item['company']; ?></td>
                                    <td class="area"><?= $item['area']; ?></td>
                                    <td class="unit"><?= $item['unit']; ?></td>
                                    <td class="equipment"><?= $item['equipment']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<script>
    var getCompany = [];
    var getArea = [];
    var getUnit = [];
    var getEquipment = [];
    // get val company
    $('.company').each(function() {
        getCompany.push($(this).text());
    });
    // get val area
    $('.area').each(function() {
        getArea.push($(this).text());
    });
    // get val unit
    $('.unit').each(function() {
        getUnit.push($(this).text());
    });
    // get val equipment
    $('.equipment').each(function() {
        getEquipment.push($(this).text());
    });
    let v = new Vue({
        el: '#app',
        data: () => ({
            company: getCompany,
            area: getArea,
            unit: getUnit,
            equipment: getEquipment,
        }),
        created: function() {
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            this.getData();
        },
        methods: {
            getData() {
                var dt = this.data;
                return dt;
                loading('show');
            },
            insertData() {
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
                        axios.post("<?= base_url('Asset/insertExcel'); ?>", {
                            // adminequip_id: "1",
                            company: this.company,
                            area: this.area,
                            unit: this.unit,
                            equipment: this.equipment,
                        }).then(res => {
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
                        swalWithBootstrapButtons.fire(
                            'Cancelled!',
                            'You cancelled uploading this file.',
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
                                window.location.href = "<?= base_url('Asset/import'); ?>";
                            }
                        })
                    }
                })
            }
        }
    });
</script>
<?= $this->endSection(); ?>