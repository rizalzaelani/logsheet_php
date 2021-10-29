const checkAbnormal = (val, approvedAt) => {
    if (!isNullEmptyOrUndefined(approvedAt)) {
        if (val.condition != 'Normal' & val.condition != '' & val.condition != null & val.condition != undefined) {
            if (val.findingId == null) {
                return {
                    'class': 'danger',
                    'name': 'Follow Up'
                };
            } else {
                if (val.condition == 'Closed') {
                    return {
                        'class': 'primary',
                        'name': 'Is Closed'
                    };
                } else if (val.condition == 'Open') {
                    return {
                        'class': 'warning',
                        'name': 'Is Followed Up'
                    };
                } else {
                    return {
                        'class': 'danger',
                        'name': 'Follow Up'
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
            if ((val.min != null & val.max != null & val.value >= val.min & val.value <= val.max) || (val.min != null & val.max == null & val.value >= val.min) || (val.min == null & val.max != null & val.value <= val.max) || (val.min == null & val.max == null) || isNaN(val.value)) {
                return {
                    'class': '',
                    'name': 'Normal'
                };
            } else {
                return {
                    'class': 'danger',
                    'name': 'Follow Up'
                };
            }
        } else if (val.inputType == "select") {
            let itmAbnormal = val.abnormal.toLowerCase().split(",");
            let isContain = _.includes(itmAbnormal, val.value.toLowerCase());
            if (isNullEmptyOrUndefined(val.abnormal) || isNullEmptyOrUndefined(val.option) || isContain == false) {
                return {
                    'class': '',
                    'name': 'Normal'
                };
            } else {
                return {
                    'class': 'danger',
                    'name': 'Follow Up'
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