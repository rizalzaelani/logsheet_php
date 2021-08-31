var base_url = document.querySelector('base').getAttribute('href');
var loadingEl = `<div id="loadingEl" style="position:fixed;top:0;left:0;bottom:0;right:0;overflow:hidden;background: rgba(255,255,255,0.8);backdrop-filter:blur(5px);z-index:9999;transition: all 0.5 ease-in-out;">
<img src="${base_url}/public/img/loader.png" alt="" style="width: 75px; height: 75px; position: absolute;top: 50%;left: 50%;margin:-60px 0 0 -60px;-webkit-animation:spin 2s linear infinite;-moz-animation:spin 2s linear infinite;animation:spin 2s linear infinite; ">
<style>
@-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
@-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
</style>
</div>`;
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

var profile = () => {
    $.ajax({
        url: `${base_url}/Login/getProfile`,
        type: 'GET',
        dataType: 'JSON',
        success: function (res) {
            if (res.status == "success") {
                var html = `<div class="text-left">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" disabled class="form-control-mod" value="${res.data.name}" maxlength="100" name="name" placeholder="Name">
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" disabled name="username" maxlength="50" value="${res.data.username}" class="form-control-mod" placeholder="Username" />
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" disabled name="email" maxlength="100" value="${res.data.email}" class="form-control-mod" placeholder="Email" />
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <input type="text" disabled name="role" maxlength="100" value="${res.data.role}" class="form-control-mod" placeholder="Role" />
        </div>
        </div>`;
                Swal.fire({
                    title: 'Profile',
                    html: html
                });
            } else {
                Swal.fire({
                    title: 'Failed!',
                    text: res.message,
                    icon: 'error'
                });
            }
        }
    })
}

var logout = () => {
    if (typeof Swal == 'function') {
        Swal.fire({
            title: 'LOGOUT',
            text: 'Are you sure want to logout ?',
            icon: 'info',
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: 'Logout',
            confirmButtonColor: '#d91717'
        }).then((result)=>{
            if(result.value) {
                window.location.href = base_url + '/Login/Logout';
            }
        });
    } else {
        confirm('Are you sure want to logout ?') ? window.location.href = base_url + '/Login/Logout' : false;
    }
}

var changePassword = () => {
    $.ajax({
        url: `${base_url}/Login/getProfile`,
        type: 'GET',
        dataType: 'JSON',
        success: function (res) {
            if (res.status == "success") {
                var html = `<div class="text-left">
        <div class="form-group">
            <input type="text" class="form-control-mod mb-2" maxlength="50" name="oldPassword" placeholder="Old Password"/>
            <input type="text" class="form-control-mod mb-2" maxlength="50" name="newPassword" placeholder="New Password"/>
            <input type="text" class="form-control-mod mb-2" maxlength="50" name="repeatPassword" placeholder="Repeat Password"/>
        </div>
        </div>`;
                Swal.fire({
                    title: 'Change Password',
                    html: html,
                    showConfirmButton: true,
                    confirmButtonText: 'Save Changes'
                }).then((result)=>{
                    if(result.value) {
                        var oldPassword = $('input[name="oldPassword"]').val();
                        var newPassword = $('input[name="newPassword"]').val();
                        var repeatPassword = $('input[name="repeatPassword"]').val();
                        if(newPassword == repeatPassword) {
                        $.ajax({
                           url: `${base_url}/Login/changePassword`,
                            type: 'POST',
                            dataType: 'JSON',
                            data: { username: res.data.username, oldPassword: oldPassword, newPassword: newPassword },
                            success: (res1)=>{
                                if(res1.status == "success") {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: res1.message,
                                        icon: 'success'
                                    });
                                } else {
                                    Swal.fire({
                                        title: res1.status,
                                        text: res1.message,
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                        }
                    }
                });
            } else {
                Swal.fire({
                    title: 'Failed!',
                    text: res.message,
                    icon: 'error'
                });
            }
        }
    })
}

