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

$(function () {
    $('.result-box').click(function(){
        select_text();
        copy_to_clipboard();
    });
});