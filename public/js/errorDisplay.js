function displayError(form, errors) {
    $('#'+form+' .form-control').removeClass('is-invalid is-valid');
    $('#'+form+' .invalid-feedback').text('');
    
    $.each(errors, function (key, value) {
        let input = $('[name="' + key + '"]');
        input.addClass('is-invalid');
        input.next('.invalid-feedback').text(value[0]);
    });
}