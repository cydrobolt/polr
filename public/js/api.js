function apiCall(path, data) {
    var base_api_path = '/api/v2/';
    var api_path = base_api_path + path;
    $.ajax({
        url: api_path,
        data: data
    }).done(function(res) {
        return res;
    });
}
