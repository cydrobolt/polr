<table id="{{$table_id}}" class="table table-hover">
    <thead>
        <tr>
            <th>@lang('snippets.linktable.linkending')</th>
            <th>@lang('snippets.linktable.longlink')</th>
            <th>@lang('snippets.linktable.clicks')</th>
            <th>@lang('snippets.linktable.date')</th>
            @if ($table_id == "admin_links_table")
            {{-- Show action buttons only if admin view --}}
            <th>@lang('snippets.linktable.creator')</th>
            <th>@lang('snippets.linktable.disable')</th>
            <th>@lang('snippets.linktable.delete')</th>
            @endif
        </tr>
    </thead>
</table>
