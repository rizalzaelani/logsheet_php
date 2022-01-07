<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row" id="app">
    <div class="col-12">
        <div class="card card-main">
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
                                <label for="categoryName">Category Name</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="categoryName" v-model="category.categoryName" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="descCategory">Description</label>
                            </div>
                            <div class="col-8">
                                <textarea class="form-control" id="descCategory" v-model="category.description" rows="5" readonly></textarea>
                            </div>
                        </div>
                        <div :class="checkEditCategory ? 'form-group row fade-in' : 'd-none'">
                            <div class="col-4">
                                <label for="image">Image</label>
                            </div>
                            <div class="col-8">
                                <input type="file" class="filepond w-100" name="filepond" id="image" accept="image/png, image/jpeg, image/gif" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 d-flex justify-content-end align-items-center">
                                <button :class="checkEditCategory ? 'd-none' : 'btn btn-md btn-primary mr-1 fade-in'" type="button" @click="editCategory()" id="btnEdit"><i class="fa fa-edit"></i> Edit</button>
                                <button :class="checkEditCategory ? 'fade-in btn btn-md btn-secondary mr-1' : 'd-none'" type="button" @click="cancelEditCategory()" id="btnCancelEdit"><i class="fa fa-times"></i> Cancel</button>
                                <button :class="checkEditCategory ? 'fade-in btn btn-md btn-danger mr-1' : 'd-none'" type="button" @click="deleteCategory()" id="btnDelete"><i class="fa fa-trash"></i> Delete</button>
                                <button :class="checkEditCategory ? 'fade-in btn btn-md btn-success mr-1' : 'd-none'" type="button" @click="saveEditCategory()" id="btnSaveEdit"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center align-items-center p-1 ">
                        <img class="img-thumbnail fade-in" :src="category.image" alt="" style="width: 200px !important;">
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-main">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1">
                    <h4>Template</h4>
                    <div>
                        <button class="btn btn-sm btn-outline-primary" @click="addTemplate()">
                            <i class="fa fa-plus"></i> Add Template
                        </button>
                    </div>
                </div>
                <div class="table-responsive w-100">
                    <table class="table table-striped w-100" id="tableTemplate">
                        <thead class="bg-primary">
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Add Template-->
        <div class="modal fade" id="modalTemplate" tabindex="-1" role="dialog" aria-labelledby="modalTemplateTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTemplateTitle">Add Template</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="form-group">
                                <form action="">
                                    <div class="mb-3">
                                        <label for="templateName">Template Name</label>
                                        <input id="templateName" type="text" class="form-control" required v-model="templateName" placeholder="Template Name (e.g Apar, Hydrant, etc.)">
                                    </div>
                                    <div class="mb-3">
                                        <label for="descTemplate">Description</label>
                                        <textarea id="descTemplate" type="text" rows="5" class="form-control" required v-model="descTemplate" placeholder="Description"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fileTemplate">Upload file template here</label>
                                        <input type="file" class="filepond w-100" name="filepond" id="fileTemplate" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                        <button type="button" class="btn btn-primary" @click="doAddTemplate()" id="btnAdd"><i class="fa fa-plus"></i> Add Template</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Template-->
        <div class="modal fade" id="modalTemplateEdit" tabindex="-1" role="dialog" aria-labelledby="modalTemplateEditTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTemplateEditTitle">Edit Template</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <template v-if="template != ''">
                            <div class="mb-3">
                                <label for="name">Template Name</label>
                                <input id="name" type="text" class="form-control" required v-model="template[0].name" placeholder="Template Name (e.g Apar, Hydrant, etc.)">
                            </div>
                            <div class="mb-3">
                                <label for="desc">Description</label>
                                <textarea id="desc" type="text" rows="5" class="form-control" required v-model="template[0].descTemplate" placeholder="Description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="current">Current File</label><br>
                                <a :href="template[0].path" target="_blank" class="btn btn-sm btn-primary" id="current"><i class="fa fa-download"></i> Download</a>
                            </div>
                        </template>
                        <div class="mb-3">
                            <label for="edited">Upload new file here</label>
                            <input type="file" class="filepond w-100" name="filepond" id="edited" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                        <button type="button" class="btn btn-primary" @click="updateTemplate()" id="btnAdd"><i class="fa fa-save"></i> Save Changes</button>
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
    const {
        reactive,
        ref,
        onMounted
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var myModal = ref('');
            var category = <?= json_encode($category) ?>;
            var templateData = <?= json_encode($template) ?>;
            var table = ref(null);
            var modal = ref(null);

            var templateName = ref("");
            var descTemplate = ref("");
            var fileTemplate = ref("");

            var template = ref("");
            var fileEdited = ref("");

            var image = ref("");

            var checkEditCategory = ref(false);

            const getData = () => {
                return new Promise(async (resolve, reject) => {
                    try {
                        table.value = await $('#tableTemplate').DataTable({
                            drawCallback: function(settings) {
                                $(document).ready(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                })
                            },
                            processing: true,
                            serverSide: true,
                            scrollY: "calc(100vh - 272px)",
                            language: {
                                processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
                                lengthMenu: "Showing _MENU_ ",
                                info: "of _MAX_ entries",
                                infoEmpty: 'of 0 entries',
                            },
                            dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
                            ajax: {
                                url: "<?= base_url('/Template/datatableTemplate'); ?>" + '/' + category.categoryIndustryId,
                                type: "POST",
                                data: {},
                                complete: () => {
                                    resolve();
                                }
                            },
                            columns: [{
                                    data: "name",
                                    name: "name"
                                },
                                {
                                    data: "descTemplate",
                                    name: "descTemplate"
                                },
                                {
                                    data: "templateId",
                                    name: "templateId",
                                    render: function(data, type, row, meta) {
                                        return `<div class="d-flex justify-content-center align-items-center">
                                            <button class="btn btn-sm btn-outline-primary mr-1" onclick="editTemplate('` + data + `')">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteTemplate('` + data + `')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>`
                                    }
                                }
                            ]
                        });
                    } catch (er) {
                        console.log(er)
                        reject(er);
                    }
                })

            };

            const editCategory = () => {
                checkEditCategory.value = true;
                $('#categoryName').removeAttr('readonly');
                $('#descCategory').removeAttr('readonly');
            }

            const cancelEditCategory = () => {
                checkEditCategory.value = false;
                $('#categoryName').prop('readonly', true);
                $('#descCategory').prop('readonly', true);
            }

            const deleteCategory = () => {
                const swalWithBootstrapButtons = swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mr-1',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Area you sure?',
                    text: "You can't restore this data!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: '<i class="fa fa-times"></i> Cancel',
                    confirmButtonText: '<i class="fa fa-check"></i> Yes, delete!',
                })
            }

            const saveEditCategory = () => {
                if (category.categoryName == "" || category.description == "") {
                    return swal.fire({
                        icon: 'warning',
                        title: 'Field category name and description cannot empty!'
                    })
                }

                let formdata = new FormData();
                formdata.append('category', JSON.stringify(v.category));
                formdata.append('image', image.value);
                axios({
                    url: '<?= base_url('Template/updateCategory') ?>',
                    method: 'POST',
                    data: formdata
                }).then((res) => {
                    let rsp = res.data;
                    if (rsp.status == 200) {
                        swal.fire({
                            icon: 'success',
                            title: rsp.message
                        })

                        return location.reload();
                    } else {
                        return swal.fire({
                            icon: 'error',
                            title: rsp.message
                        })
                    }
                })
            }

            function addTemplate() {
                this.templateName = "";
                this.descTemplate = "";
                this.fileTemplate = "";
                $('#fileTemplate').filepond('removeFiles');

                this.modal = new coreui.Modal(document.getElementById('modalTemplate'), {});
                this.modal.show();
            }

            function doAddTemplate(){
                if (this.templateName == "" || this.fileTemplate == "") {
                    return swal.fire({
                        icon: 'warning',
                        title: 'Field template name and file cannot be empty!'
                    })
                }

                let formdata = new FormData();
                formdata.append('categoryId', category.categoryIndustryId);
                formdata.append('templateName', this.templateName);
                formdata.append('descTemplate', this.descTemplate);
                formdata.append('fileTemplate', this.fileTemplate);
                axios({
                    url: "<?= base_url('Template/addTemplate') ?>",
                    method: 'POST',
                    data: formdata
                }).then((res) => {
                    let rsp = res.data;
                    if (rsp.status == 200) {
                        swal.fire({
                            icon: 'success',
                            title: rsp.message
                        })
                        $('#modalTemplate').modal('hide');
                        v.table.draw();
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: rsp.message
                        })
                    }
                })
            }

            const editTemplate = (param) => {
                fileEdited.value = "";
                $('#edited').filepond('removeFiles');
                // FilePond.destroy(document.querySelector('#photoParam'));
                axios({
                    url: '<?= base_url('Template/editTemplate') ?>',
                    method: 'POST',
                    data: {
                        templateId: param
                    }
                }).then((res) => {
                    let rsp = res.data;
                    if (rsp.status == 200) {
                        v.template = rsp.data
                        v.modal = new coreui.Modal(document.getElementById('modalTemplateEdit'), {});
                        v.modal.show();
                        filepondEdited()
                    } else {
                        return swal.fire({
                            icon: 'error',
                            title: rsp.message
                        })
                    }
                })
            }

            const updateTemplate = () => {
                if (v.template[0].name == "") {
                    return swal.fire({
                        icon: 'warning',
                        title: 'Field template name cannot empty!'
                    })
                }

                let formdata = new FormData();
                formdata.append('template', JSON.stringify(v.template[0]));
                formdata.append('file', v.fileEdited);
                axios({
                    url: '<?= base_url('Template/updateTemplate') ?>',
                    method: 'POST',
                    data: formdata
                }).then((res) => {
                    let rsp = res.data;
                    if (rsp.status == 200) {
                        swal.fire({
                            icon: 'success',
                            title: rsp.message
                        })
                        $('#modalTemplateEdit').modal('hide');
                        v.table.draw();
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: rsp.message
                        })
                    }
                })
            }

            const deleteTemplate = (param) => {
                const swalWithBootstrapButtons = swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mr-1',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Area you sure?',
                    text: "You can't restore this data!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: '<i class="fa fa-times"></i> Cancel',
                    confirmButtonText: '<i class="fa fa-check"></i> Yes, delete!',
                }).then((y) => {
                    if (y.isConfirmed) {
                        axios({
                            url: "<?= base_url('Template/deleteTemplate') ?>",
                            method: 'POST',
                            data: {
                                'templateId': param
                            }
                        }).then((res) => {
                            let rsp = res.data;
                            if (rsp.status == 200) {
                                swal.fire({
                                    icon: 'success',
                                    title: rsp.message
                                })
                                v.table.draw()
                            } else {
                                swal.fire({
                                    icon: 'error',
                                    title: rsp.message
                                })
                            }
                        })
                    }
                })
            }

            const filepondCategory = () => {
                let categoryImage = {
                    acceptedFileTypes: ['image/png', 'image/jpeg'],
                    allowImagePreview: true,
                    imagePreviewMaxHeight: 200,
                    allowImageCrop: true,
                    allowMultiple: false,
                    credits: false,
                    styleLoadIndicatorPosition: 'center bottom',
                    styleProgressIndicatorPosition: 'right bottom',
                    styleButtonRemoveItemPosition: 'left bottom',
                    styleButtonProcessItemPosition: 'right bottom',
                };
                let img = FilePond.create(document.querySelector('#image'), categoryImage);
                img.on('addfile', (error, file) => {
                    v.image = file.file
                })
                img.on('removefile', (error, file) => {
                    v.image = ref("");
                })
            }

            const filepondTemplate = () => {
                let templateFile = {
                    acceptedFileTypes: 'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xlsx',
                    allowImagePreview: true,
                    imagePreviewMaxHeight: 200,
                    allowImageCrop: true,
                    allowMultiple: false,
                    credits: false,
                    styleLoadIndicatorPosition: 'center bottom',
                    styleProgressIndicatorPosition: 'right bottom',
                    styleButtonRemoveItemPosition: 'left bottom',
                    styleButtonProcessItemPosition: 'right bottom',
                };
                let pond = FilePond.create(document.querySelector('#fileTemplate'), templateFile);
                pond.on('addfile', (error, file) => {
                    v.fileTemplate = file.file
                })
                pond.on('removefile', (error, file) => {
                    v.fileTemplate = ref("");
                })
            }

            const filepondEdited = () => {
                let y = {
                    acceptedFileTypes: 'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xlsx',
                    allowImagePreview: true,
                    imagePreviewMaxHeight: 200,
                    allowImageCrop: true,
                    allowMultiple: false,
                    credits: false,
                    styleLoadIndicatorPosition: 'center bottom',
                    styleProgressIndicatorPosition: 'right bottom',
                    styleButtonRemoveItemPosition: 'left bottom',
                    styleButtonProcessItemPosition: 'right bottom',
                };
                let pEdited = FilePond.create(document.querySelector('#edited'), y);
                pEdited.on('addfile', (error, file) => {
                    v.fileEdited = file.file
                })
                pEdited.on('removefile', (error, file) => {
                    v.fileEdited = ref("");
                })
            }

            onMounted(() => {
                FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType, FilePondPluginFilePoster);
                filepondCategory()
                filepondTemplate()
                getData()
            })


            return {
                myModal,
                category,
                templateData,
                table,

                image,

                editCategory,
                checkEditCategory,
                cancelEditCategory,
                deleteCategory,
                saveEditCategory,

                addTemplate,
                templateName,
                descTemplate,
                fileTemplate,
                doAddTemplate,

                editTemplate,
                template,
                fileEdited,
                updateTemplate,
                deleteTemplate,

                filepondCategory,
                filepondTemplate,
                getData,
                filepondEdited,

            };
        },
    }).mount('#app');

    function editTemplate(param) {
        return v.editTemplate(param);
    }

    function deleteTemplate(param) {
        return v.deleteTemplate(param);
    }
</script>
<?= $this->endSection(); ?>