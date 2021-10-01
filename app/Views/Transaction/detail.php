<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->

<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main pb-4">
            <div class="d-flex justify-content-between my-1">
                <h4><?= $title ?></h4>
                <h5 class="header-icon">
                    <a href="<?= base_url("Transaction") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                </h5>
            </div>

            <div class="row mt-2">
                <div class="col-sm-6">
                    <table class="table mt-2">
                        <tr class="mt-1">
                            <th style="width: 200px;">Asset</th>
                            <td>: <?= $scheduleTrxData["assetName"] ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th>Asset Number</th>
                            <td>: <?= $scheduleTrxData["assetNumber"] ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th>Tag</th>
                            <td>:
                                <?php
                                if($scheduleTrxData['tagName'] != '-'){
                                    $assetTagValue = (array_values(array_unique(explode(",", $scheduleTrxData['tagName']))));
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
                                if($scheduleTrxData['tagLocationName'] != '-'){
                                    $assetTagValue = (array_values(array_unique(explode(",", $scheduleTrxData['tagLocationName']))));
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
                            <th>Schedule Type</th>
                            <td>: <?= $scheduleTrxData["schType"] ?></td>
                        </tr>
                        <tr class="mt-1">
                            <th>Description</th>
                            <td>: <?= $scheduleTrxData["description"] ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6 d-none d-sm-flex flex-row align-items-center">
                    <img src="/img/logo-act.png" alt="Image" class="img-thumbnail m-0 border-0">
                </div>
                <div class="col-12 mb-4">
                    <hr />
                </div>
                <div class="col-12">
                    <div class="table-responsive table-record-fix-width">
                        <table class="table table-responsive-sm table-hover table-bordered table-outline" id="detailRecord">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Parameter</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Min / Abnormal</th>
                                    <th class="text-center">Max / Normal</th>
                                    <th class="text-center">UoM / Option</th>
                                    <th class="text-center">Value</th>
                                    <?php if ($scheduleTrxData["approvedAt"] != null) { ?>
                                        <th class="text-center">Action</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(val, key) in recordParameterData">
                                    <td class="text-center">{{ key+1 }}</td>
                                    <td>{{ val.parameterName }}</td>
                                    <td class="text-center">{{ val.description }}</td>

                                    <td class="text-center">
                                        <span v-if="!val.option" :class="!val.min ? 'font-italic' : ''">{{ !val.min ? "(Empty)" : val.min }}</span>
                                        <span v-else :class="!val.abnormal ? 'font-italic' : ''">{{ !val.abnormal ? "(Empty)" : val.abnormal }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span v-if="!val.option" :class="!val.max ? 'font-italic' : ''">{{ !val.max ? "(Empty)" : val.max }}</span>
                                        <span v-else :class="!val.normal ? 'font-italic' : ''">{{ !val.normal ? "(Empty)" : val.normal }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span v-if="!val.option" :class="!val.uom ? 'font-italic' : ''">{{ !val.uom ? "(Empty)" : val.uom }}</span>
                                        <span v-else :class="!val.option ? 'font-italic' : ''">{{ !val.option ? "(Empty)" : val.option }}</span>
                                    </td>
                                    <td class="text-center" :class="checkAbnormal(val)">{{ !val.value ? "(Empty)" : val.value }}</td>

                                    <?php if ($scheduleTrxData["approvedAt"] != null) { ?>
                                        <td class="text-center">
                                            <a :href="'<?= base_url() ?>/Finding/' + (isNullEmptyOrUndefined(val.findingId) ? 'issue?trxId=' + val.trxId : 'detail?findingId=' + val.findingId)" :target="(isNullEmptyOrUndefined(val.findingId) ? '' : '_blank')" class="btn btn-sm" v-if="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class != ''" :class="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class">{{ checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>').name }}</a>
                                        </td>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 my-4">
                    <div class="row">
                        <div class="col-12"></div>
                    </div>
                </div>
                <?php if ($scheduleTrxData["approvedAt"] == null) { ?>
                    <div class="col-12">
                        <button type="button" class="btn btn-success w-100" id="btnAppTrx" v-on:click="approveTrx()"><i class="fa fa-check"></i> Approve</button>
                    </div>
                <?php } ?>
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
            var recordParameterData = <?= json_encode($trxData ?? []) ?>;

            var test = Vue.ref('');

            Vue.onMounted(() => {
                // console.log("adshad");
            });

            return {
                recordParameterData,
                isNullEmptyOrUndefined,
                xhrThrowRequest,
                test
            }
        },
        methods: {
            approveTrx() {
                Swal.fire({
                    title: "Do You Want Approve This Transaction ?",
                    html: "<textarea id='notesApprove' class='form-control' placeholder='Leave a comment'></textarea>",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    let notesApprove = $("#notesApprove").val();
                    $("#btnAppTrx").html('<i class="fa fa-spinner fa-pulse"></i> Processing');
                    $("#btnAppTrx").attr("disabled", true);
                    if (result.value) {
                        Swal.fire({
                            title: "Wait a minute, Approve on processing",
                            icon: "info",
                            showCancelButton: false,
                            showConfirmButton: false
                        });

                        let response = axios.post("<?= base_url() ?>/Transaction/approveTrx", {
                            "scheduleTrxId": "<?= $scheduleTrxData["scheduleTrxId"]; ?>",
                            "approvedNotes": notesApprove
                        }).then((res) => {
                            xhrThrowRequest(res)
                                .then(() => {
                                    Swal.fire({
                                        title: res.data.message,
                                        icon: "success",
                                        timer: 3000
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                })
                                .catch((rej) => {
                                    if (rej.throw) {
                                        throw new Error(rej.message);
                                    }
                                });
                        });
                    } else {
                        $("#btnAppTrx").html('<i class="fa fa-check"></i> Approve');
                        $("#btnAppTrx").attr("disabled", false);
                    }
                });
            },
            checkAbnormal(val, approvedAt) {
                if (!isNullEmptyOrUndefined(approvedAt)) {
                    if (val.condition != 'Normal' & val.condition != '' & val.condition != null & val.condition != undefined) {
                        if (val.findingId == null) {
                            return {
                                'class': 'btn-danger',
                                'name': 'Follow Up'
                            };
                        } else {
                            if (val.condition == 'Closed') {
                                return {
                                    'class': 'btn-primary',
                                    'name': 'Is Closed'
                                };
                            } else if (val.condition == 'Open') {
                                return {
                                    'class': 'btn-warning',
                                    'name': 'Is Followed Up'
                                };
                            } else {
                                return {
                                    'class': 'btn-danger',
                                    'name': 'Follow Up'
                                };
                            }
                        }
                    } else {
                        return {
                            'class': '',
                            'name': 'Normal'
                        };
                    }
                } else {
                    if (val.inputType == "input") {
                        if ((val.min != null & val.max != null & val.value >= val.min & val.value <= val.max) || (val.min != null & val.max == null & val.value >= val.min) || (val.min == null & val.max != null & val.value <= val.max) || (val.min == null & val.max == null) || isNaN(val.value)) {
                            return {
                                'class': '',
                                'name': 'Normal'
                            };
                        } else {
                            return {
                                'class': 'btn-danger',
                                'name': 'Follow Up'
                            };
                        }
                    } else if (val.inputType == "select") {
                        let itmAbnormal = val.abnormal.toLowerCase().split(",");
                        let isContain = _.includes(itmAbnormal, val.value.toLowerCase());
                        if (isNullEmptyOrUndefined(val.abnormal) || isNullEmptyOrUndefined(val.option) || isContain == false) {
                            return {
                                'class': '',
                                'name': 'Normal'
                            };
                        } else {
                            return {
                                'class': 'btn-danger',
                                'name': 'Follow Up'
                            };
                        }
                    } else {
                        return {
                            'class': '',
                            'name': 'Normal'
                        };
                    }
                }
            }
        }
    }).mount("#app")
</script>
<?= $this->endSection(); ?>