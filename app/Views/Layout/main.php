<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= base_url(); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('/img/icon.png'); ?>" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Core UI">
    <meta name="author" content="CoreUI">
    <meta name="keyword" content="Dashboard,Monitor,CoreUI">
    <title>Nocola - Equipment Record</title>

    <style type="text/css">

    </style>

    <!-- Main styles for this application-->
    <?php if (isset($css)) : ?>
        <?php foreach ($css as $item) : ?>
            <link href="<?= $item ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
    <?= $this->renderSection('customStyles'); ?>
</head>

<body class="c-app">
    <?= $this->include('Layout/Components/sidebar'); ?>
    <div class="c-wrapper">
        <?= $this->include('Layout/Components/header'); ?>
        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                        <?= $this->renderSection('content'); ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div id="loader">
        <div class="loadingio-spinner-double-ring">
            <div class="ldio">
                <div></div>
                <div></div>
                <div>
                    <div></div>
                </div>
                <div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($js) : ?>
        <?php foreach ($js as $item) : ?>
            <script type="text/javascript" src="<?= $item ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?= $this->renderSection('customScripts'); ?>
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(document).ready(function() {
            $('#tblop').DataTable({
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },
                order: [
                    [1, 'asc']
                ]
            });

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
                scrollY: "calc(100vh - 300px)",
            });
        });
    </script>
    <script type="text/javascript">
        //filter
        $('#company').select2({
            theme: 'coreui',
            placeholder: "Filter Asset",
            allowClear: true
        })

        $('#area').select2({
            theme: 'coreui',
            placeholder: "Filter Location",
            allowClear: true
        })

        $('#unit').select2({
            theme: 'coreui',
            placeholder: "Filter Tag",
            allowClear: true
        })
    </script>
</body>

</html>