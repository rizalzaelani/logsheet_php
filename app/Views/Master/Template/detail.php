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
                        <div class="form-group row">
                            <div class="col-12 d-flex justify-content-end align-items-center">
                                <button class="btn btn-md btn-primary mr-1" type="button" @click="editCategory()" id="btnEdit"><i class="fa fa-edit"></i> Edit</button>
                                <button style="display: none;" class="btn btn-md btn-secondary mr-1" type="button" @click="cancelEditLocation()" id="btnCancelEdit"><i class="fa fa-times"></i> Cancel</button>
                                <button style="display: none;" class="btn btn-md btn-danger mr-1" type="button" @click="deleteLocation()" id="btnDelete"><i class="fa fa-trash"></i> Delete</button>
                                <button style="display: none;" class="btn btn-md btn-success mr-1" type="button" @click="saveEditLocation()" id="btnSaveEdit"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 imgMap h-100" style="border: 1px solid #d8dbe0;">
                        <img src="category.image" alt="" style="width: 200px !important;">
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
        ref
    } = Vue;
    let v = Vue.createApp({
        el: '#app',
        setup() {
            var myModal = ref('');
            var category = <?= json_encode($category) ?>;
            var template = <?= json_encode($template) ?>;


            return {
                myModal,
                category,
                template,
            };
        },
    }).mount('#app');
</script>
<?= $this->endSection(); ?>