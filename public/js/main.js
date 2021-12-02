const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

const xhrThrowRequest = (res) => {
    return new Promise((resolve, reject) => {
        try {
            if (res.status == 200) {
                let resData = res.data;
                if (resData.status == 200) {
                    resolve(true);
                } else if (resData.status == 500) {
                    if (!isNullEmptyOrUndefined(resData.data) && typeof (resData.data) == "object") {
                        let outAlert = `<ul class="list-group">`;
                        for (let r in resData.data) {
                            outAlert += `<li class="list-group-item list-group-item-warning">${resData.data[r]}</li>`;
                        }
                        outAlert += `</ul>`;

                        Swal.fire({
                            title: resData.message,
                            icon: resData.alertType ?? 'error',
                            html: outAlert
                        })
                    } else {
                        Swal.fire({
                            title: 'Opps!! Something Wrong',
                            text: resData.message,
                            icon: resData.alertType ?? 'error',
                        })
                    }

                    reject({
                        throw: false,
                        message: ""
                    });
                } else {
                    if (resData.exception) {
                        Swal.fire({
                            title: resData.message,
                            icon: resData.alertType ?? 'error',
                            html: resData.exception
                        })
                    } else {
                        if (!isNullEmptyOrUndefined(resData.data) & typeof (resData.data) == "object") {

                            let outAlert = `<ul class="list-group">`;
                            for (let r in resData.data) {
                                outAlert += `<li class="list-group-item list-group-item-warning">${resData.data[r]}</li>`;
                            }
                            outAlert += `</ul>`;

                            Swal.fire({
                                title: resData.message,
                                icon: resData.alertType ?? 'error',
                                html: outAlert
                            })
                        } else {
                            Swal.fire({
                                title: CapitalizeEachWords(resData.alertType),
                                text: resData.message,
                                icon: resData.alertType ?? 'error',
                            })
                        }
                    }

                    reject({
                        throw: false,
                        message: ""
                    });
                }
            } else if (res.status == 401) {
                Swal.fire({
                    title: "Session is Timeout, Please Login Again",
                    icon: "warning",
                    onAfterClose: function () {
                        window.location.reload();
                    }
                });

                reject({
                    throw: true,
                    message: "Session is Timeout, Please Login Again"
                });
            } else {
                Swal.fire({
                    title: "Error While Request, Please Try Again",
                    icon: "error",
                });

                reject({
                    throw: true,
                    message: "Error While Request, Please Try Again"
                });
            }
        } catch (err) {
            console.err(err);
            alert("An Error has Occured");

            reject(true);
        }
    });
}

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

const fireEvent = (element, event) => {
    if (document.createEventObject) {
        // dispatch for IE
        let evt = document.createEventObject();
        return element.fireEvent('on' + event, evt)
    } else {
        // dispatch for firefox + others
        let evt = document.createEvent("HTMLEvents");
        evt.initEvent(event, true, true); // event type,bubbling,cancelable
        return !element.dispatchEvent(evt);
    }
}

//Convert Table to Json
function tableToJson(idTable) {
    var myRows = [];
    var $headers = $("#" + idTable + " thead th");
    $("#" + idTable + " tbody tr").each(function (index) {
        $cells = $(this).find("td");
        myRows[index] = {};
        $cells.each(function (cellIndex) {
            myRows[index][firstLower($($headers[cellIndex]).html())] = $(this).html();
        });
    });

    return myRows;
}

var tableToExcel = (function () {
    var uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
        , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
    return function (table, name) {
        if (!table.nodeType) table = document.getElementById(table)
        var ctx = { worksheet: "Sheet 1" || 'Worksheet', table: table.innerHTML }
        var link = document.createElement('a');
        document.body.appendChild(link);
        link.download = name + ".xls";
        link.href = uri + base64(format(template, ctx));
        link.click();
    }
})();

// loader

let isXhrRequest = new Promise((resolve) => {
    hideShowLoader();
    resolve(true);
});

(function () {
    isXhrRequest.then(() => {
        let loaderTgt = document.getElementById("loader");
        if (loaderTgt) loaderTgt.classList.add("d-none");
    });
})();

function hideShowLoader() {
    let loaderTgt = document.getElementById("loader");
    // loading
    axios.interceptors.request.use((config) => {
        if (loaderTgt) loaderTgt.classList.remove("d-none");
        return config;
    }, (error) => {
        if (loaderTgt) loaderTgt.classList.remove("d-none");
        return Promise.reject(error);
    });

    axios.interceptors.response.use((response) => {
        if (loaderTgt) loaderTgt.classList.add("d-none");
        return response;
    }, (error) => {
        if (loaderTgt) loaderTgt.classList.add("d-none");
        return Promise.reject(error);
    });
}

// end loader