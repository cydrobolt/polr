<table class="table table-hover">
    <tr>
        <th>Link Ending</th>
        <th>Long Link</th>
        <th>Clicks</th>
        <th>Date</th>
        <th>Secret</th>
        @if ($role == 'admin')
        <th>Disable</th>
        @endif

    </tr>
    @foreach ($links as $link)
    <tr>
        <td>{{$link->short_url}}</td>
        {{-- TODO truncate long link --}}
        <td>{{$link->long_url}}</td>
        <td>{{$link->clicks}}</td>
        <td>{{$link->created_at}}</td>
        <td>{{isset($link->secret_key)}}</td>
        @if ($role == 'admin')

        <td>
            <a data-link-ending='{{$link->short_url}}' class='btn btn-sm @if($link->is_disabled) btn-success @else btn-danger @endif toggle-link'>
                @if ($link->is_disabled)
                Enable
                @else
                Disable
                @endif
            </a>
        </td>
        @endif

    </tr>
    @endforeach
</table>
