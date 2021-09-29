function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function CapitalizeEachWords(str) {
    if (str != null & str != "") {
        str = str.replace(/\w\S*/g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }

    return str;
}

function lowerEachWords(str) {
    if (str != null & str != "") {
        str = str.replace(/\w\S*/g, function (txt) {
            return txt.charAt(0).toLowerCase() + txt.substr(1);
        });
    }

    return str;
}

function isNullEmptyOrUndefined(value) {
    if (value === "" || value == null || value == undefined) {
        return true;
    }
    else {
        return false;
    }
}