$(() => {
    $('.dropdown-not-close').on('click', function(event) {
        event.stopPropagation();
    });
    $("body").on('click', '.dropdown-not-close', function(event) {
        event.stopPropagation();
    });

    $(document).on('click', '.dt-search', function(){
        let dtSearchInput = $(`input[data-target='${$(this).data('target')}']`);
        dtSearchInput.closest(".dt-search-input").show();
        dtSearchInput.focus();
    });

    $(document).on('click', '.dt-search-hide', function(){
        $(this).closest(".dt-search-input").hide();
    });

    $(document).on('click', '[data-toggle=copy]', function(){
        let target = $(this).attr("data-target") ?? "";
        copyToClipboardTarget(target);
    });

    $("input[type=password]").next().on('click', function () {
        if ($(this).hasClass("input-group-append"))
        {
            let $pwd = $($(this).prev()[0]);
            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');
                $pwd.next().children().children().removeAttr("class").attr("class", "fa fa-eye-slash");
            } else {
                $pwd.attr('type', 'password');
                $pwd.next().children().children().removeAttr("class").attr("class", "fa fa-eye");
            }
        }
    });
});

function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

const copyToClipboard = str => {
    const el = document.createElement('textarea');
    el.value = str;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    
    alert("Text Copied!");
};

const copyToClipboardTarget = tgt => {
    let target = document.querySelector(tgt);
    let str = "";
    if(target != null){
        str = target.innerText == "" ? target.value : target.innerText;
    }

    const el = document.createElement('textarea');
    el.value = str;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);

    alert("Text Copied!");
};

function setTextToLengthbaru(str, replaceChar, length, align){
    str = (str ?? "").toString();
    if(length < str.length){
        return str.substring(0, length)
    } else {
        let x = (length - str.length);
        if (align.toLocaleLowerCase() == "l") {
            while(x--) str += replaceChar;
        } else if (align.toLocaleLowerCase() == "r"){
            while(x--) str = replaceChar + str;
        }
        return str;
    }
}

function transformInToFormObject(data) {
    let formData = new FormData();
    for (let key in data) {
        if (Array.isArray(data[key])) {
            data[key].forEach((obj, index) => {
                let keyList = Object.keys(obj);
                keyList.forEach((keyItem) => {
                    let keyName = [key, "[", index, "]", ".", keyItem].join("");
                    formData.append(keyName, obj[keyItem]);
                });
            });
        } else if (typeof data[key] === "object") {
            for (let innerKey in data[key]) {
                formData.append(`${key}.${innerKey}`, data[key][innerKey]);
            }
        } else {
            formData.append(key, data[key]);
        }
    }
    return formData;
}