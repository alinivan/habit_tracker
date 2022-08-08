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
        ajax('/tracker/fast-create', 'search-field', 'POST')
        $('#search-field input').val('');
        location.reload();

    }
});

$(document).on('keypress', function (e) {
    if (e.which === 47) {
        e.preventDefault();
        $('#search-field input').focus();
    }
});