function z(t) {
    return atob(t);
}
$(function() {
    function y (a) {
        return z(a);
    }
    var var1 = $("#j").val();
    var var2 = y(var1);
    var var5 = $("#k").val();
    var var4 = y(var5);
    $("#i").val("http://"+var4+"/"+var2);
});
