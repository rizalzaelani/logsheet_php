<?= $this->extend('Layout/main'); ?>

<?= $this->section('customStyles'); ?>
<!-- Custom Style Css -->
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-main card-border-top fixed-height">
            <div class="container-fluid">
                <div class="card-header d-flex flex-row justify-content-between">
                    <h4 class="title"><?= (isset($subtitle) ? $subtitle : ''); ?></h4>
                    <div>
                        <button class="btn btn-sm btn-primary">button</button>
                    </div>
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('customScripts'); ?>
<!-- Custom Script Js -->
<?= $this->endSection(); ?>