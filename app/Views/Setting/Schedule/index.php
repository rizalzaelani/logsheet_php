<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-main fixed-height">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1">
                    <h4><?= $title ?></h4>
                    <h5 class="header-icon">
                        <a href="#filterDT" onclick="return false;" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="filterDT" id="filter"><i class="fa fa-filter" data-toggle="tooltip" title="Filter"></i></a>
                        <!-- <a href="javascript:;" onclick="table.ajax.reload();"><i class="fa fa-redo-alt" data-toggle="tooltip" title="Refresh"></i></a> -->
                        <a href="javascript:;" class="dt-search" data-target="#tableEq"><i class="fa fa-search" data-toggle="tooltip" title="Search"></i></a>
                        <a href="#" class="ml-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" data-toggle="tooltip" title="Option"></i></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= base_url('/Asset/add'); ?>"><i class="fa fa-plus mr-2"></i> Add Asset</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('/Asset/import'); ?>"><i class="fa fa-upload mr-2"></i> Import Data</a>
                            <a class="dropdown-item" href="<?= base_url('/Asset/export'); ?>"><i class="fa fa-file-excel mr-2"></i> Export Data</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" onclick="v.table.draw()"><i class="fa fa-sync-alt mr-2"></i> Reload</a>
                        </div>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<?= $this->endSection(); ?>