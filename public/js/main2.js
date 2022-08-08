function modal_close() {
    $('#modal').html('');
}

function modalIsOpen() {
    if ($('#modal').html().length > 1) {
        return true;
    }
    return false;
}

$(document).keypress(function (event) {
    if (modalIsOpen()) {
        var keycode = (event.keyCode ? event.keyCode : event.which);

        if (keycode === 13) {
            event.preventDefault();
            $('.modal-save').trigger('click');
        }
    }
});


$(document).keyup(function (event) {
    if (modalIsOpen()) {
        var keycode = (event.keyCode ? event.keyCode : event.which);

        if (keycode === 27) {
            event.preventDefault();
            modal_close();
        }
    }
});

$('#search-field').on('keypress', function (e) {
    if (e.which === 13) {
        let element = $('#search-field')[0];
        let data = new FormData(element);

        $.ajax({
            url: '/tracker/fast-create',
            data: data,
            type: 'POST',
            processData: false,
            contentType: false,
            cache: false
        }).done(function (response) {
            if (typeof response.url === 'string') {
                window.location.replace('/' + response.url);
            } else {
                location.reload();
            }
            // $('#search-field input').val('');
        });

    }
});

$(document).on('keypress', function (e) {
    if (e.which === 47) {
        e.preventDefault();
        $('#search-field input').focus();
    }
});