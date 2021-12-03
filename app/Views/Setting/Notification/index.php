<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main fixed-height">
            <div class="card-body">
                <div class="dt-search-input">
                    <div class="input-container">
                        <a href="javascript:void(0)" class="suffix text-decoration-none dt-search-hide"><i class="c-icon cil-x" style="font-size: 1.5rem;"></i></a>
                        <input name="dt-search" class="material-input" type="text" data-target="#tableEq" placeholder="Search Data Asset" />
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <h4><?= $title ?></h4>
                    <h5 class="header-icon">
                        <a href="javascript:;" class="dt-search" data-target="#tableEq"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
                        <a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:;" @click="showNotifModal()"><i class="fa fa-plus mr-2"></i> Add Notification</a>
                            <a class="dropdown-item" href="javascript:;" @click="showTrash = !showTrash"><i class="far fa-trash-alt mr-2"></i> {{ showTrash ? 'Hide Trash' : 'Show Trash' }}</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" @click="reloadTable()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
                        </div>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped w-100" id="tableNotification">
                        <thead class="bg-primary">
                            <tr>
                                <th style="width: 70px;">Type</th>
                                <th>Alert Contact</th>
                                <th>Trigger</th>
                                <th style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="notifModal" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-0 mt-3">
                        <label for="type">Alert Type</label>
                        <select class="form-control" name="type" v-model="notifForm.type">
                            <option value="email">Email</option>
                            <option value="telegram">Telegram</option>
                            <option value="sms">SMS</option>
                        </select>
                    </div>
                    <span class="invalid-feedback-password" :class="notifFormErr.type ? '' : 'd-none'">
                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                        </svg>
                        {{ notifFormErr.type }}
                    </span>
                    <div class="form-group mb-0 mt-3">
                        <label for="name">Friendly Name</label>
                        <input class="form-control" type="text" name="friendlyName" v-model="notifForm.friendlyName">
                    </div>
                    <span class="invalid-feedback-password" :class="notifFormErr.friendlyName ? '' : 'd-none'">
                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                        </svg>
                        {{ notifFormErr.friendlyName }}
                    </span>
                    <div class="form-group mb-0 mt-3" :class="notifForm.type != 'email' ? 'd-none' : ''">
                        <label for="email">Email</label>
                        <input class="form-control" type="email" name="email" v-model="notifForm.email">
                    </div>
                    <div class="form-group mb-0 mt-3" :class="notifForm.type != 'telegram' ? 'd-none' : ''">
                        <label for="chatId">Chat Id</label>
                        <input class="form-control" type="text" name="chatId" v-model="notifForm.chatId">
                    </div>
                    <div class="form-group mb-0 mt-3" :class="notifForm.type != 'sms' ? 'd-none' : ''">
                        <label for="noTelp">No Telp</label>
                        <input class="form-control" type="text" name="noTelp" v-model="notifForm.noTelp">
                    </div>
                    <span class="invalid-feedback-password" :class="notifFormErr.value ? '' : 'd-none'">
                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                        </svg>
                        {{ notifFormErr.value }}
                    </span>
                    <div class="form-group mb-0 mt-3">
                        <div class="btn-group-toggle d-flex" id="trigger">
                            <label class="flex-fill btn btn-outline-primary mx-1 mb-1" :class="notifForm.trigger.includes('Open Finding') ? 'active' : ''" for="openFindingInput">
                                <input class="form-check-input" name="trigger" id="openFindingInput" type="checkbox" value="Open Finding" @change="setValTrigger('Open Finding')"> Open Finding
                            </label>
                            <label class="flex-fill btn btn-outline-primary mx-1 mb-1" :class="notifForm.trigger.includes('Closed Finding') ? 'active' : ''" for="closeFindingInput">
                                <input class="form-check-input" name="trigger" id="closeFindingInput" type="checkbox" value="Closed Finding" @change="setValTrigger('Closed Finding')"> Closed Finding
                            </label>
                        </div>
                    </div>
                    <span class="invalid-feedback-password" :class="notifFormErr.trigger ? '' : 'd-none'">
                        <svg aria-hidden="true" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                        </svg>
                        {{ notifFormErr.trigger }}
                    </span>
                </div>
                <div class="modal-footer d-flex" :class="notifForm.notificationId ? 'justify-content-between' : ''">
                    <div>
                        <button type="button" class="btn btn-outline-dark ml-2" data-dismiss="modal" id="cancel"><i class=" fa fa-times"></i> Cancel</button>
                        <button type="button" class="btn btn-success ml-2 " @click="saveNotif()"><i class="fa fa-save"></i> Save</button>
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
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var table;
            const notifData = Vue.reactive([]);
            const notifForm = Vue.reactive({
                type: 'email',
                trigger: []
            });
            const notifFormErr = Vue.reactive({});
            const showTrash = Vue.ref(false);

            const reloadTable = () => {
                table.draw();
            }

            const getData = () => {
                return new Promise(async (resolve, reject) => {
                    try {
                        table = await $('#tableNotification').DataTable({
                            drawCallback: function(settings) {
                                $(document).ready(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                })
                            },
                            processing: true,
                            serverSide: true,
                            scrollY: "calc(100vh - 272px)",
                            // responsive: true,
                            language: {
                                processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
                                lengthMenu: "Showing _MENU_ ",
                                info: "of _MAX_ entries",
                                infoEmpty: 'of 0 entries',
                            },
                            dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
                            ajax: {
                                url: "<?= base_url('/Notification/datatable') ?>",
                                type: "POST",
                                dataSrc: function(json) {
                                    notifData.splice(0, notifData.length);
                                    notifData.push(...json.data);

                                    return json.data;
                                },
                            },
                            columns: [{
                                    data: "type",
                                },
                                {
                                    data: "value",
                                },
                                {
                                    data: "trigger",
                                },
                                {
                                    data: "notificationId",
                                },
                            ],
                            order: [0, 'asc'],
                            columnDefs: [{
                                    targets: 0,
                                    class: "text-center",
                                    render: function(data, type, row) {
                                        if (data == 'email') {
                                            return `
                                            <svg class="c-icon c-icon-xl">
                                                <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-envelope-closed') ?>"></use>
                                            </svg>
                                        `;
                                        } else if (data == 'telegram') {
                                            return `
                                            <svg class="c-icon c-icon-xl">
                                                <use xlink:href="<?= base_url('/icons/coreui/svg/brand.svg#cib-telegram-plane') ?>"></use>
                                            </svg>
                                        `;
                                        } else if (data == 'sms') {
                                            return `
                                            <svg class="c-icon c-icon-xl">
                                                <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-phone') ?>"></use>
                                            </svg>
                                        `;
                                        } else {
                                            return `
                                            <svg class="c-icon c-icon-xl">
                                                <use xlink:href="<?= base_url('/icons/coreui/svg/free.svg#cil-minus') ?>"></use>
                                            </svg>
                                        `;
                                        }
                                    }
                                },
                                {
                                    targets: 2,
                                    class: "text-center",
                                    render: function(data, type, row) {
                                        if (data != '-') {
                                            let dt = Array.from(new Set(data.split(',')));
                                            let list_dt = '';
                                            $.each(dt, function(key, value) {
                                                list_dt += '<span class="badge badge-primary p-1 mr-1 mb-1 badge-size">' + value + '</span>';
                                            });
                                            return '<div style="max-height: 56px !important; overflow-y: scroll;">' + list_dt + '</div>';
                                        } else {
                                            return data;
                                        }
                                    }
                                },
                                {
                                    targets: -1,
                                    class: "text-center",
                                    orderable: false,
                                    render: function(data, type, row) {
                                        let outBtn = '';
                                        if (!row.deletedAt) {
                                            outBtn += `<button type="button" class="btn btn-link p-1" onclick="v.detailNotif('${data}')"><i class="fas fa-pencil-alt"></i></button>`;
                                            outBtn += `<button type="button" class="btn btn-link p-1" onclick="v.changeStatus('${data}', '${row.status == 'active' ? 'disable' : 'active'}')"><i class="fas fa-${row.status == 'active' ? 'pause' : 'play'}"></i></button>`;
                                        } else {
                                            outBtn += `<button type="button" class="btn btn-link p-1" onclick="v.restoreNotif('${data}')"><i class="fa fa-undo"></i></button>`;
                                        }
                                        outBtn += `<button type="button" class="btn btn-link p-1" onclick="v.deleteNotif('${data}')"><i class="fa fa-times"></i></button>`;

                                        return outBtn;
                                    }
                                },
                            ],
                            'createdRow': function(row, data) {
                                <?php //if (checkRoleList("FINDING.DETAIL.LIST.VIEW")) : 
                                ?>
                                // row.setAttribute("data-id", data.notificationId);
                                // row.classList.add("cursor-pointer");
                                <?php //endif; 
                                ?>
                            },
                        });
                    } catch (er) {
                        console.log(er)
                        reject(er);
                    }
                })
            }

            const showNotifModal = () => {
                if (notifForm.notificationId) {
                    notifForm.notificationId = "";
                    notifForm.friendlyName = "";
                    notifForm.type = "email";
                    notifForm.email = "";
                    notifForm.chatId = "";
                    notifForm.noTelp = "";
                    notifForm.trigger = [];

                    notifFormErr.type = null;
                    notifFormErr.friendlyName = null;
                    notifFormErr.value = null;
                    notifFormErr.trigger = null;
                }

                $("#notifModal").modal("show");
            }

            const detailNotif = (notificationId) => {
                let filtNotif = _.filter(notifData, (val) => val.notificationId == notificationId);
                if (filtNotif.length > 0) {
                    notifForm.notificationId = filtNotif[0].notificationId;
                    notifForm.friendlyName = filtNotif[0].friendlyName;
                    notifForm.type = filtNotif[0].type;
                    notifForm.email = filtNotif[0].type == 'email' ? filtNotif[0].value : "";
                    notifForm.chatId = filtNotif[0].type == 'telegram' ? filtNotif[0].value : "";
                    notifForm.noTelp = filtNotif[0].type == 'sms' ? filtNotif[0].value : "";
                    notifForm.trigger = (filtNotif[0].trigger ?? "").split(",");

                    notifFormErr.type = null;
                    notifFormErr.friendlyName = null;
                    notifFormErr.value = null;
                    notifFormErr.trigger = null;

                    $("#notifModal").modal("show");
                }
            }

            const changeStatus = (notificationId, status) => {
                let res = axios.post("<?= site_url("Notification/changeStatus") ?>", {
                    notificationId: notificationId ?? "",
                    status: status
                }).then(res => {
                    xhrThrowRequest(res)
                        .then(() => {
                            Toast.fire({
                                title: 'Success Change Status!',
                                icon: 'success'
                            });

                            table.draw();
                        })
                        .catch((rej) => {
                            if (rej.throw) {
                                throw new Error(rej.message);
                            }
                        });
                })
            }

            const setValTrigger = (val) => {
                if (notifForm.trigger.includes(val)) {
                    let iVT = notifForm.trigger.findIndex((i) => i == val);
                    if (iVT != undefined && iVT >= 0) {
                        notifForm.trigger.splice(iVT, 1);
                    }
                } else {
                    notifForm.trigger.push(val);
                }
            }

            const saveNotif = () => {
                if (notifForm.type && notifForm.friendlyName && notifForm.trigger.length > 0 && ((notifForm.type == "email" && notifForm.email) || (notifForm.type == "telegram" && notifForm.chatId) || (notifForm.type == "sms" && notifForm.noTelp))) {
                    if (!validateEmail(notifForm.email) & notifForm.type == "email") {
                        notifFormErr.email = "Email is not valid";
                    } else {
                        let res = axios.post("<?= site_url("Notification/saveNotif") ?>", {
                            notificationId: notifForm.notificationId ?? "",
                            friendlyName: notifForm.friendlyName,
                            type: notifForm.type,
                            value: (notifForm.type == 'email' ? notifForm.email : (notifForm.type == 'telegram' ? notifForm.chatId : notifForm.noTelp)),
                            trigger: (notifForm.trigger ?? []).join(","),
                        }).then(res => {
                            xhrThrowRequest(res)
                                .then(() => {
                                    Toast.fire({
                                        title: 'Success Save Notification!',
                                        icon: 'success'
                                    });
                                    $("#notifModal").modal("hide");

                                    notifForm.type = "email";
                                    notifForm.friendlyName = "";
                                    notifForm.email = "";
                                    notifForm.chatId = "";
                                    notifForm.noTelp = "";
                                    notifForm.trigger = [];

                                    notifFormErr.type = null;
                                    notifFormErr.friendlyName = null;
                                    notifFormErr.value = null;
                                    notifFormErr.trigger = null;

                                    table.draw();
                                })
                                .catch((rej) => {
                                    if (rej.throw) {
                                        throw new Error(rej.message);
                                    }
                                });
                        })
                    }
                } else {
                    if (!notifForm.type) notifFormErr.type = "Name is required";
                    if (!notifForm.friendlyName) notifFormErr.friendlyName = "Friendly Name is required";
                    if (!notifForm.email && notifForm.type == "email") notifFormErr.value = "Email is required";
                    if (!notifForm.chatId && notifForm.type == "telegram") notifFormErr.value = "Chat Id is required";
                    if (!notifForm.noTelp && notifForm.type == "sms") notifFormErr.value = "Phone Number is required";
                    if (notifForm.trigger.length <= 0) notifFormErr.trigger = "Trigger is required";
                }
            }

            const deleteNotif = (notificationId) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: (showTrash.value ? "You won't be able to revert this!" : "You can be able to restore this!"),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let res = axios.post("<?= site_url("Notification/deleteNotif") ?>", {
                            notificationId: notificationId ?? "",
                            hard: (showTrash.value ? 1 : 0)
                        }).then(res => {
                            xhrThrowRequest(res)
                                .then(() => {
                                    Toast.fire({
                                        title: 'Success Delete Notification!',
                                        icon: 'success'
                                    });
                                    $("#notifModal").modal("hide");

                                    notifForm.type = "email";
                                    notifForm.friendlyName = "";
                                    notifForm.email = "";
                                    notifForm.chatId = "";
                                    notifForm.noTelp = "";
                                    notifForm.trigger = [];

                                    notifFormErr.type = null;
                                    notifFormErr.friendlyName = null;
                                    notifFormErr.value = null;
                                    notifFormErr.trigger = null;

                                    table.draw();
                                })
                                .catch((rej) => {
                                    if (rej.throw) {
                                        throw new Error(rej.message);
                                    }
                                });
                        })
                    }
                })
            }

            const restoreNotif = (notificationId) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You can update this data after restored!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let res = axios.post("<?= site_url("Notification/restoreNotif") ?>", {
                            notificationId: notificationId ?? ""
                        }).then(res => {
                            xhrThrowRequest(res)
                                .then(() => {
                                    Toast.fire({
                                        title: 'Success Restore Notification!',
                                        icon: 'success'
                                    });
                                    $("#notifModal").modal("hide");

                                    notifForm.type = "email";
                                    notifForm.friendlyName = "";
                                    notifForm.email = "";
                                    notifForm.chatId = "";
                                    notifForm.noTelp = "";
                                    notifForm.trigger = [];

                                    notifFormErr.type = null;
                                    notifFormErr.friendlyName = null;
                                    notifFormErr.value = null;
                                    notifFormErr.trigger = null;

                                    table.draw();
                                })
                                .catch((rej) => {
                                    if (rej.throw) {
                                        throw new Error(rej.message);
                                    }
                                });
                        })
                    }
                })
            }

            Vue.watch(
                () => notifForm.type,
                (state, prevState) => {
                    notifFormErr.value = "";
                }
            )

            Vue.watch(
                showTrash,
                (state, prevState) => {
                    table.ajax.url("<?= base_url('/Notification/datatable') ?>?deleted=" + (showTrash.value == true ? '1' : '0')).load();
                }
            )

            Vue.onMounted(() => {
                getData();

                // $(document).on('click', '#tableNotification tbody tr', function() {
                //     detailNotif($(this).attr("data-id"));
                // });
            })

            return {
                notifData,
                notifForm,
                notifFormErr,
                showTrash,
                changeStatus,
                detailNotif,
                reloadTable,
                setValTrigger,
                saveNotif,
                deleteNotif,
                restoreNotif,
                showNotifModal,
            }
        }
    }).mount("#app");
</script>

<?= $this->endSection(); ?>