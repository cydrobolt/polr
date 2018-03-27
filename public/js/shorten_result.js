function select_text() {
    window.getSelection().selectAllChildren(document.getElementById('result-box'));
}

function copy_to_clipboard(){
    var result_to_clipboard = new Clipboard('.result-box');
    result_to_clipboard.on('success', function(){
        toastr.success('Copied to clipboard', 'Success');
        result_to_clipboard.destroy();
    });
}


$('#generate-qr-code').click(function () {
    var container = $('.qr-code-container');
    container.empty();
    new QRCode(container.get(0), {
        text: original_link,
        width: 280,
        height: 280
    });
    container.find('img').attr('alt', original_link);
    container.show();
});

$(function () {
    $('.result-box').click(function(){
        select_text();
        copy_to_clipboard();
    });
});