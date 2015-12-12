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
        <td>{{$user->email}}</td>
        <td>{{$user->created_at}}</td>
        <td>{{$user->active}}</td>
        {{-- <td>Active: {{$user->api_active}}</td> --}}
        <td>
            <a href='#'
                class='activate-api-modal btn btn-sm btn-info'

                data-api-active='{{$user->api_active}}'
                data-api-key='{{$user->api_key}}'
                data-api-quota='{{$user->api_quota}}'
                data-username='{{$user->username}}'>
                API info
            </a>
        </td>

        <td>
            <a href='#'
                class='activate-edit-modal btn btn-sm btn-success'

                data-username='{{$user->username}}'>
                Edit
            </a>
        </td>

    </tr>
    @endforeach
</table>
