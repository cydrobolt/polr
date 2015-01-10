$(function() {
   $( document ).tooltip();
   var optionsbutton = $('#showoptions');
   $('#options').hide();
   var slid=0;
   optionsbutton.click(function() {
       //var absfoot = $('#polrfooter').html();
       //var pfoot = '<p id="footer">&copy; Copyright 2014 Polr</p>';
        if (slid===0) {
            $("#options").slideDown();
            slid = 1;
        }
        else {
            $("#options").slideUp();
            slid=0;
        }
   });
   $('#checkavail').click(function() {
var customlink = $('#custom').val();
var request = $.ajax({
  url: "helpers/helper-linkcheck.php",
  type: "POST",
  data: { link : customlink },
  dataType: "html"
});
$('#status').html('<span><i class="fa fa-spinner"></i> Loading</span>');
request.done(function( msg ) {
  if(msg=='0') {
      $('#status').html(' <span style="color:red"><i class="fa fa-ban"></i> Already in use</span>');
  }
  else if(msg=='1') {
      $('#status').html('<span style="color:green"><i class="fa fa-check"></i> Available</span>');
  }
  else if(msg=='2') {
      $('#status').html('<span style="color:orange"><i class="fa fa-exclamation-triangle"></i> Invalid Custom URL Ending</span>');
  }
  else {
      $('#status').html(' <span style="color:red"><i class="fa fa-exclamation-circle"></i> An error occured. Try again</span>'+msg);
  }
});

request.fail(function( jqXHR, textStatus ) {
  $('#status').html(' <span style="color:red"><i class="fa fa-exclamation-circle"></i> An error occured. Try again</span>'+textstatus);
});
   });
   min = 1;
   max = 3;
   var i = Math.floor(Math.random() * (max - min + 1)) + min;
   changeTips(i);
   var tipstimer=setInterval(function(){changeTips(i);i++;},8000);

   function setTip(tip) {
       $("#tips").html(tip);
   }

   function changeTips(tcase) {
       switch(tcase) {
           case 1:
               setTip('Want to see the stats for an URL? Simply add a + to the URL (site.com/+url)');
               break;
           case 2:
               setTip('Create an account to keep track of your links');
               break;
           case 3:
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

            if ($(this).find('.btn-primary').size()>0) {
                    $(this).find('.btn').toggleClass('btn-primary');
            }
            if ($(this).find('.btn-danger').size()>0) {
                    $(this).find('.btn').toggleClass('btn-danger');
            }
            if ($(this).find('.btn-success').size()>0) {
                    $(this).find('.btn').toggleClass('btn-success');
            }
            if ($(this).find('.btn-info').size()>0) {
                    $(this).find('.btn').toggleClass('btn-info');
            }

            $(this).find('.btn').toggleClass('btn-default');

    });
});
