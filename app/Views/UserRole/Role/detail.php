<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<style>
    .tree {
        margin-top: 2rem;
        list-style: none;
        padding-inline-start: 0;
    }

    .tree ul {
        list-style: none;
        display: none;
        margin: 4px auto;
        margin-left: 6px;
        border-left: 1px dashed #dfdfdf;
        padding-inline-start: 0;
    }

    .tree li {
        padding: 12px 18px;
        cursor: pointer;
        vertical-align: middle;
        background: #fff;
    }

    .tree li:first-child {
        border-radius: 3px 3px 0 0;
    }

    .tree li:last-child {
        border-radius: 0 0 3px 3px;
    }

    .tree .active,
    .active li {
        background: #efefef;
    }

    .tree label {
        cursor: pointer;
    }

    .tree input {
        vertical-align: middle;
    }

    .tree input[type=checkbox] {
        margin: -2px 6px 0 0px;
    }

    .has>label {
        color: #000;
    }

    .tree .total {
        color: #e13300;
    }

    .rotate {
        -moz-transition: all .2s linear;
        -webkit-transition: all .2s linear;
        transition: all .2s linear;
    }

    .rotate.down {
        -ms-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
    }

    .visible-false {
        visibility: hidden;
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mt-1 mb-2">
                    <h4><?= $title ?></h4>
                    <h5 class="header-icon">
                        <a href="<?= $_SERVER['HTTP_REFERER'] ?? site_url("role") ?>" class="decoration-none"><i class="fa fa-arrow-left mr-1" title="Back"></i> Back</a>
                    </h5>
                </div>
                <div class="p-4 mt-3 border">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <input class="form-control" id="role" type="text" placeholder="Role Name" v-model="groupData.name">
                    </div>
                    <div class="d-block">
                        <button type="button" class="btn btn-outline-primary mr-2" @click="SCAllRole(true)">Select All</button>
                        <button type="button" class="btn btn-outline-primary mr-2" @click="SCAllRole(false)">Clear All</button>
                    </div>

                    <div class="w-100 mb-3">
                        <ul class="tree">
                            <template v-for="(val, key) in groupRoleList()">
                                <li class="has" v-if="val.group != 'null'">
                                    <span class="collapse-tree p-2"><i class="fas fa-caret-right rotate"></i></span>
                                    <input type="checkbox" name="group[]" :value="val.group" :id="val.group">
                                    <label :for="val.group">{{val.group}} <span class="total">({{val.value.length}})</span></label>

                                    <ul>
                                        <template v-for="(val1, key1) in val.value">
                                            <li class="has" v-if="val1.group != 'null'">
                                                <span class="collapse-tree p-2"><i class="fas fa-caret-right rotate"></i></span>
                                                <input type="checkbox" name="group[]" :value="val1.group" :id="val1.group">
                                                <label :for="val1.group">{{val1.group}} <span class="total">({{val1.value.length}})</span></label>

                                                <ul>
                                                    <template v-for="(val2, key2) in val1.value">
                                                        <li>
                                                            <span class="p-2"><i class="fa fa-info visible-false"></i></span>
                                                            <input type="checkbox" name="role[]" :value="val2.roleId">
                                                            <label>{{val2.name ?? val2.code}}</label>
                                                        </li>
                                                    </template>
                                                </ul>
                                            </li>
                                            <template v-else>
                                                <template v-for="(val2, key2) in val1.value">
                                                    <li>
                                                        <span class="p-2"><i class="fa fa-info visible-false"></i></span>
                                                        <input type="checkbox" name="role[]" :value="val2.roleId">
                                                        <label>{{val2.name ?? val2.code}}</label>
                                                    </li>
                                                </template>
                                            </template>
                                        </template>
                                    </ul>
                                </li>
                                <template v-else>
                                    <template v-for="(val1, key1) in val.value">
                                        <template v-for="(val2, key2) in val1.value">
                                            <li>
                                                <span class="p-2"><i class="fa fa-info visible-false"></i></span>
                                                <input type="checkbox" name="role[]" :value="val2.roleId">
                                                <label>{{val2.name ?? val2.code}}</label>
                                            </li>
                                        </template>
                                    </template>
                                </template>

                            </template>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn btn-outline-dark mr-2" @click="window.location.href = '<?= site_url('role') ?>'"><i class="fa fa-times"></i> Cancel</button>
                        <button type="button" class="btn btn-info mr-2" @click="saveRole()"><i class="fa fa-save"></i> Save</button>
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
        setup() {
            const groupData = Vue.reactive(<?= json_encode($groupData ?? []) ?>);
            const roleList = Vue.reactive(<?= json_encode($roleData ?? []) ?>);

            const groupRoleList = () => {
                return _.chain(roleList).groupBy("parent1").map((v, k) => {
                    return {
                        group: k,
                        value: _.chain(v).groupBy("parent2").map((v1, k1) => {
                            return {
                                group: k1,
                                value: v1
                            }
                        }).sortBy("group").value()
                    }
                }).sortBy("group").value();
            }

            const SCAllRole = (cond) => {
                document.querySelectorAll(".tree input[type=checkbox]").forEach((v, k) => {
                    v.checked = cond;
                });
            }

            const getRoleChecked = () => {
                let roleChecked = [];
                document.querySelectorAll(".tree input[name='role[]']:checked").forEach((v, k) => {
                    roleChecked.push(v.value);
                });
                return roleChecked;
            }

            const saveRole = () => {
                let roleChecked = getRoleChecked();
                let response = axios.post("<?= base_url('role/saveGroup') ?>", {
                    "groupId": groupData.groupId ?? "",
                    "name": groupData.name,
                    "roleId": roleChecked.join(",")
                }).then((res) => {
                    xhrThrowRequest(res)
                        .then(() => {
                            Swal.fire({
                                title: res.data.message,
                                icon: "success",
                                timer: 3000
                            }).then(() => {
                                window.location.href = "<?= base_url("role") ?>";
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
            }

            Vue.onMounted(() => {
                $(document).on('click', '.tree .collapse-tree', function(e) {
                    $(this).children("i").toggleClass("down");
                    $(this).siblings('ul').slideToggle();
                    e.stopPropagation();
                });

                $(document).on('change', '.tree input[type=checkbox]', function(e) {
                    if ($(this).attr("name") == "group[]") {
                        $(this).siblings("ul").find("input[type=checkbox]").prop('checked', this.checked);
                        if (this.checked) {
                            $(this).siblings("ul").slideDown();
                            $(this).siblings("ul").find("ul").slideDown();

                            $(this).siblings("ul").find("i").addClass("down");
                            $(this).siblings("span").find("i").addClass("down");
                        } else {
                            $(this).siblings("ul").slideUp();
                            $(this).siblings("ul").find("ul").slideUp();

                            $(this).siblings("ul").find("i").removeClass("down");
                            $(this).siblings("span").find("i").removeClass("down");
                        }
                    }

                    let listLi = $(this).parent().parent().children("li");
                    let setCheckedParent = true;
                    if (listLi.children("input[type=checkbox]").length != listLi.children("input[type=checkbox]:checked").length) {
                        setCheckedParent = false;
                    }
                    $(this).parent().parent().parent().children("input[type=checkbox]").prop('checked', setCheckedParent)
                    $(this).parent().parent().parent().parent().parent().children("input[type=checkbox]").prop('checked', setCheckedParent)
                    e.stopPropagation();
                });

                (groupData.roleId ?? "").split(",").forEach((v) => {
                    let inputCheck = document.querySelector(".tree input[value='" + v + "']");
                    if (inputCheck) {
                        inputCheck.checked = true;
                        fireEvent(inputCheck, 'change');
                    }
                })
            })

            return {
                groupData,
                roleList,
                groupRoleList,
                SCAllRole,
                getRoleChecked,
                saveRole
            }
        }
    }).mount("#app")
</script>
<?= $this->endSection(); ?>