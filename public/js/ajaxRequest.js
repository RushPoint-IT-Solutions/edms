function ajaxRequest(option) {
    const { data, success, type, url, beforeSend, complete, errors } = option
    
    $.ajax({
        type: type,
        url: url,
        data: data,
        dataType:"json",
        beforeSend: function() {
            if (typeof beforeSend === "function") {
                beforeSend();
            }
        },
        success: function(response) {
            if (typeof success === "function") {
                success(response);
            }
        },
        complete: function() {
            if (typeof complete === "function") {
                complete();
            }
        },
        error: function(xhr, status, err) {
            if (typeof errors === "function") {
                errors({ xhr, status, err });
            }
        }
    })
}