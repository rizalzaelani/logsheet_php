<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logsheet Digital</title>
    <?php

    use CodeIgniter\Database\BaseUtils;

    if (isset($css)) : ?>
        <?php foreach ($css as $item) : ?>
            <link href="<?= $item ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="css/bd-wizard.css">
</head>
<?php
$session = \Config\Services::session();
$email      = $session->get('email');
$name       = $session->get('name');
$parameter  = json_decode($session->get('parameter'));
?>

<body>
    <!-- particles.js container -->
    <!-- <div id="particles-js"></div> -->

    <main class="d-flex min-vh-100" id="">
        <div class="card bd-wizard-card">
            <div class="card-header p-5">
                <h1 class="text-uppercase text-center mb-0">Logsheet Digital</h1>
            </div>
            <div class="card-body pt-0">
                <div id="wizard">
                    <h3>
                        <div class="media">
                            <div class="bd-wizard-step-icon d-flex justify-content-center align-items-center">
                                <i class="cis cis-box"></i>
                            </div>
                            <div class="media-body">
                                <h5 class="bd-wizard-step-title">Purchase Package</h5>
                                <p class="bd-wizard-step-subtitle">No credit card is needed. We provide affordable price and guaranteed quality with competitive results.
                                </p>
                            </div>
                        </div>
                    </h3>
                    <section>
                        <h5 class="section-heading">Select Purchase Package</h5>
                        <div class="w-100">
                            <ul class="nav nav-tabs w-100 d-flex justify-content-center" id="myTab" role="tablist">
                                <?php
                                foreach ($packageGroup as $key => $value) : ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-uppercase <?= $value['packageGroupName'] == 'small' ? 'active' : ''; ?>" id="<?= $value['packageGroupName'] ?>-tab" data-toggle="tab" href="#<?= $value['packageGroupName'] ?>" role="tab" aria-controls="<?= $value['packageGroupName'] ?>" aria-selected="true"><?= $value['packageGroupName'] ?> pack</a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="tab-content w-100 mt-4" id="myTabContent">
                                <div class="tab-pane fade show active w-100" id="small" role="tabpanel" aria-labelledby="small-tab">
                                    <div class="row w-100 m-0">
                                        <div class="col-3 px-1">
                                            <div class="card card-main">
                                                <div class="text-uppercase">
                                                    <b>
                                                        <p>services</p>
                                                    </b>
                                                </div>
                                                <div>
                                                    <p class="text-uppercase">users</p>
                                                    <p class="text-uppercase">assets</p>
                                                    <p class="text-uppercase">parameters</p>
                                                    <p class="text-uppercase">tags</p>
                                                    <p class="text-uppercase">transactions</p>
                                                    <p class="text-uppercase">storage</p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php foreach ($small as $key => $value) : ?>
                                            <div class="col-3 px-1">
                                                <label class="w-100">
                                                    <input type="radio" name="package" class="form-control radio-card d-none" value="<?= $value['packageId'] ?>">
                                                    <div class="card card-main card-packagenew">
                                                        <div class="package-title">
                                                            <b>
                                                                <p class="text-uppercase text-center"><?= $value['name'] ?></p>
                                                            </b>
                                                        </div>
                                                        <div>
                                                            <p class="text-center"><?= $value['userMax'] ?></p>
                                                            <p class="text-center"><?= $value['assetMax'] ?></p>
                                                            <p class="text-center"><?= $value['parameterMax'] ?></p>
                                                            <p class="text-center"><?= $value['tagMax'] ?></p>
                                                            <p class="text-center"><?= $value['trxDailyMax'] ?> / day</p>
                                                            <p class="text-center">10 GB</p>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="professional" role="tabpanel" aria-labelledby="professional-tab">
                                    <div class="row w-100 m-0">
                                        <div class="col-3 px-1">
                                            <div class="card card-main">
                                                <div class="text-uppercase">
                                                    <b>
                                                        <p>services</p>
                                                    </b>
                                                </div>
                                                <div>
                                                    <p class="text-uppercase">users</p>
                                                    <p class="text-uppercase">assets</p>
                                                    <p class="text-uppercase">parameters</p>
                                                    <p class="text-uppercase">tags</p>
                                                    <p class="text-uppercase">transactions</p>
                                                    <p class="text-uppercase">storage</p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php foreach ($professional as $key => $value) : ?>
                                            <div class="col-3 px-1">
                                                <label class="w-100">
                                                    <input type="radio" name="package" class="form-control radio-card d-none" value="<?= $value['packageId'] ?>">
                                                    <div class="card card-packagenew card-main">
                                                        <div class="text-uppercase text-center">
                                                            <b>
                                                                <p><?= $value['name'] ?></p>
                                                            </b>
                                                        </div>
                                                        <div>
                                                            <p class="text-center"><?= $value['userMax'] ?></p>
                                                            <p class="text-center"><?= $value['assetMax'] ?></p>
                                                            <p class="text-center"><?= $value['parameterMax'] ?></p>
                                                            <p class="text-center"><?= $value['tagMax'] ?></p>
                                                            <p class="text-center"><?= $value['trxDailyMax'] ?> / day</p>
                                                            <p class="text-center">10 GB</p>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="enterprise" role="tabpanel" aria-labelledby="enterprise-tab">
                                    <div class="row w-100 m-0">
                                        <div class="col-3 px-1">
                                            <div class="card card-main">
                                                <div class="text-uppercase">
                                                    <b>
                                                        <p>services</p>
                                                    </b>
                                                </div>
                                                <div>
                                                    <p class="text-uppercase">users</p>
                                                    <p class="text-uppercase">assets</p>
                                                    <p class="text-uppercase">parameters</p>
                                                    <p class="text-uppercase">tags</p>
                                                    <p class="text-uppercase">transactions</p>
                                                    <p class="text-uppercase">storage</p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php foreach ($enterprise as $key => $value) : ?>
                                            <div class="col-3 px-1">
                                                <label class="w-100">
                                                    <input type="radio" name="package" class="form-control radio-card d-none" value="<?= $value['packageId'] ?>">
                                                    <div class="card card-packagenew card-main">
                                                        <div class="text-uppercase text-center">
                                                            <b>
                                                                <p><?= $value['name'] ?></p>
                                                            </b>
                                                        </div>
                                                        <div>
                                                            <p class="text-center"><?= $value['name'] != 'enterprise' ? '-' : $value['userMax'] ?></p>
                                                            <p class="text-center"><?= $value['name'] != 'enterprise' ? '-' : $value['assetMax'] ?></p>
                                                            <p class="text-center"><?= $value['name'] != 'enterprise' ? '-' : $value['parameterMax'] ?></p>
                                                            <p class="text-center"><?= $value['name'] != 'enterprise' ? '-' : $value['tagMax'] ?></p>
                                                            <p class="text-center"><?= $value['name'] != 'enterprise' ? '-' : $value['trxDailyMax'] ?> / day</p>
                                                            <p class="text-center"><?= $value['name'] != 'enterprise' ? '-' : '10GB' ?></p>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-none">
                            <?php
                            foreach ($package as $key => $value) : ?>
                                <div class="col-xl-6 h-100 mb-3">
                                    <label class="w-100">
                                        <input type="radio" name="package" class="card-input-element form-control" value="<?= $value['packageId'] ?>" />
                                        <div class="card w-100 card-package h-100">
                                            <div class="card-header lite d-flex justify-content-center">
                                                <h5 class="mb-0 text-uppercase"><?= $value['name'] ?> Pack</h5>
                                            </div>
                                            <div class="mt-2" style="padding: 0 1.25rem">
                                                <p><i class="fa fa-check mr-2 text-success"></i><b><?= number_format($value['assetMax']) ?></b> Assets</p>
                                                <p><i class="fa fa-check mr-2 text-success"></i><b><?= number_format($value['parameterMax']) ?></b> Parameters</p>
                                                <p><i class="fa fa-check mr-2 text-success"></i><b><?= number_format($value['tagMax']) ?></b> Tags</p>
                                                <p><i class="fa fa-check mr-2 text-success"></i><b><?= number_format($value['trxDailyMax']) ?></b> Transactions</p>
                                                <p><i class="fa fa-check mr-2 text-success"></i><b><?= number_format($value['userMax']) ?></b> Users</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-danger d-none" id="packageMessage">*Please choose one item first.</p>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary btnWizard" id="btnPackage" data-step="next">Next</button>
                        </div>
                        <!-- <p class="card-footer-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip </p> -->
                    </section>
                    <!-- <h3>
                        <div class="media">
                            <div class="bd-wizard-step-icon d-flex justify-content-center align-items-center">
                                <i class="cis cis-briefcase"></i>
                            </div>
                            <div class="media-body">
                                <h5 class="bd-wizard-step-title">Business Details</h5>
                                <p class="bd-wizard-step-subtitle">
                                    Your business data is required to register the application.
                                </p>
                            </div>
                        </div>
                    </h3>
                    <section>
                        <div class="brand-wrapper">
                        </div>
                        <h5 class="section-heading">Enter Business Details</h5>
                        <div class="form-group">
                            <label for="fullName" class="sr-only">Full Name</label>
                            <input type="text" name="fullName" id="fullName" onkeyup="fullName(event.target)" class="form-control" placeholder="Full Name">
                        </div>
                        <div class="form-group">
                            <label for="companyName" class="sr-only">Company Name</label>
                            <input type="text" name="companyName" id="companyName" onkeyup="companyName(event.target)" class="form-control" placeholder="Company Name">
                        </div>
                        <div class="form-group">
                            <label for="address" class="sr-only">Address</label>
                            <textarea class="form-control" name="address" id="address" cols="30" rows="5" placeholder="Address" onkeyup="address(event.target)"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" onkeyup="email(event.target)" class="form-control" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber" class="sr-only">Phone Number</label>
                            <input type="tele" name="phoneNumber" id="phoneNumber" onkeyup="phoneNumber(event.target)" class="form-control" placeholder="Phone Number">
                        </div>
                        <p class="text-danger d-none" id="personalMessage">*All fields cannot be empty.</p>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary btnWizard mr-3" data-step="previous">Previous</button>
                            <button class="btn btn-primary btnWizard" id="btnPersonalData" data-step="next">Next</button>
                        </div>
                    </section> -->
                    <h3>
                        <div class="media">
                            <div class="bd-wizard-step-icon d-flex justify-content-center align-items-center">
                                <i class="cis cis-comment-square-lines"></i>
                            </div>
                            <div class="media-body">
                                <h5 class="bd-wizard-step-title">Review</h5>
                                <p class="bd-wizard-step-subtitle">
                                    Details of the purchase package and business will be displayed here.
                                </p>
                            </div>
                        </div>
                    </h3>
                    <section>
                        <div class="brand-wrapper">
                            <!-- <img src="img/wizard/logo.svg" alt="logo" class="logo"> -->
                        </div>
                        <h5 class="section-heading">Review Details</h5>
                        <div id="review">
                            <div id="reviewChild">

                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary mr-3 btnWizard" data-step="previous">Previous</button>
                            <button class="btn btn-primary btnWizard" id="btnReview" data-step="next">Next</button>
                        </div>
                    </section>
                    <h3>
                        <div class="media">
                            <div class="bd-wizard-step-icon d-flex justify-content-center align-items-center">
                                <i class="cis cis-shield-check"></i>
                            </div>
                            <div class="media-body">
                                <h5 class="bd-wizard-step-title">Submit</h5>
                                <p class="bd-wizard-step-subtitle">
                                    Agreement terms and conditions.
                                </p>
                            </div>
                        </div>
                    </h3>
                    <section>
                        <div class="brand-wrapper">
                            <!-- <img src="img/wizard/logo.svg" alt="logo" class="logo"> -->
                        </div>
                        <h5 class="section-heading">Accept Agreement</h5>
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Harum velit voluptatem, veniam deserunt minima ratione aliquid a fugiat sequi dolor iure ad accusamus maiores error, obcaecati cum nemo odit iusto?</p>
                        <div class="form-check my-4">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="termsAgreement" id="termsAgreement" value="termsAgreed">
                                I agree to the terms and conditions
                            </label>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary btnWizard" id="btnFinish" disabled data-step="finish">Finish</button>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
    <?php if ($js) : ?>
        <?php foreach ($js as $item) : ?>
            <script type="text/javascript" src="<?= $item ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    <script>
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }
    </script>
    <script src="js/bd-wizard.js"></script>
    <script>
        var selectedPackage = "";
        var personalData = {
            fullName: '<?= $name ?>',
            companyName: '<?= $parameter->company ?>',
            address: '<?= $parameter->city . ' ' . $parameter->postalCode . ', ' . $parameter->country ?>',
            email: '<?= $email ?>',
            phoneNumber: '<?= $parameter->noTelp ?>',
        };
        var package = <?= json_encode($package) ?>;
        var packageAll = <?= json_encode($packageAll) ?>;
        var packagePrice = <?= json_encode($packagePrice) ?>;
        var selectedPrice = "";
        $('input[type="radio"][name="package"]').on('change', function() {
            let val = $(this).val();
            let lengthPackage = packageAll.length;
            for (let i = 0; i < lengthPackage; i++) {
                let packageId = packageAll[i].packageId;
                if (packageId == val) {
                    let selected = packageAll[i];
                    selectedPackage = selected;
                }
            }
            let arrPrice = [];
            for (let i = 0; i < packagePrice.length; i++) {
                let packageId = packagePrice[i].packageId;
                if (packageId == val) {
                    let price = packagePrice[i];
                    arrPrice.push(price);
                }
            }
            selectedPrice = arrPrice;

            $('#reviewChild').remove();
            if (personalData.fullName == "" || personalData.companyName == "" || personalData.address == "" || personalData.email == "" || personalData.phoneNumber == "") {
                $('#personalMessage').removeClass('d-none');
                return;
            } else {
                $('#personalMessage').addClass('d-none');
                var review = "";
                var selectoption = "";
                var period = "1-Month";
                if (selectedPackage.packageGroupName == 'small') {
                    review += `<div class="text-uppercase d-flex align-items-center justify-content-between my-4">
                            <div>
                                <b>Package Version </b><span class="ml-1 badge badge-info">` + selectedPackage.name + `</span>
                            </div>
                        </div>`;
                } else if (selectedPackage.packageGroupName == 'professional') {
                    review += `<div class="text-uppercase d-flex align-items-center justify-content-between my-4">
                            <div>
                                <b>Package Version </b><span class="ml-1 badge badge-warning text-white">` + selectedPackage.name + `</span>
                            </div>
                        </div>`;
                } else {
                    review += `<div class="text-uppercase d-flex align-items-center justify-content-between my-4">
                            <div>
                                <b>Package Version </b><span class="ml-1 badge badge-danger">` + selectedPackage.name + `</span>
                            </div>
                        </div>`;
                }
                review += `<p><i class='fa fa-check text-success'></i> <b class="text-uppercase"> ` + formatNumber(selectedPackage.assetMax) + `</b> Assets</p>`;
                review += `<p><i class='fa fa-check text-success'></i> <b class="text-uppercase"> ` + formatNumber(selectedPackage.parameterMax) + `</b> Parameters</p>`;
                review += `<p><i class='fa fa-check text-success'></i> <b class="text-uppercase"> ` + formatNumber(selectedPackage.tagMax) + `</b> Tags</p>`;
                review += `<p><i class='fa fa-check text-success'></i> <b class="text-uppercase"> ` + formatNumber(selectedPackage.trxDailyMax) + `</b> Transactions / day </p>`;
                review += `<p><i class='fa fa-check text-success'></i> <b class="text-uppercase"> ` + formatNumber(selectedPackage.userMax) + `</b> Users</p>`;
                for (let b = 0; b < selectedPrice.length; b++) {
                    selectoption += '<option value="' + selectedPrice[b].period + '">' + selectedPrice[b].period + '</option>'
                }
                var price = "";
                let lengthPrice = packagePrice.length;
                if (selectedPackage.packageGroupName == 'small') {
                    price += `<div class="d-flex justify-content-start align-items-center">
                            <div class="mr-2">
                                <b class="text-uppercase">Package Version </b>
                                <span class="text-uppercase ml-1 badge badge-info">` + selectedPackage.name + `</span>` + ' / ' + `
                            </div>
                            <div>
                                <select id="month">` + selectoption + `</select>
                            </div>
                        </div>`
                } else if (selectedPackage.packageGroupName == 'professional') {
                    price += `<div class="d-flex justify-content-start align-items-center">
                            <div class="mr-2">
                                <b class="text-uppercase">Package Version </b>
                                <span class="text-uppercase ml-1 badge badge-warning text-white">` + selectedPackage.name + `</span>` + ' / ' + `
                            </div>
                            <div>
                                <select id="month">` + selectoption + `</select>
                            </div>
                        </div>`
                } else {
                    price += `<div class="d-flex justify-content-start align-items-center">
                            <div class="mr-2">
                                <b class="text-uppercase">Package Version </b>
                                <span class="text-uppercase ml-1 badge badge-danger">` + selectedPackage.name + `</span>` + ' / ' + `
                            </div>
                            <div>
                                <select id="month">` + selectoption + `</select>
                            </div>
                        </div>`
                }
                for (let i = 0; i < lengthPrice; i++) {
                    if ((packagePrice[i].packageId == selectedPackage.packageId) && (packagePrice[i].period == period)) {
                        price += `<div id="price"><b>Rp ` + formatNumber(packagePrice[i].price) + `</b></div>`;
                    }
                }
                $('#review').append(`
                        <div id="reviewChild">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card w-100 card-review h-100">
                                        <div class="card-header lite">
                                            <h5 class="mb-0">APPLICATION DETAILS</h5>
                                        </div>
                                        <div class="mt-2" style="padding: 0 1.25rem">
                                            ` + review + `
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4 mb-2">
                                <div class="col-12">
                                    <div class="card w-100 card-review h-100">
                                        <div class="card-header lite">
                                            <h5 class="mb-0">BUSINESS DETAILS</h5>
                                        </div>
                                        <div class="mt-4" style="padding: 0 1.25rem">
                                            <table>
                                                <tr>
                                                    <th><p>Full Name</p></th>
                                                    <td><p>:</p></td>
                                                    <td class="text-uppercase"><p>` + " " + personalData.fullName + `</p></td>
                                                </tr>
                                                <tr>
                                                    <th><p>Company Name</p></th>
                                                    <td><p>:</p></td>
                                                    <td class="text-uppercase"><p>` + " " + personalData.companyName + `</p></td>
                                                </tr>
                                                <tr>
                                                    <th><p>Address</p></th>
                                                    <td><p>:</p></td>
                                                    <td class="text-uppercase"><p>` + " " + personalData.address + `</p></td>
                                                </tr>
                                                <tr>
                                                    <th><p>Email</p></th>
                                                    <td><p>:</p></td>
                                                    <td class="text-uppercase"><p>` + " " + personalData.email + `</p></td>
                                                </tr>
                                                <tr>
                                                    <th><p>Phone Number</p></th>
                                                    <td><p>:</p></td>
                                                    <td class="text-uppercase"><p>` + " " + personalData.phoneNumber + `</p></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4 mb-2">
                                <div class="col-12">
                                    <div class="card w-100 card-review h-100">
                                        <div class="card-header">
                                            <h5 class="text-uppercase mb-0">payment details</h5>
                                        </div>
                                        <div class="mt-2" style="padding: 0 1.25rem" id="payment">
                                            <div class="d-flex justify-content-between align-items-center my-4" id="paymentChild">
                                                    ` + price + `
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `);
                // wizard.steps('next');
                $('#month').select2(({
                    theme: 'coreui'
                }))
                $('#month').on('change', function() {
                    month = $(this).val();
                    $('#price').remove();
                    var price = "";
                    for (let i = 0; i < lengthPrice; i++) {
                        if ((packagePrice[i].packageId == selectedPackage.packageId) && (packagePrice[i].period == month)) {
                            price += `<div id="price"><b>Rp ` + formatNumber(packagePrice[i].price) + `</b></div>`;
                        }
                    }
                    $('#paymentChild').append(`
                            ` + price + `
                        `)
                })
            }
        })

        $('#termsAgreement').on('change', function() {
            let checked = $(this).prop('checked') ? true : false;
            if (checked) {
                $('#btnFinish').prop('disabled', false);
            } else {
                $('#btnFinish').prop('disabled', true);
            }
        })

        $('#btnFinish').on('click', function() {
            $('#btnFinish').text('');
            $('#btnFinish').attr('disabled', true);
            $('#btnFinish').append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ... ');
            let formdata = new FormData();
            formdata.append('package', selectedPackage.packageId);
            let lengthPackagePrice = packagePrice.length;
            let valPeriod = $('#month').val();
            for (let i = 0; i < lengthPackagePrice; i++) {
                if ((packagePrice[i].packageId == selectedPackage.packageId) && (packagePrice[i].period == valPeriod)) {
                    formdata.append('packagePrice', packagePrice[i].packagePriceId);
                }
            }
            formdata.append('fullName', personalData.fullName);
            formdata.append('companyName', personalData.companyName);
            formdata.append('address', personalData.address);
            formdata.append('email', personalData.email);
            formdata.append('phoneNumber', personalData.phoneNumber);
            axios({
                url: '<?= base_url('Wizard/getInvoice') ?>',
                data: formdata,
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'multipart/form-data'
                }
            }).then((res) => {
                let rsp = res.data;
                if (rsp.status == 200) {
                    $('#btnFinish').text('');
                    $('#btnFinish').attr('disabled', false);
                    $('#btnFinish').text('Finish');
                    swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: rsp.message
                    }).then((result) => {
                        if (result.isConfirmed) {
                            localStorage.clear();
                            localStorage.setItem('invoice', JSON.stringify(res.data));
                            window.open('<?= base_url('Wizard/Invoice'); ?>' + '/' + rsp.data.ref_number);
                            window.location.href = '<?= base_url('/Subscription') ?>';
                        }
                    })
                } else {
                    $('#btnFinish').text('');
                    $('#btnFinish').attr('disabled', false);
                    $('#btnFinish').text('Finish');
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'error',
                        title: rsp.message
                    })
                }
            })
        })
    </script>
</body>

</html>