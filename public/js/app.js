var base_url = document.querySelector('base').getAttribute('href');
var loadingEl = `<div id="loadingEl" style="position:fixed;top:0;left:0;bottom:0;right:0;overflow:hidden;background: rgba(255,255,255,0.8);backdrop-filter:blur(5px);z-index:9999;transition: all 0.5 ease-in-out;">
<div style="position:absolute;left:50%;top:50%;transform:translate(-50%);">
<div class="spinner-grow text-success" role="status">
  <span class="sr-only">Loading...</span>
</div>
<div class="spinner-grow text-danger" role="status">
  <span class="sr-only">Loading...</span>
</div>
<div class="spinner-grow text-warning" role="status">
  <span class="sr-only">Loading...</span>
</div>
<div class="spinner-grow text-info" role="status">
  <span class="sr-only">Loading...</span>
</div>
</div>
</div>`;
// function loading() {
//     $(document).ajaxStart(function () {
//         // show loader on start
//         $('#laodingEl').show();
//         $('.c-app').addClass("blur");
//     });
//     $(document).ajaxStop(function () {
//         // hide loader on success
//         $('#loadingEl').hide();
//         $('.c-app').removeClass("blur");
//     });
// }

// let isAjaxRun = new Promise((resolve) => {
//     loading();
//     resolve(true);
// });

// $(() => {
//     isAjaxRun.then(() => {
//         $('#loadingEl').hide();
//         $('.c-app').removeClass("blur");
//     });
// });

// window.onbeforeunload = function () {
//     $('#loadingEl').show();
//     $('.c-app').addClass("blur");
// }
var loading = (status) => {
    if (status == "show") {
        $('body').append(loadingEl);
    } else {
        $('#loadingEl').remove();
    }
}
var ajaxTime;
$.ajaxSetup({
    beforeSend: function () {
        loading('show');
        ajaxTime = new Date().getTime();
    },
    error: function (jqXHR, textStatus, errorThrown) {
        loading('hide');
        if (typeof Swal == 'function') {
            Swal.fire({
                title: jqXHR.status + ' - ' + jqXHR.statusText,
                text: jqXHR.responseJSON.message,
                icon: 'error'
            });
        } else {
            alert(jqXHR.status + ' - ' + jqXHR.statusText + '\n' + jqXHR.responseJSON.message);
        }
    },
    complete: function (jqXHR, textStatus) {
        var totalTime = new Date().getTime() - ajaxTime;
        if (totalTime < 500) {
            setTimeout(function () {
                loading('hide');
            }, 500 - totalTime);
        } else {
            loading('hide');
        }
    }
});

function loadColorScheme() {
    if (localStorage.getItem('ColorScheme') == 'dark') {
        document.body.classList.add('c-dark-theme');
    } else {
        localStorage.setItem('ColorScheme', 'light');
    }
}

var mutationObserveTheme = () => {
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.attributeName === "class") {
                var attributeValue = mutation.target.classList;
                if (attributeValue.contains('c-dark-theme')) {
                    localStorage.setItem('ColorScheme', 'dark');
                } else {
                    localStorage.setItem('ColorScheme', 'light');
                }
            }
        });
    });
    observer.observe(document.querySelector('body'), {
        attributes: true
    });
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    mutationObserveTheme();
    loadColorScheme();
})

$(document).ajaxSuccess(function () {
    $('[data-toggle="tooltip"]').tooltip()
});