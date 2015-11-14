<table class="table table-hover">
    <tr>
        <th>Link Ending</th>
        <th>Long Link</th>
        <th>Clicks</th>
        <th>Date</th>
        <th>Secret</th>
    </tr>
    @foreach ($links as $link)
    <tr>
        <td>{{$link->short_url}}</td>
        <!-- TODO truncate long link -->
        <td>{{$link->long_url}}</td>
        <td>{{$link->clicks}}</td>
        <td>{{$link->created_at}}</td>
        <td>{{isset($link->secret_key)}}</td>
    </tr>
    @endforeach
</table>
