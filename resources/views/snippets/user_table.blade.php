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
                ng-click="openAPIModal($event, '{{$user->username}}', '{{$user->api_key}}', '{{$user->api_active}}', '{{$user->api_quota}}', '{{$user->id}}')">
                API info
            </a>
        @else
            N/A
        @endif
        </td>
        <td>
            @if (session('username') == $user->username)ADMIN
            @else
            <select onchange="changeUserRole($(this));" name="user_roles" id="user_roles" data-user-id='{{$user->id}}'>
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
