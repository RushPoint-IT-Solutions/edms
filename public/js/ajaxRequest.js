function ajaxRequest(option) {
    const { data, success, type, url, processData, contentType, beforeSend, complete, error } = option
    
    $.ajax({
        type: type,
        url: url,
        data: data,
        dataType:"json",
        processData: processData,
        contentType: contentType,
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
        error: function(err) {
            if (typeof error === "function") {
                error(err);
            }
        }
    })
}