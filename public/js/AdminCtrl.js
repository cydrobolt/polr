polr.controller('AdminCtrl', function($scope, $compile) {
    $scope.syncHash = function() {
        var url = document.location.toString();
        if (url.match('#')) {
            $('.admin-nav a[href=#' + url.split('#')[1] + ']').tab('show');
        }
    };

    $scope.appendModal = function(html, id) {
        id = esc_selector(id);

        $(".ng-root").append(html);
        var modal_ele = $("#" + id);

        modal_ele.append(html);
        modal_ele.modal();
        $compile(modal_ele)($scope);

        $("body").delegate("#" + id, "hidden.bs.modal", function() {
            modal_ele.remove();
        });
    };

    $scope.hideRow = function(el, msg) {
        el.text(msg);
        el.parent().parent().slideUp();
    };

    $scope.deleteUser = function($event) {
        var el = $($event.target);
        var user_id = el.data('user-id');

        apiCall('admin/delete_user', {
            'user_id': user_id,
        }, function(new_status) {
            $scope.hideRow(el, 'Deleted!');
        });
    };

    $scope.deleteLink = function($event, link_ending) {
        var el = $($event.target);

        apiCall('admin/delete_link', {
            'link_ending': link_ending,
        }, function(new_status) {
            $scope.hideRow(el, 'Deleted!');
        });
    }

    $scope.generateNewAPIKey = function($event, user_id, is_dev_tab) {
        var el = $($event.target);
        var status_display_elem = el.prevAll('.status-display');

        if (is_dev_tab) {
            status_display_elem = el.parent().prev().children();
        }

        apiCall('admin/generate_new_api_key', {
            'user_id': user_id,
        }, function(new_status) {
            if (status_display_elem.is('input')) {
                status_display_elem.val(new_status);
            } else {
                status_display_elem.text(new_status);
            }
        });
    };

    $scope.toggleAPIStatus = function($event, user_id) {
        var el = $($event.target);
        var status_display_elem = el.prevAll('.status-display');

        apiCall('admin/toggle_api_active', {
            'user_id': user_id,
        }, function(new_status) {
            new_status = res_value_to_text(new_status);
            status_display_elem.text(new_status);
        });
    };

    $scope.toggleLink = function($event, link_ending) {
        var el = $($event.target);
        var curr_action = el.text();

        apiCall('admin/toggle_link', {
            'link_ending': link_ending,
        }, function(next_action) {
            toastr.success(curr_action + " was successful.", "Success");
            if (next_action == 'Disable') {
                el.removeClass('btn-success');
                el.addClass('btn-danger');
            } else {
                el.removeClass('btn-danger');
                el.addClass('btn-success');
            }

            el.text(next_action);
        });
    };

    $scope.updateAPIQuota = function($event, user_id) {
        var el = $($event.target);
        var new_quota = el.prevAll('.api-quota').val();

        apiCall('admin/edit_api_quota', {
            'user_id': user_id,
            'new_quota': parseInt(new_quota)
        }, function(next_action) {
            toastr.success("Quota successfully changed.", "Success");
        });
    }


    $scope.openAPIModal = function($event, username, api_key, api_active, api_quota, user_id) {
        var el = $($event.target);

        var markup = $('#api-modal-template').html();

        var modal_id = "api-modal-" + username;
        var modal_context = {
            id: modal_id,
            api_key: api_key,
            api_active: parseInt(api_active),
            api_quota: api_quota,
            user_id: user_id,
            title: "API Information for " + username,
            body: markup
        };
        var mt_html = $scope.modal_template(modal_context);
        var compiled_mt = Handlebars.compile(mt_html);
        mt_html = compiled_mt(modal_context);
        $scope.appendModal(mt_html, modal_id);
    }

    $scope.init = function() {
        var modal_source = $("#modal-template").html();
        $scope.modal_template = Handlebars.compile(modal_source);

        $('.admin-nav a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
        $scope.syncHash();

        $(window).on('hashchange', function() {
            $scope.syncHash();
        });

        $("a[href^=#]").on("click", function(e) {
            history.pushState({}, '', this.href);
        });
    }

    $scope.init();
});
