<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<link href='https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
<script src='https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js'></script>
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main fixed-height">
            <div class="card-body">
                <div class="dt-search-input">
                    <div class="input-container">
                        <a href="javascript:void(0)" class="suffix text-decoration-none dt-search-hide"><i class="c-icon cil-x" style="font-size: 1.5rem;"></i></a>
                        <input name="dt-search" class="material-input" type="text" data-target="#tableLocation" placeholder="Search Data Transaction" />
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <h4><?= $title ?></h4>
                    <h5 class="header-icon">
                        <a href="<?= base_url('Location'); ?>" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</a>
                    </h5>
                </div>
                <div class="row mt-2">
                    <div class="col-6 h-100">
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="tagLocationName">Location Name</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="tagLocationName" v-model="tagLocationName" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="latitude">Latitude</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="latitude" v-model="latitude" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="longitude">Longitude</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="longitude" v-model="longitude" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="description">Description</label>
                            </div>
                            <div class="col-8">
                                <textarea class="form-control" id="description" v-model="description" rows="5" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 d-flex justify-content-end align-items-center">
                                <button class="btn btn-sm btn-outline-primary mr-1" type="button" @click="editLocation()" id="btnEdit"><i class="fa fa-edit"></i> Edit</button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary mr-1" type="button" @click="cancelEditLocation()" id="btnCancelEdit"><i class="fa fa-times"></i> Cancel</button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary mr-1" type="button" @click="deleteLocation()" id="btnDelete"><i class="fa fa-trash"></i> Delete</button>
                                <button style="display: none;" class="btn btn-sm btn-outline-primary mr-1" type="button" @click="saveEditLocation()" id="btnSaveEdit"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 imgMap h-100" style="border: 1px solid #d8dbe0;">
                        <div class="mt-2 mb-2" id="mapLocation" style="width: 100% !important; height: 300px"></div>
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
            myModal: '',
            tagLocationId: "<?= $location['tagLocationId']; ?>",
            tagLocationName: "<?= $location['tagLocationName']; ?>",
            latitude: "<?= $location['latitude']; ?>",
            longitude: "<?= $location['longitude']; ?>",
            description: "<?= $location['description']; ?>",
        },
        methods: {
            editLocation() {
                $("input[type=text]").removeAttr("readonly");
                $('textarea[id=description]').removeAttr("readonly");
                $('#btnCancelEdit').show();
                $('#btnEdit').hide();
                $('#btnSaveEdit').show();
                $('#btnDelete').show();
            },
            cancelEditLocation() {
                $("input[type=text]").attr("readonly", "readonly");
                $('textarea[id=description]').attr("readonly", "readonly");
                $('#btnEdit').show();
                $('#btnCancelEdit').hide();
                $('#btnSaveEdit').hide();
                $('#btnDelete').hide();
            },
            saveEditLocation() {
                axios.post("<?= base_url('Location/update'); ?>", {
                    tagLocationId: this.tagLocationId,
                    tagLocationName: this.tagLocationName,
                    latitude: this.latitude,
                    longitude: this.longitude,
                    description: this.description
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
            deleteLocation() {
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
                        axios.post("<?= base_url('Location/delete'); ?>", {
                            tagLocationId: this.tagLocationId
                        }).then(res => {
                            if (res.data.status == 'success') {
                                swalWithBootstrapButtons.fire({
                                    title: 'Success!',
                                    text: res.data.message,
                                    icon: 'success',
                                    allowOutsideClick: false
                                }).then(okay => {
                                    if (okay) {
                                        swal.fire({
                                            title: 'Please Wait!',
                                            text: 'Redirecting..',
                                            onOpen: function() {
                                                swal.showLoading()
                                            }
                                        })
                                        window.location.href = "<?= base_url('Location/'); ?>";
                                    }
                                })
                            }
                        })
                    }
                })
            }
        }
    })
    $(document).ready(function() {
        mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';
        const map = new mapboxgl.Map({
            container: 'mapLocation', // container ID
            style: 'mapbox://styles/mapbox/streets-v11', // style URL
            center: [<?= $location['longitude']; ?>, <?= $location['latitude']; ?>], // starting position [lng, lat]
            zoom: 14, // starting zoom
        });
        map.addControl(new mapboxgl.FullscreenControl());
        map.resize();
        const marker = new mapboxgl.Marker()
            .setLngLat([<?= $location['longitude']; ?>, <?= $location['latitude']; ?>])
            .addTo(map);
    })
</script>
<?= $this->endSection(); ?>