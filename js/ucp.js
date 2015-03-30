$(document).ready(function() {
    $('#tabsb').tab();
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
    } 
    
    // Change hash for page-reload
    $('.nav-tabs a').on('shown', function (e) {
        window.location.hash = e.target.hash;
    });
});

function dodisable(baseval) {
    //var baseval = $(this).attr('id');
    var basevalr = baseval;
    console.log('Baseval: '+basevalr);
    console.log(this);
    var request = $.ajax({
        url: "ucp-disablelink.php",
        type: "POST",
        data: {baseval: basevalr},
        dataType: "html"
    });
    $("."+baseval).html('<span><i class="fa fa-spinner"></i></span>');
    request.done(function(msg) {
       if(msg=='success') {
           $("."+baseval).html(' <span style="color:green"><i class="fa fa-check"></i>Disabled</span>');
       }
       else if(msg=='error') {
           $("."+baseval).html(' <span style="color:orange"><i class="fa fa-ban"></i>Error, try again (reload page).</span>');
       }
       else {
           $("."+baseval).html(' <span style="color:red"><i class="fa fa-ban"></i>Error. Perhaps you were logged out or do not<br /> have sufficient permissions.</span>');
       }
    });

    request.fail(function(jqXHR, textStatus) {
        $('#status').html(' <span style="color:red"><i class="fa fa-exclamation-circle"></i> An error occured. Try again</span>' + textstatus);
    });
}
function doenable(baseval) {
    //var baseval = $(this).attr('id');
    var basevalr = baseval;
    console.log('Baseval: '+basevalr);
    console.log(this);
    var request = $.ajax({
        url: "ucp-enablelink.php",
        type: "POST",
        data: {baseval: basevalr},
        dataType: "html"
    });
    $("."+baseval).html('<span><i class="fa fa-spinner"></i></span>');
    request.done(function(msg) {
       if(msg=='success') {
           $("."+baseval).html(' <span style="color:green"><i class="fa fa-check"></i>Enabled</span>');
       }
       else if(msg=='error') {
           $("."+baseval).html(' <span style="color:orange"><i class="fa fa-ban"></i>Error, try again (reload page).</span>');
       }
       else {
           $("."+baseval).html(' <span style="color:red"><i class="fa fa-ban"></i>Error. Perhaps you were logged out or do not<br /> have sufficient permissions.</span>');
       }
    });

    request.fail(function(jqXHR, textStatus) {
        $('#status').html(' <span style="color:red"><i class="fa fa-exclamation-circle"></i> An error occured. Try again</span>' + textstatus);
    });
}
