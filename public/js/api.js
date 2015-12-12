function apiCall(path, data) {
    var base_api_path = BASE_API_PATH;
    var api_path = base_api_path + path;
    console.log('api call');
    $.ajax({
        url: api_path,
        data: data
    }).done(function(res) {
        return res;
    });
}
