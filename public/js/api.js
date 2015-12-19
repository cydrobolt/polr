function apiCall(path, data, callback) {
    var base_api_path = BASE_API_PATH;
    var api_path = base_api_path + path;
    $.ajax({
        url: api_path,
        data: data,
        method: 'POST',
    }).done(function(res) {
        if (callback) {
            callback(res);
        }
    });
}

function res_value_to_text(res_val) {
    if (res_val == 1) {
        return 'True';
    }
    else {
        return 'False';
    }
}
