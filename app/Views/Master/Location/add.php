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
                        <a href="<?= $_SERVER['HTTP_REFERER'] ?? site_url("role") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                    </h5>
                </div>
                <div class="row mt-2">
                    <div class="col-6 h-100">
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="tagLocationName">Location Name</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="tagLocationName" v-model="tagLocationName" placeholder="Location Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="latitude">Latitude</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="latitude" v-model="latitude" placeholder="Latitude">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="longitude">Longitude</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="longitude" v-model="longitude" placeholder="Longitude">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="description">Description</label>
                            </div>
                            <div class="col-8">
                                <textarea class="form-control" id="description" v-model="description" rows="5" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 d-flex justify-content-end align-items-center">
                                <button class="btn btn-md btn-success mr-1" type="button" @click="addLocation()" id="btnSaveEdit"><i class="fa fa-save"></i> Save</button>
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
    const { ref } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var tagLocationName = ref('');
            var latitude = ref('');
            var longitude = ref('');
            var description = ref('');

            function addLocation() {
                axios.post("<?= base_url('Location/addTagLocation'); ?>", {
                    tagLocationName: this.tagLocationName,
                    latitude: this.latitude,
                    longitude: this.longitude,
                    description: this.description
                }).then(res => {
                    if (res.data.status == 200) {
                        swal.fire({
                            icon: 'success',
                            title: res.data.message
                        }).then(okay => {
                            if (okay) {
                                swal.fire({
                                    title: 'Please Wait!',
                                    text: 'Redirecting..',
                                    onOpen: function() {
                                        swal.showLoading()
                                    }
                                })
                                window.location.href = '<?= base_url('/Location') ?>';
                            }
                        })
                    }else{
                        swal.fire({
                            icon: 'error',
                            title: res.data.message,
                        })
                    }
                })
            };

            return {
                tagLocationName,
                latitude,
                longitude,
                description,
                addLocation,
            }
        },
    }).mount('#app');

    mapboxgl.accessToken = 'pk.eyJ1Ijoicml6YWx6YWVsYW5pIiwiYSI6ImNrdDRpbXhxeDAyangybnF5djR4b3k2aTAifQ.iyKzoo6ca1BdaOtcaEShCw';
            const map = new mapboxgl.Map({
                container: 'mapLocation', // container ID
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
                        // coordinates.style.display = 'block';
                        let lat = lnglat.lat;
                        let long = lnglat.lng;
                        v.latitude = lat;
                        v.longitude = long;
                    }
                    marker.on('dragend', onDragEnd);
</script>
<?= $this->endSection(); ?>