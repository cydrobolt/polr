$(function() {
   $( document ).tooltip();
   var optionsbutton = $('#showoptions');
   $('#options').hide();
   var slid=0;
   optionsbutton.click(function() {
        if(slid==0) {
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
  url: "linkcheck.php",
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
   max = 5;
   var i = Math.floor(Math.random() * (max - min + 1)) + min;
   changeTips(i);
   var tipstimer=setInterval(function(){changeTips(i);i++;},4000);
   
   function setTip(tip) {
       $("#tips").html(tip);
   }
   
   function changeTips(tcase) {
       switch(tcase) {
           case 1:
               setTip('Want to see the stats for an URL? Simply add a + to the URL (polr.cf/+url)');
               break;
           case 2:
               setTip('Found an issue? Click <a href="https://github.com/Cydrobolt/polr/issues?state=open">here</a> to report it.');
               break;
           case 3:
               setTip('Are you a developer? Polr offers a free API, and our team is working on creating a self-hosted option. Join us on <a href="//webchat.freenode.net/?channels=#polr">IRC</a>');
               break;
           case 4:
               setTip('Did you know you can change the URL ending by clicking on "Link Options"?');
               break;
           case 5:
               setTip('Need help? Contact us <a href="//polr.cf/contact.php">here</a>!');
               break;
           case 6:
               setTip('Polr is a non-profit minimalistic URL Shortening platform.');
               break;
           case 7:
               setTip('Like Polr? With a Polr Account, you can keep track of all your links in one place. (register/login is at the top right corner, on the navbar)');
               i = 1;
               break;
       }
   }
});