var original_link;

function select_text() {
    $('.result-box').focus().select();
}

$('.result-box').click(select_text);
$('.result-box').change(function () {
    $(this).val(original_link);
});


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


var clipboardDemos = new Clipboard('[data-clipboard-demo]');
clipboardDemos.on('success', function(e) {
    e.clearSelection();
    showTooltip(e.trigger, 'Copied!');
});
var btns = document.querySelectorAll('.input-group-addon');
for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener('mouseleave', clearTooltip);
    btns[i].addEventListener('blur', clearTooltip);
}
function clearTooltip(e) {
    e.currentTarget.setAttribute('class', 'input-group-addon');
    e.currentTarget.removeAttribute('aria-label');
}
function showTooltip(elem, msg) {
    elem.setAttribute('class', 'input-group-addon tooltipped tooltipped-s');
    elem.setAttribute('aria-label', msg);
}

$(function () {
    original_link = $('.result-box').val();
    select_text();
});
