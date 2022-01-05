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
                        <input name="dt-search" class="material-input" type="text" data-target="#tableLocation" placeholder="Search Data Transaction" />
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <h4><?= $title ?></h4>
                    <h5 class="header-icon">
                        <a href="javascript:;" class="dt-search" data-target="#tableLocation"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
                        <a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" @click="addCategory()" style="cursor: pointer !important;"><i class="fa fa-plus mr-2"></i> Add Category</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" @click="table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
                        </div>
                    </h5>
                </div>
                <div class="table-responsive w-100">
                    <table class="table w-100 table-hover" id="tableTemplate">
                        <thead class="bg-primary">
                            <tr>
                                <th>Category</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Add Category-->
    <div class="modal fade" id="modalCategory" tabindex="-1" role="dialog" aria-labelledby="modalCategoryTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCategoryTitle">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <form action="">
                            <div class="mb-3">
                                <label for="categoryName">Category Name</label>
                                <input id="categoryName" type="text" class="form-control" required v-model="categoryName" placeholder="Category Name">
                            </div>
                            <div class="mb-3">
                                <label for="descCategory">Description</label>
                                <textarea id="descCategory" type="text" rows="5" class="form-control" required v-model="descCategory" placeholder="Description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="descCategory">Image</label>
                                <input type="file" class="filepond w-100" name="filepond" id="image" accept="image/png, image/jpeg, image/gif" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="btnCancel()" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button type="button" class="btn btn-primary" @click="doAddCategory()" id="btnAdd"><i class="fa fa-plus"></i> Add Category</button>
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
        ref,
        onMounted
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var data = ref(null);
            var table = ref(null);
            var modal = ref("");

            var categoryName = ref("");
            var descCategory = ref("");
            var image = ref("");

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
                                url: "<?= base_url('/Template/datatable'); ?>",
                                type: "POST",
                                data: {},
                                complete: () => {
                                    resolve();
                                }
                            },
                            columns: [{
                                    data: "categoryName",
                                    name: "categoryName"
                                },
                                {
                                    data: "description",
                                    name: "description"
                                },
                            ],
                            'createdRow': function(row, data) {
                                row.setAttribute("data-id", data.categoryIndustryId);
                                row.classList.add("cursor-pointer");
                                row.setAttribute("data-toggle", "tooltip");
                                row.setAttribute("data-html", "true");
                                row.setAttribute("title", "<div>Click to go to category detail</div>");
                            },
                        });
                    } catch (er) {
                        console.log(er)
                        reject(er);
                    }
                })

            };

            const addCategory = () => {
                this.modal = new coreui.Modal(document.getElementById('modalCategory'), {});
                this.modal.show();
            }

            const doAddCategory = () => {
                if (categoryName.value == "" || descCategory.value == "" || image.value == "") {
                    return swal.fire({
                        icon: 'warning',
                        title: 'All fields cannot be empty!'
                    })
                }
                let formdata = new FormData();
                formdata.append('categoryName', categoryName.value);
                formdata.append('descCategory', descCategory.value);
                formdata.append('image', image.value);
                axios({
                    url: '<?= base_url('Template/addCategory') ?>',
                    method: 'POST',
                    data: formdata
                }).then((res) => {
                    let rsp = res.data;
                    if (rsp.status == 200) {
                        swal.fire({
                            icon: 'success',
                            title: rsp.message
                        })
                        $('#modalCategory').modal('hide');
                        v.table.draw();
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: rsp.message
                        })
                    }
                })

                categoryName.value = "";
                descCategory.value = "";
                image.value = "";
            }

            onMounted(() => {
                getData();
                FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType, FilePondPluginFilePoster);
                let search = $(".dt-search-input input[data-target='#tableLocation']");
                search.unbind().bind("keypress", function(e) {
                    if (e.which == 13 || e.keyCode == 13) {
                        let searchData = search.val();
                        table.value.search(searchData).draw();
                    }
                });

                $(document).on('click', '#tableTemplate tbody tr', function() {
                    if ($(this).attr("data-id")) window.location.href = "<?= site_url('Template/detail') ?>/" + $(this).attr("data-id");
                });

                let assetPhotoPond = {
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
                let assetPhoto1 = FilePond.create(document.querySelector('#image'), assetPhotoPond);
                assetPhoto1.on('addfile', (error, file) => {
                    v.image = file.file
                })
                assetPhoto1.on('removefile', (error, file) => {
                    v.image = ref("");
                })
            });

            return {
                data,
                table,

                addCategory,
                doAddCategory,
                categoryName,
                descCategory,
                image
            }
        },
    }).mount('#app');
</script>
<?= $this->endSection(); ?>