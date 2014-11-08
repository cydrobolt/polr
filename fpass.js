$(document).ready(function() {
   $("#submit").prop('disabled', true);
});


$('#rnpass').change(function() {
    if ($('#rnpass').val() == $('#npass').val()) {
        $('#warn').html('<div style=\'width: 450px; text-align: center; margin:0 auto;\' class="alert alert-success">Woo, the passwords match!</div>');
        $("#submit").prop('disabled', false); // show submit button
    }
    if ($('#rnpass').val() != $('#npass').val()) {
        $('#warn').html('<div style=\'width: 450px; text-align: center; margin:0 auto;\' class="alert alert-danger">Those passwords don\'t match :(</div>');
        $("#submit").prop('disabled', true); // hide submit button
    }
});
