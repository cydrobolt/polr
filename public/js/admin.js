$('.admin-nav a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
});

var url = document.location.toString();
if (url.match('#')) {
    $('.admin-nav a[href=#'+url.split('#')[1]+']').tab('show') ;
}
