$(function() {
    var optionsButton = $('#show-link-options');
    $('#options').hide();
    var slide = 0;
    optionsButton.click(function() {
        if (slide === 0) {
            $("#options").slideDown();
            slide = 1;
        } else {
            $("#options").slideUp();
            slide = 0;
        }
    });
    $('#check-link-availability').click(function() {
        var custom_link = $('.custom-url-field').val();
        var request = $.ajax({
            url: "/api/v2/link_avail_check",
            type: "POST",
            data: {
                link_ending: custom_link
            },
            dataType: "html"
        });
        $('#link-availability-status').html('<span><i class="fa fa-spinner"></i> Loading</span>');
        request.done(function(msg) {
            if (msg == 'unavailable') {
                $('#link-availability-status').html(' <span style="color:red"><i class="fa fa-ban"></i> Already in use</span>');
            } else if (msg == 'available') {
                $('#link-availability-status').html('<span style="color:green"><i class="fa fa-check"></i> Available</span>');
            } else if (msg == 'invalid') {
                $('#link-availability-status').html('<span style="color:orange"><i class="fa fa-exclamation-triangle"></i> Invalid Custom URL Ending</span>');
            } else {
                $('#link-availability-status').html(' <span style="color:red"><i class="fa fa-exclamation-circle"></i> An error occured. Try again</span>' + msg);
            }
        });

        request.fail(function(jqXHR, textStatus) {
            $('#link-availability-status').html(' <span style="color:red"><i class="fa fa-exclamation-circle"></i> An error occured. Try again</span>' + textstatus);
        });
    });
    min = 1;
    max = 2;
    var i = Math.floor(Math.random() * (max - min + 1)) + min;
    changeTips(i);
    var tipstimer = setInterval(function() {
        changeTips(i);
        i++;
    }, 8000);

    function setTip(tip) {
        $("#tips").html(tip);
    }

    function changeTips(tcase) {
        switch (tcase) {
            case 1:
                setTip('Create an account to keep track of your links');
                break;
            case 2:
                setTip('Did you know you can change the URL ending by clicking on "Link Options"?');
                i = 1;
                break;
        }
    }
});

$(function() {
    // Setup drop down menu
    $('.dropdown-toggle').dropdown();

    // Fix input element click problem
    $('.dropdown input, .dropdown label').click(function(e) {
        e.stopPropagation();
    });
    $('.btn-toggle').click(function() {
        $(this).find('.btn').toggleClass('active');

        if ($(this).find('.btn-primary').size() > 0) {
            $(this).find('.btn').toggleClass('btn-primary');
        }
        if ($(this).find('.btn-danger').size() > 0) {
            $(this).find('.btn').toggleClass('btn-danger');
        }
        if ($(this).find('.btn-success').size() > 0) {
            $(this).find('.btn').toggleClass('btn-success');
        }
        if ($(this).find('.btn-info').size() > 0) {
            $(this).find('.btn').toggleClass('btn-info');
        }

        $(this).find('.btn').toggleClass('btn-default');

    });
});
