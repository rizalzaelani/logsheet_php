const checkAbnormal = (val, approvedAt) => {
    if (!isNullEmptyOrUndefined(approvedAt)) {
        if (val.condition != 'Normal' && val.condition) {
            if (val.findingId) {
                return {
                    'class': 'danger',
                    'name': 'Open'
                };
            } else {
                if (val.condition == 'Closed') {
                    return {
                        'class': 'primary',
                        'name': 'Closed'
                    };
                } else if (val.condition == 'Open') {
                    return {
                        'class': 'warning',
                        'name': 'Responded'
                    };
                } else {
                    return {
                        'class': 'danger',
                        'name': 'Open'
                    };
                }
            }
        } else {
            return {
                'class': '',
                'name': 'Normal'
            };
        }
    } else {
        if (val.inputType == "input") {
            val.value = parseFloat(val.value);
            val.min = parseFloat(val.min);
            val.max = parseFloat(val.max);
            if ((val.min && val.max && val.value >= val.min && val.value <= val.max) || (val.min && !val.max && val.value >= val.min) || (!val.min && val.max && val.value <= val.max) || (!val.min && !val.max) || !val.value) {
                return {
                    'class': '',
                    'name': 'Normal'
                };
            } else {
                return {
                    'class': 'danger',
                    'name': 'Open'
                };
            }
        } else if (val.inputType == "select") {
            let itmAbnormal = val.abnormal.toLowerCase().split(",");
            let isContain = _.includes(itmAbnormal, val.value.toLowerCase());
            if (!val.abnormal || !val.option || isContain == false) {
                return {
                    'class': '',
                    'name': 'Normal'
                };
            } else {
                return {
                    'class': 'danger',
                    'name': 'Open'
                };
            }
        } else {
            return {
                'class': '',
                'name': 'Normal'
            };
        }
    }
};