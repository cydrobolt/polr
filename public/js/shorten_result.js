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
        height: 280,
        useSVG: false
    });
    container.find('img').attr('alt', original_link);
    container.show();
});

$('#download-qr-code').click(function () {
    var svgGen = document.createElement("div");
    var filename = (function (link) {
        let pagename = document.location.host,
            link_id = (new URL(link)).pathname.replace('/','');
        return `${pagename}-${link_id}.svg`;
    })(original_link);

    new QRCode(svgGen, {
        text: original_link,
        width: 280,
        height: 280,
        useSVG: true
    });

    svgGen.firstChild.setAttribute("xmlns", "http://www.w3.org/2000/svg");
    svgGen.firstChild.setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    var svgData = svgGen.innerHTML;
    var svgBlob = new Blob([svgData], {type: "image/svg+xml;charset=utf-8"});
    var svgUrl = URL.createObjectURL(svgBlob);
    var downloadLink = document.createElement("a");
    downloadLink.href = svgUrl;
    downloadLink.download = filename;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
});


var clipboard = new Clipboard('#clipboard-copy');
clipboard.on('success', function(e) {
    e.clearSelection();
    $('#clipboard-copy').tooltip('show');
});

$('#clipboard-copy').on('blur',function () {
    $(this).tooltip('destroy');
}).on('mouseleave',function () {
    $(this).tooltip('destroy');
});

$(function () {
    original_link = $('.result-box').val();
    select_text();
});
