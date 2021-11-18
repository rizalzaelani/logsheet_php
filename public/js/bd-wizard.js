//Wizard Init

$("#wizard").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "fade",
    titleTemplate: '#title#',
});

var wizard = $("#wizard")

//Form control

$('#btnPackage').on('click', function() {
    if (selectedPackage == "") {
        $('#packageMessage').removeClass('d-none');
        return;
    }else{
        $('#packageMessage').addClass('d-none');
        wizard.steps('next');
    }
});

$('#btnPersonalData').on('click', function(){
    $('#reviewChild').remove();
    if (personalData.fullName == "" || personalData.companyName == "" || personalData.typeCompany == "" || personalData.position == "" || personalData.numberEmployee == "" || personalData.email == "" || personalData.phoneNumber == "") {
        $('#personalMessage').removeClass('d-none');
        return;
    }else{
        $('#personalMessage').addClass('d-none');
        var review = "";
        var period = "3-Month";
        if (selectedPackage.packageGroupName == 'small') {
            review += `<div class="text-uppercase d-flex align-items-center justify-content-between my-4">
                <div>
                    <b>Package Version </b><span class="ml-1 badge badge-info">`+selectedPackage.name+`</span>
                </div>
            </div>`;
        }else if(selectedPackage.packageGroupName == 'professional'){
            review += `<div class="text-uppercase d-flex align-items-center justify-content-between my-4">
                <div>
                    <b>Package Version </b><span class="ml-1 badge badge-warning text-white">`+selectedPackage.name+`</span>
                </div>
            </div>`;
        }else{
            review += `<div class="text-uppercase d-flex align-items-center justify-content-between my-4">
                <div>
                    <b>Package Version </b><span class="ml-1 badge badge-danger">`+selectedPackage.name+`</span>
                </div>
            </div>`;
        }
        review += `<p><i class='fa fa-check text-success'></i> <b class="text-uppercase"> `+formatNumber(selectedPackage.assetMax)+`</b> Assets</p>`;
        review += `<p><i class='fa fa-check text-success'></i> <b class="text-uppercase"> `+formatNumber(selectedPackage.parameterMax)+`</b> Parameters</p>`;
        review += `<p><i class='fa fa-check text-success'></i> <b class="text-uppercase"> `+formatNumber(selectedPackage.tagMax)+`</b> Tags</p>`;
        review += `<p><i class='fa fa-check text-success'></i> <b class="text-uppercase"> `+formatNumber(selectedPackage.trxDailyMax)+`</b> Transactions / day </p>`;
        review += `<p><i class='fa fa-check text-success'></i> <b class="text-uppercase"> `+formatNumber(selectedPackage.userMax)+`</b> Users</p>`;
        var price = "";
        let lengthPrice = packagePrice.length;
        if (selectedPackage.packageGroupName == 'small') {
            price += `<div class="d-flex justify-content-start align-items-center">
            <div class="mr-2">
                <b class="text-uppercase">Package Version </b>
                <span class="text-uppercase ml-1 badge badge-info">`+selectedPackage.name+`</span>`+ ' / '+`
            </div>
            <div>
                <select id="month">
                    <option value="1-Month">1 Month</option>
                    <option value="3-Month" selected>3 Month</option>
                    <option value="6-Month">6 Month</option>
                    <option value="12-Month">12 Month</option>
                </select>
            </div>
        </div>`
        }else if(selectedPackage.packageGroupName == 'professional'){
            price += `<div class="d-flex justify-content-start align-items-center">
            <div class="mr-2">
                <b class="text-uppercase">Package Version </b>
                <span class="text-uppercase ml-1 badge badge-warning text-white">`+selectedPackage.name+`</span>`+ ' / '+`
            </div>
            <div>
                <select id="month">
                    <option value="1-Month">1 Month</option>
                    <option value="3-Month" selected>3 Month</option>
                    <option value="6-Month">6 Month</option>
                    <option value="12-Month">12 Month</option>
                </select>
            </div>
        </div>`
        }else{
            price += `<div class="d-flex justify-content-start align-items-center">
            <div class="mr-2">
                <b class="text-uppercase">Package Version </b>
                <span class="text-uppercase ml-1 badge badge-danger">`+selectedPackage.name+`</span>`+ ' / '+`
            </div>
            <div>
                <select id="month">
                    <option value="1-Month">1 Month</option>
                    <option value="3-Month" selected>3 Month</option>
                    <option value="6-Month">6 Month</option>
                    <option value="12-Month">12 Month</option>
                </select>
            </div>
        </div>`
        }
        for (let i = 0; i < lengthPrice; i++) {
            if ((packagePrice[i].packageId == selectedPackage.packageId) && (packagePrice[i].period == period)) {
                price += `<div id="price"><b>Rp `+  formatNumber(packagePrice[i].price) +`</b></div>`;
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
                            `+review+`
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
                        <div class="mt-2" style="padding: 0 1.25rem">
                            <table>
                                <tr>
                                    <th><p>Full Name</p></th>
                                    <td><p>:</p></td>
                                    <td class="text-uppercase"><p>`+ " " + personalData.fullName +`</p></td>
                                </tr>
                                <tr>
                                    <th><p>Company Name</p></th>
                                    <td><p>:</p></td>
                                    <td class="text-uppercase"><p>`+ " " + personalData.companyName +`</p></td>
                                </tr>
                                <tr>
                                    <th><p>Type Of Company</p></th>
                                    <td><p>:</p></td>
                                    <td class="text-uppercase"><p>`+ " " + personalData.typeCompany +`</p></td>
                                </tr>
                                <tr>
                                    <th><p>Position On Company</p></th>
                                    <td><p>:</p></td>
                                    <td class="text-uppercase"><p>`+ " " + personalData.position +`</p></td>
                                </tr>
                                <tr>
                                    <th><p>Number Of Employee</p></th>
                                    <td><p>:</p></td>
                                    <td class="text-uppercase"><p>`+ " " + personalData.numberEmployee +`</p></td>
                                </tr>
                                <tr>
                                    <th><p>Email</p></th>
                                    <td><p>:</p></td>
                                    <td class="text-uppercase"><p>`+ " " + personalData.email +`</p></td>
                                </tr>
                                <tr>
                                    <th><p>Phone Number</p></th>
                                    <td><p>:</p></td>
                                    <td class="text-uppercase"><p>`+ " " + personalData.phoneNumber +`</p></td>
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
                                    `+price+`
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `);
        wizard.steps('next');
        $('#month').select2(({
            theme: 'coreui'
        }))
        $('#month').on('change', function(){
            month = $(this).val();
            $('#price').remove();
            var price = "";
            for (let i = 0; i < lengthPrice; i++) {
                if ((packagePrice[i].packageId == selectedPackage.packageId) && (packagePrice[i].period == month)) {
                    price += `<div id="price"><b>Rp `+  formatNumber(packagePrice[i].price) +`</b></div>`;
                }
            }
            $('#paymentChild').append(`
                `+price+`
            `)
        })
    }
})

$('#btnReview').on('click', function(){
    wizard.steps('next');
})

$('[data-step="previous"]').on('click', function() {
    wizard.steps('previous');
});

$('[data-step="finish"]').on('click', function() {
    wizard.steps('finish');
});