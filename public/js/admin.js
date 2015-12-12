function syncHash() {
    var url = document.location.toString();
    if (url.match('#')) {
        $('.admin-nav a[href=#'+url.split('#')[1]+']').tab('show') ;
    }
}

function appendModal(html, id) {
    $('body').append(html);
    var modal_ele = $('#' + id);
    modal_ele.append(html);
    modal_ele.modal();
    $( "body" ).delegate( "#" + id, "hidden.bs.modal", function () {
        modal_ele.remove();
    });
}

$(function () {
    var modal_source   = $("#modal-template").html();
    var modal_template = Handlebars.compile(modal_source);

    $('.admin-nav a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });
    syncHash();

    $(window).on('hashchange',function(){
        syncHash();
    });

    $('.activate-api-modal').click(function () {
        var te = $(this);
        var username = te.data('username');
        var api_key = te.data('api-key');
        var api_active = te.data('api-active');
        var api_quota = te.data('api-quota');

        var markup = `
            <div>
                <p>
                    <span>API Active</span>:
                    {{#if api_active}}
                        True
                    {{else}}
                        False
                    {{/if}}
                    - <a href='#' class='btn btn-xs btn-success'>Active (click to toggle)</a>
                </p>
                <p>
                    <span>API Key: <code>{{api_key}}</code></span>
                </p>
                <p>
                    <span>API Quota: <code>{{api_quota}}</code></span>
                </p>
            </div>
        `;
        var modal_id = "api-modal-" + username;
        var modal_context = {
            id: modal_id,
            api_key: api_key,
            api_active: api_active,
            api_quota: api_quota,
            title: "API Information for " + username,
            body: markup
        };
        var mt_html = modal_template(modal_context);
        var compiled_mt = Handlebars.compile(mt_html);
        mt_html = compiled_mt(modal_context);
        appendModal(mt_html, modal_id);
    });

    $('.activate-edit-modal').click(function () {
        // activate modal
    });

});
