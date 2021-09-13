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
                        <a href="javascript:;" class="dt-search" data-target="#tableLocation"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
                        <a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= base_url('/Location/add'); ?>"><i class="fa fa-plus mr-2"></i> Add Location</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('/Location/import'); ?>"><i class="fa fa-upload mr-2"></i> Import Data</a>
                            <a class="dropdown-item" href="<?= base_url('/Location/export'); ?>"><i class="fa fa-file-excel mr-2"></i> Export Data</a>
                        </div>
                    </h5>
                </div>
                <div class="row mt-2">
                    <div class="col-6 h-100">
                        <table class="table mt-2">
                            <tr class="mt-2">
                                <th>Location Name</th>
                                <td>:</td>
                                <td><?= $location['tagLocationName']; ?></td>
                            </tr>
                            <tr class="mt-2">
                                <th>Latitude</th>
                                <td>:</td>
                                <td><?= $location['latitude']; ?></td>
                            </tr>
                            <tr class="mt-2">
                                <th>Longitude</th>
                                <td>:</td>
                                <td><?= $location['longitude']; ?></td>
                            </tr>
                            <tr class="mt-2">
                                <th>Description</th>
                                <td>:</td>
                                <td><?= $location['description']; ?></td>
                            </tr>
                            <tr class="mt-1">
                                <th>Action</th>
                                <td>:</td>
                                <th class="d-flex justify-content-start align-items-center">
                                    <button class="btn btn-sm mr-1" type="button" @click="editLocation()" id="btnEdit"><i class="fa fa-edit mr-1"></i>Edit</button>
                                    <button class="btn btn-sm mr-1" type="button" @click="deleteLocation()"><i class="fa fa-trash mr-1"></i>Delete</button>
                                </th>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6 imgMap h-100" style="border: 1px solid #d8dbe0;">
                        <div class="mt-2 mb-2" id="mapLocation" style="width: 100% !important; height: 300px"></div>
                    </div>
                </div>

                <!-- Modal Edit-->
                <div class="modal fade" id="modalLocation" tabindex="-1" role="dialog" aria-labelledby="modalLocationTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLocationTitle">Edit Location</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="form-group">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="tagLocationName">Tag Location Name <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="tagLocationName"></i></label>
                                        <input id="tagLocationName" type="text" class="form-control" required v-model="location">
                                    </div>
                                    <div class="mb-3">
                                        <label for="latitude">Latitude <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="latitude"></i></label>
                                        <input id="latitude" type="text" class="form-control" required v-model="latitude">
                                    </div>
                                    <div class="mb-3">
                                        <label for="longitude">Longitude <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="longitude"></i></label>
                                        <input id="longitude" type="text" class="form-control" required v-model="longitude">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description">Description <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="longitude"></i></label>
                                        <input id="description" type="text" class="form-control" required v-model="description">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button class="btn btn-primary" @click="update()">Save changes</button>
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
            myModal: '',
            tagLocationId: "<?= $location['tagLocationId']; ?>",
            location: "<?= $location['tagLocationName']; ?>",
            latitude: "<?= $location['latitude']; ?>",
            longitude: "<?= $location['longitude']; ?>",
            description: "<?= $location['description']; ?>",
        },
        methods: {
            editLocation() {
                this.myModal = new coreui.Modal(document.getElementById('modalLocation'), {});
                this.myModal.show();
            },
            update() {
                axios.post("<?= base_url('Location/update'); ?>", {
                    tagLocationId: this.tagLocationId,
                    tagLocationName: this.location,
                    latitude: this.latitude,
                    longitude: this.longitude,
                    description: this.description
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
                            text: 'You have successfully update data.',
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
                                    text: 'You have successfully deleted this data.',
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
                                        window.location.href = "<?= base_url('Asset'); ?>";
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