function select_text() {
    window.getSelection().selectAllChildren(document.getElementById('result-box'));
}

$(function () {
    select_text();
    $('.result-box').click(function(){
        select_text();
    });
});