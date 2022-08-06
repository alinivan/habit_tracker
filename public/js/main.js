function ajax(url, form_id, method = 'GET') {
    let element = $('#' + form_id)[0];
    let data = new FormData(element);

    $.ajax({
        url: url,
        data: data,
        type: method,
        processData: false,
        contentType: false,
        cache: false
    }).done(function () {
        modal_close();
    });
}

let mobile_sidebar_open = 0;

$('.mobile_sidebar').click(function () {
    if (mobile_sidebar_open === 1) {
        $('#sidebar_opacity').addClass('opacity-0');
        $('#sidebar_translate').addClass('-translate-x-full');
        $('#sidebar_mobile_overlay').addClass('opacity-0 hidden');
        $('#sidebar_mobile_overlay_2').addClass('hidden');
        mobile_sidebar_open = 0;
    } else {
        $('#sidebar_opacity').removeClass('opacity-0');
        $('#sidebar_translate').removeClass('-translate-x-full');
        $('#sidebar_mobile_overlay').removeClass('opacity-0 hidden');
        $('#sidebar_mobile_overlay_2').removeClass('hidden');
        mobile_sidebar_open = 1;
    }
});

$('#user-menu-button').click(function () {
    if ($(this).data('open') === 1) {
        $('#user-menu-area').addClass('transform opacity-0 scale-95 hidden');
        $(this).data('open', 0);
    } else {
        $('#user-menu-area').removeClass('transform opacity-0 scale-95 hidden');
        $(this).data('open', 1);
    }
});

