function validateForm() {
    let errors = 0;
    if (validateCompanyField() !== true) {
        errors++;
    }
    if (validateEmailField() !== true) {
        errors++;
    }
    if (validateStartDateField() !== true) {
        errors++;
    }
    if (validateEndDateField() !== true) {
        errors++;
    }

    return errors <= 0;

}

function validateCompanyField() {
    let id = 'company';
    if (isEmpty(id)) {
        throwError(id, 'Company symbol cannot be empty')
        return false;
    }
    if (isAlpha(id) === false) {
        throwError(id, 'Alphabetic symbols Only')
        return false;
    }

    if (isCompanyExists(id) === false) {
        throwError(id, 'Company does not exists')
        return false;
    }
    return true;
}

function validateEmailField() {
    let id = 'email';
    if (isEmpty(id)) {
        throwError(id, 'Email address cannot be empty')
        return false;
    }
    if (isEmail(id) === false) {
        throwError(id, 'Invalid email address')
        return false;
    }
    return true;
}

function validateStartDateField() {
    let id = 'start_date';
    let endDateId = 'end_date';
    if (isEmpty(id)) {
        throwError(id, 'Start date cannot be empty')
        return false;
    }
    if (isDate(id) === false) {
        throwError(id, 'Invalid start date')
        return false;
    }
    if (dateInFuture(id)) {
        throwError(id, 'We dont know future :-(');
        return false;
    }
    let endDate = Date.parse(fieldValue(endDateId));
    let startDate = Date.parse(fieldValue(id));

    if (startDate && endDate && startDate > endDate) {
        throwError(id, 'Must be lower or equal than End Date');
        throwError(endDateId, 'Must be greater or equal than Start Date');
        return false;
    }
    return true;
}

function validateEndDateField() {
    let id = 'end_date';
    if (isEmpty(id)) {
        throwError(id, 'End date cannot be empty')
        return false;
    }
    if (isDate(id) === false) {
        throwError(id, 'Invalid end date')
        return false;
    }
    if (dateInFuture(id)) {
        throwError(id, 'We dont know future :-(');
        return false;
    }
    return true;
}

function isEmpty(id) {
    return fieldValue(id) === '';
}

function isAlpha(id) {
    let regex = /^[a-zA-Z]*$/;
    return regex.test(fieldValue(id));
}

function isEmail(id) {
    let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(fieldValue(id));
}

function isDate(id) {
    let dateString = fieldValue(id);
    let regex = /^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/;

    if (!regex.test(dateString)) {
        return false;
    }
    return !isNaN(Date.parse(dateString));

}

function dateInFuture(id) {
    let timestamp = Date.parse(fieldValue(id));
    let current = Date.now();
    return timestamp > current;

}

function isCompanyExists(id) {
    let companyExists = false;
    jQuery.ajax({
        url: backendEndpoint+'company/'+fieldValue(id),
        success: function(data) {
            companyExists = true;
        },
        async:false
    });

    return companyExists;
}

function throwError(id, message) {
    $('#' + id).addClass('invalid');
    $('#' + id + '_message').attr('data-error', message);
}

function fieldValue(id) {
    return $.trim($('#' + id).val());
}