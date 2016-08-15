<table class="table table-hover">
    <tr>
        <th>Link Ending</th>
        <th>Long Link</th>
        <th>Clicks</th>
        <th>Date</th>
        @if ($role == 'admin')
        <th>Creator</th>
        <th>Disable</th>
        <th>Delete</th>
        @endif

    </tr>
    @foreach ($links as $link)
    <tr>
        <td>
            @if (!empty($link->secret_key))
                {{$link->short_url}}/{{$link->secret_key}}
            @else
                {{$link->short_url}}
            @endif

        </td>
        <td class='wrap-text'>
            <a href='{{$link->long_url}}'>
                {{str_limit($link->long_url, 50, '...')}}
            </a>
        </td>
        <td>{{$link->clicks}}</td>
        <td>{{$link->created_at}}</td>
        @if ($role == 'admin')
        <td>{{$link->creator}}</td>

        <td>
            <a ng-click="toggleLink($event, '{{$link->short_url}}')" class='btn btn-sm @if($link->is_disabled) btn-success @else btn-danger @endif'>
                @if ($link->is_disabled)
                Enable
                @else
                Disable
                @endif
            </a>
        </td>

        <td>
            <a ng-click="deleteLink($event, '{{$link->short_url}}')"
                class='btn btn-sm btn-warning delete-link'>
                Delete
            </a>
        </td>

        @endif

    </tr>
    @endforeach
</table>
