$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function esc_selector(selector) {
    return selector.replace( /(:|\.|\[|\]|,)/g, "\\$1" );
}
