@if (!$current_page)
<?php $current_page = 1; ?>
@endif

<ul class="pagination">
    <li class='@if ($current_page-1 < $first_page) disabled @endif'>
        <a id='scrollLeft-{{$pagination_id}}' href='{{route('admin')}}/admin?page={{$current_page - 1}}#{{$selection_name}}'>&laquo;</a>
    </li>

    @for ($i=$current_page-4;i<=$last_page;$i++)
        @if (($current_page + $i) >= 4)
            <?php return ?>
        @endif

        @if (($i >= $first_page) && ($i <= $last_page))
            <li class='@if ($current_page == $i) active @endif'>
                <a data-page='{{$i}}' href='{{route('admin')}}/admin?page={{$i}}#{{$selection_name}}'>{{$i}}</a>
            </li>
        @endif
    @endfor

    <li class='@if ($current_page+1 > $first_page) disabled @endif'>
        <a id='scrollRight-{{$pagination_id}}' data-page='{{$page}}' href='{{route('admin')}}/admin?page={{$current_page + 1}}#{{$selection_name}}'>&raquo;</a>
    </li>
</ul>
