<table id="{{$table_id}}" class="table table-hover">
    <thead>
        <tr>
            <th>{{ __('snippets.linktable.linkending') }}</th>
            <th>{{ __('snippets.linktable.longlink') }}</th>
            <th>{{ __('snippets.linktable.clicks') }}</th>
            <th>{{ __('snippets.linktable.date') }}</th>
            @if ($table_id == "admin_links_table")
            {{-- Show action buttons only if admin view --}}
            <th>{{ __('snippets.linktable.creator') }}</th>
            <th>{{ __('snippets.linktable.disable') }}</th>
            <th>{{ __('snippets.linktable.delete') }}</th>
            @endif
        </tr>
    </thead>
</table>
