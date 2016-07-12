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
        <td>{{$user->username}}</td>
        <td class='wrap-text'>{{$user->email}}</td>
        <td>{{$user->created_at}}</td>
        <td>{{$user->active ? 'true' : 'false'}}</td>
        <td>
        @if ($user->active)
            <a  class='activate-api-modal btn btn-sm btn-info'

                data-api-active='{{$user->api_active}}'
                data-api-key='{{$user->api_key}}'
                data-api-quota='{{$user->api_quota}}'
                data-user-id='{{$user->id}}'
                data-username='{{$user->username}}'>
                API info
            </a>
        @else
            N/A
        @endif
        </td>

        <td>
            <a  class='delete-user btn btn-sm btn-danger @if (session('username') == $user->username)disabled @endif'

                data-user-id='{{$user->id}}'>
                Delete
            </a>
        </td>

    </tr>
    @endforeach
</table>
