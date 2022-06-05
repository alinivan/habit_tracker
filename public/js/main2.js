function modal_close() {
    $('#modal').html('');
}

$(document).keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);

    if(keycode === 13){
        event.preventDefault();
        $('.modal-save').trigger('click');
    }
});


$(document).keyup(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);

    if(keycode === 27){
        event.preventDefault();
        modal_close();
    }
});