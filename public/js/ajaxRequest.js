function ajaxRequest(option) {
    const { data, success, type, url, beforeSend, complete } = option
    
    $.ajax({
        type: type,
        url: url,
        data: data,
        dataType:"json",
        beforeSend: function() {
            if (beforeSend) {
                beforeSend()
            }
        },
        success: function(response) {
            success(response)
        },
        complete: function() {
            if (complete) {
                complete()
            }
        },  
        error: function(error) {
            console.error(error);
            error()
        }
    })
}