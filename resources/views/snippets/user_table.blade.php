<div style="margin-bottom: 10px;">
    <a ng-click="toggleNewUserBox($event)" id="add_user_btn" class='btn btn-primary btn-sm status-display'>Add New User</a>
    <div id="add_user_box" style="display: none; margin-top: 10px;">
        <table class="table">
            <tr>
                <th width="20%">Username</th>
                <th width="20%">Password</th>
                <th width="20%">Email</th>
                <th width="10%">Role</th>
                <th width="10%"></th>
            </tr>
            <tr>
                <td><input id="new_user_name" /></td>
                <td><input id="new_user_password" /></td>
                <td><input id="new_user_email" /></td>
                <td>
                    <select id="new_user_role">
                        @foreach  ($roles as $role_text => $role_val)
                            <option value="{{$role_val}}">{{$role_text}}</option>
                        @endforeach
                    </select>
                </td>
                <td><a ng-click="addNewUser($event)" class='btn btn-primary btn-sm status-display' style="padding-left: 15px; padding-right: 15px;">Add</a></td>
            </tr>
        </table>
        <div id="new_user_status" style="text-align: center;"></div>
    </div>
</div>
<table class="table table-hover">
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Created At</th>
        <th>Activated</th>
        <th>API</th>
        <th>Role</th>
        <th>More</th>
    </tr>
    @foreach ($users as $user)
    <tr>
        <td class='wrap-text'>{{$user->username}}</td>
        <td class='wrap-text'>{{$user->email}}</td>
        <td>{{$user->created_at}}</td>
        <td>
            <a  ng-click="toggleUserActiveStatus($event)" class='btn btn-sm status-display @if ($user->active)btn-success @else btn-danger @endif @if (session('username') == $user->username)disabled @endif' data-user-id='{{$user->id}}'>
                {{$user->active ? 'true' : 'false'}}
            </a>
        </td>
        <td>
        @if ($user->active)
            <a  class='activate-api-modal btn btn-sm btn-info'
                ng-click="openAPIModal($event, '{{$user->username}}', '{{$user->api_key}}', '', '{{$user->api_quota}}', '{{$user->id}}')" data-api-active="{{$user->api_active}}" id="api_info_btn_{{$user->id}}">
                API info
            </a>
        @else
            N/A
        @endif
        </td>
        <td>
            @if (session('username') == $user->username)ADMIN
            @else
            <select onchange="changeUserRole($(this));" name="user_roles" id="user_roles" data-user-id='{{$user->id}}' style="width: 100%;">
                @foreach  ($roles as $role_text => $role_val)
                    <option value="{{$role_val}}" @if ($user->role == $role_val) selected @endif>{{$role_text}}</option>
                @endforeach
            </select>
            @endif
        </td>
        <td>
            <a  ng-click="deleteUser($event)" class='btn btn-sm btn-danger @if (session('username') == $user->username)disabled @endif'
                data-user-id='{{$user->id}}' data-user-name='{{$user->username}}'>
                Delete
            </a>
        </td>
    </tr>
    @endforeach
</table>
