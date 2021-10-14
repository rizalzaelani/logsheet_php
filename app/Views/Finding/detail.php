<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-sm-7">
        <div class="card card-main">
            <div class="card-body">
                <div class="d-flex justify-content-between mt-1 mb-2">
                    <h4><?= $title ?></h4>
                    <h5 class="header-icon">
                        <a href="<?= base_url("Transaction") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                    </h5>
                </div>
                <table class="table mt-2">
                    <tr class="mt-1">
                        <th>Asset</th>
                        <td>: <?= $findingData['assetName'] ?></td>
                    </tr>
                    <tr class="mt-1">
                        <th>Asset Number</th>
                        <td>: <?= $findingData['assetNumber'] ?></td>
                    </tr>
                    <tr class="mt-1">
                        <th>Tag</th>
                        <td>:
                            <?php
                            if ($findingData['tagName'] != '-') {
                                $assetTagValue = (array_values(array_unique(explode(",", $findingData['tagName']))));
                                $length = count($assetTagValue);
                                for ($i = 0; $i < $length; $i++) { ?>
                                    <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">
                                        <?= $assetTagValue[$i]; ?>
                                    </span>
                            <?php }
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr class="mt-1">
                        <th>Location</th>
                        <td>:
                            <?php
                            if ($findingData['tagLocationName'] != '-') {
                                $assetTagValue = (array_values(array_unique(explode(",", $findingData['tagLocationName']))));
                                $length = count($assetTagValue);
                                for ($i = 0; $i < $length; $i++) { ?>
                                    <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">
                                        <?= $assetTagValue[$i]; ?>
                                    </span>
                            <?php }
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Parameter</th>
                        <td>: <?= $findingData['parameterName'] ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>: <?= $findingData['description'] ?></td>
                    </tr>
                    <tr>
                        <th>Min / Abnormal</th>
                        <td>: <?= ($findingData['option'] ?? '') == '' ? $findingData['min'] : $findingData['abnormal'] ?></td>
                    </tr>
                    <tr>
                        <th>Max / Normal</th>
                        <td>: <?= ($findingData['option'] ?? '') == '' ? $findingData['max'] : $findingData['normal'] ?></td>
                    </tr>
                    <tr>
                        <th>Unit Of Measure</th>
                        <td>: <?= $findingData['uom'] ?></td>
                    </tr>
                    <tr>
                        <th>Value</th>
                        <td>: <?= $findingData['value'] ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <?php if($findingData['condition'] == "Open"){ ?>
            <div class="card card-main">
                <div class="card-body">
                    <div class="d-flex justify-content-between mt-1 mb-2">
                        <h4>Form Timeline</h4>
                    </div>
                    <div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Notes..." v-model="timelineNotes"></textarea>
                        </div>
                        <div class="form-group d-flex justify-content-between">
                            <button class="btn btn-primary" type="button" @click="updateFindingLog();"><i class="fa fa-sync"></i> Update</button>
                            <button class="btn btn-outline-primary" @click="closeFinding();" id="closeFinding" type="button" style="margin-left: 10px;"><i class="fa fa-check"></i> Close Finding</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-sm-5">
        <div class="card card-main">
            <div class="card-body">
                <div class="d-flex justify-content-between mt-1 mb-4">
                    <h4>Timeline</h4>
                </div>
                <div class="history-tl-container">
                    <ul class="tl" id="listTimeline" v-for="(val, key) in timelineData">
                        <li class="tl-item" :class="key == 0 ? 'dot-danger' : (key == (timelineData.length - 1) & '<?= $findingData['condition'] ?>' == 'Closed' ? 'dot-primary' : 'dot-success')">
                            <div class="item-detail">{{ moment(val.createdAt).format("DD MMM YYYY HH:mm:ss") }}</div>
                            <div class="item-title font-weight-bold mb-2">{{ val.createdBy }}</div>
                            <div class="item-notes">{{ val.notes }}</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->

<script>
    let v = Vue.createApp({
        setup() {
            const findingData = <?= json_encode($findingData) ?>;

            let timelineData = Vue.reactive([]);
            const timelineNotes = Vue.ref("");

            //Function
            const getFindingLog = () => {
                axios.get("<?= site_url("Finding/getFindingLog") ?>?findingId=" + findingData.findingId) //, { findingId: findingData.findingId })
                    .then((res) => {
                        xhrThrowRequest(res)
                            .then(() => {
                                timelineData.splice(0);
                                timelineData.push(...res.data.data);
                            })
                            .catch((rej) => {
                                if (rej.throw) {
                                    throw new Error(rej.message);
                                }
                            });
                    });
            }

            const updateFindingLog = () => {
                let response = axios.post("<?= site_url("Finding/addFindingLog") ?>", {
                    findingId: findingData.findingId,
                    notes: timelineNotes.value
                }).then((res) => {
                    xhrThrowRequest(res)
                        .then(() => {
                            // Swal.fire({
                            //     title: res.data.message,
                            //     icon: "success",
                            //     timer: 3000,
                            //     toast: true,
                            // });

                            timelineData.push(res.data.data);

                            // getFindingLog();
                        })
                        .catch((rej) => {
                            if (rej.throw) {
                                throw new Error(rej.message);
                            }
                        });
                });
            };

            const closeFinding = () => {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do You Want to Close this Finding",
                    icon: "warning",
                    showCancelButton: true
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                            title: "Wait a minute, Data on Processing",
                            icon: "info",
                            showCancelButton: false,
                            showConfirmButton: false
                        });

                        document.getElementById("closeFinding").innerHTML = '<i class="fa fa-spinner fa-pulse"></i> Closing Finding';
                        document.getElementById("closeFinding").setAttribute("disabled", true);

                        axios.post("<?= base_url("Finding/closeFinding") ?>?findingId=" + findingData.findingId)
                            .then((res) => {
                                xhrThrowRequest(res)
                                    .then(() => {
                                        Swal.fire({
                                            title: res.data.message,
                                            icon: "success",
                                        }).then(() => {
                                            window.location.reload();
                                        })
                                    })
                                    .catch((rej) => {
                                        if (rej.throw) {
                                            throw new Error(rej.message);
                                        }
                                        
                                        document.getElementById("closeFinding").innerHTML = '<i class="fa fa-check"></i> Close Finding';
                                        document.getElementById("closeFinding").removeAttribute("disabled");
                                    });
                            });
                    }
                });
            }

            Vue.onMounted(() => {
                getFindingLog();
            });

            return {
                findingData,
                timelineData,
                timelineNotes,
                updateFindingLog,
                moment,
                closeFinding
            };
        }
    }).mount("#app")
</script>
<?= $this->endSection(); ?>