<table class="table table-hover">
    <tr>
        <th>Link Ending</th>
        <th>Long Link</th>
        <th>Clicks</th>
        <th>Date</th>
        <th>Secret</th>
        @if ($role == 'admin')
        <th>Creator</th>
        <th>Disable</th>
        <th>Delete</th>
        @endif

    </tr>
    @foreach ($links as $link)
    <tr>
        <td>{{$link->short_url}}</td>
        <td class='wrap-text'>
            <a href='{{$link->long_url}}'>
                {{str_limit($link->long_url, 50, '...')}}
            </a>
        </td>
        <td>{{$link->clicks}}</td>
        <td>{{$link->created_at}}</td>
        <td>{{empty($link->secret_key) ? 'false' : 'true'}}</td>

        @if ($role == 'admin')
        <td>{{$link->creator}}</td>

        <td>
            <a data-link-ending='{{$link->short_url}}' class='btn btn-sm @if($link->is_disabled) btn-success @else btn-danger @endif toggle-link'>
                @if ($link->is_disabled)
                Enable
                @else
                Disable
                @endif
            </a>
        </td>

        <td>
            <a data-link-ending='{{$link->short_url}}' class='btn btn-sm btn-warning delete-link'>
                Delete
            </a>
        </td>

        @endif

    </tr>
    @endforeach
</table>
