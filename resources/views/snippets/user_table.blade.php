<table class="table table-hover">
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Created At</th>
        <th>Activated</th>
        <th>API</th>
        <th>More</th>
    </tr>
    @foreach ($users as $user)
    <tr>
        <td class='wrap-text'>{{$user->username}}</td>
        <td class='wrap-text'>{{$user->email}}</td>
        <td>{{$user->created_at}}</td>
        <td>{{$user->active ? 'true' : 'false'}}</td>
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
            <a  ng-click="deleteUser($event)" class='btn btn-sm btn-danger @if (session('username') == $user->username)disabled @endif'
                data-user-id='{{$user->id}}'>
                Delete
            </a>
        </td>

    </tr>
    @endforeach
</table>
