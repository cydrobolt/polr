polr.directive('editLongLinkModal', function () {
    return {
        scope: {
            oldLongLink: '=',
            linkEnding: '=',
            cleanModals: '='
        },
        templateUrl: '/directives/editLongLinkModal.html',
        transclude: true,
        controller: function ($scope, $element, $timeout) {
            $scope.init = function () {
                // Destroy directive and clean modal on close
                $element.find('.modal').on("hidden.bs.modal", function () {
                    $scope.$destroy();
                    $scope.cleanModals('editLongLink');
                });
            }

            $scope.saveChanges = function () {
                // Save long URL changes
                apiCall('admin/edit_link_long_url', {
                    'link_ending': $scope.linkEnding,
                    'new_long_url': $element.find('input').val()
                }, function(data) {
                    toastr.success('The link was updated.', 'Success')
                }, function(err) {
                    toastr.error('The new URL format is not valid.', 'Error');
                });
            };

            $scope.init();
        }
    };
});

polr.directive('editUserApiInfoModal', function () {
    return {
        scope: {
            userId: '=',
            apiActive: '=',
            apiKey: '=',
            apiQuota: '=',
            generateNewApiKey: '=',
            cleanModals: '='
        },
        templateUrl: '/directives/editUserApiInfoModal.html',
        transclude: true,
        controller: function ($scope, $element, $timeout) {
            $scope.init = function () {
                // Destroy directive and clean modal on close
                $element.find('.modal').on("hidden.bs.modal", function () {
                    $scope.$destroy();
                    $scope.cleanModals('editUserApiInfo');
                });

                $scope.apiActive = res_value_to_text($scope.apiActive);
            }

            // Toggle API access status
            $scope.toggleAPIStatus = function() {
                apiCall('admin/toggle_api_active', {
                    'user_id': $scope.userId,
                }, function(new_status) {
                    $scope.apiActive = res_value_to_text(new_status);
                    $scope.$digest();
                });
            };

            // Generate new API key for user_id
            $scope.parentGenerateNewAPIKey = function($event) {
                $scope.generateNewApiKey($event, $scope.userId, false);
            };

            // Update user API quotas
            $scope.updateAPIQuota = function() {
                apiCall('admin/edit_api_quota', {
                    'user_id': $scope.userId,
                    'new_quota': parseInt($scope.apiQuota)
                }, function(next_action) {
                    toastr.success("Quota successfully changed.", "Success");
                });
            };

            $scope.init();
        }
    };
});

polr.controller('AdminCtrl', function($scope, $compile, $timeout) {
    /* Initialize $scope variables */
    $scope.state = {
        showNewUserWell: false
    };
    $scope.datatables = {};
    $scope.modals = {
        editLongLink: [],
        editUserApiInfo: []
    };
    $scope.newUserParams = {
        username: '',
        userPassword: '',
        userEmail: '',
        userRole: ''
    };

    $scope.syncHash = function() {
        var url = document.location.toString();
        if (url.match('#')) {
            $('.admin-nav a[href=#' + url.split('#')[1] + ']').tab('show');
        }
    };

    $scope.cleanModals = function(modalType) {
        $timeout(function () {
            $scope.modals[modalType].shift();
        });

        $scope.reloadLinkTables();
    };

    // Initialise Datatables elements
    $scope.initTables = function() {
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
            $scope.datatables['admin_users_table'] = $('#admin_users_table').DataTable($.extend({
                "ajax": BASE_API_PATH + 'admin/get_admin_users',

                "columns": [
                    {className: 'wrap-text', data: 'username', name: 'username'},
                    {className: 'wrap-text', data: 'email', name: 'email'},
                    {data: 'created_at', name: 'created_at'},

                    {data: 'toggle_active', name: 'toggle_active', orderable: false, searchable: false},
                    {data: 'api_action', name: 'api_action', orderable: false, searchable: false},
                    {data: 'change_role', name: 'change_role', orderable: false, searchable: false},
                    {data: 'delete', name: 'delete', orderable: false, searchable: false}
                ]
            }, datatables_config));
        }
        if ($('#admin_links_table').length) {
            $scope.datatables['admin_links_table'] = $('#admin_links_table').DataTable($.extend({
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

        $scope.datatables['user_links_table'] = $('#user_links_table').DataTable($.extend({
            "ajax": BASE_API_PATH + 'admin/get_user_links',

            "columns": [
                {className: 'wrap-text', data: 'short_url', name: 'short_url'},
                {className: 'wrap-text', data: 'long_url', name: 'long_url'},
                {data: 'clicks', name: 'clicks'},
                {data: 'created_at', name: 'created_at'}
            ]
        }, datatables_config));
    };

    $scope.reloadLinkTables = function () {
        // Reload DataTables for affected tables
        // without resetting page
        if ('admin_links_table' in $scope.datatables) {
            $scope.datatables['admin_links_table'].ajax.reload(null, false);
        }

        $scope.datatables['user_links_table'].ajax.reload(null, false);
    };

    $scope.reloadUserTables = function () {
        $scope.datatables['admin_users_table'].ajax.reload(null, false);
    };

    /*
        User Management
    */
    $scope.toggleUserActiveStatus = function($event, user_id) {
        var el = $($event.target);

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
        });
    };

    $scope.checkNewUserFields = function() {
        var response = true;

        $('.new-user-fields input').each(function () {
            if ($(this).val().trim() == '' || response == false) {
                response = false;
            }
        });

        return response;
    }

    $scope.addNewUser = function($event) {
        // Allow admins to add new users

        if (!$scope.checkNewUserFields()) {
            toastr.error("Fields cannot be empty.", "Error");
            return false;
        }

        apiCall('admin/add_new_user', {
            'username': $scope.newUserParams.username,
            'user_password': $scope.newUserParams.userPassword,
            'user_email': $scope.newUserParams.userEmail,
            'user_role': $scope.newUserParams.userRole,
        }, function(result) {
            toastr.success("User " + $scope.newUserParams.username + " successfully created.", "Success");
            $('#new-user-form').clearForm();
            $scope.datatables['admin_users_table'].ajax.reload();
        }, function () {
            toastr.error("An error occured while creating the user.", "Error");
        });
    }

    // Delete user
    $scope.deleteUser = function($event, user_id) {
        var delete_user_confirm = window.confirm('Are you sure you would like to delete this user?');

        if (delete_user_confirm) {
            apiCall('admin/delete_user', {
                'user_id': user_id,
            }, function(new_status) {
                toastr.success('User successfully deleted.', 'Success');
                $scope.reloadUserTables();
            });
        }
    };

    $scope.changeUserRole = function(role, user_id) {
        apiCall('admin/change_user_role', {
            'user_id': user_id,
            'role': role,
        }, function(result) {
            toastr.success("User role successfully changed.", "Success");
        });
    };

    // Open user API settings menu
    $scope.openAPIModal = function($event, username, api_key, api_active, api_quota, user_id) {
        var el = $($event.target);

        $scope.modals.editUserApiInfo.push({
            apiKey: api_key,
            apiQuota: parseInt(api_quota),
            userId: user_id,
            apiActive: api_active
        });

        $timeout(function () {
            $('#edit-user-api-info-' + user_id).modal('show');
        });
    };

    /*
        Link Management
    */

    // Delete link
    $scope.deleteLink = function($event, link_ending) {
        var delete_link_confirm = window.confirm('Are you sure you would like to delete this link?');

        if (delete_link_confirm) {
            apiCall('admin/delete_link', {
                'link_ending': link_ending,
            }, function(new_status) {
                toastr.success('Link successfully deleted.', 'Success');
                $scope.reloadLinkTables();
            });
        }
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

    // Edit links' long_url
    $scope.editLongLink = function(link_ending, old_long_link) {
        $scope.modals.editLongLink.push({
            linkEnding: link_ending,
            oldLongLink: old_long_link,
        });

        $timeout(function () {
            $('#edit-long-link-' + link_ending).modal('show');
        });
    }

    /*
        Initialisation
    */

    // Initialise AdminCtrl
    $scope.init = function() {
        var modal_source = $("#modal-template").html();

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
