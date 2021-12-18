<!DOCTYPE html>
<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Logsheet Digital">
    <meta name="author" content="Nocola IoT Solution">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>Verify Email | Logsheet Digital</title>

    <link href="<?= base_url(); ?>/css/style.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/css/custom-style.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/icons/coreui/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/vendors/fontawesome/css/all.css" rel="stylesheet">
</head>
<style>
    @media (max-width: 576px) {
        .card {
            box-shadow: unset !important;
        }

        body {
            background-color: #fff !important;
        }
    }
</style>

<body class="c-app flex-sm-row align-items-sm-center">
    <div class="container" id="app">
        <div class="row justify-content-center">
            <div class="col-sm-5 p-0 p-sm-1">
                <div class="card card-main">
                    <div class="card-body p-sm-5">
                        <div class="w-100 text-center">
                            <img src="<?= base_url('/img/logo-act.png') ?>" width="200">
                        </div>

                        <div class="d-flex align-items-center flex-row mt-5 mb-3" style="height: 100%;">
                            <div class="text-center w-100">
                                <h2><?= $titleVerified ?></h2>
                                <p><?= $messageVerified ?></p>
                            </div>
                        </div>

                        <div class="w-100 text-center">
                            <a href="<?= site_url() ?>" class="text-info font-weight-500">Sign In Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>