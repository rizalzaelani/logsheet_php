const xhrThrowRequest = (res) => {
    return new Promise((resolve, reject) => {
        try {
            if (res.status == 200) {
                let resData = res.data;
                if (resData.status == 200) {
                    resolve(true);
                } else if (resData.status == 500) {
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

// loader

let isXhrRequest = new Promise((resolve) => {
    hideShowLoader();
    resolve(true);
});

(function() {
    isXhrRequest.then(() => {
        document.getElementById("loader").classList.add("d-none");
    });
})();

function hideShowLoader() {
    // loading
    axios.interceptors.request.use((config) => {
        document.getElementById("loader").classList.remove("d-none");
        return config;
    }, (error) => {
        document.getElementById("loader").classList.remove("d-none");
        return Promise.reject(error);
    });

    axios.interceptors.response.use((response) => {
        document.getElementById("loader").classList.add("d-none");
        return response;
    }, (error) => {
        document.getElementById("loader").classList.add("d-none");
        return Promise.reject(error);
    });
}

// end loader