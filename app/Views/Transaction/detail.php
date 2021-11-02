<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->

<style>
    .slide-button {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 40px;
        border-radius: 20px;
        background: #0000001f;
        padding: 0.5rem;
        border: 0;
    }

    .slide-button label {
        text-transform: capitalize;
        margin: 0;
        font-weight: bold;
        color: var(--success);
    }

    .slide-button button {
        transition: width 0.3s, border-radius 0.3s, height 0.3s;
        position: absolute;
        left: 0;
        width: 60px;
        height: 60px;
        border-radius: 30px;
    }

    .slide-button button.unlocked {
        transition: all 0.3s;
        left: 0 !important;
        width: inherit;
        background-color: #54df82;
    }

    .slide-button button i.font-xl {
        height: unset;
        width: unset;
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main pb-4">
            <div class="d-flex justify-content-between mt-1 mb-2">
                <h4><?= $title ?></h4>
                <h5 class="header-icon">
                    <a href="<?= base_url("Transaction") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                </h5>
            </div>

            <div class="row mt-2">
                <div class="col-md-7">
                    <table class="table mt-2">
                        <tr>
                            <th style="width: 200px;">Asset</th>
                            <td>{{ scheduleTrxData.assetName }}</td>
                        </tr>
                        <tr>
                            <th>Asset Number</th>
                            <td>{{ scheduleTrxData.assetNumber }}</td>
                        </tr>
                        <tr>
                            <th>Tag</th>
                            <td>
                                <template v-if="scheduleTrxData.tagName" v-for="(val, key) in _.uniq(scheduleTrxData.tagName.split(','))">
                                    <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                </template>
                                <template v-else>-</template>
                            </td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>
                                <template v-if="scheduleTrxData.tagLocationName" v-for="(val, key) in _.uniq(scheduleTrxData.tagLocationName.split(','))">
                                    <span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">{{ val }}</span>
                                </template>
                                <template v-else>-</template>
                            </td>
                        </tr>
                        <template v-if="scheduleTrxData.descriptionJson">
                            <template v-for="(val, key) in scheduleTrxData.descriptionJson">
                                <tr class="mt-1 descJson">
                                    <th class="text-capitalize">{{val.key.replace(/([A-Z])/g, " $1")}}</th>
                                    <td>{{ val.value ? val.value : '-' }}</td>
                                </tr>
                            </template>
                        </template>
                        <template v-else>
                            <tr>
                                <th>Description</th>
                                <td class="pl-0">{{ scheduleTrxData.description }}</td>
                            </tr>
                        </template>
                    </table>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="p-2 d-flex justify-content-between align-items-center" style="border-radius: .5rem;border: 2px solid #1d6de5;">
                                <h6 class="text-primary text-uppercase font-weight-bold px-2" style="position: absolute;top: -8px;background: #fff;">Scanning</h6>
                                <div class="d-flex justify-content-start">
                                    <img src="<?= base_url("img/icon/qrcode.png") ?>" class="img" style="height: 2.4rem;margin: .3rem" />
                                    <div class="ml-4">
                                        <h4 class="mb-0 text-uppercase">{{ scheduleTrxData.scannedBy }}</h4>
                                        <span class="text-muted font-sm">{{ moment(scheduleTrxData.scannedAt).format("DD MMM YYYY HH:mm") }}</span>
                                    </div>
                                </div>
                                <h6 class="mb-0 mr-2 text-uppercase">{{ scheduleTrxData.scannedWith }}</h6>
                            </div>
                        </div>
                        <div class="col-12 mb-4" v-if="scheduleTrxData.approvedAt">
                            <div class="p-2 d-flex justify-content-between align-items-center" style="border-radius: .5rem;border: 2px solid #1d6de5;">
                                <h6 class="text-primary text-uppercase font-weight-bold px-2" style="position: absolute;top: -8px;background: #fff;">APPROVAL</h6>
                                <div class="d-flex justify-content-start">
                                    <img src="<?= base_url("img/icon/checking.png") ?>" class="img" style="height: 2.4rem;margin: .3rem" />
                                    <div class="ml-4">
                                        <h4 class="mb-0 text-uppercase">{{ scheduleTrxData.approvedBy }}</h4>
                                        <span class="text-muted font-sm">{{ moment(scheduleTrxData.approvedAt).format("DD MMM YYYY HH:mm") }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($scheduleTrxData["approvedAt"] == null) { ?>
                        <div class="slide-button mt-5" id="slideApproveBg">
                            <button type="button" class="btn btn-success" id="slideApprove"><i class="fa fa-check font-xl"></i></button>
                            <label>Slide to Approve</label>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-12 mb-4">
                    <hr />
                </div>
                <div class="col-12">
                    <div class="table-responsive table-record-fix-width">
                        <table class="table table-responsive-sm table-hover table-bordered table-outline border-bottom" id="detailRecord">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Parameter</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Normal</th>
                                    <th class="text-center">Abnormal</th>
                                    <th class="text-center">UoM</th>
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
                                        <span v-if="!val.option" :class="!val.min & !val.max ? 'font-italic' : ''">{{ !val.min & !val.max ? "(Any)" : (!val.min ? ('<' + val.max) : (!val.max ? ('>' + val.min) : (val.min + ' - ' + val.max))) }}</span>
                                        <span v-else :class="!val.normal ? 'font-italic' : ''">{{ !val.normal ? "(Empty)" : val.normal }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span v-if="!val.option" :class="!val.min & !val.max ? 'font-italic' : ''">{{ !val.min & !val.max ? "(Any)" : ((val.min ? ('x < ' + val.min) : '') + '; ' + (val.max ? ('x > ' + val.max) : '')) }}</span>
                                        <span v-else :class="!val.abnormal ? 'font-italic' : ''">{{ !val.abnormal ? "(Empty)" : val.abnormal }}</span>
                                    </td>
                                    <td class="text-center" :class="!val.uom ? 'font-italic' : ''">{{ !val.uom ? "(Empty)" : val.uom }}</td>
                                    <td class="text-center" :class="checkAbnormal(val).class != '' ? 'font-weight-bold text-' + checkAbnormal(val).class : ''">{{ !val.value ? "(Empty)" : val.value }}</td>

                                    <?php if ($scheduleTrxData["approvedAt"] != null) { ?>
                                        <td class="text-center">
                                            <a :href="'<?= base_url() ?>/Finding/' + (isNullEmptyOrUndefined(val.findingId) ? 'issue?trxId=' + val.trxId : 'detail?findingId=' + val.findingId)" :target="(isNullEmptyOrUndefined(val.findingId) ? '' : '_blank')" class="btn btn-sm" v-if="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class != ''" :class="(checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class != '' ? 'btn-' + (checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>')).class : ''">{{ checkAbnormal(val, '<?= $scheduleTrxData["approvedAt"] ?? '' ?>').name }}</a>
                                        </td>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            <div :class="scheduleTrxData.approvedAt ? 'col-sm-6' : 'col-12'">
                <div class="card card-main pb-4">
                    <h5 class="mb-3">Notes of Scanned</h5>
                    <h6 class="font-italic text-muted">{{ scheduleTrxData.scannedNotes ?? '( empty )' }}</h6>
                </div>
            </div>
            <div v-if="scheduleTrxData.approvedAt" class="col-sm-6">
                <div class="card card-main pb-4">
                    <h5 class="mb-3">Notes of Approved</h5>
                    <h6 class="font-italic text-muted">{{ scheduleTrxData.approvedNotes ?? '( empty )' }}</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card card-main pb-4">
            <h5 class="mb-3">Attachment</h5>
            <div class="row" v-if="attachmentTrxData.length > 0">
                <div class="col-sm-6 col-md-4 col-lg-3" v-for="(val, key) in attachmentTrxData">
                    <div class="card">
                        <img class="card-img-top" :src="val.attachment" :alt="'Attachment' + key">
                        <div class="card-body">
                            <p class="card-text">{{ val.notes ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <h6 class="font-italic text-muted">( No Attachment )</h6>
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
            var scheduleTrxData = <?= json_encode($scheduleTrxData); ?>;
            var recordParameterData = <?= json_encode($trxData ?? []) ?>;
            var attachmentTrxData = <?= json_encode($attachmentTrxData ?? []) ?>;

            if (IsJsonString(scheduleTrxData?.description)) {
                scheduleTrxData.descriptionJson = JSON.parse(scheduleTrxData.description);
            } else {
                scheduleTrxData.descriptionJson = [];
            }

            const approveTrx = () => {
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
                                        // window.location.reload();
                                    });
                                })
                                .catch((rej) => {
                                    if (rej.throw) {
                                        throw new Error(rej.message);
                                    }
                                    $('#slideApprove').removeClass('unlocked');
                                    $('#slideApprove').html(`<i class="fa fa-check font-xl"></i>`);
                                });
                        });
                    } else {
                        $('#slideApprove').removeClass('unlocked');
                        $('#slideApprove').html(`<i class="fa fa-check font-xl"></i>`);
                    }
                });
            };

            return {
                moment,
                scheduleTrxData,
                recordParameterData,
                attachmentTrxData,
                isNullEmptyOrUndefined,
                xhrThrowRequest,
                approveTrx,
                checkAbnormal
            }
        }
    }).mount("#app");

    (function() {
        var initialMouse = 0;
        var slideMovementTotal = 0;
        var mouseIsDown = false;
        var slider = $('#slideApprove');

        slider.on('mousedown touchstart', function(event) {
            mouseIsDown = true;
            slideMovementTotal = $('#slideApproveBg').width() - $(this).width() + 10;
            initialMouse = event.clientX || event.originalEvent.touches[0].pageX;
        });

        $(document.body, '#slideApprove').on('mouseup touchend', function(event) {
            if (!mouseIsDown)
                return;
            mouseIsDown = false;
            var currentMouse = event.clientX || event.changedTouches[0].pageX;
            var relativeMouse = currentMouse - initialMouse;

            if (relativeMouse < slideMovementTotal) {
                $('.slide-button label').fadeTo(300, 1);
                slider.animate({
                    left: "0px"
                }, 300);
                return;
            }
            slider.addClass('unlocked');
            slider.html(`<i class="fa fa-spinner fa-spin font-xl"></i> Approving`);
            v.approveTrx();

            setTimeout(() => {
                slider.css({
                    'left': '0px'
                });
                $('.slide-button label').fadeTo(0, 100);
            }, 100);
        });

        $(document.body).on('mousemove touchmove', function(event) {
            if (!mouseIsDown)
                return;

            var currentMouse = event.clientX || event.originalEvent.touches[0].pageX;
            var relativeMouse = currentMouse - initialMouse;
            var slidePercent = 1 - (relativeMouse / slideMovementTotal);

            $('.slide-button label').fadeTo(0, slidePercent);

            if (relativeMouse <= 0) {
                slider.css({
                    'left': '0px'
                });
                return;
            }
            if (relativeMouse >= slideMovementTotal + 10) {
                slider.css({
                    'left': slideMovementTotal + 'px'
                });
                return;
            }
            slider.css({
                'left': relativeMouse - 10
            });
        });
    })();
</script>
<?= $this->endSection(); ?>