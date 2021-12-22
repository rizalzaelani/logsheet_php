//Wizard Init

$("#wizard").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "fade",
    titleTemplate: '#title#',
});

var wizard = $("#wizard")

//Form control

$('#btnPackage').on('click', function () {
    if (selectedPackage == "") {
        $('#packageMessage').removeClass('d-none');
        return;
    } else {
        $('#packageMessage').addClass('d-none');
        wizard.steps('next');
    }
});

// $('#btnReview').on('click', function () {
    
// })

$('#btnReview').on('click', function () {
    wizard.steps('next');
})

$('[data-step="previous"]').on('click', function () {
    wizard.steps('previous');
});

$('[data-step="finish"]').on('click', function () {
    wizard.steps('finish');
});