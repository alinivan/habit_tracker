function ajax(url, form_id, method = 'GET') {
    var form = $('#'+form_id)[0];
    var data = new FormData(form);

    $.ajax({
        url: url,
        data: data,
        type: method,
        processData: false,
        contentType: false,
        cache: false
    }).done(function() {
        modal_close();
    });
}

