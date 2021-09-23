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
                        <input name="dt-search" class="material-input" type="text" data-target="#tableTag" placeholder="Search Data Tag" />
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <h4><?= $title ?></h4>
                    <h5 class="header-icon">
                        <a href="javascript:;" class="dt-search" data-target="#tableTag"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
                        <a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item cursor-pointer" @click="handleAdd()"><i class="fa fa-plus mr-2"></i> Add Tag</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('/Tag/import'); ?>"><i class="fa fa-upload mr-2"></i> Import Data</a>
                            <a class="dropdown-item" href="<?= base_url('/Tag/export'); ?>"><i class="fa fa-file-excel mr-2"></i> Export Data</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" onclick="v.table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
                        </div>
                    </h5>
                </div>
                <div class="table-responsive w-100">
                    <table class="table w-100 table-hover" id="tableTag">
                        <thead class="bg-primary">
                            <tr>
                                <th>Tag Name</th>
                                <th>Description</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- Modal Tambah-->
                <div class="modal fade" id="modalTag" tabindex="-1" role="dialog" aria-labelledby="modalTagTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTagTitle">Add Tag</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <form action="">
                                        <div class="mb-3">
                                            <label for="tagname">Tag Name <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Name for tag"></i></label>
                                            <input id="tagname" type="text" class="form-control" required v-model="tagName" placeholder="Tag Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="description">Description <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Description for tag"></i></label>
                                            <textarea id="description" type="text" rows="5" class="form-control" required v-model="description" placeholder="Description"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
                                <button type="button" class="btn btn-success" @click="add()"><i class="fa fa-plus"></i> Add Tag</button>
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
            data: '',
            table: '',
            modalTag: '',
            tagName: '',
            description: '',
            tagId: null,
        },
        mounted() {
            this.getData();

            let search = $(".dt-search-input input[data-target='#tableTag']");
            search.unbind().bind("keypress", function(e) {
                if (e.which == 13 || e.keyCode == 13) {
                    let searchData = search.val();
                    v.table.search(searchData).draw();
                }
            });
        },
        methods: {
            getData() {
                return new Promise(async (resolve, reject) => {
                    try {
                        this.table = await $('#tableTag').DataTable({
                            processing: true,
                            serverSide: true,
                            responsive: true,
                            autoWidth: true,
                            scrollY: "calc(100vh - 272px)",
                            language: {
                                processing: `<div class="spinner-border text-primary" role="status"><pan class= "sr-only">Loading... </span></div>`,
                                lengthMenu: "Showing _MENU_ ",
                                info: "of _MAX_ entries",
                                infoEmpty: 'of 0 entries',
                            },
                            dom: '<"float-left"B><"">t<"dt-fixed-bottom mt-2"<"d-sm-flex justify-content-between"<"d-flex justify-content-center justify-content-sm-start mb-3 mb-sm-0 ptd-4"<"d-flex align-items-center"l><"d-flex align-items-center"i>><pr>>>',
                            ajax: {
                                url: "<?= base_url('/Tag/datatable'); ?>",
                                type: "POST",
                                data: {},
                                complete: () => {
                                    resolve();
                                }
                            },
                            columns: [{
                                    data: "tagName",
                                    name: "tagName"
                                },
                                {
                                    data: "description",
                                    name: "description"
                                },
                            ],
                            order: [0, 'asc'],
                            columnDefs: [{
                                defaultContent: "<div class='d-flex justify-content-center align-items-center'><button class='btn btn-outline-success btn-sm mr-1'><i class='fa fa-edit'></i> Edit</button><button class='btn btn-outline-danger btn-sm'><i class='fa fa-trash'></i> Delete</button></div>",
                                targets: 2,
                            }]
                        });
                    } catch (er) {
                        console.log(er)
                        reject(er);
                    }
                })

            },
            handleAdd() {
                this.modalTag = new coreui.Modal(document.getElementById('modalTag'), {});
                this.modalTag.show();
            },
            add() {
                axios.post("<?= base_url('Tag/add'); ?>", {
                    tagId: this.tagId,
                    userId: '3f0857bf-0fab-11ec-95b6-5600026457d1',
                    tagName: this.tagName,
                    description: this.description
                }).then(res => {
                    if (res.data.status == 'success') {
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire({
                            title: 'Success!',
                            text: res.data.message,
                            icon: 'success',
                            allowOutsideClick: false
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
                    }else{
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-danger',
                            },
                            buttonsStyling: false
                        })
                        swalWithBootstrapButtons.fire({
                            title: 'Failed!',
                            text: res.data.message,
                            icon: 'error',
                            allowOutsideClick: false
                        })
                    }
                })
            }
        }
    })
</script>
<?= $this->endSection(); ?>