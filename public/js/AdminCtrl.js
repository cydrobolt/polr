polr.controller('AdminCtrl', function($scope, $compile) {
    $scope.syncHash = function() {
        var url = document.location.toString();
        if (url.match('#')) {
            $('.admin-nav a[href=#' + url.split('#')[1] + ']').tab('show');
        }
    };

    // Initialise Datatables elements
    $scope.initTables = function () {
        var datatables_config = {
            'autoWidth': false,
            'processing': true,
            'serverSide': true,

            'drawCallback': function () {
                // Compile Angular bindings on each draw
                $compile($(this))($scope);
            }
        };

        if ($('#admin_users_table').length) {
            var admin_users_table = $('#admin_users_table').DataTable($.extend({
                "ajax": BASE_API_PATH + 'admin/get_admin_users',

                "columns": [
                    {className: 'wrap-text', data: 'username', name: 'username'},
                    {className: 'wrap-text', data: 'email', name: 'email'},
                    {data: 'created_at', name: 'created_at'},

                    {data: 'toggle_active', name: 'toggle_active'},
                    {data: 'api_action', name: 'api_action', orderable: false, searchable: false},
                    {data: 'change_role', name: 'change_role'},
                    {data: 'delete', name: 'delete', orderable: false, searchable: false}
                ]
            }, datatables_config));
        }
        if ($('#admin_links_table').length) {
            var admin_links_table = $('#admin_links_table').DataTable($.extend({
                "ajax": BASE_API_PATH + 'admin/get_admin_links',

                "columns": [
                    {className: 'wrap-text', data: 'short_url', name: 'short_url'},
                    {className: 'wrap-text', data: 'long_url', name: 'long_url'},
                    {data: 'clicks', name: 'clicks'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'creator', name: 'creator'},

                    {data: 'disable', name: 'disable', orderable: false, searchable: false},
                    {data: 'delete', name: 'delete', orderable: false, searchable: false}

                ]
            }, datatables_config));
        }

        var user_links_table = $('#user_links_table').DataTable($.extend({
            "ajax": BASE_API_PATH + 'admin/get_user_links',

            "columns": [
                {className: 'wrap-text', data: 'short_url', name: 'short_url'},
                {className: 'wrap-text', data: 'long_url', name: 'long_url'},
                {data: 'clicks', name: 'clicks'},
                {data: 'created_at', name: 'created_at'}
            ]
        }, datatables_config));
    };

    // Append modals to Angular root
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

    // Hide table rows
    $scope.hideRow = function(el, msg) {
        el.text(msg);
        el.parent().parent().slideUp();
    };

    $scope.toggleUserActiveStatus = function($event) {
        var el = $($event.target);
        var user_id = el.data('user-id');

        apiCall('admin/toggle_user_active', {
            'user_id': user_id,
        }, function(new_status) {
            var text = (new_status == 1) ? 'Active' : 'Inactive';
            el.text(text);
            if (el.hasClass('btn-success')) {
                el.removeClass('btn-success').addClass('btn-danger');
            }
            else {
                el.removeClass('btn-danger').addClass('btn-success');
            }
        });
    }

    $scope.toggleNewUserBox = function($event) {
        var el = $($event.target);
        $('#add_user_box').toggle();
        if (el.text() == 'Add New User') {
            el.text('Cancel');
            $('#new_user_name').focus();
        }
        else {
            el.text('Add New User');
            $scope.resetNewUserFields();
        }
    }

    $scope.resetNewUserFields = function() {
        $('#new_user_name').val('');
        $('#new_user_password').val('');
        $('#new_user_email').val('');
        $("#new_user_role").val($("#new_user_role option:first").val());
        $('#new_user_status').text('');
        $('#new_user_status').css('color', '#000');
    };

    $scope.checkNewUserFiels = function() {
        var user_name = $('#new_user_name').val();
        var user_password = $('#new_user_password').val();
        var user_email = $('#new_user_email').val();

        if (user_name.trim() == ''  ||  user_password.trim() == ''  ||  user_email.trim() == '') return false;

        return true;
    }

    $scope.addNewUser = function($event) {
        const status_error1 = 'Fields cannot be empty !';
        const status_error2 = 'Unknown Error !';
        const status_ok = 'New User added !\nPlease refresh page to see all users.';

        var user_name = $('#new_user_name').val();
        var user_password = $('#new_user_password').val();
        var user_email = $('#new_user_email').val();
        var user_role = $('#new_user_role').val();

        if (!$scope.checkNewUserFiels()) {
            $('#new_user_status').text(status_error1);
            $('#new_user_status').css('color', '#f00');
            
            return;
        }

        apiCall('admin/add_new_user', {
            'user_name': user_name,
            'user_password': user_password,
            'user_email': user_email,
            'user_role': user_role,
        }, function(result) {
            if (result == 'OK') {
                $('#new_user_status').text(status_ok).css('color', '#325d88').hide();
                $('#new_user_status').fadeIn('normal', function() {
                    $(this).delay(3000).fadeOut('slow', function() {
                        $('#add_user_box').toggle();
                        $('#add_user_btn').text('Add New User');
                        $('#new_user_status').show();
                        $scope.resetNewUserFields();
                    });
                });
            }
            else {
                $('#new_user_status').text(status_error2);
                $('#new_user_status').css('color', '#f00');
            }
        });
    }

    // Delete user
    $scope.deleteUser = function($event) {
        var el = $($event.target);
        var user_id = el.data('user-id');
        var user_name = el.data('user-name');

        var confirmation = confirm("User '" + user_name + "' will be deleted.\nAre you sure?");
        if (!confirmation) return;

        apiCall('admin/delete_user', {
            'user_id': user_id,
        }, function(new_status) {
            $scope.hideRow(el, 'Deleted!');
        });
    };

    // Delete link
    $scope.deleteLink = function($event, link_ending) {
        var el = $($event.target);

        apiCall('admin/delete_link', {
            'link_ending': link_ending,
        }, function(new_status) {
            $scope.hideRow(el, 'Deleted!');
        });
    };

    // Generate new API key for user_id
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
            $('a#api_info_btn_' + user_id).attr('data-api-key', new_status);
        });
    };

    // Toggle API access status
    $scope.toggleAPIStatus = function($event, user_id) {
        var el = $($event.target);
        var status_display_elem = el.prevAll('.status-display');

        apiCall('admin/toggle_api_active', {
            'user_id': user_id,
        }, function(new_status) {
            $('a#api_info_btn_' + user_id).attr('data-api-active', new_status);
            new_status = res_value_to_text(new_status);
            status_display_elem.text(new_status);
        });
    };

    // Disable and enable links
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

    // Update user API quotas
    $scope.updateAPIQuota = function($event, user_id) {
        var el = $($event.target);
        var new_quota = el.prevAll('.api-quota').val();

        apiCall('admin/edit_api_quota', {
            'user_id': user_id,
            'new_quota': parseInt(new_quota)
        }, function(next_action) {
            $('a#api_info_btn_' + user_id).attr('data-api-quota', new_quota);
            toastr.success("Quota successfully changed.", "Success");
        });
    };

    // Open user API settings menu
    $scope.openAPIModal = function($event, username, user_id) {
        var el = $($event.target);
        
        api_active = $('a#api_info_btn_' + user_id).attr('data-api-active');
        api_key = $('a#api_info_btn_' + user_id).attr('data-api-key');
        api_quota = $('a#api_info_btn_' + user_id).attr('data-api-quota');

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
    };

    // Initialise AdminCtrl
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

        $scope.initTables();
    };

    $scope.init();
});

function changeUserRole(what) {
    var user_id = what.attr('data-user-id');
    var role = what.val();

    apiCall('admin/change_user_role', {
        'user_id': user_id,
        'role': role,
    }, function(result) {
        if (result == 'OK') {
            var parent_td = what.parent();
            var add = '<div id="role_changed_' + user_id + '" style="display: none; color: #fff; background: #0a0; font-weight: bold; text-align: center;">Changed</div>';
            parent_td.append(add);
            $('#role_changed_' + user_id).fadeIn('normal', function() {
                $(this).delay(1000).fadeOut('slow', function() {
                    $('#role_changed_' + user_id).remove(); 
                });
            });
        }
    });
}
